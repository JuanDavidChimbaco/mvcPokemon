<?php

namespace Core;

/**
 * Migration Runner
 */
class Migrator
{
    private \Core\Database $db;
    private string $migrationsPath;
    private string $table = 'migrations';
    
    public function __construct(string $migrationsPath = null)
    {
        echo "=== MIGRATOR CONSTRUCTOR ===\n";
        
        // Check what config function is being used
        echo "config function exists: " . (function_exists('config') ? 'YES' : 'NO') . "\n";
        echo "config function namespace: " . (function_exists('config') ? 'global' : 'unknown') . "\n";
        
        // Test direct require
        $configFile = __DIR__ . '/../config/app.php';
        echo "Config file: $configFile\n";
        $directConfig = require $configFile;
        echo "Direct require result: " . ($directConfig ? 'OK' : 'NULL') . "\n";
        if ($directConfig) {
            echo "Direct config keys: " . implode(', ', array_keys($directConfig)) . "\n";
            echo "Direct database.default: " . ($directConfig['database']['default'] ?? 'NOT SET') . "\n";
        }
        
        $defaultConn = config('database.default');
        echo "config('database.default') = '$defaultConn'\n";
        echo "Type: " . gettype($defaultConn) . ", Len: " . strlen((string)$defaultConn) . "\n";
        
        $fullKey = 'database.connections.' . $defaultConn;
        echo "Looking for key: '$fullKey'\n";
        
        $dbConfig = config($fullKey);
        echo "config('$fullKey') = " . ($dbConfig ? 'ARRAY' : 'NULL/FALSE') . "\n";
        if ($dbConfig) {
            echo "Keys: " . implode(', ', array_keys($dbConfig)) . "\n";
        }
        
        if (!$dbConfig) {
            // Debug: show all database config
            $allDbConfig = config('database');
            echo "All database config: " . ($allDbConfig ? 'SET' : 'NULL') . "\n";
            if ($allDbConfig) {
                echo "Database keys: " . implode(', ', array_keys($allDbConfig)) . "\n";
            }
            
            throw new \RuntimeException("Database config not found for connection: $defaultConn");
        }
        
        $this->db = \Core\Database::getInstance($dbConfig);
        $this->migrationsPath = $migrationsPath ?? dirname(__DIR__, 2) . '/database/migrations';
        $this->ensureMigrationTable();
    }
    
    private function ensureMigrationTable(): void
    {
        $this->db->execute("
            CREATE TABLE IF NOT EXISTS `{$this->table}` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `migration` VARCHAR(255) NOT NULL,
                `batch` INT NOT NULL,
                `executed_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
    }
    
    public function run(): void
    {
        $files = glob($this->migrationsPath . '/*.php');
        sort($files);
        
        $ran = $this->getRanMigrations();
        $batch = $this->getNextBatchNumber();
        $ranCount = 0;
        
        foreach ($files as $file) {
            $migration = basename($file, '.php');
            
            if (in_array($migration, $ran)) {
                echo "  Already ran: {$migration}\n";
                continue;
            }
            
            require_once $file;
            $className = $this->getClassName($migration);
            
            if (!class_exists($className)) {
                echo "  Class not found: {$className}\n";
                continue;
            }
            
            echo "  Running: {$migration} ... ";
            
            try {
                $migrationInstance = new $className();
                $migrationInstance->up();
                
                $this->db->insert($this->table, [
                    'migration' => $migration,
                    'batch' => $batch,
                ]);
                
                $ranCount++;
                echo "OK\n";
            } catch (\Throwable $e) {
                echo "FAILED\n";
                echo "  Error: {$e->getMessage()}\n";
                break;
            }
        }
        
        echo "\nMigrations completed: {$ranCount} new migrations executed.\n";
    }
    
    public function rollback(int $steps = 1): void
    {
        $migrations = $this->db->select(
            "SELECT * FROM `{$this->table}` ORDER BY `batch` DESC, `id` DESC LIMIT ?",
            [$steps]
        );
        
        if (empty($migrations)) {
            echo "Nothing to rollback.\n";
            return;
        }
        
        foreach ($migrations as $migration) {
            $file = $this->migrationsPath . '/' . $migration['migration'] . '.php';
            
            if (!file_exists($file)) {
                echo "  File not found: {$migration['migration']}\n";
                continue;
            }
            
            require_once $file;
            $className = $this->getClassName($migration['migration']);
            
            if (!class_exists($className)) {
                echo "  Class not found: {$className}\n";
                continue;
            }
            
            echo "  Rolling back: {$migration['migration']} ... ";
            
            try {
                $migrationInstance = new $className();
                $migrationInstance->down();
                
                $this->db->delete($this->table, "id = ?", [$migration['id']]);
                
                echo "OK\n";
            } catch (\Throwable $e) {
                echo "FAILED\n";
                echo "  Error: {$e->getMessage()}\n";
            }
        }
    }
    
    public function reset(): void
    {
        $migrations = $this->db->select("SELECT * FROM `{$this->table}` ORDER BY `batch` DESC, `id` DESC");
        
        foreach ($migrations as $migration) {
            $file = $this->migrationsPath . '/' . $migration['migration'] . '.php';
            
            if (!file_exists($file)) continue;
            
            require_once $file;
            $className = $this->getClassName($migration['migration']);
            
            if (!class_exists($className)) continue;
            
            try {
                $migrationInstance = new $className();
                $migrationInstance->down();
                $this->db->delete($this->table, "id = ?", [$migration['id']]);
            } catch (\Throwable $e) {
                // Continue
            }
        }
        
        echo "All migrations rolled back.\n";
    }
    
    public function status(): void
    {
        $files = glob($this->migrationsPath . '/*.php');
        sort($files);
        
        $ran = $this->getRanMigrations();
        
        echo "\nMigration Status:\n";
        echo str_repeat('-', 80) . "\n";
        
        foreach ($files as $file) {
            $migration = basename($file, '.php');
            $status = in_array($migration, $ran) ? 'RAN' : 'PENDING';
            echo sprintf("  [%-7s] %s\n", $status, $migration);
        }
        
        echo str_repeat('-', 80) . "\n";
    }
    
    private function getRanMigrations(): array
    {
        $rows = $this->db->select("SELECT `migration` FROM `{$this->table}`");
        return array_column($rows, 'migration');
    }
    
    private function getNextBatchNumber(): int
    {
        $row = $this->db->selectOne("SELECT MAX(`batch`) as max_batch FROM `{$this->table}`");
        return ($row['max_batch'] ?? 0) + 1;
    }
    
    private function getClassName(string $migration): string
    {
        // Format: YYYY_MM_DD_HHMMSS_class_name
        // Remove timestamp prefix (first 4 parts separated by _)
        $parts = explode('_', $migration, 5);
        if (count($parts) >= 5) {
            $name = $parts[4];
        } else {
            // Fallback: remove first 4 parts
            $parts = explode('_', $migration);
            $name = implode('_', array_slice($parts, 4));
        }
        
        return 'Database\\Migrations\\' . str_replace(' ', '', ucwords(str_replace('_', ' ', $name)));
    }
}
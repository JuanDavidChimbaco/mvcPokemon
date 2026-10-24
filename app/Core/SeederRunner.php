<?php

namespace Core;

/**
 * Seeder Runner
 */
class SeederRunner
{
    private \Core\Database $db;
    private string $seedersPath;
    
    public function __construct(?string $seedersPath = null)
    {
        $this->db = \Core\Database::getInstance(config('database.connections.' . config('database.default')));
        $this->seedersPath = $seedersPath ?? dirname(__DIR__, 2) . '/database/seeders';
    }
    
    public function run(?string $class = null): void
    {
        if ($class) {
            $this->runSeeder($class);
        } else {
            $files = glob($this->seedersPath . '/*Seeder.php');
            sort($files);
            
            foreach ($files as $file) {
                $className = 'Database\\Seeders\\' . basename($file, '.php');
                $this->runSeeder($className);
            }
        }
    }
    
    private function runSeeder(string $className): void
    {
        $file = $this->seedersPath . '/' . str_replace('Database\\Seeders\\', '', $className) . '.php';
        
        if (!file_exists($file)) {
            echo "  File not found: {$className}\n";
            return;
        }
        
        require_once $file;
        
        if (!class_exists($className)) {
            echo "  Class not found: {$className}\n";
            return;
        }
        
        echo "  Seeding: {$className} ... ";
        
        try {
            $seeder = new $className();
            $seeder->run();
            echo "OK\n";
        } catch (\Throwable $e) {
            echo "FAILED\n";
            echo "  Error: {$e->getMessage()}\n";
        }
    }
}
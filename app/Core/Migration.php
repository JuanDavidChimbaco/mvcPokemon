<?php

namespace Core;

/**
 * Migration base class
 */
abstract class Migration
{
    protected \Core\Database $db;
    
    public function __construct()
    {
        $this->db = \Core\Database::getInstance(config('database.connections.' . config('database.default')));
    }
    
    abstract public function up(): void;
    abstract public function down(): void;
    
    protected function createTable(string $name, callable $callback): void
    {
        $builder = new Blueprint($name, $this->db, true);
        $callback($builder);
        $builder->execute();
    }
    
    protected function table(string $name, callable $callback): void
    {
        $builder = new Blueprint($name, $this->db, false);
        $callback($builder);
        $builder->execute();
    }
    
    protected function dropTable(string $name): void
    {
        $this->db->execute("DROP TABLE IF EXISTS `{$name}`");
    }
    
    protected function dropTableIfExists(string $name): void
    {
        $this->db->execute("DROP TABLE IF EXISTS `{$name}`");
    }
}

/**
 * Blueprint para definir estructura de tablas
 */
class Blueprint
{
    private string $table;
    private \Core\Database $db;
    private bool $create;
    private array $columns = [];
    private array $indexes = [];
    private array $foreignKeys = [];
    private string $engine = 'InnoDB';
    private string $charset = 'utf8mb4';
    private string $collation = 'utf8mb4_unicode_ci';
    
    public function __construct(string $table, \Core\Database $db, bool $create = true)
    {
        $this->table = $table;
        $this->db = $db;
        $this->create = $create;
    }
    
    // Column types
    public function id(string $name = 'id'): self
    {
        $this->columns[$name] = "`{$name}` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY";
        return $this;
    }
    
    public function bigIncrements(string $name): self
    {
        return $this->id($name);
    }
    
    public function string(string $name, int $length = 255): self
    {
        $this->columns[$name] = "`{$name}` VARCHAR({$length}) NOT NULL";
        return $this;
    }
    
    public function text(string $name): self
    {
        $this->columns[$name] = "`{$name}` TEXT NOT NULL";
        return $this;
    }
    
    public function integer(string $name, bool $unsigned = true): self
    {
        $this->columns[$name] = "`{$name}` INT" . ($unsigned ? " UNSIGNED" : "") . " NOT NULL";
        return $this;
    }
    
    public function bigInteger(string $name, bool $unsigned = true): self
    {
        $this->columns[$name] = "`{$name}` BIGINT" . ($unsigned ? " UNSIGNED" : "") . " NOT NULL";
        return $this;
    }
    
    public function foreignId(string $name): self
    {
        return $this->bigInteger($name, true);
    }
    
    public function foreignIdFor(string $model, ?string $column = null): self
    {
        $column = $column ?? (basename(str_replace('\\', '/', $model)) . '_id');
        return $this->foreignId($column);
    }
    
    public function constrained(?string $table = null, string $column = 'id', string $onDelete = 'CASCADE', string $onUpdate = 'CASCADE'): self
    {
        $last = array_key_last($this->columns);
        if ($last) {
            $table = $table ?? str_replace('_id', '', $last);
            $table = rtrim($table, 's'); // singular
            $this->foreign($last, $table . 's', $column, $onDelete, $onUpdate);
        }
        return $this;
    }
    
    public function onDelete(string $action): self
    {
        // Modify the last foreign key's onDelete action
        $lastFk = array_key_last($this->foreignKeys);
        if ($lastFk) {
            $this->foreignKeys[$lastFk] = preg_replace(
                '/ON DELETE \w+/',
                "ON DELETE {$action}",
                $this->foreignKeys[$lastFk]
            );
        }
        return $this;
    }
    
    public function onUpdate(string $action): self
    {
        $lastFk = array_key_last($this->foreignKeys);
        if ($lastFk) {
            $this->foreignKeys[$lastFk] = preg_replace(
                '/ON UPDATE \w+/',
                "ON UPDATE {$action}",
                $this->foreignKeys[$lastFk]
            );
        }
        return $this;
    }
    
    public function decimal(string $name, int $precision = 10, int $scale = 2): self
    {
        $this->columns[$name] = "`{$name}` DECIMAL({$precision}, {$scale}) NOT NULL";
        return $this;
    }
    
    public function boolean(string $name): self
    {
        $this->columns[$name] = "`{$name}` TINYINT(1) NOT NULL DEFAULT 0";
        return $this;
    }
    
    public function enum(string $name, array $values, string $default = ''): self
    {
        $vals = "'" . implode("','", $values) . "'";
        $def = $default ? " DEFAULT '{$default}'" : '';
        $this->columns[$name] = "`{$name}` ENUM({$vals}) NOT NULL{$def}";
        return $this;
    }
    
    public function timestamp(string $name): self
    {
        $this->columns[$name] = "`{$name}` TIMESTAMP NULL";
        return $this;
    }
    
    public function timestamps(): self
    {
        $this->columns['created_at'] = "`created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP";
        $this->columns['updated_at'] = "`updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP";
        return $this;
    }
    
    public function softDeletes(string $name = 'deleted_at'): self
    {
        $this->columns[$name] = "`{$name}` TIMESTAMP NULL";
        return $this;
    }
    
    // Modifiers
    public function nullable(): self
    {
        $last = array_key_last($this->columns);
        if ($last) {
            $this->columns[$last] = str_replace('NOT NULL', 'NULL', $this->columns[$last]);
        }
        return $this;
    }
    
    public function default(mixed $value): self
    {
        $last = array_key_last($this->columns);
        if ($last) {
            $val = is_string($value) ? "'{$value}'" : $value;
            $this->columns[$last] .= " DEFAULT {$val}";
        }
        return $this;
    }
    
    public function unique(?string $name = null): self
    {
        $last = array_key_last($this->columns);
        if ($last) {
            $indexName = $name ?? "{$this->table}_{$last}_unique";
            $this->indexes[$indexName] = "UNIQUE KEY `{$indexName}` (`{$last}`)";
        }
        return $this;
    }
    
    public function index(?string $name = null, ?array $columns = null): self
    {
        $cols = $columns ?? [array_key_last($this->columns)];
        $indexName = $name ?? "{$this->table}_" . implode('_', $cols) . "_index";
        $this->indexes[$indexName] = "KEY `{$indexName}` (`" . implode('`, `', $cols) . "`)";
        return $this;
    }
    
    public function primary(array $columns): self
    {
        $this->indexes['primary'] = "PRIMARY KEY (`" . implode('`, `', $columns) . "`)";
        return $this;
    }
    
    public function foreign(string $column, string $referencedTable, string $referencedColumn = 'id', string $onDelete = 'CASCADE', string $onUpdate = 'CASCADE'): self
    {
        $name = "{$this->table}_{$column}_foreign";
        $this->foreignKeys[$name] = "CONSTRAINT `{$name}` FOREIGN KEY (`{$column}`) REFERENCES `{$referencedTable}` (`{$referencedColumn}`) ON DELETE {$onDelete} ON UPDATE {$onUpdate}";
        return $this;
    }
    
    public function engine(string $engine): self
    {
        $this->engine = $engine;
        return $this;
    }
    
    public function charset(string $charset, ?string $collation = null): self
    {
        $this->charset = $charset;
        $this->collation = $collation ?? $charset . '_unicode_ci';
        return $this;
    }
    
    public function execute(): void
    {
        if ($this->create) {
            $this->createTable();
        } else {
            $this->alterTable();
        }
    }
    
    private function createTable(): void
    {
        $cols = implode(",\n    ", $this->columns);
        $indexes = $this->indexes ? ",\n    " . implode(",\n    ", $this->indexes) : '';
        $fks = $this->foreignKeys ? ",\n    " . implode(",\n    ", $this->foreignKeys) : '';
        
        $sql = "CREATE TABLE `{$this->table}` (\n    {$cols}{$indexes}{$fks}\n) ENGINE={$this->engine} DEFAULT CHARSET={$this->charset} COLLATE={$this->collation}";
        
        $this->db->execute($sql);
    }
    
    private function alterTable(): void
    {
        foreach ($this->columns as $name => $def) {
            $exists = $this->db->selectOne(
                "SELECT COUNT(*) as cnt FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?",
                [$this->table, $name]
            );
            
            if ($exists && $exists['cnt'] > 0) {
                $this->db->execute("ALTER TABLE `{$this->table}` MODIFY COLUMN {$def}");
            } else {
                $this->db->execute("ALTER TABLE `{$this->table}` ADD COLUMN {$def}");
            }
        }
        
        foreach ($this->indexes as $name => $def) {
            if ($name !== 'primary') {
                try {
                    $this->db->execute("ALTER TABLE `{$this->table}` ADD {$def}");
                } catch (\Throwable $e) {
                    // Index might already exist
                }
            }
        }
        
        foreach ($this->foreignKeys as $name => $def) {
            try {
                $this->db->execute("ALTER TABLE `{$this->table}` ADD {$def}");
            } catch (\Throwable $e) {
                // FK might already exist
            }
        }
    }
}
<?php

namespace Core;

/**
 * Base Seeder class
 */
abstract class Seeder
{
    protected \Core\Database $db;
    
    public function __construct()
    {
        $this->db = \Core\Database::getInstance(config('database.connections.' . config('database.default')));
    }
    
    abstract public function run(): void;
    
    protected function insert(string $table, array $data): int
    {
        return $this->db->insert($table, $data);
    }
    
    protected function insertMany(string $table, array $data): int
    {
        $count = 0;
        foreach ($data as $row) {
            $this->db->insert($table, $row);
            $count++;
        }
        return $count;
    }
    
    protected function truncate(string $table): void
    {
        $this->db->execute("TRUNCATE TABLE `{$table}`");
    }
}
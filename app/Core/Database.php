<?php

namespace Core;

use PDO;
use PDOException;

/**
 * Singleton Database Connection - PDO wrapper con query builder básico
 */
class Database
{
    private static ?self $instance = null;
    private PDO $pdo;
    private array $config;
    private array $queryLog = [];
    
    private function __construct(array $config)
    {
        $this->config = $config;
        $this->connect();
    }
    
    public static function getInstance(array $config = null): self
    {
        if (self::$instance === null) {
            if ($config === null) {
                throw new \RuntimeException('Database config required on first call');
            }
            self::$instance = new self($config);
        }
        return self::$instance;
    }
    
    private function connect(): void
    {
        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=%s',
            $this->config['host'],
            $this->config['port'],
            $this->config['database'],
            $this->config['charset']
        );
        
        try {
            $this->pdo = new PDO($dsn, $this->config['username'], $this->config['password'], $this->config['options']);
        } catch (PDOException $e) {
            throw new \RuntimeException('Database connection failed: ' . $e->getMessage(), 0, $e);
        }
    }
    
    public function getPdo(): PDO
    {
        return $this->pdo;
    }
    
    // ===== Query Builder Básico =====
    
    public function table(string $table): QueryBuilder
    {
        return new QueryBuilder($this->pdo, $table);
    }
    
    public function select(string $query, array $params = []): array
    {
        return $this->execute($query, $params)->fetchAll();
    }
    
    public function selectOne(string $query, array $params = []): ?array
    {
        return $this->execute($query, $params)->fetch() ?: null;
    }
    
    public function insert(string $table, array $data): int
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        
        $this->execute($sql, $data);
        return (int)$this->pdo->lastInsertId();
    }
    
    public function update(string $table, array $data, string $where, array $whereParams = []): int
    {
        $params = [];
        $set = implode(', ', array_map(
            static function ($key, $index) use ($data, &$params): string {
                $placeholder = "__update_set_{$index}";
                $params[$placeholder] = $data[$key];
                return "{$key} = :{$placeholder}";
            },
            array_keys($data),
            array_keys($data)
        ));

        if (array_is_list($whereParams)) {
            $index = 0;
            $where = preg_replace_callback('/\?/', static function () use (&$index, $whereParams, &$params): string {
                if (!array_key_exists($index, $whereParams)) {
                    throw new \InvalidArgumentException('Not enough parameters supplied for UPDATE condition.');
                }

                $placeholder = "__update_where_{$index}";
                $params[$placeholder] = $whereParams[$index];
                $index++;
                return ':' . $placeholder;
            }, $where);

            if ($index !== count($whereParams)) {
                throw new \InvalidArgumentException('Too many parameters supplied for UPDATE condition.');
            }
        } else {
            $params = array_merge($params, $whereParams);
        }

        $sql = "UPDATE {$table} SET {$set} WHERE {$where}";
        
        return $this->execute($sql, $params)->rowCount();
    }
    
    public function delete(string $table, string $where, array $params = []): int
    {
        $sql = "DELETE FROM {$table} WHERE {$where}";
        return $this->execute($sql, $params)->rowCount();
    }
    
    public function execute(string $sql, array $params = []): \PDOStatement
    {
        $this->logQuery($sql, $params);
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
    
    public function transaction(callable $callback): mixed
    {
        $this->pdo->beginTransaction();
        try {
            $result = $callback($this);
            $this->pdo->commit();
            return $result;
        } catch (\Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
    
    private function logQuery(string $sql, array $params): void
    {
        if (config('debug')) {
            $this->queryLog[] = [
                'sql' => $sql,
                'params' => $params,
                'time' => microtime(true),
            ];
        }
    }
    
    public function getQueryLog(): array
    {
        return $this->queryLog;
    }
}

/**
 * Query Builder fluido simple
 */
class QueryBuilder
{
    private PDO $pdo;
    private string $table;
    private array $wheres = [];
    private array $params = [];
    private array $selects = ['*'];
    private array $joins = [];
    private ?int $limit = null;
    private ?int $offset = null;
    private array $orders = [];
    private array $groups = [];
    private string $latestQuery = '';
    
    public function __construct(PDO $pdo, string $table)
    {
        $this->pdo = $pdo;
        $this->table = $table;
    }
    
    public function select(array|string $columns = ['*']): self
    {
        $this->selects = is_array($columns) ? $columns : func_get_args();
        return $this;
    }
    
    public function where(string $column, string $operator, mixed $value, string $boolean = 'and'): self
    {
        $this->wheres[] = ['column' => $column, 'operator' => $operator, 'value' => $value, 'boolean' => $boolean];
        return $this;
    }
    
    public function whereIn(string $column, array $values): self
    {
        $this->wheres[] = ['column' => $column, 'operator' => 'IN', 'value' => $values, 'boolean' => 'and', 'in_values' => $values];
        return $this;
    }
    
    public function join(string $table, string $first, string $operator, string $second, string $type = 'inner'): self
    {
        $this->joins[] = compact('table', 'first', 'operator', 'second', 'type');
        return $this;
    }
    
    public function leftJoin(string $table, string $first, string $operator, string $second): self
    {
        return $this->join($table, $first, $operator, $second, 'left');
    }
    
    public function orderBy(string $column, string $direction = 'asc'): self
    {
        $this->orders[] = compact('column', 'direction');
        return $this;
    }
    
    public function groupBy(string|array $columns): self
    {
        $this->groups = is_array($columns) ? $columns : func_get_args();
        return $this;
    }
    
    public function limit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }
    
    public function offset(int $offset): self
    {
        $this->offset = $offset;
        return $this;
    }
    
    public function paginate(int $perPage = 15, int $page = 1): array
    {
        $this->offset(($page - 1) * $perPage)->limit($perPage);
        $items = $this->get();
        $total = $this->count();
        
        return [
            'data' => $items,
            'current_page' => $page,
            'per_page' => $perPage,
            'total' => $total,
            'last_page' => (int)ceil($total / $perPage),
        ];
    }
    
    public function get(string $modelClass = null): array
    {
        $sql = $this->toSql();
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->params);
        $results = $stmt->fetchAll();
        
        if ($modelClass && class_exists($modelClass)) {
            return array_map([$modelClass, 'hydrate'], $results);
        }
        
        return $results;
    }
    
    public function first(string $modelClass = null): ?array
    {
        $this->limit = 1;
        $results = $this->get($modelClass);
        return $results[0] ?? null;
    }
    
    public function count(): int
    {
        $clone = clone $this;
        $clone->selects = ['COUNT(*) as total'];
        $clone->orders = [];
        $clone->limit = null;
        $clone->offset = null;
        $clone->params = []; // Reset params to avoid conflicts
        $result = $clone->first();
        return (int)($result['total'] ?? 0);
    }
    
    public function exists(): bool
    {
        return $this->count() > 0;
    }
    
    public function insert(array $data): int
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);
        return (int)$this->pdo->lastInsertId();
    }
    
    public function update(array $data): int
    {
        $set = implode(', ', array_map(fn($k) => "{$k} = :{$k}", array_keys($data)));
        $sql = "UPDATE {$this->table} SET {$set}" . $this->buildWheres();
        
        $params = array_merge($data, $this->params);
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }
    
    public function delete(): int
    {
        $sql = "DELETE FROM {$this->table}" . $this->buildWheres();
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->params);
        return $stmt->rowCount();
    }
    
    public function toSql(): string
    {
        $sql = 'SELECT ' . implode(', ', $this->selects) . ' FROM ' . $this->table;
        
        foreach ($this->joins as $join) {
            $sql .= " {$join['type']} JOIN {$join['table']} ON {$join['first']} {$join['operator']} {$join['second']}";
        }
        
        $sql .= $this->buildWheres();
        
        if ($this->groups) {
            $sql .= ' GROUP BY ' . implode(', ', $this->groups);
        }
        
        if ($this->orders) {
            $sql .= ' ORDER BY ' . implode(', ', array_map(fn($o) => "{$o['column']} {$o['direction']}", $this->orders));
        }
        
        if ($this->limit) {
            $sql .= ' LIMIT ' . $this->limit;
        }
        
        if ($this->offset) {
            $sql .= ' OFFSET ' . $this->offset;
        }
        
        $this->latestQuery = $sql;
        return $sql;
    }
    
    public function getLatestQuery(): string
    {
        return $this->latestQuery;
    }
    
    private function buildWheres(): string
    {
        $this->params = [];
        if (empty($this->wheres)) return '';

        $sql = ' WHERE ';
        foreach ($this->wheres as $i => $where) {
            if ($i > 0) {
                $sql .= ' ' . strtoupper($where['boolean']) . ' ';
            }
            
            if (isset($where['in_values'])) {
                $placeholders = [];
                foreach ($where['in_values'] as $value) {
                    $paramName = ':param_' . count($this->params);
                    $placeholders[] = $paramName;
                    $this->params[$paramName] = $value;
                }
                $sql .= "{$where['column']} {$where['operator']} (" . implode(', ', $placeholders) . ')';
            } else {
                $paramName = ':param_' . count($this->params);
                $this->params[$paramName] = $where['value'];
                $sql .= "{$where['column']} {$where['operator']} {$paramName}";
            }
        }
        return $sql;
    }
}
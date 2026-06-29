<?php

declare(strict_types=1);

namespace App\Core;

use PDO;

abstract class Model
{
    protected static string $table = '';
    protected static string $primaryKey = 'id';
    protected static array $fillable = [];
    protected static array $hidden = ['password'];
    protected PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function find(int $id): ?array
    {
        $table = static::$table;
        $pk = static::$primaryKey;
        $stmt = $this->db->prepare("SELECT * FROM {$table} WHERE {$pk} = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function findBy(string $column, mixed $value): ?array
    {
        $table = static::$table;
        $stmt = $this->db->prepare("SELECT * FROM {$table} WHERE {$column} = :value LIMIT 1");
        $stmt->execute(['value' => $value]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function all(string $orderBy = 'id', string $direction = 'DESC'): array
    {
        $table = static::$table;
        $stmt = $this->db->query("SELECT * FROM {$table} ORDER BY {$orderBy} {$direction}");
        return $stmt->fetchAll();
    }

    public function where(array $conditions, string $orderBy = 'id', string $direction = 'DESC'): array
    {
        $table = static::$table;
        $where = [];
        $params = [];

        foreach ($conditions as $column => $value) {
            $where[] = "{$column} = :{$column}";
            $params[$column] = $value;
        }

        $whereClause = implode(' AND ', $where);
        $stmt = $this->db->prepare(
            "SELECT * FROM {$table} WHERE {$whereClause} ORDER BY {$orderBy} {$direction}"
        );
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function create(array $data): int
    {
        $table = static::$table;
        $filteredData = $this->filterFillable($data);
        $filteredData['created_at'] = date('Y-m-d H:i:s');
        $filteredData['updated_at'] = date('Y-m-d H:i:s');

        $columns = implode(', ', array_keys($filteredData));
        $placeholders = ':' . implode(', :', array_keys($filteredData));

        $stmt = $this->db->prepare("INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})");
        $stmt->execute($filteredData);

        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $table = static::$table;
        $pk = static::$primaryKey;
        $filteredData = $this->filterFillable($data);
        $filteredData['updated_at'] = date('Y-m-d H:i:s');

        $set = [];
        foreach ($filteredData as $column => $value) {
            $set[] = "{$column} = :{$column}";
        }
        $setClause = implode(', ', $set);
        $filteredData['id'] = $id;

        $stmt = $this->db->prepare("UPDATE {$table} SET {$setClause} WHERE {$pk} = :id");
        return $stmt->execute($filteredData);
    }

    public function delete(int $id): bool
    {
        $table = static::$table;
        $pk = static::$primaryKey;
        $stmt = $this->db->prepare("DELETE FROM {$table} WHERE {$pk} = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function count(array $conditions = []): int
    {
        $table = static::$table;

        if (empty($conditions)) {
            $stmt = $this->db->query("SELECT COUNT(*) FROM {$table}");
            return (int)$stmt->fetchColumn();
        }

        $where = [];
        $params = [];
        foreach ($conditions as $column => $value) {
            $where[] = "{$column} = :{$column}";
            $params[$column] = $value;
        }

        $whereClause = implode(' AND ', $where);
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$table} WHERE {$whereClause}");
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    public function paginate(int $page = 1, int $perPage = 15, array $conditions = []): array
    {
        $table = static::$table;
        $offset = ($page - 1) * $perPage;
        $total = $this->count($conditions);

        $where = '';
        $params = [];
        if (!empty($conditions)) {
            $whereParts = [];
            foreach ($conditions as $column => $value) {
                $whereParts[] = "{$column} = :{$column}";
                $params[$column] = $value;
            }
            $where = 'WHERE ' . implode(' AND ', $whereParts);
        }

        $stmt = $this->db->prepare(
            "SELECT * FROM {$table} {$where} ORDER BY id DESC LIMIT :limit OFFSET :offset"
        );
        foreach ($params as $key => $value) {
            $stmt->bindValue(":{$key}", $value);
        }
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return [
            'data' => $stmt->fetchAll(),
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => (int)ceil($total / $perPage),
        ];
    }

    public function rawQuery(string $sql, array $params = []): array
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function rawExecute(string $sql, array $params = []): bool
    {
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    private function filterFillable(array $data): array
    {
        if (empty(static::$fillable)) {
            return $data;
        }
        return array_intersect_key($data, array_flip(static::$fillable));
    }
}

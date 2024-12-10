<?php

namespace Framework\Database;

use PDO;
use PDOException;

class Model
{
    protected static PDO $db;
    protected static string $table;

    public function __construct()
    {
        if (!isset(static::$db)) {
            throw new PDOException("Database connection is not initialized");
        }
    }

    public static function setDatabase(PDO $db): void
    {
        static::$db = $db;
    }

    public static function all(): array
    {
        $stmt = static::$db->query("SELECT * FROM " . static::$table);
        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $stmt = static::$db->prepare("SELECT * FROM " . static::$table . " WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public static function create(array $data): bool
    {
        $keys = implode(',', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $sql = "INSERT INTO " . static::$table . " ($keys) VALUES ($placeholders)";

        $stmt = static::$db->prepare($sql);
        return $stmt->execute($data);
    }

    public static function update(int $id, array $data): bool
    {
        $setClause = implode(', ', array_map(fn($key) => "$key = :$key", array_keys($data)));
        $sql = "UPDATE " . static::$table . " SET $setClause WHERE id = :id";

        $stmt = static::$db->prepare($sql);
        $data['id'] = $id;
        return $stmt->execute($data);
    }

    public static function delete(int $id): bool
    {
        $stmt = static::$db->prepare("DELETE FROM " . static::$table . " WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}

<?php

namespace Framework\Database;

use PDO;
use PDOException;

class Database
{
    private PDO $connection;

    public function __construct(string $dsn, string $username, string $password, array $options = [])
    {
        try {
//            $dsn = sprintf(
//                'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
//                $_ENV['DB_HOST'],
//                $_ENV['DB_PORT'],
//                $_ENV['DB_DATABASE']
//            );
            $defaultOptions = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ];

            $this->connection = new PDO($dsn, $username, $password, $options + $defaultOptions);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new PDOException("Database connection failed: " . $e->getMessage());
        }
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }
}

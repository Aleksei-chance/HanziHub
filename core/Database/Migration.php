<?php

namespace Framework\Database;

use PDO;

class Migration
{
    protected PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function run(string $migration): void
    {
        $filePath = __DIR__ . '/../../database/migrations/' . $migration . '.php';

        if (!file_exists($filePath)) {
            throw new \Exception("Migration file not found: {$filePath}");
        }

        require_once $filePath;

        if (!class_exists($migration)) {
            throw new \Exception("Migration class not found: {$migration}");
        }

        $migrationClass = new $migration($this->db);
        $migrationClass->up();

        $this->logMigration($migration);
    }

    public function rollback(string $migration): void
    {
        $filePath = __DIR__ . '/../../database/migrations/' . $migration . '.php';

        if (!file_exists($filePath)) {
            throw new \Exception("Migration file not found: {$filePath}");
        }

        require_once $filePath;

        if (!class_exists($migration)) {
            throw new \Exception("Migration class not found: {$migration}");
        }

        $migrationClass = new $migration($this->db);
        $migrationClass->down();

        $this->removeMigrationLog($migration);
    }

    protected function logMigration(string $migration): void
    {
        $this->db->exec("INSERT INTO migrations (migration) VALUES ('$migration')");
    }

    protected function removeMigrationLog(string $migration): void
    {
        $this->db->exec("DELETE FROM migrations WHERE migration = '$migration'");
    }

    public function getExecutedMigrations(): array
    {
        $stmt = $this->db->query("SELECT migration FROM migrations");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}

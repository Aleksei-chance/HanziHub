<?php

namespace Framework\Database;

use PDO;

class Migration
{
    protected PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->ensureMigrationsTable();
    }

    protected function ensureMigrationsTable(): void
    {
        $this->db->exec("
            CREATE TABLE IF NOT EXISTS migrations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );
        ");
    }

    public function runAll(): void
    {
        $pendingMigrations = $this->getPendingMigrations();

        foreach ($pendingMigrations as $migration) {
            echo "Running migration: $migration\n";
            $this->run($migration);
        }
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

    public function getPendingMigrations(): array
    {
        $executedMigrations = $this->getExecutedMigrations();
        $migrationFiles = glob(__DIR__ . '/../../database/migrations/*.php');

        $pendingMigrations = [];
        foreach ($migrationFiles as $file) {
            $migration = basename($file, '.php');
            if (!in_array($migration, $executedMigrations)) {
                $pendingMigrations[] = $migration;
            }
        }

        return $pendingMigrations;
    }
}

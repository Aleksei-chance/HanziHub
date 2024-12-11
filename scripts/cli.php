<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Framework\Console\CommandRunner;
use Framework\Database\Migration;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$pdo = new PDO(
    "mysql:host={$_ENV['DB_HOST']};port={$_ENV['DB_PORT']};dbname={$_ENV['DB_DATABASE']}",
    $_ENV['DB_USERNAME'],
    $_ENV['DB_PASSWORD']
);

$migrationManager = new Migration($pdo);

$runner = new CommandRunner();

$runner->registerCommand('migrate:up', function () use ($migrationManager) {
    $migrationManager->runAll();
    echo "All pending migrations applied.\n";
});

$runner->registerCommand('migrate:rollback', function ($args) use ($migrationManager) {
    $migration = $args[0] ?? null;
    if (!$migration) {
        echo "Please specify a migration to rollback.\n";
        return;
    }
    $migrationManager->rollback($migration);
    echo "Migration $migration roller back.";
});

$runner->registerCommand('migrate:status', function () use ($migrationManager) {
    $executed = $migrationManager->getExecutedMigrations();
    echo "Executed migrations:\n";
    foreach ($executed as $migration) {
        echo " - {$migration}\n";
    }
});

try {
    $runner->run($argv);
} catch (Exception $e) {
    echo "Runner error: " . $e->getMessage();
}

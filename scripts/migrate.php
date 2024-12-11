<?php

global $pdo;
require_once __DIR__ . '/../bootstrap/app.php';

use Framework\Database\Migration;

$migrationManager = new Migration($pdo);

$comand = $argv[1] ?? null;

if ($comand === 'up') {
    $migrationManager->runAll();
    echo "Migrations applied.\n";
} else if ($comand === 'down') {
    $migrationManager->rollback('CreateBlocksTable');
    echo "Migrations rolled back.\n";
} else {
    echo "Usage: php migrate.php [up|down]\n";
}

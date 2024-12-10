<?php

use Framework\Support\ExceptionHandler;

require_once __DIR__ . '/../bootstrap/app.php';

try {
    $stmt = $pdo->query('SELECT NOW() as `current_time`');
    $result = $stmt->fetch();
    dump($result);
} catch (Exception $e) {
    echo "Database error: " . $e->getMessage();
}

$exceptionHandler = new ExceptionHandler();

set_exception_handler([$exceptionHandler, 'handle']);


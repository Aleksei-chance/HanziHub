<?php

require_once __DIR__ . '/../bootstrap/app.php';

use Framework\Support\ExceptionHandler;

$exceptionHandler = new ExceptionHandler();

set_exception_handler([$exceptionHandler, 'handle']);


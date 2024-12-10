<?php

namespace Framework\Support;

use Throwable;

class ExceptionHandler
{
    public function handle(Throwable $exception): void
    {
        http_response_code(500);

        if ($_ENV['APP_ENV'] === 'development') {
            echo "<h1>Exception: " . $exception->getMessage() . "</h1>";
            echo "<p>In file: " . $exception->getFile() . " on line " . $exception->getLine() . "</p>";
            echo "<pre>" . $exception->getTraceAsString() . "</pre>";
        } else {
            echo "An error occurred. Please try again later.";
        }

        error_log($exception);
    }
}

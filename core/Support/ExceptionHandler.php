<?php

namespace Framework\Support;

use Throwable;

class ExceptionHandler
{
    public static function handle(Throwable $exception): void
    {
        http_response_code(500);

        if (self::isApiRequest()) {
            header('Content-Type: application/json');
            echo json_encode([
                'error' => true,
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ]);
        } else {
            header('Content-Type: text/html');
            echo "<h1>Server Error</h1>";
            echo "<p><strong>Message:</strong> {$exception->getMessage()}</p>";
            echo "<p><strong>File:</strong> {$exception->getFile()}</p>";
            echo "<p><strong>Line:</strong> {$exception->getLine()}</p>";
        }

        self::logError($exception);
//        if ($_ENV['APP_ENV'] === 'development') {
//            echo "<h1>Exception: " . $exception->getMessage() . "</h1>";
//            echo "<p>In file: " . $exception->getFile() . " on line " . $exception->getLine() . "</p>";
//            echo "<pre>" . $exception->getTraceAsString() . "</pre>";
//        } else {
//            echo "An error occurred. Please try again later.";
//        }
//
//        error_log($exception);
    }

    private static function isApiRequest(): bool
    {

        $acceptHeader = $_SERVER['HTTP_ACCEPT'] ?? '';
        if (strpos($acceptHeader, 'application/json') !== false) {
            return true;
        }

        $requestUri = $_SERVER['REQUEST_URI'] ?? '';
        if (strpos($requestUri, '/api') === 0) {
            return true;
        }

        return false;
    }

    private static function logError(Throwable $exception): void
    {
        $logMessage = "[" . date('Y-m-d H:i:s') . "] " . $exception->getMessage() . " in" . $exception->getFile() . ":" . $exception->getLine() . "\n";
        file_put_contents(__DIR__ . '/../../logs/error.log', $logMessage, FILE_APPEND);
    }
}

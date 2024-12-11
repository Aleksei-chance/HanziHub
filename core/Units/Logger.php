<?php

namespace Framework\Units;

class Logger
{
    protected static string $logFile = __DIR__ . '/../../logs/error.log';

    public static function logError(string $message): void
    {
        $timestamp = date('Y-m-d H:i:s');
        $formattedMessage = "[{$timestamp}] {$message}\n";

        $logDir = dirname(self::$logFile);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true);
        }

        file_put_contents(self::$logFile, $formattedMessage, FILE_APPEND);
    }
}

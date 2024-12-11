<?php

namespace Framework\Console;


class CommandRunner
{
    protected array $commands = [];

    public function registerCommand(string $name, callable $handle): void
    {
        $this->commands[$name] = $handle;
    }

    public function run(array $argv): void
    {
        $command = $argv[1] ?? null;

        if (!$command || !isset($this->commands[$command])) {
            throw new \Exception("Unknown command: {$command}\n");
            echo "Unknown command: {$command}\n";
            echo "Availabale commands:\n";
            foreach (array_keys($this->commands) as $name) {
                echo " - $name\n";
            }
            exit(1);
        }

        try {
            $handler = $this->commands[$command];
            $handler(array_slice($argv, 2));
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
        }
    }
}

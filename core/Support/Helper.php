<?php

namespace Framework\Support;

class Helper
{
    public static function greet(string $name): string
    {
        return "Hello, {$name}";
    }
}

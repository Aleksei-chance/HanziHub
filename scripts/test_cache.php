<?php

require_once __DIR__ . '/../bootstrap/app.php';

use Framework\Cache\Cache;

$cache = new Cache();

$cache->set('example_key', 'Hello, World!', 15);
echo "Cached value: " . $cache->get('example_key') . PHP_EOL;

sleep(12);

echo "After expiration: " . var_export($cache->get('example_key'), true) . PHP_EOL;

$cache->clear();

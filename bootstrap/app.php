<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Framework\Routing\Router;

$router = new Router();

$router->add('GET', '/', function () {
    return "Welcome to HanziHub!";
});

$router->add('GET','/about', function () {
    return "About HanziHub";
});

$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

dump($router->dispatch($method, $uri));

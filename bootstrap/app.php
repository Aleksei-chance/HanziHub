<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Framework\Routing\Router;

$router = new Router();

$router->add('GET', '/', function () {
    return "Welcome to HanziHub!";
});

$router->add('GET','/user/{id}', function ($id) {
    return "User ID: $id";
});

$router->add('GET','/post/{id}/comment/{commentId}', function ($id, $commentId) {
    return "Post ID: $id, Comment ID: $commentId";
});

$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

dump($router->dispatch($method, $uri));

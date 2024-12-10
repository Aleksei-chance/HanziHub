<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

use Framework\Routing\Router;
use App\Controllers\HomeController;
use App\Middleware\AuthMiddleware;

$router = new Router();
$homeController = new HomeController();

$router->add('GET', '/', [$homeController, 'index']);
$router->add('GET', '/dashboard', [$homeController, 'dashboard'], [AuthMiddleware::class]);

$router->add('GET','/user/{id}', function ($id) {
    return "User ID: $id";
});

$router->add('GET','/post/{id}/comment/{commentId}', function ($id, $commentId) {
    return "Post ID: $id, Comment ID: $commentId";
});

$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

dump($router->dispatch($method, $uri));

<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use Framework\Database\Database;
use Framework\Database\Model;
use Framework\Routing\Router;
use App\Controllers\HomeController;
use App\Middleware\AuthMiddleware;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$database = new Database();
$pdo = $database->getConnection();

Model::setDatabase($pdo);


if (php_sapi_name() !== 'cli') {
    $router = new Router();
    $homeController = new HomeController();
    $method = $_SERVER['REQUEST_METHOD'];
    $uri = $_SERVER['REQUEST_URI'];

    $router->add('GET', '/', [$homeController, 'index']);
    $router->add('GET', '/dashboard', [$homeController, 'dashboard'], [AuthMiddleware::class]);

    $router->add('GET','/user/{id}', function ($id) {
        return "User ID: $id";
    });

    $router->add('GET','/post/{id}/comment/{commentId}', function ($id, $commentId) {
        return "Post ID: $id, Comment ID: $commentId";
    });

    if ($method && $uri) {
        dump($router->dispatch($method, $uri));
    } else {
        throw new \Exception("Invalid request context. HTTP method orURI is missing.");
    }
}








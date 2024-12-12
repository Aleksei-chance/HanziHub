<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use Framework\Database\Database;
use Framework\Database\Model;
use Framework\Routing\Router;
use App\Controllers\HomeController;
use App\Middleware\AuthMiddleware;
use Framework\Units\Logger;
use Framework\Support\ExceptionHandler;

set_exception_handler([ExceptionHandler::class, 'handle']);
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

//set_exception_handler(function (Exception $exception) {
//    Logger::logError($exception->getMessage());
//    http_response_code(500);
//    echo "An error occurred. Check the logs for details.";
//});

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$config = [
    'dsn' => "mysql:host={$_ENV['DB_HOST']};port={$_ENV['DB_PORT']};dbname={$_ENV['DB_DATABASE']};charset=utf8mb4",
    'username' => $_ENV['DB_USERNAME'],
    'password' => $_ENV['DB_PASSWORD'],
];

try {
    $database = new Database($config['dsn'], $config['username'], $config['password']);
    $pdo = $database->getConnection();
} catch (\Exception $e) {
    die("Error: " . $e->getMessage());
}


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
        echo($router->dispatch($method, $uri));
    } else {
        throw new \Exception("Invalid request context. HTTP method orURI is missing.");
    }
}

//throw new Exception('Test!');





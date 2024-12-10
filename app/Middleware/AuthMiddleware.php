<?php

namespace App\Middleware;

use Closure;
class AuthMiddleware implements MiddlewareInterface
{
    public function handle($request, Closure $next)
    {
        if (empty($_SESSION['user'])) {
            http_response_code(403);
            die('Access denied. You are not logged in.');
        }

        return $next($request);
    }
}

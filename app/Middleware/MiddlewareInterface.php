<?php

namespace App\Middleware;

use Closure;

interface MiddlewareInterface
{
    public function handle($request, Closure $next);
}

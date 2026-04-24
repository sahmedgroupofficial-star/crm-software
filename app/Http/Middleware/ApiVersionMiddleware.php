<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiVersionMiddleware
{
    public function handle(Request $request, Closure $next): mixed
    {
        return $next($request);
    }
}

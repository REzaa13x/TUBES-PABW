<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AllowCrossOrigin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Laravel's global HandleCors middleware uses config/cors.php, including
        // preflight requests. Do not override the configured origin with '*'.
        return $next($request);
    }
}

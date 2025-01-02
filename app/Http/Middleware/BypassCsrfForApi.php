<?php

namespace App\Http\Middleware;

use Closure;

class BypassCsrfForApi
{
    public function handle($request, Closure $next)
    {
        // Nonaktifkan CSRF untuk rute ini
        return $next($request);
    }
}

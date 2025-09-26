<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Authenticate
{
    public function handle(Request $request, Closure $next, ...$guards): Response
    {
        if (!auth()->check()) {
            // Redirect guests to login page
            return redirect('/login');
        }

        return $next($request);
    }
}

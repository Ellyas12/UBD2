<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureCodeVerified
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('password_reset_verified')) {
            return redirect()->route('verify.form')
                ->with('error', 'Please verify your code first.');
        }

        return $next($request);
    }
}
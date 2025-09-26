<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureEmailSubmitted
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('password_reset_email')) {
            return redirect()->route('forgot.form')
                ->with('error', 'Please enter your email first.');
        }

        return $next($request);
    }
}
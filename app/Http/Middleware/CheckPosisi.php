<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPosisi
{
    public function handle($request, Closure $next, ...$posisis)
    {
        if (!in_array(auth()->user()->posisi, $posisis)) {
            abort(403, 'Access denied');
        }

        return $next($request);
    }
}

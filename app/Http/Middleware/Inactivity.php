<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Inactivity
{
    public function handle($request, Closure $next)
    {
        $timeout = 10 * 60;

        if (Auth::check()) {
            $last = session('lastActivityTime');

            if ($last && (time() - $last) > $timeout) {
                Auth::logout();
                session()->flush();

                return redirect()->route('login.form')
                    ->with('message', 'You have been logged out due to inactivity.');
            }

            session(['lastActivityTime' => time()]);
        }

        return $next($request);
    }
}

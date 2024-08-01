<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRegistration
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && session('registration_completed')) {
            return $next($request);
        }
        return redirect('/');
    }
}

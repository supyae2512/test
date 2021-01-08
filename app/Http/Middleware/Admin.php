<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Admin
{
    private $login_url = 'auth/login';

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::guard('web')->check() && Auth::guard('web')->user()) {
            return $next($request);
        }
//        if (Auth::check() && Auth::user()->isAdmin()) {
//            return $next($request);
//        }

        return redirect($this->login_url);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {

//        if (Auth::guard($guard)->check()) {
//            return redirect('/home');
//        }

        if (Auth::guard($guard)->guest()) {
            if ($request->ajax() || $request->wantsJson()) {

                return response('Unauthorized.', 401);
            } else {
                $response = [
                    'status'  => 401,
                    'message' => 'Unauthorized API Request'
                ];
                return Response::json($response);

            }
        }

        return $next($request);
    }
}

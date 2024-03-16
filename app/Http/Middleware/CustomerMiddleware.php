<?php

namespace App\Http\Middleware;

use Closure;
use Redirect;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CustomerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!Auth::check()){
            return Redirect::to("/");
        }

        if (Auth::user() && Auth::user()->user_type != '2')
        {
           // return new Response(view('unauthorized')->with('role', 'Doctor'));
             return Redirect::to("/");
        }
        return $next($request);
    }
}
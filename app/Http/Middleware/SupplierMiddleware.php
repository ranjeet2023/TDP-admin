<?php

namespace App\Http\Middleware;

use Closure;
use Redirect;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class SupplierMiddleware
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

        if (Auth::user() && Auth::user()->user_type != '3')
        {
            return Redirect::to("/");
            //return new Response(view('unauthorized')->with('role', 'Patient'));
        }
        return $next($request);
    }
}
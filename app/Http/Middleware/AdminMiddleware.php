<?php

namespace App\Http\Middleware;

use Closure;
use Redirect;
use Illuminate\Http\Response;
use DB;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
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

        if ($request->user() && !in_array($request->user()->user_type, array(1,4,5,6)))
        {
            return Redirect::to("/");
            //return new Response(view('unauthorized')->with('role', 'Admin'));
        }

        // $permission = DB::table('user_has_permission')->join('permission', 'permission.permission_id', '=', 'user_has_permission.permission_id')->where('user_id', Auth::user()->id)->get()->toArray();

        // var_dump(array_search($request->segment(1), array_column($permission, 'url')));
        // // die;
        // if (array_search($request->segment(1), array_column($permission, 'url')) === FALSE) {
        //     echo "redirect";
        //     // return Redirect::to("/");
        // }
        // echo "<pre>";
        // print_r($permission);
        // die;
        return $next($request);
    }
}

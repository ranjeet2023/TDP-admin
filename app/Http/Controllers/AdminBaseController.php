<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Utils\ResponseUtil;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminBaseController extends Controller
{
    public function __construct()
	{
		// if(Auth::check()){
        //     return view('dashboard');
        // }

        // return redirect("login")->withSuccess('You are not allowed to access');
		// $this->middleware('auth');

        // $data['admin'] = DB::table('user_has_permission')->join('users', 'users.id', '=', 'suppliers.sup_id')->where('user_id', Auth::user()->id)->get()->toArray();
        // dd($data['admin']);
	}


}

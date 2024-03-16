<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class GetInTouch extends Controller
{
    public function SendMessage(Request $request)
    {
        $request->validate([
            'firstname'=>'required',
            'lastname'=>'required',
            'phoneno'=>'required|numeric|min:10',
            'email' => 'required|email|unique:get_in_touche',
            'message'=>'required'
        ]);


        DB::table('get_in_touche')->insert([
            'firstname'=>$request->firstname,
            'lastname'=>$request->lastname,
            'email'=>$request->email,
            'mobile'=>$request->phoneno,
            'message'=>$request->message
        ]);
        return redirect()->back()->with('success','Thanks for showing interst');
    }
}

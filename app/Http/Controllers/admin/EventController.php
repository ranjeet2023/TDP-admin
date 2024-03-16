<?php

namespace App\Http\Controllers\admin;

use DB;
use Mail;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function postEvent(Request $request)
    {
        $request->validate([
            'email' => "required|email",
        ]);

        $subscribers = array(
            'email' => $request->email,
            'event' => $request->event,
            'created_at'=>date_create(),
        );
        $id = DB::table('subscribers')->insert($subscribers);

        $email = $request->email;

        Mail::send('emails.event.jckemail', array(), function($message) use($email){
            $message->to($email);
            $message->cc(\Cons::EMAIL_INFO);
            $message->subject("Register for the Biggest Event in Jewelry Industry: JCK Las Vegas 2022");
        });

        return redirect('/thankyou')->with('update','Account Add Successful');
    }
}

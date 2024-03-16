<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Hash;
use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;


use Mail;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('frontend.login');
    }

    public function postLogin(Request $request) {

        $request->validate([
            'email' => 'required',
            'password' => 'required|min:8',
        ]);

		$email = $request->email;
		$password = $request->password;

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if(!empty(Auth::user()->email_verified_at)){

                if(Auth::user()->is_active == 1){
                    $auth_user = Auth::user();
                    $insert = $this->saveLoginHistory($auth_user);

                    if (in_array(Auth::user()->user_type, array(1,4,5,6))) {
                        return redirect()->intended('admin');
                    }
                    if (Auth::user()->user_type == 2) {
                        $customer = DB::table('customers')->select('*')->where('cus_id', Auth::user()->id)->first();
                        return redirect()->intended('dashboard');
                    }

                    if (Auth::user()->user_type == 3) {
                        $supplier = DB::table('suppliers')->select('*')->where('sup_id', Auth::user()->id)->first();
                        return redirect()->intended('supplier');
                    }
                }
                else
                {
                    Session::flush();
                    Auth::logout();
                    return redirect("login")->withErrors('We are verifying your account. Hold on for some time.');
                }
            }
            else
            {
                Session::flush();
                Auth::logout();
                return redirect("login")->withErrors('Please verify your email.');
            }
        }
        return redirect("login")->withErrors('Email or password is incorrect. if you fail to login please try forgot password.');
	}

    public function saveLoginHistory($auth_user)
    {
        $curl = curl_init();
		// curl_setopt_array($curl, array(
		// 	CURLOPT_URL => 'http://api.ipstack.com/'.$this->input->ip_address().'?access_key=30c972d503287bb13dfbfeb916add2ed',// info@tdp.com
		// 	CURLOPT_RETURNTRANSFER => true,
		// 	CURLOPT_ENCODING => '',
		// 	CURLOPT_MAXREDIRS => 10,
		// 	CURLOPT_TIMEOUT => 0,
		// 	CURLOPT_FOLLOWLOCATION => true,
		// 	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		// 	CURLOPT_CUSTOMREQUEST => 'GET',
		// 	CURLOPT_HTTPHEADER => array(
		// 		'Cookie: __cfduid=da246203664ab2527797b90a4587b2f721610545139'
		// 	),
		// ));

        // echo 'https://api.freegeoip.app/json/'.request()->ip().'?apikey=9403fb30-42be-11ec-82be-bf0143d6a305';
        // die;
		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://api.freegeoip.app/json/'.request()->ip().'?apikey=9403fb30-42be-11ec-82be-bf0143d6a305',// info@tdp.com
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		));

		// curl_setopt($curl, CURLOPT_URL, 'https://iptwist.com');
		// curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		// curl_setopt($curl, CURLOPT_POST, 1);
		// curl_setopt($curl, CURLOPT_POSTFIELDS, "{\"ip\": \"".$this->input->ip_address()."\"}");
		// $headers = array();
		// $headers[] = 'Content-Type: application/json';
		// $headers[] = 'X-Iptwist-Token: 0tDoQyZjaWndPY7sJAgKf2hbITi230YMb9kGlbKwS1VawEfiIyDpcreLbHFYQLgI';//zIgh9mDblz4Qpkq94aHBmEYtI7RZJuB8EQSsxepJwGBxRubVwWvCzPBOOqqJvrEk
		// curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

		$response = curl_exec($curl);
		if (curl_errno($curl)) {
			// echo 'Error:' . curl_error($curl);
		}
		curl_close($curl);
		$data_ip = json_decode($response);
        session(['country' => 'india']);
		if(!empty($data_ip))
		{
            DB::table('login_history')->insert(
                array(
                    'userid' => $auth_user->id,
                    'user_type' => $auth_user->user_type,
                    'lastlogin' => date("Y-m-d H:i:s"),
                    'ip' => request()->ip(),
                    'city' => @$data_ip->city." ". @$data_ip->region_name,
                    'country' => @$data_ip->country_name,
                    // 'city' => $data_ip->city." ".$data_ip->state,
                    // 'country' => $data_ip->country,
                    'browser' => request()->userAgent(),
                )
            );
        }
        return true;
    }

    public function register()
    {
        return view('frontend.register');
    }

    public function postRegistration(Request $request) {

        $request->validate([
            'type'=>'required',
            'email' => 'required|email|unique:users|min:4',
			'mobile' => 'required|min:8',
            'companyname' => 'required|unique:users|min:4',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
        ]);

        //diamond_type

		$data = $request->all();
        // dd($data);
        $token = mt_rand() . time();
		$insert = $this->create($data, $token);

        $last_insert_id = $insert->id;

        if($data['type'] == 'supplier')
        {
            try {
                Mail::send('emails.supplier_register', ['firstname'=> $request->firstname, 'lastname'=> $request->lastname, 'link' => $token], function($message) use($request){
                    $message->to($request->email);
                    $message->subject("Thank you for registering with us | ". config('app.name'));
                });
            } catch (\Throwable $th) {

            }

            DB::table('suppliers')->insert(
                array(
                    'sup_id' => $last_insert_id,
                    'supplier_name' => $request->input('companyname'),
                    'diamond_type' => $request->input('diamond_type'),
                    'city' => $request->input('city'),
                    'country' => $request->input('country'),
                    'state' => $request->input('state'),
                )
            );

            try {
                Mail::send('emails.admin_register', ['companyname'=> $request->companyname,
                    'firstname'=> $request->firstname,
                    'lastname'=> $request->lastname,
                    'email'=> $request->email,
                    'city'=> $request->city,
                    'country'=> $request->country,
                    'link' => $token], function($message) use($request){
                    $message->to(\Cons::EMAIL_SUPPLIER);
                    $message->subject('New Supplier Register on Web '.strip_tags($request->companyname));
                });
            } catch (\Throwable $th) {

            }
        }

        if($data['type'] == 'customer')
        {
            try {
                Mail::send('emails.customer_register', ['firstname'=> $request->firstname, 'lastname'=> $request->lastname, 'link' => $token], function($message) use($request){
                    $message->to($request->email);
                    $message->bcc('neha@thediamondport.com');
                    $message->subject("Thank you for registering with us | ". config('app.name'));
                });
            } catch (\Throwable $th) {

            }

            DB::table('customers')->insert(
                array(
                    'cus_id' => $last_insert_id,
                    'customer_type' => 4, //pending
                    'country' => $request->input('country'),
                    'state' => $request->input('state'),
                    'city' => $request->input('city'),
                    'shiping_email' => $request->input('email'),
                    'source' => 'web',
                )
            );

            try {
                Mail::send('emails.admin_register', ['companyname'=> $request->companyname,
                    'firstname'=> $request->firstname,
                    'lastname'=> $request->lastname,
                    'email'=> $request->email,
                    'city'=> $request->city,
                    'country'=> $request->country,
                    'link' => $token], function($message) use($request){
                    $message->to(\Cons::EMAIL_INFO);
                    $message->subject('New Customer Register on Web '.strip_tags($request->companyname));
                });
            } catch (\Throwable $th) {

            }
        }


		return redirect("login")->withSuccess('Registration successfully. please verify your email.');
	}

    public function forgotPassword()
    {
        return view('frontend.forgot-password');
    }

    public function postForgotPassword(Request $request) {

        $request->validate([
            'email' => 'required|email'
        ]);

        $user = DB::table('users')->where('email', $request->email)->first();
        if ($user) {
            $token = mt_rand() . time();
            DB::table('users')->where('email', $request->email)->update(['reset_token' => $token]);

            if($user->user_type == 2)
            {
                $data = DB::table('customers')->where('cus_id', $user->id)->first();
            }

            if($user->user_type == 3)
            {
                $data = DB::table('suppliers')->where('sup_id', $user->id)->first();
            }

            // try {
                Mail::send('emails.password_reset', ['firstname'=> $user->firstname, 'link' => $token], function($message) use($request){
                    $message->to($request->email);
                    $message->subject('You requested for Reset Password.');
                });
            // } catch (\Throwable $th) {
            //     //throw $th;
            // }

            return redirect()->back()->with('success', trans('A reset link has been sent to your email address.'));
        }

        return redirect()->back()->with(['success' => trans('your email address not found.')]);
	}

    public function resetPassword(Request $request) {
        $user = DB::table('users')->where('reset_token', $request->token)->first();
        if ($user) {
            $data['token'] = $request->token;
            return view('frontend.reset-password')->with($data);
        }
        return view('frontend.login');
	}

    public function postResetPassword(Request $request) {

        $request->validate([
            'token' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
        ]);

        $user = DB::table('users')->where('reset_token', $request->token)->first();
        if ($user) {
            $password = Hash::make($request->password);
            DB::table('users')->where('reset_token', $request->token)->update(['reset_token' => '', 'password' => $password]);

            try {
                Mail::send('emails.password_reset_success', ['firstname'=> $user->firstname], function($message) use($user){
                    $message->to($user->email);
                    $message->subject('Your password has been successfully reset | '. config('app.name'));
                });
            } catch (\Throwable $th) {

            }
            return redirect('login')->with('success', trans('Password was just changed.'));
        }
        return redirect('login');
	}


    public function verifyEmail(Request $request) {

        $token = $request->id;
        $user = DB::table('users')->where('email_verify_code', $request->id)->first();
        if ($user) {
            DB::table('users')->where('email_verify_code',' $request->id')->update(['email_verified_at' => now(), 'email_verify_code' => '']);

            try {
                Mail::send('emails.verify_email_success', ['firstname'=> $user->firstname, 'lastname' => $user->lastname, 'link' => $token], function($message) use($user){
                    $message->to($user->email);
                    $message->subject('Thank you, your Email is verified successfully | '. config('app.name'));
                });
            } catch (\Throwable $th) {

            }
            return redirect("login")->with(['success' => trans('Thank you for Verifying your Email.')]);
        }
        return redirect("login")->with(['success' => trans('Something went wrong!')]);
	}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($data, $token)
    {
        return User::create([
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'email' => $data['email'],
            'companyname' => $data['companyname'],
            'mobile' => $data['mobile'],
            'password' => Hash::make($data['password']),
            'email_verify_code' => $token,
            'user_type' => ($data['type'] == 'supplier') ? 3 : 2,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function logout() {
        Session::flush();
        Auth::logout();

        return Redirect('login');
    }
}

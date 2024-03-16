<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use DB;
use Hash;
use Session;
use Mail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use App\Exceptions\Handler;
use Illuminate\Support\Facades\Validator;

use App\Models\Supplier;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\ViewDiamondDetail;
use App\Models\WishList;
use App\Models\Order;
use App\Models\Cart;
use App\Models\DiamondLabgrown;
use App\Models\DiamondNatural;
use App\Models\SearchLog;
USE App\Models\InvoiceItem;
USE App\Models\Notification;

class UserApiController extends Controller
{

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
            'user_type' => ($data['user_type'] == 'supplier') ? 3 : 2,
        ]);
    }

    public function Login(Request $request)
    {
        $email   = $request->email;
        $password = $request->password;
        $device_token = $request->device_token;

        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            $user = Auth::user();
            $user_id = Auth::user()->id;
            DB::table('oauth_access_tokens')->where('user_id', $user_id)->delete();
            if (Auth::user()->email_verified_at) {
                if (Auth::user()->is_active == 1) {
                    DB::table('users')->where('id', $user_id)->update([
                        'device_token' => $device_token,
                    ]);
                    $user_login_token = Auth::user()->createToken('tdpapp')->accessToken;
                    return response()->json([
                        'success' => true,
                        'message' => 'Login successful',
                        'token' => $user_login_token,
                        'data' => Auth::user()
                    ], 200);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'We are verifying your account. Hold on for some time.',
                    ], 200);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Please verify your email',
                ], 200);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Email or password is incorrect. if you fail to login please try forgot password.'
            ], 401);
        }
    }

    public function Registration(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users|min:4',
            'mobile' => 'required|min:8',
            'companyname' => 'required|unique:users|min:4',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'user_type' => 'required',
            'firstname' => 'required',
            'lastname' => 'required',
            'password' => ['required', Password::min(8)->mixedCase()->numbers()->symbols()],
        ]);

        if ($validator->fails()) {
            $message = $validator->errors();
            return response()->json([
                'success' => false,
                'message' => $message
            ], 401);
        } else {
            $data = $request->all();
            $token = mt_rand() . time();
            $insert = $this->create($data, $token);
            $last_insert_id = $insert->id;
            if ($data['user_type'] == 'supplier') {
                try {
                    Mail::send('emails.supplier_register', ['firstname' => $request->firstname, 'lastname' => $request->lastname, 'link' => $token], function ($message) use ($request) {
                        $message->to($request->email);
                        $message->subject("Thank you for registering with us | " . config('app.name'));
                    });
                } catch (\Throwable $th) {
                }

                DB::table('suppliers')->insert([
                        'sup_id' => $last_insert_id,
                        'supplier_name' => $request->input('companyname'),
                        'diamond_type' => $request->input('diamond_type'),
                        'city' => $request->input('city'),
                        'country' => $request->input('country'),
                        'state' => $request->input('state'),
                ]);

                try {
                    Mail::send('emails.admin_register', [
                        'companyname' => $request->companyname,
                        'firstname' => $request->firstname,
                        'lastname' => $request->lastname,
                        'email' => $request->email,
                        'city' => $request->city,
                        'country' => $request->country,
                        'link' => $token
                    ], function ($message) use ($request) {
                        $message->to(\Cons::EMAIL_SUPPLIER);
                        $message->subject('New Supplier Register on Web ' . strip_tags($request->companyname));
                    });
                } catch (\Throwable $th) {
                }

                return response()->json([
                    'success' => true,
                    'Message' => 'Registration successfully. please verify your email. '
                ], 201);
            }

            if ($data['user_type'] == 'customer') {
                try {
                    Mail::send('emails.customer_register', ['firstname' => $request->firstname, 'lastname' => $request->lastname, 'link' => $token], function ($message) use ($request) {
                        $message->to($request->email);
                        $message->subject("Thank you for registering with us | " . config('app.name'));
                    });
                } catch (\Throwable $th) {
                }

                DB::table('customers')->insert([
                        'cus_id' => $last_insert_id,
                        'customer_type' => 4, //pending
                        'country' => $request->input('country'),
                        'state' => $request->input('state'),
                        'city' => $request->input('city'),
                        'shiping_email' => $request->input('email'),
                        'source' => 'web',
                ]);

                try {
                    Mail::send('emails.admin_register', [
                        'companyname' => $request->companyname,
                        'firstname' => $request->firstname,
                        'lastname' => $request->lastname,
                        'email' => $request->email,
                        'city' => $request->city,
                        'country' => $request->country,
                        'link' => $token
                    ], function ($message) use ($request) {
                        $message->to(\Cons::EMAIL_INFO);
                        $message->subject('New Customer Register on Web ' . strip_tags($request->companyname));
                    });
                } catch (\Throwable $th) {
                }

                return response()->json([
                    'success' => true,
                    'Message' => 'Registration successfully. please verify your email. '
                ], 201);
            }
        }
    }

    public function Version(Request $request)
    {
        $source = $request->source;

        if ($source == "android") {
            $status['version']    = "1.1.0";
            return response()->json([
                'success' => true,
                'data' =>  $status,
                'message' => $status
            ]);
        } elseif ($source == "ios") {
            $status['version']    = "1.10";
            return response()->json([
                'success' => true,
                'data' =>  $status,
                'message' => $status
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => "Please enter perameters"
            ]);
        }
    }

    public function GuestLogin()
    {
        $status['guestlogin'] = 0; //0 = disable() and 1 = enable
        return response()->json([
            'success' => true,
            'message' => 'Guest Login Status',
            'data' => $status,
        ]);
    }

    public function ForgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user) {
            $token = mt_rand() . time();
                User::where('email', $request->email)->update(['reset_token' => $token]);

            if ($user->user_type == 2) {
                $data = Customer::where('cus_id', $user->id)->first();
            }

            if ($user->user_type == 3) {
                $data = Supplier::where('sup_id', $user->id)->first();
            }

            // try {
            Mail::send('emails.password_reset', ['firstname' => $user->firstname, 'link' => $token], function ($message) use ($request) {
                $message->to($request->email);
                $message->subject('You requested for Reset Password.');
            });
            // } catch (\Throwable $th) {
            //     //throw $th;
            // }
            return response()->json([
                'success' => true,
                'message' => 'A reset link has been sent to your email address.'
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Your email address not found.'
        ]);
    }

    public function UserProfileUpdate(Request $request)
    {
        $validate =  Validator::make($request->all(), [
            'firstname' => 'required|min:2|max:10',
            'lastname' => 'required|min:3|max:10',
            'mobile' => 'required|max:10',
            'invoiceemail' => 'required',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'passport_id' => 'required',
            'passport_file' => 'mimes:png,jpg,jpeg|max:1048',
            'avatar' => 'mimes:png,jpg,jpeg|max:1048',
            'com_reg_no' => 'required',
            'com_reg_doc' => 'mimes:pdf|max:2048',
            'director_name' => 'required',
            'directory_contact' => 'required',
            'shipping_address' => 'required',
            'company_tax' => 'required',
            'port_of_discharge' => 'required'
        ]);


        if ($validate->fails()) {
            $message = $validate->errors();
            return response()->json([
                'success' => false,
                'message' => $message,
            ]);
        } else {

            $customer_id = Auth::user()->id;
            User::where('id', $customer_id)
                ->update([
                    'firstname' => $request->firstname,
                    'lastname' => $request->lastname,
                    'mobile' => $request->mobile,
                ]);

            $customer = Customer::where('cus_id', Auth::user()->id)->first();
            $avatar = '';
            $com_reg_doc = '';
            $fileName = '';

            if (!empty($request->file('passport_file'))) {
                $fileName = time() . '_' . $request->file('passport_file')->getClientOriginalName();
                $request->file('passport_file')->storeAs('customer_doc', $fileName, 'public');
            } else {
                $fileName = $customer->passport_file;
            }


            if (!empty($request->file('com_reg_doc'))) {
                $com_reg_doc = time() . '_' . $request->file('com_reg_doc')->getClientOriginalName();
                $request->file('com_reg_doc')->storeAs('customer_doc', $com_reg_doc, 'public');
            } else {
                $com_reg_doc = $customer->com_reg_doc;
            }

            if (!empty($request->file('avatar'))) {
                $avatar = time() . '_' . $request->file('avatar')->getClientOriginalName();
                $request->file('avatar')->storeAs('customer_doc', $avatar, 'public');
            } else {
                $avatar = $customer->avatar;
            }
            Customer::where('cus_id', $customer_id)
                ->update([
                    'website' => $request->website,
                    'invoiceemail' => $request->invoiceemail,
                    'passport_id' => $request->passport_id,
                    'passport_file' => $fileName,
                    'com_reg_no' => $request->com_reg_no,
                    'country' => $request->country,
                    'state' => $request->state,
                    'city' => $request->city,
                    'com_reg_doc' => $com_reg_doc,
                    'avatar' => $avatar,
                    'director_name' => $request->director_name,
                    'directory_contact' => $request->directory_contact,
                    'shipping_address' => $request->shipping_address,
                    'company_tax' => $request->company_tax,
                    'port_of_discharge' => $request->port_of_discharge
                ]);
            return response()->json([
                'success' => true,
                'message' => 'Profile Update Successful'
            ]);
        }
    }

    public function CompanyDetails(Request $request)
    {
        $id = Auth::user()->id;

        $company_details  = Customer::with('user')->where('cus_id', $id)->first();

        if (!empty($company_details)) {
            $data['companyname'] = $company_details->companyname;
            $data['email'] = $company_details->email;
            $data['address'] = $company_details->address;
            $data['country'] = $company_details->country;
            $data['state'] = $company_details->state;
            $data['city'] = $company_details->city;
            $data['website'] = $company_details->website;
            $data['passport_id'] = $company_details->passport_id;
            $data['passport_file'] = $company_details->passport_file;
            $data['com_reg_no'] = $company_details->com_reg_no;
            $data['com_reg_doc'] = $company_details->com_reg_doc;
            $data['director_name'] = $company_details->director_name;
            $data['directory_contact'] = $company_details->directory_contact;

            $result[] = $data;
            return response()->json([
                'success' => true,
                'message' => 'Company Detail',
                'data' => $data
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'User Not Found'
            ]);
        }
    }

    public function UpdateCompanyDetails(Request $request)
    {
        $validate =  Validator::make($request->all(), [
            'website' => 'required',
            'address' => 'required',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'passport_id' => 'required',
            'passport_file' => 'mimes:png,jpg,jpeg|max:1048',
            'com_reg_no' => 'required',
            'com_reg_doc' => 'mimes:png,jpg,jpeg|max:2048',
            'director_name' => 'required',
            'directory_contact' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'all field are required',
                'data' => $validate
            ]);
        } else {
            $customer_id = Auth::user()->id;

            $customer = Customer::where('cus_id', Auth::user()->id)->first();

            // $avatar = '';
            $com_reg_doc = '';
            $fileName = '';

            if (!empty($request->file('passport_file'))) {
                $fileName = time() . '_' . $request->file('passport_file')->getClientOriginalName();
                $request->file('passport_file')->storeAs('customer_doc', $fileName, 'public');
            } else {
                $fileName = $customer->passport_file;
            }


            if (!empty($request->file('com_reg_doc'))) {
                $com_reg_doc = time() . '_' . $request->file('com_reg_doc')->getClientOriginalName();
                $request->file('com_reg_doc')->storeAs('customer_doc', $com_reg_doc, 'public');
            } else {
                $com_reg_doc = $customer->com_reg_doc;
            }

            Customer::where('cus_id', $customer_id)
                ->update([
                    'website' => $request->website,
                    'passport_id' => $request->passport_id,
                    'passport_file' => $fileName,
                    'com_reg_no' => $request->com_reg_no,
                    'country' => $request->country,
                    'state' => $request->state,
                    'city' => $request->city,
                    'com_reg_doc' => $com_reg_doc,
                    // 'avatar' => $avatar,
                    'address' => $request->address,
                    'director_name' => $request->director_name,
                    'directory_contact' => $request->directory_contact,
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Details Update Successful'
            ]);
        }
    }

    public function GetDiamond(Request $request)
    {
        $email = $request->email;
        $api_key = $request->api_key;
        $diamond_type = trim(strtolower($request->diamond_type));

        $customer_data =  User::join('customers', 'id', '=', 'cus_id')->where('email', $email)->where('api_key', $api_key)->first();

        if (!empty($diamond_type)) {
            if (!empty($customer_data->email_verified_at)) {
                if ($customer_data->is_active == 1) {
                    if (!empty($customer_data)) {
                        $customer_discount = $customer_lab_discount = 0;

                        if ($diamond_type == 'natural') {
                            $cus_discount = $customer_data->discount;
                            $result_query = DiamondNatural::select(
                                '*',
                                DB::raw('(SELECT pricechange FROM markup_setting WHERE (net_dollar BETWEEN `min_range` and max_range)) as aditional_discount'),
                                DB::raw("(SELECT caratprice FROM rap_list WHERE IF(`shape_name` = 'ROUND', shape = 'ROUND', shape != 'ROUND') AND color_name = `color` AND clarity_name = IF(`clarity` = 'FL','IF', `clarity`) AND low_size <= carat AND high_size >= carat) as raprate")
                            )
                                ->where('carat', '>', 0.17)
                                ->where('orignal_rate', '>', 50);
                        } elseif ($diamond_type == 'labgrown') {
                            $cus_discount = $customer_data->lab_discount;
                            $result_query = DiamondLabgrown::select(
                                '*',
                                DB::raw('(SELECT pricechange FROM markup_setting WHERE (net_dollar BETWEEN `min_range` and max_range)) as aditional_discount'),
                                DB::raw("(SELECT caratprice FROM rap_list WHERE IF(`shape_name` = 'ROUND', shape = 'ROUND', shape != 'ROUND') AND color_name = `color` AND clarity_name = IF(`clarity` = 'FL','IF', `clarity`) AND low_size <= carat AND high_size >= carat) as raprate")
                            )
                                ->where('carat', '>', 0.17)
                                ->where('orignal_rate', '>', 50);
                        } else {
                            return response()->json([
                                'success' => false,
                                'message' => 'Enter natural or labgrown'
                            ]);
                        }

                        if (!empty($request->stoneid) && $request->stoneid != 'undefined') {
                            $result_query->where(function ($query) use ($request) {

                                $stoneid = strtoupper($request->stoneid);
                                $stoneids = explode(",", $stoneid);
                                $query->orWhereIn('id', str_replace('LG', '', $stoneids));
                                $query->orWhereIn('certificate_no', $stoneids);
                            });
                        } else {
                            if (!empty($request->shape)) {
                                $shape = explode(',', $request->shape);
                                $result_query->whereIn('shape', $shape);
                            }
                            if (!empty($request->color)) {
                                $color = explode(',', $request->color);
                                $result_query->whereIn('color', $color);
                            }
                            if (!empty($request->clarity)) {
                                $clarity = explode(',', $request->clarity);
                                $result_query->whereIn('clarity', $clarity);
                            }
                            if (!empty($request->lab)) {
                                $lab = explode(',', $request->lab);
                                $result_query->whereIn('lab', $lab);
                            }
                            if (!empty($request->cut)) {
                                $cut = explode(',', $request->cut);
                                $result_query->whereIn('cut', $cut);
                            }
                            if (!empty($request->polish)) {
                                $polish = explode(',', $request->polish);
                                $result_query->whereIn('polish', $polish);
                            }
                            if (!empty($request->symmetry)) {
                                $symmetry = explode(',', $request->symmetry);
                                $result_query->whereIn('symmetry', $symmetry);
                            }
                            if (!empty($request->fluorescence)) {
                                $fluorescence = explode(',', $request->fluorescence);
                                $result_query->whereIn('fluorescence', $fluorescence);
                            }
                            if (!empty($request->eyeclean)) {
                                $eyeclean = explode(',', $request->eyeclean);
                                $result_query->whereIn('eyeclean', $eyeclean);
                            }
                            if (!empty($request->country)) {
                                $country = explode(',', $request->country);
                                $result_query->whereIn('country', $country);
                            }
                        }

                        $result_query->where('location', 1);
                        $result_query->where('status', '0');
                        $result_query->where('is_delete', 0);

                        $result = $result_query->paginate();

                        $updatedItems = $result->getCollection();
                        $diamond = array();

                        foreach ($updatedItems as $value) {

                            $orignal_rate = $value->rate + (($value->rate * ($cus_discount)) / 100);
                            $supplier_price = ($orignal_rate * $value->carat);

                            if($supplier_price <= 1000)
                            {
                                $procurment_price = $supplier_price + 25;
                            }
                            else if($supplier_price >= 7000)
                            {
                                $procurment_price = $supplier_price + 140;
                            }
                            else if($supplier_price > 1000 && $supplier_price < 7000)
                            {
                                $procurment_price = $supplier_price + round((2 / 100) * $supplier_price, 2);
                            }
                            $carat_price = $procurment_price / $value->carat;

                            $supplier_price = $orignal_rate * $value->carat;
                            $supplier_discount = !empty($value->raprate) ? round(($orignal_rate - $value->raprate) / $value->raprate * 100, 2) : 0;

                            $procurment_discount = !empty($value->raprate) ? round(($carat_price - $value->raprate) / $value->raprate * 100, 2) : 0;

                            $d_result['sku'] = $value->id;
                            $d_result['availability'] = $value->availability;
                            $d_result['diamond_type'] = $value->diamond_type;

                            $d_result['shape'] = $value->shape;
                            $d_result['carat'] = (string)$value->carat;
                            $d_result['color'] = $value->color;
                            $d_result['clarity'] = $value->clarity;
                            $d_result['cut'] = $value->cut;
                            $d_result['polish'] = $value->polish;
                            $d_result['symmetry'] = $value->symmetry;
                            $d_result['fluorescence'] = $value->fluorescence;

                            $d_result['lab'] = $value->lab;
                            $d_result['certificate_no'] = $value->certificate_no;

                            $d_result['country'] = $value->country;

                            $d_result['rate'] = (string)$carat_price;
                            $d_result['net_price'] = (string)$procurment_price;
                            $d_result['discount_main'] = number_format($procurment_discount, 2);
                            $d_result['raprate'] = $value->raprate;

                            $d_result['image'] = $value->image;
                            $d_result['video'] = $value->video;
                            $d_result['certi_link'] = $value->certificate_link;

                            $diamond[] = $d_result;
                        }
                        $result->setCollection(collect($diamond));
                        if ($diamond_type == 'W') {
                            $count = $result->count() . ' Natural Stone Found... ';
                        } else {
                            $count = $result->count() . ' Labgrown Stone Found... ';
                        }

                        return response()->json([
                            'success' => true,
                            'message' => $count,
                            'data' => $result
                        ], 201);
                    } else {
                        return response()->json([
                            'success' => false,
                            'message' => "User Not Found"
                        ]);
                    }
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => "User Profile Not Activate"
                    ]);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => "Email Not Verified",
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => "Enter Natural or Labgrown",
            ]);
        }
    }

    public function NaturalParameters(Request $request)
    {
        $data['shape'] = array(
            [
                "name" => "round",
                "image" => asset('assets/images/shape/round.png')
            ],
            [
                "name" => "princess",
                "image" => asset('assets/images/shape/princess.png')
            ],
            [
                "name" => "asscher",
                "image" => asset('assets/images/shape/asscher.png')
            ],
            [
                "name" => "cushion",
                "image" => asset('assets/images/shape/cushion.png')
            ],
            [
                "name" => "emerald",
                "image" => asset('assets/images/shape/emerald.png')
            ],
            [
                "name" => "heart",
                "image" => asset('assets/images/shape/heart.png')
            ],
            [
                "name" => "marquise",
                "image" => asset('assets/images/shape/marquise.png')
            ],
            [
                "name" => "oval",
                "image" => asset('assets/images/shape/oval.png')
            ],
            [
                "name" => "pear",
                "image" => asset('assets/images/shape/pear.png')
            ],
            [
                "name" => "radiant",
                "image" => asset('assets/images/shape/radiant.png')
            ],
            [
                "name" => "sq.radiant",
                "image" => asset('assets/images/shape/squareradiant.png')
            ],
            [
                "name" => "trilliant",
                "image" => asset('assets/images/shape/trilliant.png')
            ],
            [
                "name" => "cushion mod",
                "image" => asset('assets/images/shape/cus_mod.png')
            ],
            [
                "name" => "baguette",
                "image" => asset('assets/images/shape/baguette.png')
            ]
        );
        $data['carat']      = array("0.30-0.39", "0.40-0.49", "0.50-0.69", "0.70-0.89", "0.90-0.99", "1.00-1.49", "1.50-1.99", "2.00-2.99", "3.00-3.99", "4.00-4.99");
        $data['fancycolor']    = array('Black', 'Blue', 'Brown', 'Brownish', 'Chameleon', 'Champagne', 'Cognac', 'Grey', 'Green', 'Orange', 'Pink', 'Purple', 'Red', 'Violet', 'Yellow', 'White', 'Other');
        $data['fancyintensity']    = array('Black', 'Blue', 'Brown', 'Chameleon', 'Cognac', 'Grey', 'Greyish', 'Green', 'Greenish', 'Orange', 'Orangey', 'Pink', 'Pinkish', 'Purple', 'Purplish', 'Red', 'Reddish', 'Violet', 'Violetish', 'Yellow', 'Yellowish', 'White', 'Other', 'None');
        $data['fancyovertone']    = array('Black', 'Blue', 'Brown', 'Chameleon', 'Cognac', 'Grey', 'Greyish', 'Green', 'Greenish', 'Orange', 'Orangey', 'Pink', 'Pinkish', 'Purple', 'Purplish', 'Red', 'Reddish', 'Violet', 'Violetish', 'Yellow', 'Yellowish', 'White', 'Other', 'None');
        $data['brown']    = array('none', 'brown');
        $data['green']    = array('none', 'green');
        $data['milky']    = array('milky', 'lmilky', 'nomilky');
        $data['color']     = array('D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N');
        $data['clarity']   = array('FL','IF', 'VVS1', 'VVS2', 'VS1', 'VS2', 'SI1', 'SI2', 'SI3', 'I1', 'I2');
        $data['lab']       = array('GIA', 'IGI', 'HRD', 'GCAL', 'AGS');
        $data['cut']       = array('ID', 'EX', 'VG', 'GD', 'FR');
        $data['polish']    = array('EX', 'VG', 'GD', 'FR');
        $data['symmetry']  = array('EX', 'VG', 'GD', 'FR');
        $data['fluorescence'] = array('NON', 'FNT', 'MED', 'SLT', 'STG', 'VST', 'VSLT');
        $data['eyeclean']    = array('YES', 'NO');
        $data['country']      = array('INDIA', 'HONG KONG', 'ISRAEL', 'USA', 'UAE', 'BELGIUM', 'OTHER');
        return response()->json([
            'success' => true,
            'message' => "Parameter",
            'data' => $data
        ], 201);
    }

    public function NaturalDiamondSearch(Request $request)
    {
        $customer_id = Auth::user()->id;

        $customer_data = Customer::where('cus_id', $customer_id)->first();
        $customer_discount = $customer_data->discount;

        // $customer_lab_discount = $customer_data->lab_discount;

        $result_query = DiamondNatural::select('*',
            DB::raw('(SELECT pricechange FROM markup_setting WHERE (net_dollar BETWEEN `min_range` and max_range)) as aditional_discount'),
            DB::raw('(SELECT id from wish_list where customer_id = ' . $customer_id . ' AND certificate_no = diamond_natural.certificate_no AND wish_list.is_delete = "0" limit 1) as is_wishlist'),
            DB::raw("(SELECT caratprice FROM rap_list WHERE IF(`shape_name` = 'ROUND', shape = 'ROUND', shape != 'ROUND') AND color_name = `color` AND clarity_name = IF(`clarity` = 'FL','IF', `clarity`) AND low_size <= carat AND high_size >= carat) as raprate")
            )
            ->where('carat', '>', 0.17)
            ->where('orignal_rate', '>', 50);

            if (!empty($request->stoneid) && $request->stoneid != 'undefined') {
                $result_query->where(function ($query) use ($request) {
                    $stoneid = strtoupper($request->stoneid);
                    $stoneids = explode(",", $stoneid);
                    $query->orWhereIn('id', str_replace('LG', '', $stoneids));
                    $query->orWhereIn('certificate_no', str_replace('LG', '', $stoneids));
                });
            } else {
                if (!empty($request->carat_min) && !empty($request->carat_max)) {
                    $carat_min = (float)$request->carat_min;
                    $carat_max = (float)$request->carat_max;
                    if (!empty($request->carat_min) && !empty($request->carat_max)) {
                        $result_query->where('carat', '>=', $carat_min);
                    }

                    if (!empty($request->carat_min) && !empty($request->carat_max)) {
                        $result_query->where('carat', '<=', $carat_max);
                    }
                }

                if (!empty($request->table_per_min) && !empty($request->table_per_max)) {

                    $table_per_min = (float)$request->table_per_min;
                    $table_per_max = (float)$request->table_per_max;

                    if (!empty($request->table_per_min) && !empty($request->table_per_max)) {
                        $result_query->where('table_per', '>=', $table_per_min);
                    }

                    if (!empty($request->table_per_min) && !empty($request->table_per_max)) {
                        $result_query->where('table_per', '<=', $table_per_max);
                    }
                }

                if (!empty($request->depth_per_min) && !empty($request->depth_per_max)) {
                    $depth_per_min = (float)$request->depth_per_min;
                    $depth_per_max = (float)$request->depth_per_max;

                    if (!empty($request->depth_per_min) && !empty($request->depth_per_max)) {
                        $result_query->where('depth_per', '>=', $depth_per_min);
                    }

                    if (!empty($request->depth_per_min) && !empty($request->depth_per_max)) {
                        $result_query->where('depth_per', '<=', $depth_per_max);
                    }
                }

                if (!empty($request->length_mm_min) && !empty($request->length_mm_max)) {

                    $length_mm_min = (float)$request->length_mm_min;
                    $length_mm_max = (float)$request->length_mm_max;

                    if (!empty($request->length_mm_min) && !empty($request->length_mm_max)) {
                        $result_query->where('length', '>=', $length_mm_min);
                    }

                    if (!empty($request->length_mm_min) && !empty($request->length_mm_max)) {
                        $result_query->where('length', '<=', $length_mm_max);
                    }
                }

                if (!empty($request->width_mm_min) && !empty($request->width_mm_max)) {

                    $width_mm_min = (float)$request->width_mm_min;
                    $width_mm_max = (float)$request->width_mm_max;

                    if (!empty($request->width_mm_min) && !empty($request->width_mm_max)) {
                        $result_query->where('width', '>=', $width_mm_min);
                    }

                    if (!empty($request->width_mm_min) && !empty($request->width_mm_max)) {
                        $result_query->where('width', '<=', $width_mm_max);
                    }
                }

                if (!empty($request->depth_mm_min) && !empty($request->depth_mm_max)) {
                    $depth_mm_min = (float)$request->depth_mm_min;
                    $depth_mm_max = (float)$request->depth_mm_max;

                    if (!empty($request->depth_mm_min) && !empty($request->depth_mm_max)) {
                        $result_query->where('depth', '>=', $depth_mm_min);
                    }

                    if (!empty($request->depth_mm_min) && !empty($request->depth_mm_max)) {
                        $result_query->where('depth', '<=', $depth_mm_max);
                    }
                }

                if (!empty($request->crown_angle_min) && !empty($request->crown_angle_max)) {

                    $crown_angle_min = $request->crown_angle_min;
                    $crown_angle_max = $request->crown_angle_max;

                    if (!empty($request->crown_angle_min) && !empty($request->crown_angle_max)) {
                        $result_query->where('crown_angle', '>=', $crown_angle_min);
                    }

                    if (!empty($request->crown_angle_min) && !empty($request->crown_angle_max)) {
                        $result_query->where('crown_angle', '<=', $crown_angle_max);
                    }
                }

                if (!empty($request->crown_height_min) && !empty($request->crown_height_max)) {

                    $crown_height_min = $request->crown_height_min;
                    $crown_height_max = $request->crown_height_max;

                    if (!empty($request->crown_height_min) && !empty($request->crown_height_max)) {
                        $result_query->where('crown_height', '>=', $crown_height_min);
                    }

                    if (!empty($request->crown_height_min) && !empty($request->crown_height_max)) {
                        $result_query->where('crown_height', '<=', $crown_height_max);
                    }
                }

                if (!empty($request->pavilion_angle_min) && !empty($request->pavilion_angle_max)) {

                    $pavilion_angle_min = $request->pavilion_angle_min;
                    $pavilion_angle_max = $request->pavilion_angle_max;

                    if (!empty($request->pavilion_angle_min) && !empty($request->pavilion_angle_max)) {
                        $result_query->where('pavilion_angle', '>=', $pavilion_angle_min);
                    }

                    if (!empty($request->pavilion_angle_min) && !empty($request->pavilion_angle_max)) {
                        $result_query->where('pavilion_angle', '<=', $pavilion_angle_max);
                    }
                }

                if (!empty($request->pavilion_depth_min) && !empty($request->pavilion_depth_max)) {

                    $pavilion_depth_min = $request->pavilion_depth_min;
                    $pavilion_depth_max = $request->pavilion_depth_max;

                    if (!empty($request->pavilion_depth_min) && !empty($request->pavilion_depth_max)) {
                        $result_query->where('pavilion_depth', '>=', $pavilion_depth_min);
                    }

                    if (!empty($request->pavilion_depth_min) && !empty($request->pavilion_depth_max)) {
                        $result_query->where('pavilion_depth', '<=', $pavilion_depth_max);
                    }
                }

                if (!empty($request->usd_per_carat_min) && !empty($request->usd_per_carat_max)) {

                    $usd_per_carat_min = $request->usd_per_carat_min;
                    $usd_per_carat_max = $request->usd_per_carat_max;

                    if (!empty($request->usd_per_carat_min) && !empty($request->usd_per_carat_max)) {
                        $result_query->where('rate', '>=', $usd_per_carat_min);
                    }

                    if (!empty($request->usd_per_carat_min) && !empty($request->usd_per_carat_max)) {
                        $result_query->where('rate', '<=', $usd_per_carat_max);
                    }
                } else {
                    $result_query->where('rate', '>=', 0.00);
                }

                if (!empty($request->usd_value_min) && !empty($request->usd_value_max)) {

                    $usd_value_min = $request->usd_value_min;
                    $usd_value_max = $request->usd_value_max;

                    if (!empty($request->usd_value_min) && !empty($request->usd_value_max)) {
                        $result_query->where('net_dollar', '>=', $usd_value_min);
                    }

                    if (!empty($request->usd_value_min) && !empty($request->usd_value_max)) {
                        $result_query->where('net_dollar', '<=', $usd_value_max);
                    }
                } else {
                    $result_query->where('net_dollar', '>=', 0.00);
                }

                // dd($request->carat_size);
                if ($request->carat_size) {
                    $carat_size = $request->carat_size;
                    $carat = explode('-', $carat_size);
                    $carat_min = $carat[0];
                    $carat_max = $carat[1];

                    $result_query->where('carat', '>=', $carat_min);
                    $result_query->where('carat', '<=', $carat_max);
                } elseif ($request->carat_range) {
                    $carat_range = $request->carat_range;
                    $result_query->where(function ($query) use ($carat_range) {
                        $size = explode(",", $carat_range);
                        foreach ($size as $sizevalue) {
                            $query->orWhere(function ($query_c) use ($sizevalue) {
                                $sizev = explode("-", $sizevalue);
                                if (!empty($sizev[0])) {
                                    $query_c->where('carat', '>=', $sizev[0]);
                                }
                                if (!empty($sizev[1])) {
                                    $query_c->where('carat', '<=', $sizev[1]);
                                }
                            });
                        }
                    });
                }

                if (!empty($request->shape)) {
                    $shape = explode(',', $request->shape);
                    $result_query->whereIn('shape', $shape);
                }

                if (!empty($request->white_color)) {
                    $color = explode(',', $request->white_color);
                    $result_query->whereIn('color', $color);
                }
                else
                {
                    if (!empty($request->fancycolor)) {
                        $fancy_color = explode(',', $request->fancycolor);
                        $result_query->whereIn('fancy_color', $fancy_color);
                    }
                    if (!empty($request->fancyintensity)) {
                        $fancy_color = explode(',', $request->fancy_color);
                        $result_query->whereIn('fancy_intensity', $fancy_color);
                    }
                    if (!empty($request->fancyovertone)) {
                        $fancy_color = explode(',', $request->fancyovertone);
                        $result_query->whereIn('fancy_overtone', $fancy_color);
                    }
                }

                if (!empty($request->clarity)) {
                    $clarity = explode(',', $request->clarity);
                    $result_query->whereIn('clarity', $clarity);
                }
                if (!empty($request->lab)) {
                    $lab = explode(',', $request->lab);
                    $result_query->whereIn('lab', $lab);
                }
                if (!empty($request->cut)) {
                    $cut = explode(',', $request->cut);
                    $result_query->whereIn('cut', $cut);
                }
                if (!empty($request->polish)) {
                    $polish = explode(',', $request->polish);
                    $result_query->whereIn('polish', $polish);
                }
                if (!empty($request->symmetry)) {
                    $symmetry = explode(',', $request->symmetry);
                    $result_query->whereIn('symmetry', $symmetry);
                }
                if (!empty($request->fluorescence)) {
                    $fluorescence = explode(',', $request->fluorescence);
                    $result_query->whereIn('fluorescence', $fluorescence);
                }
                if (!empty($request->eyeclean)) {
                    $eyeclean = explode(',', $request->eyeclean);
                    $result_query->whereIn('eyeclean', $eyeclean);
                }
                if (!empty($request->country)) {
                    $country = explode(',', $request->country);
                    $result_query->whereIn('country', $country);
                }

                if (!empty($request->milky)) {
                    if ($request->milky == 'heavy') {
                        $milky = explode(',', $request->milky);
                        $result_query->whereIn('milky', $milky);
                    } elseif ($request->milky == 'medium') {
                        $milky = explode(',', $request->milky);
                        $result_query->whereIn('milky', $milky);
                    } else {
                        $milky = explode(',', $request->milky);
                        $result_query->whereIn('milky', $milky);
                    }
                }
            }

            $result_query->where('location', 1);
            $result_query->where('status', '0');
            $result_query->where('is_delete', 0);

            $result = $result_query->paginate();

            $updatedItems = $result->getCollection();

            SearchLog::create([
                'user_id' => $customer_id,
                'diamond_type' => 'natural',
                'certificate_no' => $request->stoneid,
                'carat' => $request->carat_size,
                'shape' => $request->shape,
                'color' => $request->color,
                'clarity' => $request->clarity,
                'cut' => $request->cut,
                'polish' => $request->polish,
                'symmetry' => $request->symmetry,
                'fluorescence' => $request->fluorescence,
                'lab' => $request->lab,
                'EyeC' => $request->eyeclean,
                // 'shade' => $shade,
                // 'milky' => $milky,
                // 'totalprice_min' => $totalprice_min,
                // 'totalprice_max' => $totalprice_max,
                // 'pricepercts_min' => $pricepercts_min,
                // 'pricepercts_max' => $pricepercts_max,

                // 'table_per_from' => $table_per_from,
                // 'table_per_to' => $table_per_to,
                // 'depth_per_from' => $depth_per_from,
                // 'depth_per_to' => $depth_per_to,

                // 'length_min' => $length_min,
                // 'length_max' => $length_max,
                // 'width_min' => $width_min,
                // 'width_max' => $width_max,
                // 'depth_min' => $depth_min,
                // 'depth_max' => $depth_max,

                // 'image_video' => $image_video,
                // 'fancy_color_diamond' => $fancy_color_diamond,
                // 'fancy_color' => $fancy_color,
                // 'fancy_intensity' => $fancy_intensity,
                // 'fancy_overtone' => $fancy_overtone,
                // 'sort_field' => $sort_field,
                // 'sort_order' => $sort_order,
                // 'start_index' => !empty($page) ? $page : 0,
                'search_date' => date('Y-m-d H:i:s'),
                'ip' => $request->ip()
            ]);

            $diamond = array();

            foreach ($updatedItems as $value) {
                $orignal_rate = $value->rate + (($value->rate * ($customer_discount)) / 100);
                $supplier_price = ($orignal_rate * $value->carat);

                if($supplier_price <= 1000)
                {
                    $procurment_price = $supplier_price + 25;
                }
                else if($supplier_price >= 7000)
                {
                    $procurment_price = $supplier_price + 140;
                }
                else if($supplier_price > 1000 && $supplier_price < 7000)
                {
                    $procurment_price = $supplier_price + round((2 / 100) * $supplier_price, 2);
                }
                $carat_price = $procurment_price / $value->carat;

                // $supplier_price = $orignal_rate * $value->carat;

                $procurment_discount = !empty($value->raprate) ? round(($carat_price - $value->raprate) / $value->raprate * 100, 2) : 0;
                $return_price = number_format((1 / 100) * $procurment_price, 2);

                $d_result['sku'] = $value->id;
                $d_result['availability'] = $value->availability;
                $d_result['diamond_type'] = $value->diamond_type;
                $d_result['is_Wishlist'] = !empty($value->is_wishlist) ? True : False;
                $d_result['shape'] = $value->shape;
                $d_result['carat'] = (string)$value->carat;
                $d_result['color'] = $value->color;
                $d_result['clarity'] = $value->clarity;
                $d_result['cut'] = $value->cut;
                $d_result['polish'] = $value->polish;
                $d_result['symmetry'] = $value->symmetry;
                $d_result['fluorescence'] = $value->fluorescence;

                $d_result['lab'] = $value->lab;
                $d_result['certificate_no'] = $value->certificate_no;

                $d_result['country'] = $value->country;

                $d_result['rate'] = (string)$carat_price;
                $d_result['net_price'] = (string)$procurment_price;
                $d_result['discount_main'] = number_format($procurment_discount, 2);
                $d_result['raprate'] = $value->raprate;

                $d_result['orignal_price'] = $supplier_price;
                $d_result['return_price'] = $return_price;

                if ($value->cloud_image) {
                    $d_result['image'] = $value->cloud_image;
                } else {
                    $d_result['image'] = asset('assets/theme/images/'.strtolower($value->shape).'.svg');
                }
                $d_result['video'] = $value->video;
                $d_result['certi_link'] = $value->certificate_link;

                // add new paramter value
                // $d_result['shade'] = $value->shade;
                // $d_result['milky'] = $value->milky;
                // $d_result['fancy_color'] = $value->fancy_color;
                // $d_result['usd_per_carat'] = $value->rate;
                // $d_result['usd_value'] = $value->net_dollar;
                // $d_result['depth_mm'] = $value->depth;
                // $d_result['depth_mm'] = $value->depth;
                // $d_result['depth_per'] = $value->depth_per;
                // $d_result['table_per'] = $value->table_per;
                // $d_result['length'] = $value->length;
                // $d_result['width'] = $value->width;
                // $d_result['crown_angle'] = $value->crown_angle;
                // $d_result['pavilion_angle'] = $value->pavilion_angle;
                // $d_result['pavilion_depth'] = $value->pavilion_depth;
                // $d_result['crown_height'] = $value->crown_height;
                $diamond[] = $d_result;
            }

        $result->setCollection(collect($diamond));

        $count = $result->count() . ' Natural Stone Found... ';

        return response()->json([
            'success' => true,
            'message' => $count,
            'data' => $result
        ], 201);
    }

    public function LabParameters(Request $request)
    {
        $data['shape'] = array(
            [
                "name" => "round",
                "image" => asset('assets/images/shape/round.png')
            ],
            [
                "name" => "princess",
                "image" => asset('assets/theme/images/princess.png')
            ],
            [
                "name" => "asscher",
                "image" => asset('assets/images/shape/asscher.png')
            ],
            [
                "name" => "cushion",
                "image" => asset('assets/images/shape/cushion.png')
            ],
            [
                "name" => "emerald",
                "image" => asset('assets/images/shape/emerald.png')
            ],
            [
                "name" => "heart",
                "image" => asset('assets/images/shape/heart.png')
            ],
            [
                "name" => "marquise",
                "image" => asset('assets/images/shape/marquise.png')
            ],
            [
                "name" => "oval",
                "image" => asset('assets/images/shape/oval.png')
            ],
            [
                "name" => "pear",
                "image" => asset('assets/images/shape/pear.png')
            ],
            [
                "name" => "radiant",
                "image" => asset('assets/images/shape/radiant.png')
            ],
            [
                "name" => "sq.radiant",
                "image" => asset('assets/images/shape/squareradiant.png')
            ],
            [
                "name" => "trilliant",
                "image" => asset('assets/images/shape/trilliant.png')
            ],
            [
                "name" => "cushion mod",
                "image" => asset('assets/images/shape/cus_mod.png')
            ],
            [
                "name" => "baguette",
                "image" => asset('assets/images/shape/baguette.png')
            ]
        );
        $data['carat']      = array("0.30-0.39", "0.40-0.49", "0.50-0.69", "0.70-0.89", "0.90-0.99", "1.00-1.49", "1.50-1.99", "2.00-2.99", "3.00-3.99", "4.00-4.99");
        $data['fancycolor']    = array('Black', 'Blue', 'Brown', 'Brownish', 'Chameleon', 'Champagne', 'Cognac', 'Grey', 'Green', 'Orange', 'Pink', 'Purple', 'Red', 'Violet', 'Yellow', 'White', 'Other');
        $data['fancyintensity']    = array('Black', 'Blue', 'Brown', 'Chameleon', 'Cognac', 'Grey', 'Greyish', 'Green', 'Greenish', 'Orange', 'Orangey', 'Pink', 'Pinkish', 'Purple', 'Purplish', 'Red', 'Reddish', 'Violet', 'Violetish', 'Yellow', 'Yellowish', 'White', 'Other', 'None');
        $data['fancyovertone']    = array('Black', 'Blue', 'Brown', 'Chameleon', 'Cognac', 'Grey', 'Greyish', 'Green', 'Greenish', 'Orange', 'Orangey', 'Pink', 'Pinkish', 'Purple', 'Purplish', 'Red', 'Reddish', 'Violet', 'Violetish', 'Yellow', 'Yellowish', 'White', 'Other', 'None');
        $data['brown']    = array('none', 'brown');
        $data['green']    = array('none', 'green');
        $data['milky']    = array('milky', 'lmilky', 'nomilky');
        $data['color']     = array('D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N');
        $data['clarity']   = array('FL','IF', 'VVS1', 'VVS2', 'VS1', 'VS2', 'SI1', 'SI2', 'SI3', 'I1', 'I2');
        $data['lab']       = array('GIA', 'IGI', 'HRD', 'GCAL', 'AGS');
        $data['cut']       = array('ID', 'EX', 'VG', 'GD', 'FR');
        $data['polish']    = array('EX', 'VG', 'GD', 'FR');
        $data['symmetry']  = array('EX', 'VG', 'GD', 'FR');
        $data['fluorescence'] = array('NON', 'FNT', 'MED', 'SLT', 'STG', 'VST', 'VSLT');
        $data['eye_clean']    = array('YES', 'NO');
        $data['country']      = array('INDIA', 'HONG KONG', 'ISRAEL', 'USA', 'UAE', 'BELGIUM', 'OTHER');

        return response()->json([
            'success' => true,
            'message' => "Parameter",
            'data' => $data
        ], 201);
    }

    public function LabDiamondSearch(Request $request)
    {
        $customer_id = Auth::user()->id;

        $customer_data = Customer::where('cus_id', $customer_id)->first();
        if(!empty($customer_data))
        {
            // $customer_discount = $customer_data->discount;
            $customer_lab_discount = $customer_data->lab_discount;

            $result_query = DiamondLabgrown::select('*',
                    DB::raw('(SELECT pricechange FROM markup_setting WHERE (net_dollar BETWEEN `min_range` and max_range)) as aditional_discount'),
                    DB::raw('(SELECT id from wish_list where customer_id = ' . $customer_id . ' AND certificate_no = diamond_labgrown.certificate_no AND wish_list.is_delete = "0" limit 1) as is_wishlist'),
                    DB::raw("(SELECT caratprice FROM rap_list WHERE IF(`shape_name` = 'ROUND', shape = 'ROUND', shape != 'ROUND') AND color_name = `color` AND clarity_name = IF(`clarity` = 'FL','IF', `clarity`) AND low_size <= carat AND high_size >= carat) as raprate")
                )
                ->where('carat', '>', 0.17)
                ->where('orignal_rate', '>', 50);

                if (!empty($request->stoneid) && $request->stoneid != 'undefined') {
                    $result_query->where(function ($query) use ($request) {
                        $stoneid = strtoupper($request->stoneid);
                        $stoneids = explode(",", $stoneid);
                        $query->orWhereIn('id', str_replace('LG', '', $stoneids));
                        $query->orWhereIn('certificate_no', str_replace('LG', '', $stoneids));
                    });
                } else {
                    if (!empty($request->carat_min) && !empty($request->carat_max)) {
                        $carat_min = (float)$request->carat_min;
                        $carat_max = (float)$request->carat_max;
                        if (!empty($request->carat_min) && !empty($request->carat_max)) {
                            $result_query->where('carat', '>=', $carat_min);
                        }

                        if (!empty($request->carat_min) && !empty($request->carat_max)) {
                            $result_query->where('carat', '<=', $carat_max);
                        }
                    }

                    if (!empty($request->table_per_min) && !empty($request->table_per_max)) {

                        $table_per_min = (float)$request->table_per_min;
                        $table_per_max = (float)$request->table_per_max;

                        if (!empty($request->table_per_min) && !empty($request->table_per_max)) {
                            $result_query->where('table_per', '>=', $table_per_min);
                        }

                        if (!empty($request->table_per_min) && !empty($request->table_per_max)) {
                            $result_query->where('table_per', '<=', $table_per_max);
                        }
                    }

                    if (!empty($request->depth_per_min) && !empty($request->depth_per_max)) {
                        $depth_per_min = (float)$request->depth_per_min;
                        $depth_per_max = (float)$request->depth_per_max;

                        if (!empty($request->depth_per_min) && !empty($request->depth_per_max)) {
                            $result_query->where('depth_per', '>=', $depth_per_min);
                        }

                        if (!empty($request->depth_per_min) && !empty($request->depth_per_max)) {
                            $result_query->where('depth_per', '<=', $depth_per_max);
                        }
                    }

                    if (!empty($request->length_mm_min) && !empty($request->length_mm_max)) {

                        $length_mm_min = (float)$request->length_mm_min;
                        $length_mm_max = (float)$request->length_mm_max;

                        if (!empty($request->length_mm_min) && !empty($request->length_mm_max)) {
                            $result_query->where('length', '>=', $length_mm_min);
                        }

                        if (!empty($request->length_mm_min) && !empty($request->length_mm_max)) {
                            $result_query->where('length', '<=', $length_mm_max);
                        }
                    }

                    if (!empty($request->width_mm_min) && !empty($request->width_mm_max)) {

                        $width_mm_min = (float)$request->width_mm_min;
                        $width_mm_max = (float)$request->width_mm_max;

                        if (!empty($request->width_mm_min) && !empty($request->width_mm_max)) {
                            $result_query->where('width', '>=', $width_mm_min);
                        }

                        if (!empty($request->width_mm_min) && !empty($request->width_mm_max)) {
                            $result_query->where('width', '<=', $width_mm_max);
                        }
                    }

                    if (!empty($request->depth_mm_min) && !empty($request->depth_mm_max)) {
                        $depth_mm_min = (float)$request->depth_mm_min;
                        $depth_mm_max = (float)$request->depth_mm_max;

                        if (!empty($request->depth_mm_min) && !empty($request->depth_mm_max)) {
                            $result_query->where('depth', '>=', $depth_mm_min);
                        }

                        if (!empty($request->depth_mm_min) && !empty($request->depth_mm_max)) {
                            $result_query->where('depth', '<=', $depth_mm_max);
                        }
                    }

                    if (!empty($request->crown_angle_min) && !empty($request->crown_angle_max)) {

                        $crown_angle_min = $request->crown_angle_min;
                        $crown_angle_max = $request->crown_angle_max;

                        if (!empty($request->crown_angle_min) && !empty($request->crown_angle_max)) {
                            $result_query->where('crown_angle', '>=', $crown_angle_min);
                        }

                        if (!empty($request->crown_angle_min) && !empty($request->crown_angle_max)) {
                            $result_query->where('crown_angle', '<=', $crown_angle_max);
                        }
                    }

                    if (!empty($request->crown_height_min) && !empty($request->crown_height_max)) {

                        $crown_height_min = $request->crown_height_min;
                        $crown_height_max = $request->crown_height_max;

                        if (!empty($request->crown_height_min) && !empty($request->crown_height_max)) {
                            $result_query->where('crown_height', '>=', $crown_height_min);
                        }

                        if (!empty($request->crown_height_min) && !empty($request->crown_height_max)) {
                            $result_query->where('crown_height', '<=', $crown_height_max);
                        }
                    }

                    if (!empty($request->pavilion_angle_min) && !empty($request->pavilion_angle_max)) {

                        $pavilion_angle_min = $request->pavilion_angle_min;
                        $pavilion_angle_max = $request->pavilion_angle_max;

                        if (!empty($request->pavilion_angle_min) && !empty($request->pavilion_angle_max)) {
                            $result_query->where('pavilion_angle', '>=', $pavilion_angle_min);
                        }

                        if (!empty($request->pavilion_angle_min) && !empty($request->pavilion_angle_max)) {
                            $result_query->where('pavilion_angle', '<=', $pavilion_angle_max);
                        }
                    }

                    if (!empty($request->pavilion_depth_min) && !empty($request->pavilion_depth_max)) {

                        $pavilion_depth_min = $request->pavilion_depth_min;
                        $pavilion_depth_max = $request->pavilion_depth_max;

                        if (!empty($request->pavilion_depth_min) && !empty($request->pavilion_depth_max)) {
                            $result_query->where('pavilion_depth', '>=', $pavilion_depth_min);
                        }

                        if (!empty($request->pavilion_depth_min) && !empty($request->pavilion_depth_max)) {
                            $result_query->where('pavilion_depth', '<=', $pavilion_depth_max);
                        }
                    }

                    if (!empty($request->usd_per_carat_min) && !empty($request->usd_per_carat_max)) {

                        $usd_per_carat_min = $request->usd_per_carat_min;
                        $usd_per_carat_max = $request->usd_per_carat_max;

                        if (!empty($request->usd_per_carat_min) && !empty($request->usd_per_carat_max)) {
                            $result_query->where('rate', '>=', $usd_per_carat_min);
                        }

                        if (!empty($request->usd_per_carat_min) && !empty($request->usd_per_carat_max)) {
                            $result_query->where('rate', '<=', $usd_per_carat_max);
                        }
                    } else {
                        $result_query->where('rate', '>=', 0.00);
                    }

                    if (!empty($request->usd_value_min) && !empty($request->usd_value_max)) {

                        $usd_value_min = $request->usd_value_min;
                        $usd_value_max = $request->usd_value_max;

                        if (!empty($request->usd_value_min) && !empty($request->usd_value_max)) {
                            $result_query->where('net_dollar', '>=', $usd_value_min);
                        }

                        if (!empty($request->usd_value_min) && !empty($request->usd_value_max)) {
                            $result_query->where('net_dollar', '<=', $usd_value_max);
                        }
                    } else {
                        $result_query->where('net_dollar', '>=', 0.00);
                    }

                    // dd($request->carat_size);
                    if ($request->carat_size) {
                        $carat_size = $request->carat_size;
                        $carat = explode('-', $carat_size);
                        $carat_min = $carat[0];
                        $carat_max = $carat[1];

                        $result_query->where('carat', '>=', $carat_min);
                        $result_query->where('carat', '<=', $carat_max);
                    } elseif ($request->carat_range) {
                        $carat_range = $request->carat_range;
                        $result_query->where(function ($query) use ($carat_range) {
                            $size = explode(",", $carat_range);
                            foreach ($size as $sizevalue) {
                                $query->orWhere(function ($query_c) use ($sizevalue) {
                                    $sizev = explode("-", $sizevalue);
                                    if (!empty($sizev[0])) {
                                        $query_c->where('carat', '>=', $sizev[0]);
                                    }
                                    if (!empty($sizev[1])) {
                                        $query_c->where('carat', '<=', $sizev[1]);
                                    }
                                });
                            }
                        });
                    }

                    if (!empty($request->shape)) {
                        $shape = explode(',', $request->shape);
                        $result_query->whereIn('shape', $shape);
                    }

                    if (!empty($request->white_color)) {
                        $color = explode(',', $request->white_color);
                        $result_query->whereIn('color', $color);
                    }
                    else
                    {
                        if (!empty($request->fancycolor)) {
                            $fancy_color = explode(',', $request->fancycolor);
                            $result_query->whereIn('fancy_color', $fancy_color);
                        }
                        if (!empty($request->fancyintensity)) {
                            $fancy_color = explode(',', $request->fancy_color);
                            $result_query->whereIn('fancy_intensity', $fancy_color);
                        }
                        if (!empty($request->fancyovertone)) {
                            $fancy_color = explode(',', $request->fancyovertone);
                            $result_query->whereIn('fancy_overtone', $fancy_color);
                        }
                    }

                    if (!empty($request->white_color)) {
                        $color = explode(',', $request->white_color);
                        $result_query->whereIn('color', $color);
                    }
                    if (!empty($request->clarity)) {
                        $clarity = explode(',', $request->clarity);
                        $result_query->whereIn('clarity', $clarity);
                    }
                    if (!empty($request->lab)) {
                        $lab = explode(',', $request->lab);
                        $result_query->whereIn('lab', $lab);
                    }
                    if (!empty($request->cut)) {
                        $cut = explode(',', $request->cut);
                        $result_query->whereIn('cut', $cut);
                    }
                    if (!empty($request->polish)) {
                        $polish = explode(',', $request->polish);
                        $result_query->whereIn('polish', $polish);
                    }
                    if (!empty($request->symmetry)) {
                        $symmetry = explode(',', $request->symmetry);
                        $result_query->whereIn('symmetry', $symmetry);
                    }
                    if (!empty($request->fluorescence)) {
                        $fluorescence = explode(',', $request->fluorescence);
                        $result_query->whereIn('fluorescence', $fluorescence);
                    }
                    if (!empty($request->eyeclean)) {
                        $eyeclean = explode(',', $request->eyeclean);
                        $result_query->whereIn('eyeclean', $eyeclean);
                    }
                    if (!empty($request->country)) {
                        $country = explode(',', $request->country);
                        $result_query->whereIn('country', $country);
                    }

                    if (!empty($request->milky)) {
                        if ($request->milky == 'heavy') {
                            $milky = explode(',', $request->milky);
                            $result_query->whereIn('milky', $milky);
                        } elseif ($request->milky == 'medium') {
                            $milky = explode(',', $request->milky);
                            $result_query->whereIn('milky', $milky);
                        } else {
                            $milky = explode(',', $request->milky);
                            $result_query->whereIn('milky', $milky);
                        }
                    }
                }
                $result_query->where('location', 1);
                $result_query->where('status', '0');
                $result_query->where('is_delete', 0);

            $result = $result_query->paginate();

            $updatedItems = $result->getCollection();

            SearchLog::create([
                'user_id' => $customer_id,
                'diamond_type' => 'labgrown-app',
                'certificate_no' => $request->stoneid,
                'carat' => $request->carat_size,
                'shape' => $request->shape,
                'color' => $request->color,
                'clarity' => $request->clarity,
                'cut' => $request->cut,
                'polish' => $request->polish,
                'symmetry' => $request->symmetry,
                'fluorescence' => $request->fluorescence,
                'lab' => $request->lab,
                'EyeC' => $request->eyeclean,
                // 'shade' => $shade,
                // 'milky' => $milky,
                // 'totalprice_min' => $totalprice_min,
                // 'totalprice_max' => $totalprice_max,
                // 'pricepercts_min' => $pricepercts_min,
                // 'pricepercts_max' => $pricepercts_max,

                // 'table_per_from' => $table_per_from,
                // 'table_per_to' => $table_per_to,
                // 'depth_per_from' => $depth_per_from,
                // 'depth_per_to' => $depth_per_to,

                // 'length_min' => $length_min,
                // 'length_max' => $length_max,
                // 'width_min' => $width_min,
                // 'width_max' => $width_max,
                // 'depth_min' => $depth_min,
                // 'depth_max' => $depth_max,

                // 'image_video' => $image_video,
                // 'fancy_color_diamond' => $fancy_color_diamond,
                // 'fancy_color' => $fancy_color,
                // 'fancy_intensity' => $fancy_intensity,
                // 'fancy_overtone' => $fancy_overtone,
                // 'sort_field' => $sort_field,
                // 'sort_order' => $sort_order,
                // 'start_index' => !empty($page) ? $page : 0,
                'search_date' => date('Y-m-d H:i:s'),
                'ip' => $request->ip()
            ]);

            $diamond = array();

            foreach ($updatedItems as $value) {

                $orignal_rate = $value->rate + (($value->rate * ($customer_lab_discount)) / 100);
                $supplier_price = ($orignal_rate * $value->carat);

                if($supplier_price <= 1000)
                {
                    $procurment_price = $supplier_price + 25;
                }
                else if($supplier_price >= 7000)
                {
                    $procurment_price = $supplier_price + 140;
                }
                else if($supplier_price > 1000 && $supplier_price < 7000)
                {
                    $procurment_price = $supplier_price + round((2 / 100) * $supplier_price, 2);
                }
                $carat_price = $procurment_price / $value->carat;

                // $supplier_price = $orignal_rate * $value->carat;

                $procurment_discount = !empty($value->raprate) ? round(($carat_price - $value->raprate) / $value->raprate * 100, 2) : 0;
                $return_price = number_format((1 / 100) * $procurment_price, 2);

                $d_result['sku'] = $value->id;
                $d_result['availability'] = $value->availability;
                $d_result['diamond_type'] = $value->diamond_type;
                $d_result['is_Wishlist'] = !empty($value->is_wishlist) ? True : False;
                $d_result['shape'] = $value->shape;
                $d_result['carat'] = (string)$value->carat;
                $d_result['color'] = $value->color;
                $d_result['clarity'] = $value->clarity;
                $d_result['cut'] = $value->cut;
                $d_result['polish'] = $value->polish;
                $d_result['symmetry'] = $value->symmetry;
                $d_result['fluorescence'] = $value->fluorescence;
                $d_result['lab'] = $value->lab;
                $d_result['certificate_no'] = $value->certificate_no;

                $d_result['country'] = $value->country;

                $d_result['rate'] = (string)$carat_price;
                $d_result['net_price'] = (string)$procurment_price;
                $d_result['discount_main'] = number_format($procurment_discount, 2);
                $d_result['raprate'] = $value->raprate;

                $d_result['orignal_price'] = $supplier_price;
                $d_result['return_price'] = $return_price;

                if ($value->cloud_image) {
                    $d_result['image'] = $value->cloud_image;
                } else {
                    $d_result['image'] = asset('assets/theme/images/'.strtolower($value->shape).'.svg');
                }
                $d_result['video'] = $value->video;
                $d_result['certi_link'] = $value->certificate_link;

                // add new paramter value
                // $d_result['depth'] = $value->depth;
                // $d_result['milky'] = $value->milky;
                // $d_result['shade'] = $value->shade;
                // $d_result['depth_per'] = $value->depth_per;
                // $d_result['table_per'] = $value->table_per;
                // $d_result['length'] = $value->length;
                // $d_result['width'] = $value->width;
                // $d_result['crown_angle'] = $value->crown_angle;
                // $d_result['pavilion_angle'] = $value->pavilion_angle;
                // $d_result['pavilion_depth'] = $value->pavilion_depth;
                // $d_result['crown_height'] = $value->crown_height;
                $diamond[] = $d_result;
            }
            $result->setCollection(collect($diamond));

            // $result['total'] = $result->count();
            $count = $result->count() . ' Lab Grown Stone Found... ';

            return response()->json([
                'success' => true,
                'message' => $count,
                'data' => $result
            ], 201);
        }
        else
        {
            return response()->json([
                'success' => false,
                'message' => 'Error Occur Please try after some time'
            ]);
        }
    }

    public function DiamondDetail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'certi' => 'required',
            'daimond_type' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Enter Valid certificate Detail'
            ]);
        } else {
            $certi = strtoupper($request->certi);
            $diamond_type = strtoupper($request->daimond_type);
            if ($diamond_type == "L" || $diamond_type == "W") {
                $customer_id = Auth::user()->id;
                $customer_data = Customer::where('cus_id', $customer_id)->first();
                $customer_discount = $customer_data->discount;
                $customer_lab_discount = $customer_data->lab_discount;

                if ($diamond_type == "L") {
                    $value = DiamondLabgrown::where('certificate_no', $certi)->select(
                        '*',
                        DB::raw('(SELECT pricechange FROM markup_setting WHERE (net_dollar BETWEEN `min_range` and max_range)) as aditional_discount'),
                        DB::raw('(SELECT id from wish_list where customer_id = ' . $customer_id . ' AND certificate_no = diamond_labgrown.certificate_no AND wish_list.is_delete = "0" limit 1) as is_wishlist'),
                        DB::raw("(SELECT caratprice FROM rap_list WHERE IF(`shape_name` = 'ROUND', shape = 'ROUND', shape != 'ROUND') AND color_name = `color` AND clarity_name = IF(`clarity` = 'FL','IF', `clarity`) AND low_size <= carat AND high_size >= carat) as raprate")
                    )->first();

                    if (!empty($value)) {
                        $orignal_rate = $value->rate + (($value->rate * ($customer_lab_discount)) / 100);
                        $supplier_price = ($orignal_rate * $value->carat);

                        if($supplier_price <= 1000)
                        {
                            $procurment_price = $supplier_price + 25;
                        }
                        else if($supplier_price >= 7000)
                        {
                            $procurment_price = $supplier_price + 140;
                        }
                        else if($supplier_price > 1000 && $supplier_price < 7000)
                        {
                            $procurment_price = $supplier_price + round((2 / 100) * $supplier_price, 2);
                        }
                        $carat_price = $procurment_price / $value->carat;

                        $supplier_price = $orignal_rate * $value->carat;

                        $procurment_discount = !empty($value->raprate) ? round(($carat_price - $value->raprate) / $value->raprate * 100, 2) : 0;
                        $return_price = number_format((1 / 100) * $procurment_price, 2);

                        ViewDiamondDetail::create([
                            'customer_id' => $customer_id,
                            'supplier_id' => $value->supplier_id,
                            'supplier_name' => $value->supplier_name,
                            'diamond_type' => $value->diamond_type,
                            'certificate_no' => $value->certificate_no,
                        ]);

                        $d_result['sku'] = $value->id;
                        $d_result['availability'] = $value->availability;
                        $d_result['diamond_type'] = $value->diamond_type;
                        $d_result['is_Wishlist'] = !empty($value->is_wishlist) ? True : False;
                        $d_result['shape'] = $value->shape;
                        $d_result['carat'] = (string)$value->carat;
                        $d_result['color'] = $value->color;
                        $d_result['clarity'] = $value->clarity;
                        $d_result['cut'] = $value->cut;
                        $d_result['polish'] = $value->polish;
                        $d_result['symmetry'] = $value->symmetry;
                        $d_result['fluorescence'] = $value->fluorescence;

                        $d_result['lab'] = $value->lab;
                        $d_result['certificate_no'] = $value->certificate_no;

                        $d_result['country'] = $value->country;

                        $d_result['rate'] = (string)$carat_price;
                        $d_result['net_price'] = (string)$procurment_price;
                        $d_result['discount_main'] = number_format($procurment_discount, 2);
                        $d_result['raprate'] = $value->raprate;

                        $d_result['orignal_price'] = $supplier_price;
                        $d_result['return_price'] = $return_price;

                        $d_result['certi_link'] = $value->certificate_link;
                        // $d_result['certificate_download'] = $value->certificate_download;
                        $d_result['width'] = $value->width;
                        $d_result['depth'] = $value->depth;
                        $d_result['length'] = $value->length;
                        // $d_result['location'] = $value->location;
                        $d_result['city'] = $value->city;
                        $d_result['milky'] = $value->milky;
                        $d_result['eyeclean'] = $value->eyeclean;
                        $d_result['hna'] = $value->hna;
                        $d_result['depth_per'] = $value->depth_per;
                        $d_result['table_per'] = $value->table_per;
                        $d_result['crown_angle'] = $value->crown_angle;
                        $d_result['crown_height'] = $value->crown_height;
                        $d_result['pavilion_angle'] = $value->pavilion_angle;
                        $d_result['pavilion_depth'] = $value->pavilion_depth;
                        // $d_result['discount'] = $value->discount;
                        // $d_result['rap'] = $value->rap;
                        // $d_result['orignal_rate'] = $value->orignal_rate;
                        // $d_result['rate'] = $value->rate;
                        // $d_result['orignal_rate'] = $value->orignal_rate;
                        // $d_result['net_dollar'] = $value->net_dollar;
                        $d_result['key_symbols'] = $value->key_symbols;
                        $d_result['fancy_color'] = $value->fancy_color;
                        $d_result['fancy_intensity'] = $value->fancy_intensity;
                        $d_result['fancy_overtone'] = $value->fancy_overtone;
                        // $d_result['image_status'] = $value->image_status;
                        // $d_result['cloud_image'] = $value->cloud_image;
                        if ($value->image) {
                            $d_result['image'] = $value->image;
                        } else {
                            $d_result['image'] = asset('assets/theme/images/'.strtolower($value->shape).'.svg');
                        }
                        $d_result['video'] = $value->video;
                        $d_result['heart'] = $value->heart;
                        // $d_result['cloud_heart'] = $value->cloud_heart;
                        $d_result['arrow'] = $value->arrow;
                        // $d_result['cloud_arrow'] = $value->cloud_arrow;
                        $d_result['asset'] = $value->asset;
                        // $d_result['cloud_asset'] = $value->cloud_asset;
                        $d_result['canada_mark'] = $value->canada_mark;
                        $d_result['cutlet'] = $value->cutlet;
                        $d_result['luster'] = $value->luster;
                        $d_result['gridle'] = $value->gridle;
                        $d_result['gridle_per'] = $value->gridle_per;
                        $d_result['girdle_thin'] = $value->girdle_thin;
                        $d_result['girdle_thick'] = $value->girdle_thick;
                        $d_result['shade'] = $value->shade;
                        $d_result['c_type'] = $value->c_type;
                        $d_result['status'] = $value->status;
                        // $d_result['supplier_comments'] = $value->supplier_comments;
                        $d_result['culet_condition'] = $value->culet_condition;
                        // $d_result['hold_for'] = $value->hold_for;
                        // $d_result['hold_status'] = $value->hold_status;

                        $d_result['updated_at'] = $value->updated_at;
                        $d_result['supplier_name'] = $value->supplier_name;

                        return response()->json([
                            'success' => true,
                            'message' => 'Labgrown Diamond',
                            'data' => $d_result
                        ]);
                    } else {
                        return response()->json([
                            'success' => false,
                            'message' => 'No Diamond Found',
                        ]);
                    }
                } else {
                    $value = DiamondNatural::where('certificate_no', $certi)->select(
                        '*',
                        DB::raw('(SELECT pricechange FROM markup_setting WHERE (net_dollar BETWEEN `min_range` and max_range)) as aditional_discount'),
                        DB::raw('(SELECT id from wish_list where customer_id = ' . $customer_id . ' AND certificate_no = diamond_natural.certificate_no AND wish_list.is_delete = "0" limit 1) as is_wishlist'),
                        DB::raw("(SELECT caratprice FROM rap_list WHERE IF(`shape_name` = 'ROUND', shape = 'ROUND', shape != 'ROUND') AND color_name = `color` AND clarity_name = IF(`clarity` = 'FL','IF', `clarity`) AND low_size <= carat AND high_size >= carat) as raprate")
                    )->first();

                    if (!empty($value)) {
                        $orignal_rate = $value->rate + (($value->rate * ($customer_discount)) / 100);
                        $supplier_price = ($orignal_rate * $value->carat);

                        if($supplier_price <= 1000)
                        {
                            $procurment_price = $supplier_price + 25;
                        }
                        else if($supplier_price >= 7000)
                        {
                            $procurment_price = $supplier_price + 140;
                        }
                        else if($supplier_price > 1000 && $supplier_price < 7000)
                        {
                            $procurment_price = $supplier_price + round((2 / 100) * $supplier_price, 2);
                        }
                        $carat_price = $procurment_price / $value->carat;

                        $supplier_price = $orignal_rate * $value->carat;

                        $procurment_discount = !empty($value->raprate) ? round(($carat_price - $value->raprate) / $value->raprate * 100, 2) : 0;
                        $return_price = number_format((1 / 100) * $procurment_price, 2);

                        ViewDiamondDetail::create([
                            'customer_id' => $customer_id,
                            'supplier_id' => $value->supplier_id,
                            'supplier_name' => $value->supplier_name,
                            'diamond_type' => $value->diamond_type,
                            'certificate_no' => $value->certificate_no,
                        ]);

                        $d_result['sku'] = $value->id;
                        $d_result['availability'] = $value->availability;
                        $d_result['diamond_type'] = $value->diamond_type;
                        $d_result['is_Wishlist'] = !empty($value->is_wishlist) ? True : False;
                        $d_result['shape'] = $value->shape;
                        $d_result['carat'] = (string)$value->carat;
                        $d_result['color'] = $value->color;
                        $d_result['clarity'] = $value->clarity;
                        $d_result['cut'] = $value->cut;
                        $d_result['polish'] = $value->polish;
                        $d_result['symmetry'] = $value->symmetry;
                        $d_result['fluorescence'] = $value->fluorescence;

                        $d_result['lab'] = $value->lab;
                        $d_result['certificate_no'] = $value->certificate_no;

                        $d_result['country'] = $value->country;

                        $d_result['rate'] = (string)$carat_price;
                        $d_result['net_price'] = (string)$procurment_price;
                        $d_result['discount_main'] = number_format($procurment_discount, 2);
                        $d_result['raprate'] = $value->raprate;

                        $d_result['orignal_price'] = $supplier_price;
                        $d_result['return_price'] = $return_price;

                        $d_result['certi_link'] = $value->certificate_link;
                        // $d_result['certificate_download'] = $value->certificate_download;
                        $d_result['width'] = $value->width;
                        $d_result['depth'] = $value->depth;
                        $d_result['length'] = $value->length;

                        // $d_result['location'] = $value->location;
                        $d_result['city'] = $value->city;
                        $d_result['milky'] = $value->milky;
                        $d_result['eyeclean'] = $value->eyeclean;
                        $d_result['hna'] = $value->hna;
                        $d_result['depth_per'] = $value->depth_per;
                        $d_result['table_per'] = $value->table_per;
                        $d_result['crown_angle'] = $value->crown_angle;
                        $d_result['crown_height'] = $value->crown_height;
                        $d_result['pavilion_angle'] = $value->pavilion_angle;
                        $d_result['pavilion_depth'] = $value->pavilion_depth;
                        // $d_result['discount'] = $value->discount;
                        // $d_result['rap'] = $value->rap;
                        // $d_result['orignal_rate'] = $value->orignal_rate;
                        // $d_result['rate'] = $value->rate;
                        // $d_result['orignal_rate'] = $value->orignal_rate;
                        // $d_result['net_dollar'] = $value->net_dollar;
                        $d_result['key_symbols'] = $value->key_symbols;
                        $d_result['fancy_color'] = $value->fancy_color;
                        $d_result['fancy_intensity'] = $value->fancy_intensity;
                        $d_result['fancy_overtone'] = $value->fancy_overtone;
                        if ($value->image) {
                            $d_result['image'] = $value->cloud_image;
                        } else {
                            $d_result['image'] = asset('assets/theme/images/'.strtolower($value->shape).'.svg');
                        }
                        // $d_result['image'] = $value->image;
                        $d_result['video'] = $value->video;
                        $d_result['heart'] = $value->heart;
                        $d_result['arrow'] = $value->arrow;
                        $d_result['asset'] = $value->asset;
                        $d_result['canada_mark'] = $value->canada_mark;
                        $d_result['cutlet'] = $value->cutlet;
                        $d_result['luster'] = $value->luster;
                        $d_result['gridle'] = $value->gridle;
                        $d_result['gridle_per'] = $value->gridle_per;
                        $d_result['girdle_thin'] = $value->girdle_thin;
                        $d_result['girdle_thick'] = $value->girdle_thick;
                        $d_result['shade'] = $value->shade;
                        $d_result['c_type'] = $value->c_type;
                        // $d_result['status'] = $value->status;
                        // $d_result['supplier_comments'] = $value->supplier_comments;
                        $d_result['culet_condition'] = $value->culet_condition;

                        $d_result['updated_at'] = $value->updated_at;
                        $d_result['supplier_name'] = $value->supplier_name;

                        return response()->json([
                            'success' => true,
                            'message' => 'Natural Diamond',
                            'data' => $d_result
                        ]);
                    } else {
                        return response()->json([
                            'success' => false,
                            'message' => 'No Diamond Found',
                        ]);
                    }
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => "Enter Valid Diamond Type"
                ]);
            }
        }
    }


    public function AddToCart(Request $request)
    {
        $customer_id = Auth::user()->id;
        $customer_data = Customer::select('*', DB::raw('(SELECT email FROM users WHERE id = customers.added_by) as staffemail'))->where('cus_id', $customer_id)->first();

        $kyc_status = true;
        $message = '';

        //Please wait your Profile approval.
        $diamond_type =  trim(strtoupper($request->diamond_type));
        $certi = $request->certi_no;
        $return = $request->return_price;

        if (!empty($diamond_type) and !empty($certi)) {
            if ($kyc_status) {
                $email = Auth::user()->email;
                $discount_user = $customer_data->discount;
                $lab_discount_user = $customer_data->lab_discount;
                $salespersonemail = $customer_data->staffemail;
                $firstname = Auth::user()->firstname;
                $lastname = Auth::user()->lastname;

                $ip = $_SERVER['REMOTE_ADDR'];
                $date = date('Y-m-d H:i:s');

                $dprice = $total_price = 0;
                $confirm_goods_ids = '';

                if ($diamond_type == "L") {
                    $diamond_detail = DiamondLabgrown::select(
                        '*',
                        DB::raw('(SELECT pricechange FROM markup_setting WHERE (net_dollar BETWEEN `min_range` and max_range)) as aditional_discount'),
                        DB::raw('(SELECT pricechange FROM price_markup_setting WHERE (net_dollar BETWEEN `min_range` and max_range)) as adi_cost'),
                        DB::raw("(SELECT caratprice FROM rap_list WHERE IF(`shape_name` = 'ROUND', shape = 'ROUND', shape != 'ROUND') AND color_name = `color` AND clarity_name = IF(`clarity` = 'FL','IF', `clarity`) AND low_size <= carat AND high_size >= carat) as raprate"),
                        DB::raw('(SELECT hold_allow FROM suppliers WHERE sup_id = supplier_id AND stock_status = "ACTIVE" LIMIT 1) as hold_allow'),
                        DB::raw('(SELECT email FROM users WHERE id = supplier_id) as suplier_email')
                    )
                        ->where('certificate_no', $certi)->first();
                    $loat_no_html = $diamond_detail->id;
                    $cus_discount = $lab_discount_user;
                } else {
                    $diamond_detail = DiamondNatural::select(
                        '*',
                        DB::raw('(SELECT pricechange FROM markup_setting WHERE (net_dollar BETWEEN `min_range` and max_range)) as aditional_discount'),
                        DB::raw('(SELECT pricechange FROM price_markup_setting WHERE (net_dollar BETWEEN `min_range` and max_range)) as adi_cost'),
                        DB::raw("(SELECT caratprice FROM rap_list WHERE IF(`shape_name` = 'ROUND', shape = 'ROUND', shape != 'ROUND') AND color_name = `color` AND clarity_name = IF(`clarity` = 'FL','IF', `clarity`) AND low_size <= carat AND high_size >= carat) as raprate"),
                        DB::raw('(SELECT hold_allow FROM suppliers WHERE sup_id = supplier_id AND stock_status = "ACTIVE" LIMIT 1) as hold_allow'),
                        DB::raw('(SELECT email FROM users WHERE id = supplier_id) as suplier_email')
                    )
                        ->where('certificate_no', $certi)->first();

                    $loat_no_html = $diamond_detail->id;
                    $cus_discount = $discount_user;
                }

                $orignal_rate = $diamond_detail->rate + (($diamond_detail->rate * ($cus_discount)) / 100);
                $supplier_price = ($orignal_rate * $diamond_detail->carat);

                if($supplier_price <= 1000)
                {
                    $procurment_price = $supplier_price + 25;
                }
                else if($supplier_price >= 7000)
                {
                    $procurment_price = $supplier_price + 140;
                }
                else if($supplier_price > 1000 && $supplier_price < 7000)
                {
                    $procurment_price = $supplier_price + round((2 / 100) * $supplier_price, 2);
                }
                $carat_price = $procurment_price / $diamond_detail->carat;

                $return_price = number_format((1 / 100) * $procurment_price, 2);

                $return_price = 0;
                if($return == "yes")
                {
                    $return_price = (1 / 100) * $procurment_price;
                }

                $check = Cart::where('is_delete', 0)
                    ->where('customer_id', $customer_id)
                    ->where('certificate_no', $certi)->count();
                if ($check >= 1) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Diamond Already In Your Cart'
                    ]);
                } else {
                    $data_array = array(
                        'customer_id' => $customer_id,
                        'certificate_no' => $diamond_detail->certificate_no,
                        'sku' => $diamond_detail->id,
                        'diamond_type' => $diamond_detail->diamond_type,
                        'cart_rate' => $carat_price,
                        'price' => $procurment_price,
                        'orignal_price' => $supplier_price,
                        'return_price' => $return_price,
                        'ip' => $ip,
                        'created_at' => $date,
                    );

                    Cart::insertGetId($data_array);
                    return response()->json([
                        'success' => true,
                        'message' => 'Diamond Add Successful'
                    ]);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'KYC Pending'
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => "please enter valid certi and diamond type"
            ]);
        }
    }

    public function CartList(Request $request)
    {
        $customer_id = Auth::user()->id;
        $data['customer_data'] = Customer::select('*', DB::raw('(SELECT email FROM users WHERE id = customers.added_by) as staffemail'))->where('cus_id', $customer_id)->first();

        $data['cart_list'] = array();

        $cart_list = Cart::where('customer_id', $customer_id)->where('is_delete', 0)->get();

        if (!empty($cart_list->toArray())) {
            $diamond_result = array();
            foreach ($cart_list as $diamond_detail) {
                if ($diamond_detail->diamond_type == 'L') {
                    $diamond_result[] = Cart::select(
                        '*',
                        DB::raw('(SELECT pricechange FROM markup_setting WHERE (net_dollar BETWEEN `min_range` and max_range)) as aditional_discount'),
                        DB::raw("(SELECT caratprice FROM rap_list WHERE IF(`shape_name` = 'ROUND', shape = 'ROUND', shape != 'ROUND') AND color_name = `color` AND clarity_name = IF(`clarity` = 'FL','IF', `clarity`) AND low_size <= carat AND high_size >= carat) as raprate")
                    )
                    ->join('diamond_labgrown', 'cart.certificate_no', '=', 'diamond_labgrown.certificate_no')->where('cart.certificate_no', $diamond_detail->certificate_no)->where('cart.customer_id', $customer_id)->where('cart.is_delete', 0)->first();
                } elseif ($diamond_detail->diamond_type == 'W') {
                    $diamond_result[] = Cart::select(
                        '*',
                        DB::raw('(SELECT pricechange FROM markup_setting WHERE (net_dollar BETWEEN `min_range` and max_range)) as aditional_discount'),
                        DB::raw("(SELECT caratprice FROM rap_list WHERE IF(`shape_name` = 'ROUND', shape = 'ROUND', shape != 'ROUND') AND color_name = `color` AND clarity_name = IF(`clarity` = 'FL','IF', `clarity`) AND low_size <= carat AND high_size >= carat) as raprate")
                    )
                    ->join('diamond_natural', 'cart.certificate_no', '=', 'diamond_natural.certificate_no')->where('cart.certificate_no', $diamond_detail->certificate_no)->where('cart.customer_id', $customer_id)->where('cart.is_delete', 0)->first();
                }
            }

            if (!empty($diamond_result)) {
                foreach ($diamond_result as $value) {

                    if ($value->diamond_type == "L") {
                        $cus_discount = $data['customer_data']->lab_discount;
                    } elseif ($value->diamond_type == "W") {
                        $cus_discount = $data['customer_data']->discount;
                    }

                    $orignal_rate = $value->rate + (($value->rate * ($cus_discount)) / 100);
                    $supplier_price = ($orignal_rate * $value->carat);

                    if($supplier_price <= 1000)
                    {
                        $procurment_price = $supplier_price + 25;
                    }
                    else if($supplier_price >= 7000)
                    {
                        $procurment_price = $supplier_price + 140;
                    }
                    else if($supplier_price > 1000 && $supplier_price < 7000)
                    {
                        $procurment_price = $supplier_price + round((2 / 100) * $supplier_price, 2);
                    }

                    $sale_price = $procurment_price + $value->return_price;
                    $sale_rate = $sale_price / $value->carat;
                    $sale_discount = !empty($value->raprate) ? round(($sale_rate - $value->raprate) / $value->raprate * 100, 2) : 0;

                    $d_result['sku'] = $value->id;
                    $d_result['availability'] = $value->availability;
                    $d_result['diamond_type'] = $value->diamond_type;

                    $d_result['shape'] = $value->shape;
                    $d_result['carat'] = (string)$value->carat;
                    $d_result['color'] = $value->color;
                    $d_result['clarity'] = $value->clarity;
                    $d_result['cut'] = $value->cut;
                    $d_result['polish'] = $value->polish;
                    $d_result['symmetry'] = $value->symmetry;
                    $d_result['fluorescence'] = $value->fluorescence;

                    $d_result['lab'] = $value->lab;
                    $d_result['certificate_no'] = $value->certificate_no;

                    $d_result['country'] = $value->country;

                    $d_result['rate'] = (string)$sale_rate;
                    $d_result['net_price'] = (string)$sale_price;
                    $d_result['discount_main'] = number_format($sale_discount, 2);
                    $d_result['raprate'] = $value->raprate;
                    $d_result['return_price'] = $value->return_price;

                    if ($value->cloud_image) {
                        $d_result['image'] = $value->cloud_image;
                    } else {
                        $d_result['image'] = asset('assets/theme/images/'.strtolower($value->shape).'.svg');
                    }
                    $d_result['video'] = $value->video;
                    $d_result['certi_link'] = $value->certificate_link;

                    $diamond[] = $d_result;
                }
                $count = DB::table('cart')->where('is_delete', 0)->where('customer_id', $customer_id)->count();

                return response()->json([
                    'success' => true,
                    'message' => 'Record Found',
                    'count' => $count,
                    'data' => $diamond
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Record Not Found',
            ]);
        }
    }

    public function RemoveCart(Request $request)
    {
        $customer_id = Auth::user()->id;
        $certi = $request->certi;

        if (!empty($certi)) {
            $data = Cart::where('customer_id', $customer_id)->where('certificate_no', $certi)->update([
                'is_delete' => '1'
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Diamond Remove Your Cart',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Please Enter Valid Diamond Certi',
            ]);
        }
    }


    public function DiamondOrder(Request $request)
    {
        $stone = $request->data;
        $customer_id = Auth::user()->id;
        $customer_data = Customer::select('*', DB::raw('(SELECT email FROM users WHERE id = customers.added_by) as staffemail'))->where('cus_id', $customer_id)->first();

        $kyc_status = true;
        $message = 'Please complete your profile to buy more diamond';

        if ($kyc_status) {
            $discount_user = $customer_data->discount;
            $lab_discount_user = $customer_data->lab_discount;
            $salespersonemail = $customer_data->staffemail;
            $firstname = Auth::user()->firstname;
            $lastname = Auth::user()->lastname;

            $ip = $_SERVER['REMOTE_ADDR'];
            $date = date('Y-m-d H:i:s');

            $certi_value = '';
            $lab_value = '';
            $natural_value = '';
            $value_arr = $lab_value_arr = $natural_value_arr = array();
            foreach ($stone as $t) {
                if ($t['diamond_type'] == "L") {
                    $lab_value .= "'" . $t['certi'] . "',";
                    $lab_value_arr[] = $t['certi'];
                } else {
                    $natural_value .= "'" . $t['certi'] . "',";
                    $natural_value_arr[] = $t['certi'];
                }

                $certi_value .= "'" . $t['certi'] . "',";
                $value_arr[] = $t['certi'];
            }

            $certi_value = trim($certi_value, ",");
            $natural_value = trim($natural_value, ",");
            $lab_value = trim($lab_value, ",");

            $pdftotalnetprice = 0;
            $pdftotalcarat = 0;
            $total_carat = 0;
            $total_stone = 0;
            $order_items_ids = $tablerecord = '';
            $total = 0;

            if (empty($certi_value)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Please Select atleast One Diamond'
                ], 201);
            }

            if (!empty($lab_value)) {
                 DiamondLabgrown::whereIn('certificate_no', $lab_value_arr)->update(array('status' => '1'));
            }
            if (!empty($natureal_value)) {
                DiamondNatural::whereIn('certificate_no', $natural_value_arr)->update(array('status' => '1'));
            }

            $data = Order::whereIn('certificate_no', $value_arr)->get();

            if (!empty($data->toArray())) {
                return response()->json([
                    'success' => true,
                    'message' => "Some Dimaond Already orders"
                ]);
            } else {
                WishList::whereIn('certificate_no', $value_arr)->where('customer_id', $customer_id)->delete();
                // DB::table('view_diamond_detail')->whereIn('certificate_no', $value_arr)->delete();

                $total_approved = 0; //$this->Dashboard_Model->sumApproveOrderAprice();
                $total_confirm = 0; //!empty($total_approved->total_confirm) ? $total_approved->total_confirm : 0;
                $total_a_confirm = 0; //!empty($total_approved->total_a_confirm) ? $total_approved->total_a_confirm : 0;

                $dprice = $total_price = 0;
                foreach ($stone as $value) {
                    if ($value['diamond_type'] == "L") {
                        $diamond_detail = DiamondLabgrown::select(
                            '*',
                            DB::raw('(SELECT pricechange FROM markup_setting WHERE (net_dollar BETWEEN `min_range` and max_range)) as aditional_discount'),
                            DB::raw("(SELECT caratprice FROM rap_list WHERE IF(`shape_name` = 'ROUND', shape = 'ROUND', shape != 'ROUND') AND color_name = `color` AND clarity_name = IF(`clarity` = 'FL','IF', `clarity`) AND low_size <= carat AND high_size >= carat) as raprate"),
                            DB::raw('(SELECT hold_allow FROM suppliers WHERE sup_id = supplier_id AND stock_status = "ACTIVE" LIMIT 1) as hold_allow'),
                            DB::raw('(SELECT email FROM users WHERE id = supplier_id) as suplier_email')
                        )
                            ->where('certificate_no', $value['certi'])->first();

                        if (empty($diamond_detail)) {
                            continue;
                        }
                        $loat_no_html = $diamond_detail->id;
                        $v_discount = $lab_discount_user;
                    } else {

                        $diamond_detail = DiamondNatural::select(
                            '*',
                            DB::raw('(SELECT pricechange FROM markup_setting WHERE (net_dollar BETWEEN `min_range` and max_range)) as aditional_discount'),
                            DB::raw("(SELECT caratprice FROM rap_list WHERE IF(`shape_name` = 'ROUND', shape = 'ROUND', shape != 'ROUND') AND color_name = `color` AND clarity_name = IF(`clarity` = 'FL','IF', `clarity`) AND low_size <= carat AND high_size >= carat) as raprate"),
                            DB::raw('(SELECT hold_allow FROM suppliers WHERE sup_id = supplier_id AND stock_status = "ACTIVE" LIMIT 1) as hold_allow'),
                            DB::raw('(SELECT email FROM users WHERE id = supplier_id) as suplier_email')
                        )
                            ->where('certificate_no', $value['certi'])->first();
                        if (empty($diamond_detail)) {
                            continue;
                        }
                        $loat_no_html = $diamond_detail->id;
                        $v_discount = $discount_user;
                    }

                    $total_stone = $total_stone + 1;
                    $suplier_email    = $diamond_detail->suplier_email;

                    // $cc_email		= $diamond_detail->broker_email;
                    // $salesemail		= $diamond_detail->sales_email;

                    $orignal_rate = $diamond_detail->rate + (($diamond_detail->rate * ($v_discount)) / 100);
                    $supplier_price = ($orignal_rate * $diamond_detail->carat);

                    if($supplier_price <= 1000)
                    {
                        $procurment_price = $supplier_price + 25;
                    }
                    else if($supplier_price >= 7000)
                    {
                        $procurment_price = $supplier_price + 140;
                    }
                    else if($supplier_price > 1000 && $supplier_price < 7000)
                    {
                        $procurment_price = $supplier_price + round((2 / 100) * $supplier_price, 2);
                    }
                    $carat_price = $procurment_price / $diamond_detail->carat;

                    $supplier_price = $orignal_rate * $diamond_detail->carat;
                    $supplier_discount = !empty($diamond_detail->raprate) ? round(($orignal_rate - $diamond_detail->raprate) / $diamond_detail->raprate * 100, 2) : 0;

                    $procurment_discount = !empty($diamond_detail->raprate) ? round(($carat_price - $diamond_detail->raprate) / $diamond_detail->raprate * 100, 2) : 0;
                    // $procurment_price = $supplier_price;

                    $return_price = 0;
                    if($value['return_price'] == "yes")
                    {
                        $return_price = round((1 / 100) * $procurment_price, 2);
                    }
                    $sale_price = $procurment_price + $return_price;
                    $sale_rate = $sale_price / $diamond_detail->carat;
                    $sale_discount = !empty($diamond_detail->raprate) ? round(($sale_rate - $diamond_detail->raprate) / $diamond_detail->raprate * 100, 2) : 0;

                    $buy_rate = $diamond_detail->orignal_rate;
                    $buy_price = round($buy_rate * $diamond_detail->carat, 5);
                    $buy_discount = !empty($diamond_detail->raprate) ? round(($buy_rate - $diamond_detail->raprate) / $diamond_detail->raprate * 100, 2) : 0;

                    // $total = $net_price + $total;
                    // $pdftotalnetprice = $pdftotalnetprice + $net_price;
                    // $pdftotalcarat = $pdftotalcarat + $carat;

                    $data_array = array(
                        'customer_id' => $customer_id,
                        'certificate_no' => $diamond_detail->certificate_no,
                        'ref_no' => $diamond_detail->ref_no,
                        'diamond_type' => $value['diamond_type'],
                        'sale_discount' => $sale_discount,
                        'sale_price' => $sale_price,
                        'sale_rate' => $sale_rate,
                        'buy_price' => $buy_price,
                        'buy_rate' => $buy_rate,
                        'buy_discount' => $buy_discount,
                        'return_price' => $return_price,
                        'ip' => $ip,
                        'created_at' => $date,
                    );
                    $last_order_id = Order::insertGetId($data_array);

                    $order_items_ids .= $last_order_id . ',';
                    if ($value['diamond_type'] == "L") {
                        DB::insert("INSERT INTO `orders_items` (`orders_id`,`customer_id`, `id`, `supplier_id`, `supplier_name`, `ref_no`, `availability`, `diamond_type`, `shape`, `carat`, `color`, `clarity`, `cut`, `polish`, `symmetry`, `fluorescence`, `lab`, `certificate_no`, `certificate_link`, `certificate_download`, `length`, `width`, `depth`, `location`, `city`, `country`, `milky`, `eyeclean`, `hna`, `depth_per`, `table_per`, `crown_angle`, `crown_height`, `pavilion_angle`, `pavilion_depth`, `discount`, `rap`, `orignal_rate`, `rate`, `net_dollar`, `key_symbols`, `fancy_color`, `fancy_intensity`, `fancy_overtone`, `image_status`, `cloud_image`, `image`, `video`, `heart`, `cloud_heart`, `arrow`, `cloud_arrow`, `asset`, `cloud_asset`, `canada_mark`, `cutlet`, `luster`, `gridle`, `gridle_per`, `girdle_thin`, `girdle_thick`, `shade`, `c_type`, `status`, `supplier_comments`, `culet_condition`, `hold_for`, `updated_at`, `created_at`, `is_delete`)
                                    SELECT '$last_order_id','$customer_id', `id`, `supplier_id`, `supplier_name`, `ref_no`, `availability`, 'L', `shape`, `carat`, `color`, `clarity`, `cut`, `polish`, `symmetry`, `fluorescence`, `lab`, `certificate_no`, `certificate_link`, `certificate_download`, `length`, `width`, `depth`, `location`, `city`, `country`, `milky`, `eyeclean`, `hna`, `depth_per`, `table_per`, `crown_angle`, `crown_height`, `pavilion_angle`, `pavilion_depth`, `discount`, '$diamond_detail->raprate', `orignal_rate`, `rate`, `net_dollar`, `key_symbols`, `fancy_color`, `fancy_intensity`, `fancy_overtone`, `image_status`, `cloud_image`, `image`, `video`, `heart`, `cloud_heart`, `arrow`, `cloud_arrow`, `asset`, `cloud_asset`, `canada_mark`, `cutlet`, `luster`, `gridle`, `gridle_per`, `girdle_thin`, `girdle_thick`, `shade`, `c_type`, `status`, `supplier_comments`, `culet_condition`, `hold_for`, `updated_at`, '$date', `is_delete`
                                    FROM diamond_labgrown WHERE certificate_no = '" . $diamond_detail->certificate_no . "'");

                        if ($diamond_detail->color == "fancy") {
                            $color = $diamond_detail->fancy_intensity . ' ' . $diamond_detail->fancy_overtone . ' ' . $diamond_detail->fancy_color;
                        } else {
                            $color = $diamond_detail->color;
                        }
                    } else {
                        DB::insert("INSERT INTO `orders_items` (`orders_id`,`customer_id`, `id`, `supplier_id`, `supplier_name`, `ref_no`, `availability`, `diamond_type`, `shape`, `carat`, `color`, `clarity`, `cut`, `polish`, `symmetry`, `fluorescence`, `lab`, `certificate_no`, `certificate_link`, `certificate_download`, `length`, `width`, `depth`, `location`, `city`, `country`, `milky`, `eyeclean`, `hna`, `depth_per`, `table_per`, `crown_angle`, `crown_height`, `pavilion_angle`, `pavilion_depth`, `discount`, `rap`, `orignal_rate`, `rate`, `net_dollar`, `key_symbols`, `fancy_color`, `fancy_intensity`, `fancy_overtone`, `image_status`, `cloud_image`, `image`, `video`, `heart`, `cloud_heart`, `arrow`, `cloud_arrow`, `asset`, `cloud_asset`, `canada_mark`, `cutlet`, `luster`, `gridle`, `gridle_per`, `girdle_thin`, `girdle_thick`, `shade`, `c_type`, `status`, `supplier_comments`, `culet_condition`, `hold_for`, `updated_at`, `created_at`, `is_delete`)
                                    SELECT '$last_order_id','$customer_id', `id`, `supplier_id`, `supplier_name`, `ref_no`, `availability`, 'W', `shape`, `carat`, `color`, `clarity`, `cut`, `polish`, `symmetry`, `fluorescence`, `lab`, `certificate_no`, `certificate_link`, `certificate_download`, `length`, `width`, `depth`, `location`, `city`, `country`, `milky`, `eyeclean`, `hna`, `depth_per`, `table_per`, `crown_angle`, `crown_height`, `pavilion_angle`, `pavilion_depth`, `discount`, '$diamond_detail->raprate', `orignal_rate`, `rate`, `net_dollar`, `key_symbols`, `fancy_color`, `fancy_intensity`, `fancy_overtone`, `image_status`, `cloud_image`, `image`, `video`, `heart`, `cloud_heart`, `arrow`, `cloud_arrow`, `asset`, `cloud_asset`, `canada_mark`, `cutlet`, `luster`, `gridle`, `gridle_per`, `girdle_thin`, `girdle_thick`, `shade`, `c_type`, `status`, `supplier_comments`, `culet_condition`, `hold_for`, `updated_at`, '$date', `is_delete`
                                    FROM diamond_natural WHERE certificate_no = '" . $diamond_detail->certificate_no . "'");

                        if ($diamond_detail->color == "fancy") {
                            $color = $diamond_detail->fancy_intensity . ' ' . $diamond_detail->fancy_overtone . ' ' . $diamond_detail->fancy_color;
                        } else {
                            $color = $diamond_detail->color;
                        }
                    }

                    $tablerecord .= '<table width="100%" style="border:#CEC9C9 solid thin;padding: 10px;font-size: 13px;">
                        <tr>
                            <td width="25%">
                                <strong>' . $loat_no_html . '</strong>
                            </td>
                            <td width="30%">
                                <span><strong>' . $diamond_detail->lab . ': </strong> <strong> ' . $diamond_detail->certificate_no . '</strong></span>
                            </td>
                            <td width="30%" align="right"> <strong> $/CT &nbsp;$' . number_format($carat_price, 2) . '</strong></td>
                        </tr>
                        <tr>
                            <td colspan="2" width="70%">
                                <span style="font-weight: 600">' . $diamond_detail->shape . ' ' . $diamond_detail->carat . 'CT ' . $color . ' ' . $diamond_detail->clarity . ' ' . $diamond_detail->cut . ' ' . $diamond_detail->polish . ' ' . $diamond_detail->symmetry . ' ' . $diamond_detail->fluorescence . '</span>
                            </td>
                            <td width="30%" align="right"><strong>Total &nbsp;$' . number_format($sale_price, 2) . '</strong></td>
                        </tr>
                    </table>';

                    $singlerecord = '<table width="100%" style="border:#CEC9C9 solid thin;padding: 10px;font-size: 13px;">
                        <tr>
                            <td width="25%">
                                <span><a href="" style="text-decoration-color: #4f4f4f"><strong>' . $diamond_detail->ref_no . '</strong></a></span>
                            </td>
                            <td width="30%">
                                <span><strong>' . $diamond_detail->lab . ': </strong><a href="" style="text-decoration-color: #4f4f4f"> <strong> ' . $diamond_detail->certificate_no . '</strong></a></span>
                            </td>
                            <td width="30%" align="right"> <strong> $/CT &nbsp;$' . number_format($diamond_detail->orignal_rate, 2) . '</strong></td>
                        </tr>
                        <tr>
                            <td colspan="2" width="70%">
                                <span style="font-weight: 600">' . $diamond_detail->shape . ' ' . $diamond_detail->carat . 'CT ' . $color . ' ' . $diamond_detail->clarity . ' ' . $diamond_detail->cut . ' ' . $diamond_detail->polish . ' ' . $diamond_detail->symmetry . ' ' . $diamond_detail->fluorescence . '</span>
                            </td>
                            <td width="30%" align="right"><strong>Total &nbsp;$' . number_format($diamond_detail->orignal_rate * $diamond_detail->carat, 2) . '</strong></td>
                        </tr>
                    </table>';

                    $supplier_email_data = array();
                    $supplier_email_data['firstname'] = $diamond_detail->supplier_name;
                    $supplier_email_data['text_message'] = $singlerecord;

                    Mail::send('emails.orders.hold-diamond-supplier', $supplier_email_data, function ($message) use ($suplier_email) {
                        $message->from(\Cons::EMAIL_SUPPLIER, 'Supplier');
                        $message->to($suplier_email);
                        $message->cc(\Cons::EMAIL_SUPPLIER);
                        $message->subject("Hold Diamond Request Received On " . date('d-m-Y H') . " | " . env('APP_NAME'));
                    });
                }

                // $discount_terms = $discount_row = '';
                // if ($customer_data->customer_type != 1) {
                //     $temp_price = $total_price + $total_confirm;
                //     $temp_d_price = $dprice + $total_a_confirm;
                //     $pricechange = 0; //$this->Dashboard_Model->pricesettingadv($temp_d_price);
                //     $nondtotalamount = 0; //$temp_d_price * (1 + $pricechange->pricechange / 100) * (1 + $discount_user / 100);
                //     $savedamount = 0; //round($temp_price - $nondtotalamount, 2);

                //     if ($savedamount > 0.1) {
                //         $discount_row = '<tr align="right">
                //                     <td colspan="2" style="padding: 8px 0px;">
                //                         <strong style="color: #002173;line-height: 1.7;font-size: 15px;text-transform: uppercase;">Consolidate All Orders &
                //                         <span style="color: #ff0000;">SAVE $' . $savedamount . '*</span></strong>
                //                     </td>
                //                 </tr>';

                //         $discount_terms = '<p style="font-size: 13px;line-height: 1.25;color: #4f4f4f;margin:7px 0px;">* Discounts are applicable when you consolidate all your orders (new & previous) in single invoice.</p>';
                //     }
                // }

                $email_data['firstname'] = $firstname;
                $email_data['text_message'] = $tablerecord;

                //TODO::: Remove when Live
                Mail::send('emails.orders.hold-confirm-diamond-customer', $email_data, function ($message) use ($last_order_id) {
                    $message->to(\Cons::EMAIL_SALE);
                    $message->subject('New order place please confirm - ' . Auth::user()->email . ' #' . $last_order_id);
                });


                $email_data['firstname'] = $firstname;
                $email_data['text_message'] = $tablerecord;

                Mail::send('emails.orders.hold-confirm-diamond-customer', $email_data, function ($message) use ($last_order_id) {
                    $message->to(Auth::user()->email);
                    $message->subject('Thank you for your order ' . date('d-m-Y') . " | " . env('APP_NAME'));
                });
            }
            return response()->json([
                'success' => true,
                'message' => 'YOur order Placed'
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Please Upload KYC Dcoument'
            ], 401);
        }
    }

    public function HoldDiamond(Request $request)
    {
        $stone = $request->data;
        $customer_id = Auth::user()->id;
        $customer_data = Customer::select('*', DB::raw('(SELECT email FROM users WHERE id = customers.added_by) as staffemail'))->where('cus_id', $customer_id)->first();
        $kyc_status = true;
        $message = '';

        //Please wait your Profile approval.
        if ($kyc_status) {
            $email = Auth::user()->email;
            $discount_user = $customer_data->discount;
            $lab_discount_user = $customer_data->lab_discount;
            $salespersonemail = $customer_data->staffemail;
            $firstname = Auth::user()->firstname;
            $lastname = Auth::user()->lastname;

            $ip = $_SERVER['REMOTE_ADDR'];
            $date = date('Y-m-d H:i:s');

            $certi_value = '';
            $lab_value = '';
            $natural_value = '';
            $value_arr = $lab_value_arr = $natural_value_arr = array();
            $stone = json_decode($stone, true);
            foreach ($stone as $t) {
                // dd($t['diamond_type']);
                if ($t['diamond_type'] == "L") {
                    $lab_value .= "'" . $t['certi'] . "',";
                    $lab_value_arr[] = $t['certi'];
                } else {
                    $natural_value .= "'" . $t['certi'] . "',";
                    $natural_value_arr[] = $t['certi'];
                }
                $certi_value .= "'" . $t['certi'] . "',";
                $value_arr[] = $t['certi'];
            }
            $certi_value = trim($certi_value, ",");
            $natural_value = trim($natural_value, ",");
            $lab_value = trim($lab_value, ",");

            $total_price = 0;
            $total_carat = 0;
            $total_stone = 0;
            $order_items_ids = $tablerecord = '';
            $total = 0;
            if (empty($certi_value)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Please Select atleast One Diamond'
                ], 201);
            }

            if (!empty($lab_value)) {
                DiamondLabgrown::whereIn('certificate_no', $lab_value_arr)->update(array('status' => '1'));
            }
            if (!empty($natureal_value)) {
                DiamondNatural::whereIn('certificate_no', $natural_value_arr)->update(array('status' => '1'));
            }

            $data = Order::whereIn('certificate_no', $value_arr)->get();

            if (!empty($data->toArray())) {
                return response()->json([
                    'success' => false,
                    'message' => "Some Dimaond Already Hold"
                ]);
            } else {

                // DB::table('wish_list')->whereIn('certificate_no', $value_arr)->where('customer_id', $customer_id)->delete();
                // DB::table('view_diamond_detail')->whereIn('certificate_no', $value_arr)->delete();
                // DB::table('orders')->whereIn('certificate_no','')->where()

                $dprice = $total_price = 0;
                $confirm_goods_ids = '';
                foreach ($stone as $value) {
                    if ($value['diamond_type'] == "L") {
                        $diamond_detail = DiamondLabgrown::select(
                            '*',
                            DB::raw('(SELECT pricechange FROM markup_setting WHERE (net_dollar BETWEEN `min_range` and max_range)) as aditional_discount'),
                            DB::raw("(SELECT caratprice FROM rap_list WHERE IF(`shape_name` = 'ROUND', shape = 'ROUND', shape != 'ROUND') AND color_name = `color` AND clarity_name = IF(`clarity` = 'FL','IF', `clarity`) AND low_size <= carat AND high_size >= carat) as raprate"),
                            DB::raw('(SELECT hold_allow FROM suppliers WHERE sup_id = supplier_id AND stock_status = "ACTIVE" LIMIT 1) as hold_allow'),
                            DB::raw('(SELECT email FROM users WHERE id = supplier_id) as suplier_email')
                        )
                            ->where('certificate_no', $value['certi'])->first();
                        if (empty($diamond_detail)) {
                            continue;
                        }
                        $loat_no_html = $diamond_detail->id;
                        $cus_discount = $lab_discount_user;
                    } else {
                        $diamond_detail = DiamondNatural::select(
                            '*',
                            DB::raw('(SELECT pricechange FROM markup_setting WHERE (net_dollar BETWEEN `min_range` and max_range)) as aditional_discount'),
                            DB::raw("(SELECT caratprice FROM rap_list WHERE IF(`shape_name` = 'ROUND', shape = 'ROUND', shape != 'ROUND') AND color_name = `color` AND clarity_name = IF(`clarity` = 'FL','IF', `clarity`) AND low_size <= carat AND high_size >= carat) as raprate"),
                            DB::raw('(SELECT hold_allow FROM suppliers WHERE sup_id = supplier_id AND stock_status = "ACTIVE" LIMIT 1) as hold_allow'),
                            DB::raw('(SELECT email FROM users WHERE id = supplier_id) as suplier_email')
                        )
                            ->where('certificate_no', $value['certi'])->first();
                        if (empty($diamond_detail)) {
                            continue;
                        }
                        $loat_no_html = $diamond_detail->id;
                        $cus_discount = $discount_user;
                    }

                    $total_stone = $total_stone + 1;
                    $suplier_email    = $diamond_detail->suplier_email;
                    // $cc_email		= $diamond_detail->brokeremail;
                    // $salesemail		= $diamond_detail->salesemail;

                    $carat = $diamond_detail->carat;

                    $orignal_rate = $diamond_detail->rate + (($diamond_detail->rate * ($cus_discount)) / 100);
                    $supplier_price = ($orignal_rate * $diamond_detail->carat);

                    if($supplier_price <= 1000)
                    {
                        $procurment_price = $supplier_price + 25;
                    }
                    else if($supplier_price >= 7000)
                    {
                        $procurment_price = $supplier_price + 140;
                    }
                    else if($supplier_price > 1000 && $supplier_price < 7000)
                    {
                        $procurment_price = $supplier_price + round((2 / 100) * $supplier_price, 2);
                    }
                    $carat_price = $procurment_price / $diamond_detail->carat;

                    $supplier_price = $orignal_rate * $diamond_detail->carat;
                    $supplier_discount = !empty($diamond_detail->raprate) ? round(($orignal_rate - $diamond_detail->raprate) / $diamond_detail->raprate * 100, 2) : 0;

                    $procurment_discount = !empty($diamond_detail->raprate) ? round(($carat_price - $diamond_detail->raprate) / $diamond_detail->raprate * 100, 2) : 0;
                    // $procurment_price = $supplier_price;

                    $sale_price = $procurment_price;
                    $sale_rate = $sale_price / $diamond_detail->carat;
                    $sale_discount = !empty($diamond_detail->raprate) ? round(($sale_rate - $diamond_detail->raprate) / $diamond_detail->raprate * 100, 2) : 0;

                    $buy_rate = $diamond_detail->orignal_rate;
                    $buy_price = round($buy_rate * $diamond_detail->carat, 5);
                    $buy_discount = !empty($diamond_detail->raprate) ? round(($buy_rate - $diamond_detail->raprate) / $diamond_detail->raprate * 100, 2) : 0;

                    // $total = $net_price + $total;
                    // $total_price = $total_price + $net_price;
                    // $total_carat = $total_carat + $carat;

                    $data_array = array(
                        'customer_id' => $customer_id,
                        'certificate_no' => $diamond_detail->certificate_no,
                        'ref_no' => $diamond_detail->ref_no,
                        'diamond_type' => $value['diamond_type'],
                        'sale_discount' => $sale_discount,
                        'sale_price' => $sale_price,
                        'sale_rate' => $sale_rate,
                        'buy_price' => $buy_price,
                        'buy_rate' => $buy_rate,
                        'buy_discount' => $buy_discount,
                        'hold' => 1,
                        'hold_time' => $request->hold_duration,
                        'ip' => $ip,
                        'hold_at' => $date,
                        'created_at' => $date,
                    );
                    $last_order_id = Order::insertGetId($data_array);

                    $order_items_ids .= $last_order_id . ',';

                    if ($value['diamond_type'] == "L") {
                        DB::insert("INSERT IGNORE INTO `orders_items` (`orders_id`,`customer_id`, `id`, `supplier_id`, `supplier_name`, `ref_no`, `availability`, `diamond_type`, `shape`, `carat`, `color`, `clarity`, `cut`, `polish`, `symmetry`, `fluorescence`, `lab`, `certificate_no`, `certificate_link`, `certificate_download`, `length`, `width`, `depth`, `location`, `city`, `country`, `milky`, `eyeclean`, `hna`, `depth_per`, `table_per`, `crown_angle`, `crown_height`, `pavilion_angle`, `pavilion_depth`, `discount`, `rap`, `orignal_rate`, `rate`, `net_dollar`, `key_symbols`, `fancy_color`, `fancy_intensity`, `fancy_overtone`, `image_status`, `cloud_image`, `image`, `video`, `heart`, `cloud_heart`, `arrow`, `cloud_arrow`, `asset`, `cloud_asset`, `canada_mark`, `cutlet`, `luster`, `gridle`, `gridle_per`, `girdle_thin`, `girdle_thick`, `shade`, `c_type`, `status`, `supplier_comments`, `culet_condition`, `hold_for`, `updated_at`, `created_at`, `is_delete`)
                            SELECT '$last_order_id','$customer_id', `id`, `supplier_id`, `supplier_name`, `ref_no`, `availability`, 'L', `shape`, `carat`, `color`, `clarity`, `cut`, `polish`, `symmetry`, `fluorescence`, `lab`, `certificate_no`, `certificate_link`, `certificate_download`, `length`, `width`, `depth`, `location`, `city`, `country`, `milky`, `eyeclean`, `hna`, `depth_per`, `table_per`, `crown_angle`, `crown_height`, `pavilion_angle`, `pavilion_depth`, `discount`, '$diamond_detail->raprate', `orignal_rate`, `rate`, `net_dollar`, `key_symbols`, `fancy_color`, `fancy_intensity`, `fancy_overtone`, `image_status`, `cloud_image`, `image`, `video`, `heart`, `cloud_heart`, `arrow`, `cloud_arrow`, `asset`, `cloud_asset`, `canada_mark`, `cutlet`, `luster`, `gridle`, `gridle_per`, `girdle_thin`, `girdle_thick`, `shade`, `c_type`, `status`, `supplier_comments`, `culet_condition`, `hold_for`, `updated_at`, '$date', `is_delete`
                            FROM diamond_labgrown WHERE certificate_no = '" . $diamond_detail->certificate_no . "'");

                        if ($diamond_detail->color == "fancy") {
                            $color = $diamond_detail->fancy_intensity . ' ' . $diamond_detail->fancy_overtone . ' ' . $diamond_detail->fancy_color;
                        } else {
                            $color = $diamond_detail->color;
                        }
                    } else {
                        DB::insert("INSERT IGNORE INTO `orders_items` (`orders_id`,`customer_id`, `id`, `supplier_id`, `supplier_name`, `ref_no`, `availability`, `diamond_type`, `shape`, `carat`, `color`, `clarity`, `cut`, `polish`, `symmetry`, `fluorescence`, `lab`, `certificate_no`, `certificate_link`, `certificate_download`, `length`, `width`, `depth`, `location`, `city`, `country`, `milky`, `eyeclean`, `hna`, `depth_per`, `table_per`, `crown_angle`, `crown_height`, `pavilion_angle`, `pavilion_depth`, `discount`, `rap`, `orignal_rate`, `rate`, `net_dollar`, `key_symbols`, `fancy_color`, `fancy_intensity`, `fancy_overtone`, `image_status`, `cloud_image`, `image`, `video`, `heart`, `cloud_heart`, `arrow`, `cloud_arrow`, `asset`, `cloud_asset`, `canada_mark`, `cutlet`, `luster`, `gridle`, `gridle_per`, `girdle_thin`, `girdle_thick`, `shade`, `c_type`, `status`, `supplier_comments`, `culet_condition`, `hold_for`, `updated_at`, `created_at`, `is_delete`)
                                                    SELECT '$last_order_id','$customer_id', `id`, `supplier_id`, `supplier_name`, `ref_no`, `availability`, 'W', `shape`, `carat`, `color`, `clarity`, `cut`, `polish`, `symmetry`, `fluorescence`, `lab`, `certificate_no`, `certificate_link`, `certificate_download`, `length`, `width`, `depth`, `location`, `city`, `country`, `milky`, `eyeclean`, `hna`, `depth_per`, `table_per`, `crown_angle`, `crown_height`, `pavilion_angle`, `pavilion_depth`, `discount`, '$diamond_detail->raprate', `orignal_rate`, `rate`, `net_dollar`, `key_symbols`, `fancy_color`, `fancy_intensity`, `fancy_overtone`, `image_status`, `cloud_image`, `image`, `video`, `heart`, `cloud_heart`, `arrow`, `cloud_arrow`, `asset`, `cloud_asset`, `canada_mark`, `cutlet`, `luster`, `gridle`, `gridle_per`, `girdle_thin`, `girdle_thick`, `shade`, `c_type`, `status`, `supplier_comments`, `culet_condition`, `hold_for`, `updated_at`, '$date', `is_delete`
                        FROM diamond_natural WHERE certificate_no = '" . $diamond_detail->certificate_no . "'");

                        if ($diamond_detail->color == "fancy") {
                            $color = $diamond_detail->fancy_intensity . ' ' . $diamond_detail->fancy_overtone . ' ' . $diamond_detail->fancy_color;
                        } else {
                            $color = $diamond_detail->color;
                        }
                    }

                    $tablerecord .= '<table width="100%" style="border:#CEC9C9 solid thin;padding: 10px;font-size: 13px;">
                            <tr>
                                <td width="25%">
                                    <strong>' . $loat_no_html . '</strong>
                                </td>
                                <td width="30%">
                                    <span><strong>' . $diamond_detail->lab . ': </strong> <strong> ' . $diamond_detail->certificate_no . '</strong></span>
                                </td>
                                <td width="30%" align="right"> <strong> $/CT &nbsp;$' . number_format($carat_price, 2) . '</strong></td>
                            </tr>
                            <tr>
                                <td colspan="2" width="70%">
                                    <span style="font-weight: 600">' . $diamond_detail->shape . ' ' . $carat . 'CT ' . $color . ' ' . $diamond_detail->clarity . ' ' . $diamond_detail->cut . ' ' . $diamond_detail->polish . ' ' . $diamond_detail->symmetry . ' ' . $diamond_detail->fluorescence . '</span>
                                </td>
                                <td width="30%" align="right"><strong>Total &nbsp;$' . number_format($sale_price, 2) . '</strong></td>
                            </tr>
                        </table>';

                    $singlerecord = '<table width="100%" style="border:#CEC9C9 solid thin;padding: 10px;font-size: 13px;">
                            <tr>
                                <td width="25%">
                                    <span><a href="" style="text-decoration-color: #4f4f4f"><strong>' . $diamond_detail->ref_no . '</strong></a></span>
                                </td>
                                <td width="30%">
                                    <span><strong>' . $diamond_detail->lab . ': </strong><a href="" style="text-decoration-color: #4f4f4f"> <strong> ' . $diamond_detail->certificate_no . '</strong></a></span>
                                </td>
                                <td width="30%" align="right"> <strong> $/CT &nbsp;$' . number_format($diamond_detail->orignal_rate, 2) . '</strong></td>
                            </tr>
                            <tr>
                                <td colspan="2" width="70%">
                                    <span style="font-weight: 600">' . $diamond_detail->shape . ' ' . $carat . 'CT ' . $color . ' ' . $diamond_detail->clarity . ' ' . $diamond_detail->cut . ' ' . $diamond_detail->polish . ' ' . $diamond_detail->symmetry . ' ' . $diamond_detail->fluorescence . '</span>
                                </td>
                                <td width="30%" align="right"><strong>Total &nbsp;$' . number_format($diamond_detail->orignal_rate * $carat, 2) . '</strong></td>
                            </tr>
                        </table>';

                    $supplier_email_data = array();
                    $supplier_email_data['firstname'] = $diamond_detail->supplier_name;
                    $supplier_email_data['text_message'] = $singlerecord;

                    Mail::send('emails.orders.hold-diamond-supplier', $supplier_email_data, function ($message) use ($suplier_email) {
                        $message->from(\Cons::EMAIL_SUPPLIER, 'Supplier');
                        $message->to($suplier_email);
                        $message->cc(\Cons::EMAIL_SUPPLIER);
                        $message->subject("Hold Diamond Request Received On " . date('d-m-Y-H') . " | " . env('APP_NAME'));
                    });
                }

                $email_data['firstname'] = $request->firstname;
                $email_data['text_message'] = $tablerecord;
                Mail::send('emails.orders.hold-diamond-customer', $email_data, function($message) use($email){
                    $message->from(\Cons::EMAIL_SUPPLIER, 'Supplier');
                    $message->to($email);
                    $message->bcc(\Cons::EMAIL_SALE);
                    $message->subject("Hold Diamond Request Received On ". date('d-m-Y H-i') ." | ". env('APP_NAME'));
                });

                return response()->json([
                    'success' => true,
                    'message' => 'Hold Your Order Successful',
                ], 201);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Please Upload KYC Dcoument'
            ], 401);
        }
    }

    public function WishlistDiamond(Request $request)
    {
        $customer_id = Auth::user()->id;
        $stone = $request->data;

        $certi = array();
        $diamond_type = array();

        if (empty($stone)) {
            return response()->json([
                'success' => true,
                'message' => 'Please Select atleast One Diamond'
            ], 201);
        }

        $stone = json_decode($stone, true);
        foreach ($stone as $stone_detail) {
            $stone_arr[] = $stone_detail['certi'];
        }

        $data = WishList::where('customer_id', $customer_id)->whereIn('certificate_no', $stone_arr)->where('is_delete', 0)->get();
        $check_certi = $data->toArray();

        if (!empty($check_certi)) {
            return response()->json([
                'success' => false,
                'message' => 'Some diamond are already in your Wishlist',
            ]);
        } else {
            $date = date('Y-m-d H:i:s');
            foreach ($stone as $stone_detail) {
                WishList::create([
                    'customer_id' => $customer_id,
                    'certificate_no' => $stone_detail['certi'],
                    'diamond_type' => $stone_detail['diamond_type'],
                    'created_at' => $date,
                    'is_delete' => 0
                ]);
            }
            return response()->json([
                'success' => true,
                'message' => 'Diamond added into your Wishlist',
            ]);
        }
    }

    public function MyWishlist()
    {
        $customer_id = Auth::user()->id;
        $wish_list = WishList::where('customer_id', $customer_id)->where('is_delete', 0)->orderBy('created_at', 'desc')->get();

        if (!empty($wish_list->toArray())) {
            $diamond_result = array();
            foreach ($wish_list as $diamond_detail) {

                if ($diamond_detail->diamond_type == 'L') {
                    $diamond_result[] = WishList::select(
                        '*',
                        DB::raw('(SELECT pricechange FROM markup_setting WHERE (net_dollar BETWEEN `min_range` and max_range)) as aditional_discount'),
                        DB::raw("(SELECT caratprice FROM rap_list WHERE IF(`shape_name` = 'ROUND', shape = 'ROUND', shape != 'ROUND') AND color_name = `color` AND clarity_name = IF(`clarity` = 'FL','IF', `clarity`) AND low_size <= carat AND high_size >= carat) as raprate")
                    )
                        ->join('diamond_labgrown', 'wish_list.certificate_no', '=', 'diamond_labgrown.certificate_no')->where('wish_list.certificate_no', $diamond_detail->certificate_no)->where('wish_list.customer_id', $customer_id)->first();
                } elseif ($diamond_detail->diamond_type == 'W') {
                    $diamond_result[] = WishList::select(
                        '*',
                        DB::raw('(SELECT pricechange FROM markup_setting WHERE (net_dollar BETWEEN `min_range` and max_range)) as aditional_discount'),
                        DB::raw("(SELECT caratprice FROM rap_list WHERE IF(`shape_name` = 'ROUND', shape = 'ROUND', shape != 'ROUND') AND color_name = `color` AND clarity_name = IF(`clarity` = 'FL','IF', `clarity`) AND low_size <= carat AND high_size >= carat) as raprate")
                    )
                        ->join('diamond_natural', 'wish_list.certificate_no', '=', 'diamond_natural.certificate_no')->where('wish_list.certificate_no', $diamond_detail->certificate_no)->where('wish_list.customer_id', $customer_id)->first();
                }
            }

            if (!empty($diamond_result)) {
                $customer_data = Customer::where('cus_id', $customer_id)->first();
                $customer_discount = $customer_data->discount;
                $customer_lab_discount = $customer_data->lab_discount;

                foreach ($diamond_result as $diamond_detail) {
                    if (empty($diamond_detail)) {
                        continue;
                    }

                    if ($diamond_detail != null) {

                        if ($diamond_detail->diamond_type == "L") {
                            $cus_discount = $customer_lab_discount;
                        } elseif ($diamond_detail->diamond_type == "W") {
                            $cus_discount = $customer_discount;
                        }

                        $orignal_rate = $diamond_detail->rate + (($diamond_detail->rate * ($cus_discount)) / 100);
                        $supplier_price = ($orignal_rate * $diamond_detail->carat);

                        if($supplier_price <= 1000)
                        {
                            $procurment_price = $supplier_price + 25;
                        }
                        else if($supplier_price >= 7000)
                        {
                            $procurment_price = $supplier_price + 140;
                        }
                        else if($supplier_price > 1000 && $supplier_price < 7000)
                        {
                            $procurment_price = $supplier_price + round((2 / 100) * $supplier_price, 2);
                        }
                        $carat_price = $procurment_price / $diamond_detail->carat;

                        $supplier_price = $orignal_rate * $diamond_detail->carat;

                        $procurment_discount = !empty($diamond_detail->raprate) ? round(($carat_price - $diamond_detail->raprate) / $diamond_detail->raprate * 100, 2) : 0;
                        // $procurment_price = $supplier_price;

                        $return_price = round((1 / 100) * $procurment_price, 2);

                        $d_result['sku'] = $diamond_detail->id;
                        $d_result['availability'] = $diamond_detail->availability;
                        $d_result['diamond_type'] = $diamond_detail->diamond_type;

                        $d_result['shape'] = $diamond_detail->shape;
                        $d_result['carat'] = (string)$diamond_detail->carat;
                        $d_result['color'] = $diamond_detail->color;
                        $d_result['clarity'] = $diamond_detail->clarity;
                        $d_result['cut'] = $diamond_detail->cut;
                        $d_result['polish'] = $diamond_detail->polish;
                        $d_result['symmetry'] = $diamond_detail->symmetry;
                        $d_result['fluorescence'] = $diamond_detail->fluorescence;

                        $d_result['lab'] = $diamond_detail->lab;
                        $d_result['certificate_no'] = $diamond_detail->certificate_no;

                        $d_result['country'] = $diamond_detail->country;

                        $d_result['rate'] = (string)$carat_price;
                        $d_result['net_price'] = (string)$procurment_price;
                        $d_result['discount_main'] = number_format($procurment_discount, 2);
                        $d_result['raprate'] = $diamond_detail->raprate;

                        $d_result['orignal_price'] = $supplier_price;
                        $d_result['return_price'] = $return_price;

                        if ($diamond_detail->cloud_image) {
                            $d_result['image'] = $diamond_detail->cloud_image;
                        } else {
                            $d_result['image'] = asset('assets/theme/images/'.strtolower($diamond_detail->shape).'.svg');
                        }
                        $d_result['video'] = $diamond_detail->video;
                        $d_result['certi_link'] = $diamond_detail->certificate_link;

                        $diamond[] = $d_result;
                    }
                }
                return response()->json([
                    'success' => true,
                    'message' => 'Record Found',
                    'data' => $diamond
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Record Not Found',

                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No record found',
            ]);
        }
    }

    public function RemoveDaimondWishlist(Request $request)
    {
        $stone = $request->data;
        $customer_id = Auth::user()->id;
        if (!empty($stone)) {
            $stone = json_decode($stone, true);
            foreach ($stone as $value) {
                $certi[] = $value['certi'];
                $diamond_type[] = $value['diamond_type'];
            }
            WishList::where('customer_id', $customer_id)->whereIn('certificate_no', $certi)->update([
                'is_delete' => '1',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Diamond Delete Successful',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Select Diamond',
            ]);
        }
    }

    public function HoldList()
    {
        $customer_id = Auth::user()->id;
        $orders_data = Order::select('*', DB::raw('(SELECT pricechange FROM markup_setting WHERE (net_dollar BETWEEN `min_range` and max_range)) as aditional_discount'))
                        ->join('orders_items', 'orders.orders_id', '=', 'orders_items.orders_id')
                        ->where('orders.hold', 1)
                        ->where('orders.order_status', '!=', 'RELEASED')
                        ->where('orders.is_deleted', 0)
                        ->where('orders.customer_id', $customer_id)
                        ->orderBy('orders.created_at', 'desc')->get();

        if ($orders_data->isNotEmpty()) {
            $customer_data = Customer::where('cus_id', $customer_id)->first();

            $diamond = array();

            foreach ($orders_data as $diamond_detail) {

                if($diamond_detail->diamond_type == "L")
                {
                    $v_discount = $customer_data->lab_discount;
                }
                elseif($diamond_detail->diamond_type == "W")
                {
                    $v_discount = $customer_data->discount;
                }

                $orignal_rate = $diamond_detail->rate + (($diamond_detail->rate * ($v_discount)) / 100);
                $supplier_price = ($orignal_rate * $diamond_detail->carat);

                if($supplier_price <= 1000)
                {
                    $procurment_price = $supplier_price + 25;
                }
                else if($supplier_price >= 7000)
                {
                    $procurment_price = $supplier_price + 140;
                }
                else if($supplier_price > 1000 && $supplier_price < 7000)
                {
                    $procurment_price = $supplier_price + round((2 / 100) * $supplier_price, 2);
                }
                $carat_price = $procurment_price / $diamond_detail->carat;

                $supplier_price = $orignal_rate * $diamond_detail->carat;
                $supplier_discount = !empty($diamond_detail->raprate) ? round(($orignal_rate - $diamond_detail->raprate) / $diamond_detail->raprate * 100, 2) : 0;

                $procurment_discount = !empty($diamond_detail->raprate) ? round(($carat_price - $diamond_detail->raprate) / $diamond_detail->raprate * 100, 2) : 0;

                $return_price = number_format((1 / 100) * $procurment_price, 2);

                $d_result['sku'] = $diamond_detail->id;
                $d_result['availability'] = $diamond_detail->availability;
                $d_result['diamond_type'] = $diamond_detail->diamond_type;

                $d_result['shape'] = $diamond_detail->shape;
                $d_result['carat'] = (string)$diamond_detail->carat;
                $d_result['color'] = $diamond_detail->color;
                $d_result['clarity'] = $diamond_detail->clarity;
                $d_result['cut'] = $diamond_detail->cut;
                $d_result['polish'] = $diamond_detail->polish;
                $d_result['symmetry'] = $diamond_detail->symmetry;
                $d_result['fluorescence'] = $diamond_detail->fluorescence;

                $d_result['lab'] = $diamond_detail->lab;
                $d_result['certificate_no'] = $diamond_detail->certificate_no;

                $d_result['country'] = $diamond_detail->country;

                $d_result['rate'] = (string)$carat_price;
                $d_result['net_price'] = (string)$procurment_price;
                $d_result['discount_main'] = number_format($procurment_discount, 2);
                $d_result['raprate'] = $diamond_detail->raprate;

                $d_result['orignal_price'] = $supplier_price;
                $d_result['return_price'] = $return_price;

                if ($diamond_detail->cloud_image) {
                    $d_result['image'] = $diamond_detail->cloud_image;
                } else {
                    $d_result['image'] = asset('assets/theme/images/'.strtolower($diamond_detail->shape).'.svg');
                }
                $d_result['video'] = $diamond_detail->video;
                $d_result['certi_link'] = $diamond_detail->certificate_link;

                $diamond[] = $d_result;
            }

            return response()->json([
                'success' => true,
                'message' => 'Record Found',
                'data' => $diamond
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Record Not Found',
            ]);
        }
    }

    public function ConfirmDiamond(Request $request)
    {
        $customer_id = Auth::user()->id;
        $customer_data = Customer::select('*', DB::raw('(SELECT email FROM users WHERE id = customers.added_by) as staffemail'))->where('cus_id', $customer_id)->first();
        $stone = $request->data;
        $kyc_status = true;

        if ($kyc_status) {
            if (!empty($stone)) {
                $discount_user = $customer_data->discount;
                $lab_discount_user = $customer_data->lab_discount;
                $salespersonemail = $customer_data->staffemail;
                $firstname = Auth::user()->firstname;
                $lastname = Auth::user()->lastname;

                $ip = $_SERVER['REMOTE_ADDR'];
                $date = date('Y-m-d H:i:s');

                $certi_value = '';
                $lab_value = '';
                $natural_value = '';
                $value_arr = $lab_value_arr = $natural_value_arr = array();
                $stone = json_decode($request->data, true);

                foreach ($stone as $t) {
                    if ($t['diamond_type'] == "L") {
                        $lab_value .= "'" . $t['certi'] . "',";
                        $lab_value_arr[] = $t['certi'];
                    } else {
                        $natural_value .= "'" . $t['certi'] . "',";
                        $natural_value_arr[] = $t['certi'];
                    }
                    $certi_value .= "'" . $t['certi'] . "',";
                    $value_arr[] = $t['certi'];
                }
                $certi_value = trim($certi_value, ",");
                $natural_value = trim($natural_value, ",");
                $lab_value = trim($lab_value, ",");

                $pdftotalnetprice = 0;
                $pdftotalcarat = 0;
                $total_carat = 0;
                $total_stone = 0;
                $order_items_ids = $tablerecord = '';
                $total = 0;


                if (!empty($lab_value)) {
                    DiamondLabgrown::whereIn('certificate_no', $lab_value_arr)->update(array('status' => '1'));
                }
                if (!empty($natureal_value)) {
                    DiamondNatural::whereIn('certificate_no', $natural_value_arr)->update(array('status' => '1'));
                }
                // DB::table('wish_list')->whereIn('certificate_no', $value_arr)->where('customer_id', $customer_id)->delete();
                // DB::table('view_diamond_detail')->whereIn('certificate_no', $value_arr)->delete();

                $data = Order::whereIn('certificate_no', $value_arr)->get();
                if (!empty($data->toArray())) {
                    return response()->json([
                        'success' => false,
                        'message' => "Some diamond already in your Order !"
                    ]);
                } else {
                    $total_approved = 0; //$this->Dashboard_Model->sumApproveOrderAprice();
                    $total_confirm = 0; //!empty($total_approved->total_confirm) ? $total_approved->total_confirm : 0;
                    $total_a_confirm = 0; //!empty($total_approved->total_a_confirm) ? $total_approved->total_a_confirm : 0;

                    $dprice = $total_price = 0;
                    foreach ($stone as $value) {
                        if ($value['diamond_type'] == "L") {
                            $diamond_detail = DiamondLabgrown::select(
                                '*',
                                DB::raw('(SELECT pricechange FROM markup_setting WHERE (net_dollar BETWEEN `min_range` and max_range)) as aditional_discount'),
                                DB::raw("(SELECT caratprice FROM rap_list WHERE IF(`shape_name` = 'ROUND', shape = 'ROUND', shape != 'ROUND') AND color_name = `color` AND clarity_name = IF(`clarity` = 'FL','IF', `clarity`) AND low_size <= carat AND high_size >= carat) as raprate"),
                                DB::raw('(SELECT hold_allow FROM suppliers WHERE sup_id = supplier_id AND stock_status = "ACTIVE" LIMIT 1) as hold_allow'),
                                DB::raw('(SELECT email FROM users WHERE id = supplier_id) as suplier_email')
                            )
                                ->where('certificate_no', $value['certi'])->first();
                            if (empty($diamond_detail)) {
                                continue;
                            }
                            $loat_no_html = $diamond_detail->id;
                            $cus_discount = $lab_discount_user;
                        } else {
                            $diamond_detail = DiamondNatural::select(
                                '*',
                                DB::raw('(SELECT pricechange FROM markup_setting WHERE (net_dollar BETWEEN `min_range` and max_range)) as aditional_discount'),
                                DB::raw("(SELECT caratprice FROM rap_list WHERE IF(`shape_name` = 'ROUND', shape = 'ROUND', shape != 'ROUND') AND color_name = `color` AND clarity_name = IF(`clarity` = 'FL','IF', `clarity`) AND low_size <= carat AND high_size >= carat) as raprate"),
                                DB::raw('(SELECT hold_allow FROM suppliers WHERE sup_id = supplier_id AND stock_status = "ACTIVE" LIMIT 1) as hold_allow'),
                                DB::raw('(SELECT email FROM users WHERE id = supplier_id) as suplier_email')
                            )
                                ->where('certificate_no', $value['certi'])->first();
                            if (empty($diamond_detail)) {
                                continue;
                            }
                            $loat_no_html = $diamond_detail->id;
                            $cus_discount = $discount_user;
                        }

                        $total_stone = $total_stone + 1;
                        $suplier_email    = $diamond_detail->suplier_email;

                        // $cc_email		= $diamond_detail->broker_email;
                        // $salesemail		= $diamond_detail->sales_email;

                        $carat = $diamond_detail->carat;

                        $orignal_rate = $diamond_detail->rate + (($diamond_detail->rate * ($cus_discount)) / 100);
                        $supplier_price = ($orignal_rate * $diamond_detail->carat);

                        if($supplier_price <= 1000)
                        {
                            $procurment_price = $supplier_price + 25;
                        }
                        else if($supplier_price >= 7000)
                        {
                            $procurment_price = $supplier_price + 140;
                        }
                        else if($supplier_price > 1000 && $supplier_price < 7000)
                        {
                            $procurment_price = $supplier_price + round((2 / 100) * $supplier_price, 2);
                        }
                        $carat_price = $procurment_price / $diamond_detail->carat;

                        $supplier_price = $orignal_rate * $diamond_detail->carat;

                        $return_price = 0;
                        if($value['return_price'] == "yes")
                        {
                            $return_price = round((1 / 100) * $procurment_price, 2);
                        }
                        $sale_price = $procurment_price + $return_price;
                        $sale_rate = $sale_price / $diamond_detail->carat;
                        $sale_discount = !empty($diamond_detail->raprate) ? round(($sale_rate - $diamond_detail->raprate) / $diamond_detail->raprate * 100, 2) : 0;

                        $buy_rate = $diamond_detail->orignal_rate;
                        $buy_price = round($buy_rate * $carat, 5);
                        $buy_discount = !empty($diamond_detail->raprate) ? round(($buy_rate - $diamond_detail->raprate) / $diamond_detail->raprate * 100, 2) : 0;

                        // $total = $net_price + $total;
                        // $pdftotalnetprice = $pdftotalnetprice + $net_price;
                        // $pdftotalcarat = $pdftotalcarat + $carat;

                        $data_array = array(
                            'customer_id' => $customer_id,
                            'certificate_no' => $diamond_detail->certificate_no,
                            'ref_no' => $diamond_detail->ref_no,
                            'diamond_type' => $value['diamond_type'],
                            'sale_discount' => $sale_discount,
                            'sale_price' => $sale_price,
                            'sale_rate' => $sale_rate,
                            'buy_price' => $buy_price,
                            'buy_rate' => $buy_rate,
                            'buy_discount' => $buy_discount,
                            'return_price' => $return_price,
                            'ip' => $ip,
                            'created_at' => $date,
                        );
                        $last_order_id = Order::insertGetId($data_array);

                        $order_items_ids .= $last_order_id . ',';
                        if ($value['diamond_type'] == "L") {
                            DB::insert("INSERT IGNORE INTO `orders_items` (`orders_id`,`customer_id`, `id`, `supplier_id`, `supplier_name`, `ref_no`, `availability`, `diamond_type`, `shape`, `carat`, `color`, `clarity`, `cut`, `polish`, `symmetry`, `fluorescence`, `lab`, `certificate_no`, `certificate_link`, `certificate_download`, `length`, `width`, `depth`, `location`, `city`, `country`, `milky`, `eyeclean`, `hna`, `depth_per`, `table_per`, `crown_angle`, `crown_height`, `pavilion_angle`, `pavilion_depth`, `discount`, `rap`, `orignal_rate`, `rate`, `net_dollar`, `key_symbols`, `fancy_color`, `fancy_intensity`, `fancy_overtone`, `image_status`, `cloud_image`, `image`, `video`, `heart`, `cloud_heart`, `arrow`, `cloud_arrow`, `asset`, `cloud_asset`, `canada_mark`, `cutlet`, `luster`, `gridle`, `gridle_per`, `girdle_thin`, `girdle_thick`, `shade`, `c_type`, `status`, `supplier_comments`, `culet_condition`, `hold_for`, `updated_at`, `created_at`, `is_delete`)
                                                        SELECT '$last_order_id','$customer_id', `id`, `supplier_id`, `supplier_name`, `ref_no`, `availability`, 'L', `shape`, `carat`, `color`, `clarity`, `cut`, `polish`, `symmetry`, `fluorescence`, `lab`, `certificate_no`, `certificate_link`, `certificate_download`, `length`, `width`, `depth`, `location`, `city`, `country`, `milky`, `eyeclean`, `hna`, `depth_per`, `table_per`, `crown_angle`, `crown_height`, `pavilion_angle`, `pavilion_depth`, `discount`, '$diamond_detail->raprate', `orignal_rate`, `rate`, `net_dollar`, `key_symbols`, `fancy_color`, `fancy_intensity`, `fancy_overtone`, `image_status`, `cloud_image`, `image`, `video`, `heart`, `cloud_heart`, `arrow`, `cloud_arrow`, `asset`, `cloud_asset`, `canada_mark`, `cutlet`, `luster`, `gridle`, `gridle_per`, `girdle_thin`, `girdle_thick`, `shade`, `c_type`, `status`, `supplier_comments`, `culet_condition`, `hold_for`, `updated_at`, '$date', `is_delete`
                            FROM diamond_labgrown WHERE certificate_no = '" . $diamond_detail->certificate_no . "'");

                            if ($diamond_detail->color == "fancy") {
                                $color = $diamond_detail->fancy_intensity . ' ' . $diamond_detail->fancy_overtone . ' ' . $diamond_detail->fancy_color;
                            } else {
                                $color = $diamond_detail->color;
                            }
                        } else {
                            DB::insert("INSERT IGNORE INTO `orders_items` (`orders_id`,`customer_id`, `id`, `supplier_id`, `supplier_name`, `ref_no`, `availability`, `diamond_type`, `shape`, `carat`, `color`, `clarity`, `cut`, `polish`, `symmetry`, `fluorescence`, `lab`, `certificate_no`, `certificate_link`, `certificate_download`, `length`, `width`, `depth`, `location`, `city`, `country`, `milky`, `eyeclean`, `hna`, `depth_per`, `table_per`, `crown_angle`, `crown_height`, `pavilion_angle`, `pavilion_depth`, `discount`, `rap`, `orignal_rate`, `rate`, `net_dollar`, `key_symbols`, `fancy_color`, `fancy_intensity`, `fancy_overtone`, `image_status`, `cloud_image`, `image`, `video`, `heart`, `cloud_heart`, `arrow`, `cloud_arrow`, `asset`, `cloud_asset`, `canada_mark`, `cutlet`, `luster`, `gridle`, `gridle_per`, `girdle_thin`, `girdle_thick`, `shade`, `c_type`, `status`, `supplier_comments`, `culet_condition`, `hold_for`, `updated_at`, `created_at`, `is_delete`)
                                                        SELECT '$last_order_id','$customer_id', `id`, `supplier_id`, `supplier_name`, `ref_no`, `availability`, 'W', `shape`, `carat`, `color`, `clarity`, `cut`, `polish`, `symmetry`, `fluorescence`, `lab`, `certificate_no`, `certificate_link`, `certificate_download`, `length`, `width`, `depth`, `location`, `city`, `country`, `milky`, `eyeclean`, `hna`, `depth_per`, `table_per`, `crown_angle`, `crown_height`, `pavilion_angle`, `pavilion_depth`, `discount`, '$diamond_detail->raprate', `orignal_rate`, `rate`, `net_dollar`, `key_symbols`, `fancy_color`, `fancy_intensity`, `fancy_overtone`, `image_status`, `cloud_image`, `image`, `video`, `heart`, `cloud_heart`, `arrow`, `cloud_arrow`, `asset`, `cloud_asset`, `canada_mark`, `cutlet`, `luster`, `gridle`, `gridle_per`, `girdle_thin`, `girdle_thick`, `shade`, `c_type`, `status`, `supplier_comments`, `culet_condition`, `hold_for`, `updated_at`, '$date', `is_delete`
                            FROM diamond_natural WHERE certificate_no = '" . $diamond_detail->certificate_no . "'");

                            if ($diamond_detail->color == "fancy") {
                                $color = $diamond_detail->fancy_intensity . ' ' . $diamond_detail->fancy_overtone . ' ' . $diamond_detail->fancy_color;
                            } else {
                                $color = $diamond_detail->color;
                            }
                        }

                        Cart::where('customer_id',$customer_id)->where('certificate_no',$diamond_detail->certificate_no)->update([
                            'is_delete'=>'1'
                        ]);

                        $tablerecord .= '<table width="100%" style="border:#CEC9C9 solid thin;padding: 10px;font-size: 13px;">
                                <tr>
                                    <td width="25%">
                                        <strong>' . $loat_no_html . '</strong>
                                    </td>
                                    <td width="30%">
                                        <span><strong>' . $diamond_detail->lab . ': </strong> <strong> ' . $diamond_detail->certificate_no . '</strong></span>
                                    </td>
                                    <td width="30%" align="right"> <strong> $/CT &nbsp;$' . number_format($carat_price, 2) . '</strong></td>
                                </tr>
                                <tr>
                                    <td colspan="2" width="70%">
                                        <span style="font-weight: 600">' . $diamond_detail->shape . ' ' . $carat . 'CT ' . $color . ' ' . $diamond_detail->clarity . ' ' . $diamond_detail->cut . ' ' . $diamond_detail->polish . ' ' . $diamond_detail->symmetry . ' ' . $diamond_detail->fluorescence . '</span>
                                    </td>
                                    <td width="30%" align="right"><strong>Total &nbsp;$' . number_format($sale_price, 2) . '</strong></td>
                                </tr>
                            </table>';

                        $singlerecord = '<table width="100%" style="border:#CEC9C9 solid thin;padding: 10px;font-size: 13px;">
                            <tr>
                                <td width="25%">
                                    <span><a href="" style="text-decoration-color: #4f4f4f"><strong>' . $diamond_detail->ref_no . '</strong></a></span>
                                </td>
                                <td width="30%">
                                    <span><strong>' . $diamond_detail->lab . ': </strong><a href="" style="text-decoration-color: #4f4f4f"> <strong> ' . $diamond_detail->certificate_no . '</strong></a></span>
                                </td>
                                <td width="30%" align="right"> <strong> $/CT &nbsp;$' . number_format($diamond_detail->orignal_rate, 2) . '</strong></td>
                            </tr>
                            <tr>
                                <td colspan="2" width="70%">
                                    <span style="font-weight: 600">' . $diamond_detail->shape . ' ' . $carat . 'CT ' . $color . ' ' . $diamond_detail->clarity . ' ' . $diamond_detail->cut . ' ' . $diamond_detail->polish . ' ' . $diamond_detail->symmetry . ' ' . $diamond_detail->fluorescence . '</span>
                                </td>
                                <td width="30%" align="right"><strong>Total &nbsp;$' . number_format($diamond_detail->orignal_rate * $carat, 2) . '</strong></td>
                            </tr>
                        </table>';

                        $supplier_email_data = array();
                        $supplier_email_data['firstname'] = $diamond_detail->supplier_name;
                        $supplier_email_data['text_message'] = $singlerecord;

                        // dd($suplier_email);
                        Mail::send('emails.orders.hold-diamond-supplier', $supplier_email_data, function ($message) use ($suplier_email) {
                            $message->from(\Cons::EMAIL_SUPPLIER, 'Supplier');
                            $message->to($suplier_email);
                            $message->cc(\Cons::EMAIL_SUPPLIER);
                            $message->subject("Hold Diamond Request Received On " . date('d-m-Y H') . " | " . env('APP_NAME'));
                        });
                    }

                    $discount_terms = $discount_row = '';
                    if ($customer_data->customer_type != 1) {
                        $temp_price = $total_price + $total_confirm;
                        $temp_d_price = $dprice + $total_a_confirm;
                        $pricechange = 0; //$this->Dashboard_Model->pricesettingadv($temp_d_price);
                        $nondtotalamount = 0; //$temp_d_price * (1 + $pricechange->pricechange / 100) * (1 + $discount_user / 100);
                        $savedamount = 0; //round($temp_price - $nondtotalamount, 2);

                        if ($savedamount > 0.1) {
                            $discount_row = '<tr align="right">
                                            <td colspan="2" style="padding: 8px 0px;">
                                                <strong style="color: #002173;line-height: 1.7;font-size: 15px;text-transform: uppercase;">Consolidate All Orders &
                                                <span style="color: #ff0000;">SAVE $' . $savedamount . '*</span></strong>
                                            </td>
                                        </tr>';

                            $discount_terms = '<p style="font-size: 13px;line-height: 1.25;color: #4f4f4f;margin:7px 0px;">* Discounts are applicable when you consolidate all your orders (new & previous) in single invoice.</p>';
                        }
                    }

                    $email_data['firstname'] = $firstname;
                    $email_data['text_message'] = $tablerecord;

                    //TODO::: Remove when Live
                    Mail::send('emails.orders.hold-confirm-diamond-customer', $email_data, function ($message) use ($last_order_id) {
                        $message->to(\Cons::EMAIL_SALE);
                        $message->subject('New order place please confirm - ' . Auth::user()->email . ' #' . $last_order_id);
                    });


                    $email_data['firstname'] = $firstname;
                    $email_data['text_message'] = $tablerecord;

                    Mail::send('emails.orders.hold-confirm-diamond-customer', $email_data, function ($message) use ($last_order_id) {
                        $message->to(Auth::user()->email);
                        $message->subject('Thank you for your order ' . date('d-m-Y') . " | " . env('APP_NAME'));
                    });

                    return response()->json([
                        'success' => true,
                        'message' => "Thank you for your order"
                    ]);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => "Select At least On Diamond",
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => "Please Upload KYC Detail"
            ]);
        }
    }

    public function ConfirmDiamondList()
    {
        $customer_id = Auth::user()->id;
        $orders = Order::select(
            '*',
            DB::raw('(SELECT pricechange FROM markup_setting WHERE (net_dollar BETWEEN `min_range` and max_range)) as aditional_discount'),
            DB::raw("(SELECT caratprice FROM rap_list WHERE IF(`shape_name` = 'ROUND', shape = 'ROUND', shape != 'ROUND') AND color_name = `color` AND clarity_name = IF(`clarity` = 'FL','IF', `clarity`) AND low_size <= carat AND high_size >= carat) as raprate")
        )
            ->join('orders_items', 'orders.orders_id', '=', 'orders_items.orders_id')
            ->where('orders.hold', 0)
            ->where('orders.is_deleted', 0)
            ->where('orders.customer_id', $customer_id)
            ->get();

        $customer_data = Customer::where('cus_id', $customer_id)->first();

        $customer_discount = $customer_data->discount;
        $customer_lab_discount = $customer_data->lab_discount;

        if ($orders->isNotEmpty()) {
            foreach ($orders as $value) {

                if($value->diamond_type == "W")
                {
                    $cus_discount = $customer_discount;
                }
                elseif($value->diamond_type == "L")
                {
                    $cus_discount = $customer_lab_discount;
                }

                $orignal_rate = $value->rate + (($value->rate * ($cus_discount)) / 100);
                $supplier_price = ($orignal_rate * $value->carat);

                if($supplier_price <= 1000)
                {
                    $procurment_price = $supplier_price + 25;
                }
                else if($supplier_price >= 7000)
                {
                    $procurment_price = $supplier_price + 140;
                }
                else if($supplier_price > 1000 && $supplier_price < 7000)
                {
                    $procurment_price = $supplier_price + round((2 / 100) * $supplier_price, 2);
                }
                $carat_price = $procurment_price / $value->carat;

                $supplier_price = $orignal_rate * $value->carat;

                $d_result['sku'] = $value->id;
                $d_result['diamond_type'] = $value->diamond_type;
                $d_result['certificate_no'] = $value->certificate_no;
                $d_result['order_status'] = $value->order_status;
                $d_result['created_at'] = $value->created_at;
                $d_result['shape'] = $value->shape;
                $d_result['carat'] = $value->carat;
                $d_result['color'] = $value->color;
                $d_result['clarity'] = $value->clarity;
                $d_result['cut'] = $value->cut;
                $d_result['polish'] = $value->polish;
                $d_result['lab'] = $value->lab;
                $d_result['symmetry'] = $value->symmetry;
                $d_result['fluorescence'] = $value->fluorescence;
                $d_result['certificate_link'] = $value->certificate_link;
                $d_result['country'] = $value->country;

                if ($value->cloud_image) {
                    $d_result['image'] = $value->cloud_image;
                } else {
                    $d_result['image'] = asset('assets/theme/images/'.strtolower($value->shape).'.svg');
                }
                $d_result['video'] = $value->video;

                $d_result['rate'] = $value->sale_rate;
                $d_result['net_price'] = $value->sale_price;
                $d_result['discount_main'] = $value->buy_discount;
                $d_result['raprate'] = $value->raprate;

                $d_result['orignal_price'] = '';//$value->buy_price;
                $d_result['return_price'] = $value->return_price;

                $diamond[] = $d_result;
            }
            return response()->json([
                'success' => true,
                'message' => "Diamond Found",
                'data' => $diamond
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => "Diamond Not Found",
            ]);
        }
    }

    public function RecentView()
    {
        $customer_id =  Auth::user()->id;
        $recentView = ViewDiamondDetail::where('customer_id', $customer_id)
            ->where('is_delete', 0)
            ->orderBy('visited_at', 'DESC')
            ->groupBy('certificate_no')
            ->limit(5)
            ->get();

        $wish_list_count = WishList::where('customer_id', $customer_id)->where('is_delete', 0)->count();

        $hold = Order::where('hold', 1)
            ->where('hold', '!=', 'RELEASED')
            ->where('hold', '!=', 'REJECT')
            ->where('is_deleted', 0)
            ->where('customer_id', $customer_id)
            ->count();

        $cart = Cart::where('customer_id', $customer_id)->where('is_delete', 0)->count();

        $linksArray[] = asset('assets/mobile-app/hk-banner.png');
        $linksArray[] = asset('assets/mobile-app/appbanner1.png');

        $image = array_filter($linksArray);

        if (!empty($recentView->toArray())) {
            $diamond_result = array();
            foreach ($recentView as $diamond_detail) {
                if ($diamond_detail->diamond_type == 'L') {
                    $diamond_result[] = ViewDiamondDetail::select(
                        'diamond_labgrown.*',
                        DB::raw('(SELECT pricechange FROM markup_setting WHERE (diamond_labgrown.net_dollar BETWEEN `min_range` and max_range)) as aditional_discount'),
                        DB::raw("(SELECT caratprice FROM rap_list WHERE IF(`shape_name` = 'ROUND', diamond_labgrown.shape = 'ROUND', diamond_labgrown.shape != 'ROUND') AND color_name = diamond_labgrown.`color` AND clarity_name = IF(diamond_labgrown.`clarity` = 'FL','IF', diamond_labgrown.`clarity`) AND low_size <= diamond_labgrown.carat AND high_size >= diamond_labgrown.carat) as raprate")
                    )
                        ->join('diamond_labgrown', 'view_diamond_detail.certificate_no', '=', 'diamond_labgrown.certificate_no')
                        ->where('view_diamond_detail.certificate_no', $diamond_detail->certificate_no)
                        ->where('view_diamond_detail.customer_id', $customer_id)->first();
                } elseif ($diamond_detail->diamond_type == 'W') {
                    $diamond_result[] = ViewDiamondDetail::select(
                        'diamond_natural.*',
                        DB::raw('(SELECT pricechange FROM markup_setting WHERE (diamond_natural.net_dollar BETWEEN `min_range` and max_range)) as aditional_discount'),
                        DB::raw("(SELECT caratprice FROM rap_list WHERE IF(`shape_name` = 'ROUND', diamond_natural.shape = 'ROUND', diamond_natural.shape != 'ROUND') AND color_name = diamond_natural.`color` AND clarity_name = IF(diamond_natural.`clarity` = 'FL','IF', diamond_natural.`clarity`) AND low_size <= diamond_natural.carat AND high_size >= diamond_natural.carat) as raprate")
                    )
                        ->join('diamond_natural', 'view_diamond_detail.certificate_no', '=', 'diamond_natural.certificate_no')
                        ->where('view_diamond_detail.certificate_no', $diamond_detail->certificate_no)
                        ->where('view_diamond_detail.customer_id', $customer_id)->first();
                }
            }

            $customer_data = Customer::where('cus_id', $customer_id)->first();
            $customer_discount = $customer_data->discount;
            $customer_lab_discount = $customer_data->lab_discount;

            if (!empty($diamond_result)) {
                foreach ($diamond_result as $value) {
                    if (empty($value)) {
                        continue;
                    }

                    if ($value->diamond_type == "L") {
                        $cus_discount = $customer_lab_discount;
                    } elseif ($value->diamond_type == "W") {
                        $cus_discount = $customer_discount;
                    }

                    $orignal_rate = $value->rate + (($value->rate * ($cus_discount)) / 100);
                    $supplier_price = ($orignal_rate * $value->carat);

                    if($supplier_price <= 1000)
                    {
                        $procurment_price = $supplier_price + 25;
                    }
                    else if($supplier_price >= 7000)
                    {
                        $procurment_price = $supplier_price + 140;
                    }
                    else if($supplier_price > 1000 && $supplier_price < 7000)
                    {
                        $procurment_price = $supplier_price + round((2 / 100) * $supplier_price, 2);
                    }
                    $carat_price = $procurment_price / $value->carat;

                    $supplier_price = $orignal_rate * $value->carat;

                    $procurment_discount = !empty($value->raprate) ? round(($carat_price - $value->raprate) / $value->raprate * 100, 2) : 0;
                    // $procurment_price = $supplier_price;

                    $return_price = round((1 / 100) * $procurment_price, 2);

                    $d_result['sku'] = $value->id;
                    $d_result['availability'] = $value->availability;
                    $d_result['diamond_type'] = $value->diamond_type;

                    $d_result['shape'] = $value->shape;
                    $d_result['carat'] = (string)$value->carat;
                    $d_result['color'] = $value->color;
                    $d_result['clarity'] = $value->clarity;
                    $d_result['cut'] = $value->cut;
                    $d_result['polish'] = $value->polish;
                    $d_result['symmetry'] = $value->symmetry;
                    $d_result['fluorescence'] = $value->fluorescence;

                    $d_result['lab'] = $value->lab;
                    $d_result['certificate_no'] = $value->certificate_no;

                    $d_result['country'] = $value->country;

                    $d_result['rate'] = (string)$carat_price;
                    $d_result['net_price'] = (string)$procurment_price;
                    $d_result['discount_main'] = number_format($procurment_discount, 2);
                    $d_result['raprate'] = $value->raprate;

                    $d_result['orignal_price'] = $supplier_price;
                    $d_result['return_price'] = $return_price;

                    if ($value->cloud_image) {
                        $d_result['image'] = $value->cloud_image;
                    } else {
                        $d_result['image'] = asset('assets/theme/images/'.strtolower($value->shape).'.svg');
                    }
                    $d_result['video'] = $value->video;
                    $d_result['certi_link'] = $value->certificate_link;

                    $diamond[] = $d_result;
                }
                return response()->json([
                    'success' => true,
                    'message' => 'Record Found',
                    'data' => $diamond,
                    'hold' => $hold,
                    'cart' => $cart,
                    'wishlist' => $wish_list_count,
                    'image' => $image
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Record Not Found',
                    'hold' => $hold,
                    'cart' => $cart,
                    'wishlist' => $wish_list_count,
                    'image' => $image
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No record found',
                'hold' => $hold,
                'cart' => $cart,
                'wishlist' => $wish_list_count,
                'image' => $image
            ]);
        }
    }

    public function ClearRecentView()
    {
        $customer_id = Auth::user()->id;
        ViewDiamondDetail::where('customer_id', $customer_id)->update([
            'is_delete' => 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => "Clear All Diamond ",
        ]);
    }

    public function InvoiceList()
    {
        $customer_id = Auth::user()->id;

        $invoice = Invoice::select('invoice_number', 'pre_carriage', 'total_amount', 'tracking_no', 'created_at', DB::raw('(SELECT SUM(carat) FROM orders_items WHERE FIND_IN_SET(orders_items.orders_id,invoices.orders_id)) as carat'), DB::raw('(SELECT count(id) FROM orders_items WHERE FIND_IN_SET(orders_items.orders_id,invoices.orders_id)) as total_iteam'), DB::raw("'AWAITED' as payment_status") )
        ->where('customer_id',$customer_id)
        ->orderBy('invoices.created_at', 'desc')->get();

        $invoice_count = $invoice->count();

        if ($invoice_count > 0) {
            return response()->json([
                'success' => true,
                'message' => 'Found Invoice List',
                'data' => $invoice
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Not Found Invoice List'
            ]);
        }
    }

    public function InvoiceDetail(Request $request)
    {
        $invoice_number = $request->invoice_number;
        $customer_id = Auth::user()->id;

        $invoice = Invoice::select('*', DB::raw('(SELECT SUM(carat) FROM orders_items WHERE FIND_IN_SET(orders_items.orders_id,invoices.orders_id)) as carat'), DB::raw('(SELECT count(id) FROM orders_items WHERE FIND_IN_SET(orders_items.orders_id,invoices.orders_id)) as total_iteam'))
        ->where('customer_id',$customer_id)
        ->orderBy('invoices.created_at', 'desc')->get();

        foreach($invoice as $list)
        {
            $invoice_detail = InvoiceItem::select('orders_items.*','orders_items.id as sku')
            ->join('orders_items', 'orders_items.orders_id', '=', 'invoice_items.orders_id')
            ->where('invoice_items.customer_id',$customer_id)
            ->where('invoice_id',$list->invoice_id)
            ->get();
            $list->url = url('assets/invoices/');
            $list->detail = $invoice_detail;

        }

        $invoice_count = $invoice->count();

        if ($invoice_count > 0) {
            return response()->json([
                'success' => true,
                'message' => 'Found Invoice List',
                'data' => $list
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Not Found Invoice List'
            ]);
        }
    }

    public function sendWebNotification(Request $request)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $FcmToken = User::whereNotNull('device_key')->pluck('device_key')->all();

        $serverKey = 'AAAAK_Yr2-s:APA91bEB4_bNvG7upKs2PqMkQri5QVK40sf6wbMT_0rp0LRc1EiIk3nLDdwqoDxSaeX7H0qRGLavPZsWdRoN5aHA-DSPYos0AkcfLR7vxDnXvPYqIFXyJ4fBfuuk_aPDYbRzyFlAgCUs';

        $data = [
            "registration_ids" => $FcmToken,
            "notification" => [
                "title" => "Hello",
                "body" => "Test",
            ]
        ];
        $encodedData = json_encode($data);

        $headers = [
            'Authorization:key=' . $serverKey,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        // Close connection
        curl_close($ch);
        // FCM response
        dd($result);
    }

    public function UserChangePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cpassword' => 'required',
            'newpassword' => ['required', Password::min(8)->mixedCase()->numbers()->symbols()],
        ]);

        $customer_id = Auth::user()->id;
        $cpassword = $request->cpassword;
        $newpassword = hash::make($request->newpassword);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
            ]);
        } else {
            $get_password = User::find($customer_id);
            $password_check = Hash::check($cpassword, $get_password->password);

            if ($password_check) {
                User::where('id', $customer_id)->update([
                    'password' => $newpassword,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => ' Password Reset Successful',
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Sorry Password Not Matched Please Enter valid Passwod !',
                ]);
            }
        }
    }

    public function UserProfile(Request $exception)
    {
        $user_type = Auth::user()->user_type;
        if ($user_type == 1) {
            $user_id =  auth()->user()->id;
            $data = User::find($user_id);

            return response()->json([
                'success' => true,
                'message' => 'Admin Profile ',
                'user_type' => 'Admin',
                'data' => $data,
            ], 200);
        } elseif ($user_type == 2) {
            $user_id =  auth()->user()->id;

            $data = User::with('customer')->where('id', $user_id)->first();
            if (empty($data['customer']['avatar'])) {
                $data['customer']['avatar'] = asset('assets/images/user.png');
            } else {
                $data['customer']['avatar'] = asset('uploads/customer_doc/' . $data['customer']['avatar']); //'https://stage.thediamondport.com/assets/images/user.png';
            }
            return response()->json([
                'success' => true,
                'message' => 'Customer Profile ',
                'user_type' => 'Customer',
                'data' => $data
            ], 200);
        } elseif ($user_type == 3) {
            $user_id =  auth()->user()->id;
            $data = User::with('supplier')->where('id', $user_id)->first();
            if (empty($data['supplier']['avatar'])) {
                $data['supplier']['avatar'] = asset('assets/images/user.png');
            } else {
                $data['supplier']['avatar'] = asset('assets/images/' . $data['supplier']['avatar']);
            }
            return response()->json([
                'success' => true,
                'message' => 'Supplier Profile ',
                'user_type' => 'Supplier',
                'data' => $data,
            ], 200);
        } else {
            return response()->json([
                'success' => true,
                'message' => "Register your Account.",
            ], 401);
        }
    }

    public function ContactUs()
    {
        $address['address'] = array(
            [
                'address' => "2224 US-41 N, Henderson, KY 42420, USA",
                'number' => "+1-931 409 8026",
                'mailid' => "info@thediamondport.com",

            ],
            [
                'address' => "1107, Luxuria Business Hub,Near VR Mall, Dumas Road,Surat - 395007 ",
                'number' => "+91 99247 02227",
                'mailid' => "info@thediamondport.com",
            ],
            [
                'address' => "Diamond Club of Antwerp, Office 522, Pelikaanstraat 62, 2018 Antwerpen, Belgium",
                'number' => " +32 485 100 850",
                'mailid' => "info@thediamondport.com",
            ],
            [
                'address' => "Chevalier House, 45-51 Chatham Rd S, Tsim Sha Tsui, Hong Kong",
                'number' => "+91 99247 02227",
                'mailid' => "info@thediamondport.com",
            ]
        );

        return response()->json([
            "success" => true,
            'data' => $address
        ]);
    }

    public function AskQuestion()
    {
        $questionanswer['question'] = array(
            [
                'question' => "How to register on The Diamond Port?",
                'answer' => "content not set ",
            ],
            [
                'question' => "Are there any subscription charges?",
                'answer' => "content not set ",
            ],
            [
                'question' => "Does the website have an API that allows inventory to be shared between platforms?",
                'answer' => "content not set ",
            ],
            [
                'question' => "Is it possible to see the details of the provider before placing an order?",
                'answer' => "content not set ",
            ],

            [
                'question' => "Is it possible to view the website from a mobile phone?",
                'answer' => "content not set ",
            ],
            [
                'question' => "How long does it take for diamonds to be delivered?",
                'answer' => "content not set ",
            ],
            [
                'question' => "How can I purchase diamonds that aren't featured on the website?",
                'answer' => "content not set ",
            ]
        );

        return response()->json([
            'success' => true,
            'data' =>  $questionanswer
        ]);
    }

    public function GetInTouch(Request $request)
    {
        $getdata = $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'message' => 'required',
            'mobile' => 'required',
            'email' => 'required'
        ]);

        DB::table('get_in_touche')->insert($getdata);

        return response()->json([
            'success' => true,
            'message' => 'Our Team Connect With You',
        ]);
    }

    public function Userlogout()
    {
        $customer_id = Auth::user()->id;

        DB::table('oauth_access_tokens')->where('user_id', $customer_id)->delete();
        Session::flush();

        return response()->json([
            'success' => true,
            'message' => 'Logout Successful',
        ]);
    }

    public function Customernotification(Request $exception)
    {
        $customer_id = Auth::user()->id;

        $notification = Notification::select('user_id', 'title', 'body', 'created_date')
            ->where('user_id',$customer_id)
            ->orderBy('created_date', 'desc')->get();

        if (count($notification) > 0) {
            return response()->json([
                'success' => true,
                'message' => 'Found notification',
                'data' => $notification
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Not Found notification'
            ]);
        }
    }

}

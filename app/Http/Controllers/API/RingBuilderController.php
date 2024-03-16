<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RingBuilderController extends Controller
{
    public function tokenGenerate(Request $request)
    {

        $email = $request->email;
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
                    $user_login_token = Auth::user()->createToken('jewelleryApp')->accessToken;
                    return response()->json([
                        'success' => true,
                        'message' => 'Login successful',
                        'token' => $user_login_token,
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
                'message' => 'Email or password is incorrect. if you fail to login please try forgot password.',
            ], 401);
        }
    }

    public function GetConfigRing(Request $request)
    {

        $white_label = DB::table('rings')->first();
        if (!empty($white_label)) {
            $data['ring_style'] = explode(',', $white_label->ring_style);
            $data['ring_metal'] = explode(',', $white_label->ring_metal);

            return response()->json([
                'success' => true,
                'message' => "Parameter",
                'data' => $data,
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => " Token Invalid Try Again !!",
            ]);
        }

    }

    public function GetAllRing(Request $request)
    {
        $result_query = DB::table('ring_builders');

            if (!empty($request->pricemax)) {
            $price_min = $request->pricemin;
            $price_max = $request->pricemax;
            $result_query->where('total_price', '>=', $price_min)
                ->where('total_price', '<=', $price_max);
            }
            if (!empty($request->settingtype)) {
                $style = explode(',', $request->settingtype);
                $result_query->whereIn('sub_category', $style);
            }
            if (!empty($request->diamond_match)) {
                $diamond_match = $request->diamond_match;
                $result_query->where('diamond_matched_with', 'like',"%$diamond_match%");
            }
            if (!empty($request->metaltype)) {
                $metal = $request->metaltype;
                $result_query->where('metal_name', 'like', "%$metal%");
            }
            $result = $result_query->paginate(15);
            return response()->json([
                'status' => true,
                'message' => "Data Found",
                'data' => $result,
            ], 200);

    }

    public function AddRing(Request $request)
    {
        $product_id = $request->product_id;
            if (!empty($product_id)) {
            $product = DB::table('ring_builders')
                    ->where('id', $product_id)
                    ->first();
            return response()->json([
                'status' => true,
                'message' => "Data Found",
                'data' => $product,
                 ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => "Product id is missing",
            ]);
        }

    }
}

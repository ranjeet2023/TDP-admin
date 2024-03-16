<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Exception;

use App\Models\User;

class RingImportController extends Controller
{
    public function Genratetoken(Request $request)
    {
        try {
            if(!empty($request->apikey))
            {
                $loginRequest = User::with('customer')->whereHas('customer', function($q) use($request){
                    $q->where('api_key',$request->apikey);
                })->first();

                if (!empty($loginRequest)) {
                    if($loginRequest->customer->api_enable == 1){
                        Auth::loginUsingId($loginRequest->id);

                        DB::table('oauth_access_tokens')->where('user_id', $loginRequest->id)->delete();
                        $token = auth()->user()->createToken('tdp')->accessToken;
                        return response()->json(['success' => true, 'email' => $loginRequest->email, 'token' => $token], 200);
                    }
                    else{
                        $status['success'] = false;
                        $status['message'] = "Your API temporarily suspended. Please contact us.";
                        return response()->json($status);
                    }
                } else {
                    return response()->json(['success' => false, 'error' => 'You are unauthorized to access.'], 401);
                }
            }
            else{
                $status['success'] = false;
                $status['message'] = "You are unauthorized to access the requested resource. Please check API Key";
                return response()->json($status);
            }
        } catch (\Exception $e) {
            $status['success'] = false;
            $status['message'] = $e."Unexpected internal server error.";
            return response()->json($status);
        }
    }

    public function GetImportRing(Request $request)
    {
        $query = DB::table('ring');
        if (!empty($request->category)) {
            $query->where('category', 'like', '%' . $request->category);
        }
        if (!empty($request->sub_category)) {
            $query->where('sub_category', 'like', '%' . $request->sub_category);
        }
        if (!empty($request->metal)) {
            $query->where('metal_name', 'like', '%' . $request->metal);
        }
        if (!empty($request->diamond)) {
            $query->where('diamond_can_be_matched_with', 'like', '%' . $request->diamond);
        }

        $perpage = 500;
        $result = $query->paginate($perpage);
        $toal_diamond = $result->total();
        if ($toal_diamond == 0) {
            return response()->json([
                'success' => false,
                'message' => "No records found.",
                'data' => null
            ], 404);
        } else {
            $status['success'] = true;
            $status['message'] = "Record fetch successfully.";

            $status['total'] = $toal_diamond;
            $status['currentPage'] = $result->currentPage();
            $nextPageUrl = $result->nextPageUrl();
            $status['nextPageUrl'] = is_null($nextPageUrl) ? '' : $nextPageUrl;
            $status['perPage'] = $perpage;
            $status['data'] = $result->items();

            return response()->json($status, 201);
        }
    }
}

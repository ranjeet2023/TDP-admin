<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Models\Products;
use App\Models\ProductList;
use Illuminate\Support\Facades\Auth;
use DB;

class JewelleryController extends Controller
{
    public function Product()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://www.classicgrownjewelry.com/api/v1/token/GenerateToken',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array('UserName' => 'info@thediamondport','Password' => 'Tdp#123'),
        CURLOPT_HTTPHEADER => array(
            'API-KEY: 0A2CDC5AFCFB7D91432684960959A84D'
        ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        if ($err) {
            echo "cURL Error #:" . $err;
        }
        $response = json_decode($response);


        $status =  $response->status;
        $token  = $response->token;
        $id  = $response->id;

        if($status)
        {
            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://www.classicgrownjewelry.com/api/v1/token/GetFineProductList',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array('Token' => $token ,'UserId' =>   $id ,'Category' => '','MetalType' => '','MetalStamp' => '','CenterStone' => '','AccentStone' => '','SettingType' => ''),
            CURLOPT_HTTPHEADER => array(
                'API-KEY: 0A2CDC5AFCFB7D91432684960959A84D',
                'Cookie: ci_session=uihhl87050otnsuo9iuqvpercc1kirrg'
            ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            if ($err) {
                echo "cURL Error #:" . $err;
            }
            $response_product = json_decode($response);

            if($response_product->status)
            {
                ProductList::where('product_type','FineProduct')->delete();
                $products = $response_product->data->Product;
                foreach ($products as $product) {

                    $test  = Products::updateOrCreate(
                            [ 'product_id' => $product->ProductId],
                            [
                                'item_code' => $product->ItemCode ? $product->ItemCode: '',
                                'product_type' => 'FineProduct',
                                'samedesign_code'=>$product->SameDesignCode ? $product->SameDesignCode:'',
                                'product_name'=>$product->ProductName ? $product->ProductName:'',
                                'product_title'=>$product->ProductTitle?$product->ProductTitle:'',
                                'slug'=>$product->Slug?$product->Slug:'',
                                'categoryid'=>$product->CategoryId?$product->CategoryId:'',
                                'category_name'=> $product->CategoryName?$product->CategoryName:'',
                                'appxmetalwgt'=>$product->AppxMetalWgt?$product->AppxMetalWgt:'',
                                'cwgt'=>$product->CWgt?$product->CWgt:'',
                                'parent_item'=>$product->ParentItem?$product->ParentItem:'',
                                'design_code'=>$product->DesignCode?$product->DesignCode:'',
                                'metaltype'=>$product->MetalType?$product->MetalType:'',
                                'metalstamp'=>$product->MetalStamp?:'',
                                'cmetaltypename'=> $product->CMetalTypeName?$product->CMetalTypeName:'',
                                'cmetalstampname'=> $product->CMetalStampName?$product->CMetalStampName:'',
                                'metalwithstamp'=>$product->MetalWithStamp?$product->MetalWithStamp:'',
                                'settingtype'=>$product->SettingType?$product->SettingType:'',
                                'image'=>$product->Image?$product->Image:'',
                                'havechain'=> $product->HaveChain?$product->HaveChain:'',
                                'purity'=> $product->Purity?$product->Purity:'',
                                'mtype'=> $product->MType?$product->MType:'',
                                'cpurity'=>$product->CPurity?$product->CPurity:'',
                                'ctype'=>$product->CType?$product->CType:'',
                                'currency'=> $product->Currency?$product->Currency:'',
                                'productcost'=>$product->ProductCost?$product->ProductCost:''
                            ]
                        );

                    $StoneList  = $product->StoneList;
                    foreach ($StoneList as  $stone) {
                        $stone_list = new ProductList;
                        $stone_list->product_id = $stone->ProductId;
                        $stone_list->product_type = 'FineProduct';
                        $stone_list->stonetype = $stone->StoneType ? $stone->StoneType : '';
                        $stone_list->lab = $stone->Lab ? $stone->Lab : '';
                        $stone_list->certno = $stone->CertNo ? $stone->CertNo:'';
                        $stone_list->certurl = $stone->CertUrl ? $stone->CertUrl :'';
                        $stone_list->shape = $stone->Shape ? $stone->Shape:'';
                        $stone_list->fromcolor = $stone->FromColor ? $stone->FromColor : '';
                        $stone_list->tocolor = $stone->ToColor ? $stone->ToColor:'';
                        $stone_list->fromclarity = $stone->FromClarity ? $stone->FromClarity:'';
                        $stone_list->toclarity = $stone->ToClarity ? $stone->ToClarity :'';
                        $stone_list->fromcut = $stone->FromCut ? $stone->FromCut : '';
                        $stone_list->tocut = $stone->ToCut ? $stone->ToCut :'';
                        $stone_list->fromsize = $stone->FromSize ? $stone->FromSize :'';
                        $stone_list->tosize = $stone->ToSize ? $stone->ToSize:'';
                        $stone_list->pieces = $stone->Pieces ? $stone->Pieces :'';
                        $stone_list->totalwgt = $stone->TotalWgt ?$stone->TotalWgt :'';
                        $stone_list->price = $stone->Price ? $stone->Price: '';
                        $stone_list->isgem = $stone->IsGem ? $stone->IsGem:'';
                        $stone_list->save();
                    }
                }

                return response()->json([
                    'status'=>true,
                    'message'=>'Data insert successful'
                ]);
            }
            else
            {
                return response()->json([
                    'status'=>false,
                    'message'=>'Data Not Found'
                ]);
            }
        }
        else
        {
            return response()->json([
                'status'=>false,
                'message'=>'Invalid Token'
            ]);
        }
    }

    public function SemiProduct()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://www.classicgrownjewelry.com/api/v1/token/GenerateToken',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array('UserName' => 'info@thediamondport','Password' => 'Tdp#123'),
        CURLOPT_HTTPHEADER => array(
            'API-KEY: 0A2CDC5AFCFB7D91432684960959A84D'
        ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        if ($err) {
            echo "cURL Error #:" . $err;
        }
        $response = json_decode($response);


        $status =  $response->status;
        $token  = $response->token;
        $id  = $response->id;

        if($status)
        {
            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://www.classicgrownjewelry.com/api/v1/token/GetSemiProductList',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array('Token' => $token ,'UserId' =>   $id ,'Category' => '','MetalType' => '','MetalStamp' => '','CenterStone' => '','AccentStone' => '','SettingType' => ''),
            CURLOPT_HTTPHEADER => array(
                'API-KEY: 0A2CDC5AFCFB7D91432684960959A84D',
                'Cookie: ci_session=uihhl87050otnsuo9iuqvpercc1kirrg'
            ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            if ($err) {
                echo "cURL Error #:" . $err;
            }
            $response_product = json_decode($response);

            if($response_product->status)
            {
                ProductList::where('product_type','SemiProduct')->delete();
                $products = $response_product->data->Product;
                foreach ($products as $product) {

                    Products::updateOrCreate(
                            [ 'product_id' => $product->ProductId],
                            [
                                'item_code' => $product->ItemCode ? $product->ItemCode: '',
                                'product_type'=>'SemiProduct',
                                'samedesign_code'=>$product->SameDesignCode ? $product->SameDesignCode:'',
                                'product_name'=>$product->ProductName ? $product->ProductName:'',
                                'product_title'=>$product->ProductTitle?$product->ProductTitle:'',
                                'slug'=>$product->Slug?$product->Slug:'',
                                'categoryid'=>$product->CategoryId?$product->CategoryId:'',
                                // 'category_name'=> $product->CategoryName?$product->CategoryName:'',
                                'appxmetalwgt'=>$product->AppxMetalWgt?$product->AppxMetalWgt:'',
                                'cwgt'=>$product->CWgt?$product->CWgt:'',
                                'parent_item'=>$product->ParentItem?$product->ParentItem:'',
                                // 'design_code'=>$product->DesignCode?$product->DesignCode:'',
                                'metaltype'=>$product->MetalType?$product->MetalType:'',
                                'metalstamp'=>$product->MetalStamp?:'',
                                // 'cmetaltypename'=> $product->CMetalTypeName?$product->CMetalTypeName:'',
                                // 'cmetalstampname'=> $product->CMetalStampName?$product->CMetalStampName:'',
                                'metalwithstamp'=>$product->MetalWithStamp?$product->MetalWithStamp:'',
                                'settingtype'=>$product->SettingType?$product->SettingType:'',
                                'image'=>$product->Image?$product->Image:'',
                                'havechain'=> $product->HaveChain?$product->HaveChain:'',
                                'purity'=> $product->Purity?$product->Purity:'',
                                'mtype'=> $product->MType?$product->MType:'',
                                'cpurity'=>$product->CPurity?$product->CPurity:'',
                                'ctype'=>$product->CType?$product->CType:'',
                                'currency'=> $product->Currency?$product->Currency:'',
                                'productcost'=>$product->ProductCost?$product->ProductCost:''
                            ]
                        );

                    $StoneList  = $product->StoneList;
                    foreach ($StoneList as  $stone) {
                        $stone_list = new ProductList;
                        $stone_list->product_id = $stone->ProductId;
                        $stone_list->product_type = 'SemiProduct';
                        $stone_list->stonetype = $stone->StoneType ? $stone->StoneType : '';
                        $stone_list->lab = $stone->Lab ? $stone->Lab : '';
                        $stone_list->certno = $stone->CertNo ? $stone->CertNo:'';
                        $stone_list->certurl = $stone->CertUrl ? $stone->CertUrl :'';
                        $stone_list->shape = $stone->Shape ? $stone->Shape:'';
                        $stone_list->fromcolor = $stone->FromColor ? $stone->FromColor : '';
                        $stone_list->tocolor = $stone->ToColor ? $stone->ToColor:'';
                        $stone_list->fromclarity = $stone->FromClarity ? $stone->FromClarity:'';
                        $stone_list->toclarity = $stone->ToClarity ? $stone->ToClarity :'';
                        $stone_list->fromcut = $stone->FromCut ? $stone->FromCut : '';
                        $stone_list->tocut = $stone->ToCut ? $stone->ToCut :'';
                        $stone_list->fromsize = $stone->FromSize ? $stone->FromSize :'';
                        $stone_list->tosize = $stone->ToSize ? $stone->ToSize:'';
                        $stone_list->pieces = $stone->Pieces ? $stone->Pieces :'';
                        $stone_list->totalwgt = $stone->TotalWgt ?$stone->TotalWgt :'';
                        // $stone_list->price = $stone->Price ? $stone->Price: '';
                        $stone_list->isgem = $stone->IsGem ? $stone->IsGem:'';
                        $stone_list->save();
                    }
                }

                return response()->json([
                    'status'=>true,
                    'message'=>'Data insert successful'
                ]);
            }
            else
            {
                return response()->json([
                    'status'=>false,
                    'message'=>'Data Not Found'
                ]);
            }
        }
        else
        {
            return response()->json([
                'status'=>false,
                'message'=>'Invalid Token'
            ]);
        }
    }

    public function tokenGenerate(Request $request)
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
                'message' => 'Email or password is incorrect. if you fail to login please try forgot password.'
            ], 401);
        }
    }


    public function GetSemiproductData(Request $request)
    {
        $result_query= Products::with('productlist')->where('product_type','SemiProduct');
        if(!empty($request->productcost))
        {
            $price = $request->productcost;
            $price = explode('-',$price);
            $price_min = (float)$price[0];
            $price_max = (float)$price[1];
            $result_query->where('productcost', '>=', $price_min)
                ->where('productcost', '<=', $price_max);
        }
        if(!empty($request->settingstyle))
        {
            $style = explode(',',$request->settingstyle);
            $result_query->whereIn('settingtype',$style);
        }

        if(!empty($request->metaltype))
        {
            $metal = explode(',',$request->metaltype);
            $result_query->whereIn('metaltype',$metal);
        }

        $result = $result_query->paginate(16);

        return response()->json([
            'status'=>true,
            'message'=>"Data Found",
            'data' =>$result,
        ], 200);
    }


    public function AddRing(Request $request)
    {
        $product_id=$request->product_id;
        if(!empty($product_id)){
        $product = Products::with('productlist')
                    ->where('product_type', 'SemiProduct')
                    ->where('product_id', $product_id)
                    ->first();
            return response()->json([
                'status'=>true,
                'message'=>"Data Found",
                'data' =>$product,
            ], 200);
        }else{
            return response()->json([
                'success'=>false,
                'message'=>"Product id is missing"
            ]);
        }
    }

    public function GetFineproductData(Request $request)
    {
        $product = Products::with('productlist')->where('product_type','FineProduct')->paginate(16);
            return response()->json([
                'status'=>true,
                'message'=>"Data Found",
                'data' =>$product,
            ], 200);
    }
}

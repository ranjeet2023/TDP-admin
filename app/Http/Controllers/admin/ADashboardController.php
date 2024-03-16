<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\AppHelper;
use Illuminate\Support\Facades\Auth;

use DB;
use Redirect;
use App\Imports\CustomerImport;
use App\Imports\SupplierImport;

use App\Models\DiamondNatural;
use App\Models\DiamondLabgrown;
use App\Models\User;
use App\Models\Supplier;
use App\Models\Log;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Pickups;
use App\Models\Invoice;
use App\Models\TimelineCycle;

use Maatwebsite\Excel\Facades\Excel;


class ADashboardController extends Controller
{
    public function __construct()
	{
		// if(Auth::check()){
        //     return view('dashboard');
        // }

        // return redirect("login")->withSuccess('You are not allowed to access');
		// $this->middleware('auth');
	}

    public function index(Request $request)
    {

        $login_customer_seg = 'login-history-customer';
        $orders_seg = 'order-list';
        $hold_seg = 'hold-diamond-list';

        $login_customer = AppHelper::userPermission($login_customer_seg);
        $orders_seg = AppHelper::userPermission($orders_seg);
        $hold_seg = AppHelper::userPermission($hold_seg);

        $start_date = '';
        $end_date = '';
        $customer_report = [];

        for($i=1;$i<6;$i++){
            if($i==1){
                $start_date = date('Y-m-d');
                $end_date = date('Y-m-d', strtotime($start_date.'00:00:00 -1 month'));
            }
            if($i == 2){
                $start_date = date('Y-m-d', strtotime(date('Y-m-d').'00:00:00 -1 month'));
                $end_date = date('Y-m-d', strtotime($start_date.'00:00:00 -3 month'));
            }
            if($i == 3){
                $start_date = date('Y-m-d', strtotime(date('Y-m-d').'00:00:00 -4 month'));
                $end_date = date('Y-m-d', strtotime($start_date.'00:00:00 -3 month'));
            }
            if($i == 4){
                $start_date = date('Y-m-d', strtotime(date('Y-m-d').'00:00:00 -7 month'));
                $end_date = date('Y-m-d', strtotime($start_date.'00:00:00 -6 month'));
            }
            if($i == 5){
                $start_date = date('Y-m-d', strtotime(date('Y-m-d').'00:00:00 -11 month'));
                $end_date = '';
            }
            $customers[] = User::select('users.created_at as user_created_at','users.id')->join('customers','users.id','=','customers.cus_id')->where('users.created_at','<',$start_date)->where('users.created_at','>',$end_date)->count();

        }
        $data['customers'] = json_encode($customers);
        $user_type = auth::user()->user_type;
        $array['id'] = $user_id = auth::user()->id;

        $date = date('Y-m-d', strtotime(date('Y-m-d').'00:00:00 -1 month'));
        $orders = DB::select("(SELECT sum(sale_price) as total_price,count(orders_id) as count,DATE_FORMAT(created_at,'%Y-%m-%d') as created_at FROM `orders` WHERE created_at > (NOW() - INTERVAL 1 MONTH) GROUP BY DATE_FORMAT(created_at,'%Y-%m-%d') ORDER BY created_at)");
        $rejected = DB::select("(SELECT sum(sale_price) AS total_price,count(orders_id) AS count ,DATE_FORMAT(created_at,'%Y-%m-%d') AS created_at FROM orders WHERE created_at > (NOW() - INTERVAL 1 MONTH) AND order_status IN ('REJECT','RELEASED') GROUP BY DATE_FORMAT(created_at, '%Y-%m-%d') ORDER BY created_at)");

        $previous_month = date("m", strtotime ( '-1 month' , strtotime (date('Y-m-d')) ));
        $p = cal_days_in_month(CAL_GREGORIAN, $previous_month,date('Y'));
        $date = date('Y-m-d');

        for($i = 1;$i <= $p;$i++){
            $count_order = 0;
            $total_sale_price = 0;
            $count_reject_order = 0;
            $reject_total_sale_price = 0;

            $key = array_search($date, array_column($orders, 'created_at'));
            $reject_key = array_search($date, array_column($rejected, 'created_at'));

            if(is_numeric($key)){
                $count_order = $orders[$key]->count;
                $total_sale_price = $orders[$key]->total_price;
            }

            if(is_numeric($reject_key)){
                $count_reject_order = '-'.$rejected[$reject_key]->count;
                $reject_total_sale_price = '-'.$rejected[$reject_key]->total_price;
            }
            $dates[] = $date;
            $count[] = $count_order;
            $total_price[] = $total_sale_price;
            $reject_count[] = $count_reject_order;
            $reject_total_price[] = $reject_total_sale_price;
            $date = date('Y-m-d',strtotime("-$i days"));
        }

        $data['date'] = json_encode($dates);
        $data['count_orders'] = json_encode($count);
        $data['total_price'] = json_encode($total_price);
        $data['reject_count'] = json_encode($reject_count);
        $data['reject_total_price'] = json_encode($reject_total_price);
        $previous_date = date('Y-m-d H:i:s',strtotime("-1 days"));
        $days_2 = date('Y-m-d H:i:s',strtotime("-2 days"));

        $loginHistoryData = Log::with('user')->where('lastlogin','>',$previous_date)->where('user_type',2);
        $hold = Order::with('user')->where('is_deleted',0)->where('hold',1)->where('order_status','!=','RELEASED');

        if($user_type == 1 OR $login_customer->full == 1){
            $data['loginHistoryData'] = $loginHistoryData->count();
        }elseif($user_type == 4||5||6){
            $data['loginHistoryData'] = $loginHistoryData->whereHas('user',function($query) use($user_id){ $query->where('users.added_by','=',$user_id);})->count();
        }else{
            return redirect('login');
        }

        if($user_type == 1 OR $orders_seg->full == 1){
            $data['order_count'] = Order::with('user')->where('is_deleted',0)->where('order_status','!=','RELEASED')->where('hold',0)->where('created_at','>',$previous_date)->count();
            $data['orders'] = Order::with('user','orderdetail')->where('is_deleted',0)->where('order_status','!=','RELEASED')->where('hold',0)->where('created_at','>',$days_2)->get();
        }elseif($user_type == 4||5||6){
            $data['order_count'] = Order::with('user')->where('is_deleted',0)->where('order_status','!=','RELEASED')->where('hold',0)->where('created_at','>',$previous_date)->whereHas('user',function($query) use($user_id){ $query->where('users.added_by','=',$user_id);})->count();
            $data['orders'] = Order::with('user','orderdetail')->where('is_deleted',0)->where('order_status','!=','RELEASED')->where('hold',0)->where('created_at','>',$days_2)->whereHas('user',function($query) use($user_id){ $query->where('users.added_by','=',$user_id);})->get();

        }else{
            return redirect('login');
        }


        if($user_type == 1 OR $hold_seg->full == 1){
            $data['hold'] = $hold->count();
        }elseif($user_type == 4||5||6){
            $data['hold'] = $hold->whereHas('user',function($query) use($user_id){ $query->where('users.added_by','=',$user_id);})->count();
        }else{
            return redirect('login');
        }

        $data['pending_customer'] = Customer::with('customeruser')->where('customer_type', 4)->count();
        $data['pending_suppliers'] = Supplier::whereHas('users',function($query){ $query->where('user_type',3); $query->where('is_active',0); $query->where('is_delete',0); })->count();

        $data['Labnumberofdiamond'] = DiamondLabgrown::select('id')->where('carat', '>', 0.17)->where('orignal_rate','>',50)->where('is_delete',0)->count();
        $data['Natualnumberofdiamond'] = DiamondNatural::select('id')->where('carat', '>', 0.17)->where('orignal_rate','>',50)->where('is_delete',0)->count();

        return view('admin.dashboard')->with($data);
    }

    public function adminProfile(Request $request)
    {
        $id = Auth::user()->id;
        $data['admin'] = User::with('admins')->whereHas('admins',function($query)use($id){ $query->where('id', $id);})->first();

        return view('admin.admin-profile')->with($data);
    }

    public function adminProfileEdit(Request $request)
    {
        $id = Auth::user()->id;
        $data['admin'] = User::with('admins')->whereHas('admins',function($query) use($id){ $query->where('id', $id);})->first();

        return view('admin.admin-profile-edit')->with($data);
    }

    public function InoivesToPickups(){
        $invoices = Invoice::select('invoice_number','orders_id')->where('is_deleted','=','0')->get();
        foreach($invoices as $invoice){
            $orders_ids = explode(',', $invoice->orders_id);
            $check = Pickups::whereIn('orders_id',$orders_ids)->update(['invoice_number' => $invoice->invoice_number]);
        }
        dd('done');
    }

    public function importCustomer()
	{
		Excel::import(new CustomerImport, request()->file('upload-file'));
		return Redirect::to("admin")->withSuccess('Customer data upload successfully.');
	}

    public function supplierCustomer()
	{
		Excel::import(new SupplierImport, request()->file('upload-file'));
		return Redirect::to("admin")->withSuccess('Supplier data upload successfully.');
	}

    public function addCustomer()
    {
        return view('admin.customer.customer-add');
    }
    public function AddDiamond()
    {
        $data['diamond_list'] = DB::table('add_diamond')->where('is_delete',0)->get();
        $data['suppliers'] = Supplier::with('users')->whereHas('users',function($query){ $query->orderBy('companyname','asc'); })->where('stock_status','ACTIVE')->get();
        return view('admin.add-diamond')->with($data);
    }

    public function AddDiamondPost(Request $request)
    {
       $data = $request->validate([
            'netdollar'=>'required',
            'orignalrate'=>'required',
            'supplier'=>'required',
            'lotno'=>'required',
            'shape'=>'required',
            'carat'=>'required',
            'color'=>'required',
            'clarity'=>'required',
            'cut'=>'required',
            'polish'=>'required',
            'sym'=>'required',
            'fluorescence'=>'required',
            'lab'=>'required',
            'certificate'=>'required',
            // 'ct'=>'required',
            'depth'=>'required',
            'depthp'=>'required',
            'tablep'=>'required',
            'country'=>'required',
            'diamond_type'=>'required',
            'width'=>'required',
            'length'=>'required',
            'diamond_type'=>'required',
        ]);


        $supplier = Supplier::with('users')->where('sup_id', $request->supplier)->first();

        $shiping_price_array = array();
        $shiping_price_array = AppHelper::shipingPriceArray();

        $country = strtoupper($request->country);

        $newdollerpercarat = $request->orignalrate;
        $shippingprice = AppHelper::shippingPrice($shiping_price_array[$country], $newdollerpercarat);
        $shippingprice = ($shippingprice > 0) ? ($shippingprice / $request->carat) : 0;
        $newdollerpercarat = $newdollerpercarat + $shippingprice;

        $data = array(
            'supplier_id'=>$request->supplier,
            'diamond_type'=>$request->diamond_type,
            'supplier_name'=>$supplier->users->companyname,
            'ref_no'=>$request->lotno,
            'shape' =>$request->shape,
            'carat'=>$request->carat,
            'color'=>$request->color,
            'clarity'=>$request->clarity,
            'cut' =>$request->cut,
            'polish'=>$request->polish,
            'symmetry'=>$request->sym,
            'lab'=>$request->lab,
            'certificate_no'=>$request->certificate,
            'length'=>$request->length,
            'width'=>$request->width,
            'depth'=>$request->depth,
            'milky'=>$request->milky,
            'eyeclean'=>$request->eyeclean,
            'image'=>$request->image,
            'video'=>$request->video,
            'heart'=>$request->heart,
            'arrow'=>$request->arrow,
            'asset'=>$request->asset,
            'country'=>$request->country,
            'crown_angle'=>$request->cangle,
            'crown_height'=>$request->cheight,
            'pavilion_angle'=>$request->pheight,
            'pavilion_depth'=>$request->pheight,
            'shade'=>$request->shade,
            'fluorescence'=>$request->fluorescence,
            'depth_per'=>$request->depthp,
            'table_per'=>$request->tablep,
            'key_symbols'=>$request->keysymbol,

            'net_dollar'=> $request->netdollar,
            'orignal_rate'=>$request->orignalrate,

            'rate' => $newdollerpercarat,
        );


        $check_diamond = DB::table('add_diamond')
        ->where('certificate_no',$request->certificate)
        ->where('is_delete',0)
        ->count();

        if($check_diamond == 0)
        {
            DB::table('add_diamond')->insert($data);
            if($request->diamond_type == 'W')
            {
                $natural_diamond = DiamondNatural::where('certificate_no',$request->certificate)->count();
                if($natural_diamond == 0)
                {
                    DiamondNatural::insert($data);
                    return redirect('add-diamond')->with('update','Diamond Add succcessful');
                }
                else
                {
                    DiamondNatural::where('certificate_no',$request->certificate)->update(['is_delete' => 0]);
                    return redirect('add-diamond')->with('update','Diamond updated Successful');
                }
            }
            else
            {
                $check_diamond = DiamondLabgrown::where('certificate_no',$request->certificate)
                ->where('is_delete', 0)
                ->count();
                if($check_diamond == 0)
                {
                    DiamondLabgrown::insert($data);
                }
                else
                {
                    DiamondLabgrown::where('certificate_no',$request->certificate)
                    ->update(['is_delete' => 0]);
                    return redirect('add-diamond')->with('update','Diamond Add sucessful');
                }
            }
            return redirect('add-diamond')->with('update','Diamond Add succcessful');
        }
        else
        {
            return redirect('add-diamond')->with('update','Diamond already Exist');
        }
    }

    public function DeleteDiamond(Request $request)
    {
        $diamond_type = $request->check;
        $id = $request->id;
        $cerificate=DB::table('add_diamond')->where('id',$id)->first();
        $cert = $cerificate->certificate_no;

        DB::table('add_diamond')->where('id',$id)->update([
            'is_delete'=>1,
        ]);

        if($diamond_type =='W')
        {
            DiamondNatural::where('certificate_no',$cert)->update(['is_delete'=>1]);
        }
        else
        {
            DiamondLabgrown::where('certificate_no',$cert)->update(['is_delete'=>1]);
        }
        return redirect('add-diamond')->with('update','Delete Successful');
    }

    public function updateraprate() {
		//Logbouk1  //Cowboy10
		$auth_url = "https://technet.rapaport.com:449/HTTP/JSON/Prices/GetPriceSheet.aspx";
		$post_string = '{
		"request": {
			"header": {
				"username": "rushabhm",
                "password": "rushabh1"
			},
			"body":{
				"shape": "round"
				}
			}
		}';

		$request = curl_init($auth_url); // initiate curl object
		curl_setopt($request, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
		curl_setopt($request, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
		curl_setopt($request, CURLOPT_POSTFIELDS, $post_string); // use HTTP POST to send form data
		curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment this line if you get no gateway response.
		$response = curl_exec($request); // execute curl post and store results in $auth_ticket
		if (curl_error($request)) {
			echo 'error:' . curl_error($request);
		}

		$response = json_decode($response);
		$body = $response->response->body;

        DB::table('rap_list')->truncate();

		if (!empty($body->price)) {
			foreach ($body->price as $value) {
				if ($value->low_size > 0.15) {
					if($value->low_size == 5.00){
						$inserarray = array(
							'shape_name' => $value->shape,
							'low_size' => 6.00,
							'high_size' => 9.99,
							'color_name' => strtoupper($value->color),
							'clarity_name' => strtoupper($value->clarity),
							'caratprice' => $value->caratprice,
							'date' => $value->date,
						);
						DB::table('rap_list')->insert($inserarray);
					}
					if($value->low_size == 10.00){
						$inserarray = array(
							'shape_name' => $value->shape,
							'low_size' => 11.00,
							'high_size' => 999.99,
							'color_name' => strtoupper($value->color),
							'clarity_name' => strtoupper($value->clarity),
							'caratprice' => $value->caratprice,
							'date' => $value->date,
						);
						DB::table('rap_list')->insert($inserarray);
					}
					$inserarray = array(
						'shape_name' => $value->shape,
						'low_size' => $value->low_size,
						'high_size' => $value->high_size,
						'color_name' => strtoupper($value->color),
						'clarity_name' => strtoupper($value->clarity),
						'caratprice' => $value->caratprice,
						'date' => $value->date,
					);
					DB::table('rap_list')->insert($inserarray);
				}
			}
		}
		curl_close($request);

		$post_string = '{
			"request": {
				"header": {
					"username": "rushabhm",
					"password": "rushabh1"
				},
				"body":{
					"shape": "pear"
					}
				}
			}';

		$request = curl_init($auth_url); // initiate curl object
		curl_setopt($request, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
		curl_setopt($request, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
		curl_setopt($request, CURLOPT_POSTFIELDS, $post_string); // use HTTP POST to send form data
		curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment this line if you get no gateway response.
		$response = curl_exec($request); // execute curl post and store results in $auth_ticket

		$response = json_decode($response);
		$header = $response->response;
		$body = $header->body;

		if (!empty($body->price)) {
			foreach ($body->price as $value) {
				if($value->low_size == 5.00){
					$inserarray = array(
						'shape_name' => $value->shape,
						'low_size' => 6.00,
						'high_size' => 9.99,
						'color_name' => strtoupper($value->color),
						'clarity_name' => strtoupper($value->clarity),
						'caratprice' => $value->caratprice,
						'date' => $value->date,
					);
                    DB::table('rap_list')->insert($inserarray);
				}
				if($value->low_size == 10.00){
					$inserarray = array(
						'shape_name' => $value->shape,
						'low_size' => 11.00,
						'high_size' => 999.99,
						'color_name' => strtoupper($value->color),
						'clarity_name' => strtoupper($value->clarity),
						'caratprice' => $value->caratprice,
						'date' => $value->date,
					);
					DB::table('rap_list')->insert($inserarray);
				}
				$inserarray = array(
					'shape_name' => $value->shape,
					'low_size' => $value->low_size,
					'high_size' => $value->high_size,
					'color_name' => strtoupper($value->color),
					'clarity_name' => strtoupper($value->clarity),
					'caratprice' => $value->caratprice,
					'date' => $value->date,
				);
				DB::table('rap_list')->insert($inserarray);
			}
		}

		// $insert = array(
		// 	'size' => '',
		// 	'shape' => '',
		// 	'updated_date' => date('Y-m-d H:i:s')
		// );
		// $this->Userlist_Model->add_update_rap($insert);
        return redirect('admin')->withSuccess('Updated Successfully.');
	}
}

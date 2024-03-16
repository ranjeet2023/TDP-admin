<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\AppHelper;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use DB;
use Session;
use Mail;

use App\Models\Admin;
use App\Models\User;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\DiamondNatural;
use App\Models\DiamondLabgrown;
use App\Models\Cart;
use App\Models\Order;
use App\Models\WishList;
use App\Models\Lead;
use App\Models\LeadsComment;
use App\Models\ShippingDestination;
use App\Models\WhiteLabel;

class ACustomerController extends Controller
{

    public function AdminOrderPlace(Request $request){
        $id = Auth::user()->id;

        $user_type = Auth::user()->user_type;

        $url = $request->segment(1);

        $permission = AppHelper::userPermission($url);

        $customers = Customer::with('user')->whereHas('user',function($query){$query->where('is_delete','0'); $query->where('is_active','1');})->where('customers.customer_type','!=','4');

        if($user_type == 1 || $permission->full == 1){
            $data['customers'] = $customers->get()->sortBy('user.companyname');
        }
        else
        {
            $data['customers'] = $customers->whereHas('user',function($query) use($id) { $query->where('users.added_by',$id);})->get()->sortBy('user.companyname');
        }

        return view('admin.customer.place-order')->with($data);
    }

    public function searchDaimondOrder(Request $request){
        $certificate = $request->certificate;
        $customer = $request->customer;

        $diamonds = DiamondNatural::with('suppliers:sup_id,return_allow')->select('*',DB::raw("(SELECT caratprice FROM rap_list WHERE IF(`shape_name` = 'ROUND', shape = 'ROUND', shape != 'ROUND') AND color_name = `color` AND clarity_name = IF(`clarity` = 'FL','IF', `clarity`) AND low_size <= carat AND high_size >= carat) as raprate"))->where('certificate_no',$certificate)->get();

        if(count($diamonds) == 0){
            $diamonds = DiamondLabgrown::with('suppliers:sup_id,return_allow')->select('*',DB::raw("(SELECT caratprice FROM rap_list WHERE IF(`shape_name` = 'ROUND', shape = 'ROUND', shape != 'ROUND') AND color_name = `color` AND clarity_name = IF(`clarity` = 'FL','IF', `clarity`) AND low_size <= carat AND high_size >= carat) as raprate"))->where('certificate_no',$certificate)->get();
        }

        $detail = '';
        if (count($diamonds) > 0) {
            $customer = Customer::where('cus_id',$customer)->first();
            $detail .= '
                    <table class="table table-striped table-bordered">
                    <input type="hidden" id="checkfield" value="1">
                        <thead>
                                <tr>
                                <th class="column-title">Availability</th>
                                    <th class="column-title">Certificate</th>
                                    <th class="column-title">Shape</th>
                                    <th class="column-title">Type</th>
                                    <th class="column-title">SKU</th>
                                    <th class="column-title">Ref No.</th>
                                    <th class="column-title">Carat</th>
                                    <th class="column-title">Color</th>
                                    <th class="column-title">Clarity</th>
                                    <th class="column-title">Cut</th>
                                    <th class="column-title">Polish</th>
                                    <th class="column-title">Symmetry</th>
                                    <th class="column-title">Lab</th>
                                    <th class="column-title">$/Ct</th>
                                    <th class="column-title">Sell Price</th>
                                    <th class="column-title">Discount</th>
                                    </tr>
                                    </thead>
                            <tbody>';
                            if (!empty($diamonds)) {
                                foreach($diamonds as $diamond){
                                    if ($diamond->diamond_type == "L") {
                                        $cus_discount = $customer->lab_discount;
                                $diamond_type = 'Lab Grown';
                                    } elseif ($diamond->diamond_type == "W") {
                                        $cus_discount = $customer->discount;
                                $diamond_type = 'Natural';
                                    }

                                    $orignal_rate = $diamond->rate + (($diamond->rate * ($cus_discount)) / 100);
                                    $supplier_price = ($orignal_rate * $diamond->carat);

                                    $procurment_price = AppHelper::procurmentPrice($supplier_price);
                                    $data['return_price'] = $return_price = round((1 / 100) * $procurment_price, 2);

                                    $carat_price = $procurment_price / $diamond->carat;
                                    $supplier_price = $orignal_rate * $diamond->carat;
                                    $procurment_discount = !empty($diamond->raprate) ? round(($carat_price - $diamond->raprate) / $diamond->raprate * 100, 2) : 0;
                                    // $procurment_price = $supplier_price;

                            $detail.='<tr>
                                <td>'.(($diamond->is_delete == 1) ? '<span class="badge badge-danger">Not available on system</span>' : '<span class="badge badge-info">'.$diamond->availability.'</span>').'</td>
                                        <td>'.$diamond->certificate_no.'</td>
                                        <td>'.$diamond->shape.'</td>
                                <td>'.$diamond_type.'</td>
                                        <td>'.$diamond->id.'</td>
                                        <td>'.$diamond->ref_no.'</td>
                                        <td>'.$diamond->carat.'</td>
                                        <td>'.$diamond->color.'</td>
                                        <td>'.$diamond->clarity.'</td>
                                        <td>'.$diamond->cut.'</td>
                                        <td>'.$diamond->polish.'</td>
                                        <td>'.$diamond->symmetry.'</td>
                                        <td>'.$diamond->lab.'</td>
                                        <td>'.number_format(($carat_price),2).'</td>
                                        <td>'.number_format($procurment_price,2).'</td>
                                        <td>'.number_format($procurment_discount,2).'</td>
                                    </tr>';
                                };
                            }
                    $detail.='</tbody>
                    </table>
                    <div class="row mb-5">
                        <div class="col-md-5 col-sm-18">
                            <div class="form-group">
                                <label for="title"> Customer Comment<span class="text-danger">*</span></label>
                                <input type="text" class="form-control"name="cus_comment" id="cus_comment" placeholder="Customer Comment">
                            </div>
                        </div>
                        <div class="col-md-5 col-sm-18 offset-md-1">
                            <div class="form-group">
                                <label for="title"> ION Number (Print on Packet)<span class="text-danger">*</span></label>
                                <input type="text" class="form-control"name="irm_no" id="irm_no" Placeholder="Internal Order Number">
                            </div>
                        </div>
                    </div>';
            $data['detail'] = $detail;
            $data['returnstatus'] = optional($diamond->suppliers)->return_allow;
        }
        else
        {
            $data['detail'] = '<div class="col-md-12 mt-5"><input type="hidden" id="checkfield" value="0">No Result Found!</div>';
            $data['error'] = false;
        }
        echo json_encode($data);

    }

    public function holdordersave($data){
        $certificate = $data['certificate'];
        $customer_id = $data['customer'];

        if($data['cus_comment'] == null){
            $data['cus_comment'] = 'Order Place By '.Auth::user()->firstname.' '. Auth::user()->lastname;
        }

        $ordercheck = Order::whereIn('order_status',['PENDING','APPROVED'])->where('certificate_no',$certificate)->where('is_deleted','0')->first();
        if($ordercheck != null){
            if($customer_id == $ordercheck->customer_id){
                return with('This Stone Is Already Hold For This Customer!');
            }
            else{
                return with('This Stone Is Already Hold For Someone Else!');
            }
        }
        else
        {
            $total = 0;
            $total_price = 0;
            $total_carat = 0;

            $diamond = DiamondNatural::select('*',DB::raw("(SELECT caratprice FROM rap_list WHERE IF(`shape_name` = 'ROUND', shape = 'ROUND', shape != 'ROUND') AND color_name = `color` AND clarity_name = IF(`clarity` = 'FL','IF', `clarity`) AND low_size <= carat AND high_size >= carat) as raprate"),
                DB::raw('(SELECT email FROM users WHERE id = supplier_id) as suplier_email'))->where('certificate_no',$certificate)->first();

            if($diamond == null){
                $diamond = DiamondLabgrown::select('*',DB::raw("(SELECT caratprice FROM rap_list WHERE IF(`shape_name` = 'ROUND', shape = 'ROUND', shape != 'ROUND') AND color_name = `color` AND clarity_name = IF(`clarity` = 'FL','IF', `clarity`) AND low_size <= carat AND high_size >= carat) as raprate"))->where('certificate_no',$certificate)->first();
            }
            $ip = $_SERVER['REMOTE_ADDR'];
            $date = date('Y-m-d H:i:s');

            $customer = Customer::with('user')->where('cus_id',$customer_id)->first();
            $discount_user = $customer->discount;
            $lab_discount_user = $customer->lab_discount;
            $firstname = Auth::user()->firstname;
            $lastname = Auth::user()->lastname;

            if ($diamond->diamond_type == "L") {
                $cus_discount = $customer->lab_discount;
            } elseif ($diamond->diamond_type == "W") {
                $cus_discount = $customer->discount;
            }

            $orignal_rate = $diamond->rate + (($diamond->rate * ($cus_discount)) / 100);
            $supplier_price = ($orignal_rate * $diamond->carat);

            $procurment_price = AppHelper::procurmentPrice($supplier_price);

            $carat_price = $procurment_price / $diamond->carat;

            $supplier_price = $orignal_rate * $diamond->carat;

            $procurment_discount = !empty($diamond->raprate) ? round(($carat_price - $diamond->raprate) / $diamond->raprate * 100, 2) : 0;
            // $procurment_price = $supplier_price;

            $return_price = round((1 / 100) * $procurment_price, 2);
            $return_price = 0;
            if($data['return'] == "1")
            {
                $return_price = number_format((1 / 100) * $procurment_price, 2);
            }
            $carat = $diamond->carat;

            $sale_price = $procurment_price + $return_price;
            $sale_rate = $sale_price / $diamond->carat;
            $sale_discount = !empty($diamond->raprate) ? round(($sale_rate - $diamond->raprate) / $diamond->raprate * 100, 2) : 0;

            $buy_rate = $diamond->orignal_rate;
            $buy_price = round($buy_rate * $carat, 5);
            $buy_discount = !empty($diamond->raprate) ? round(($buy_rate - $diamond->raprate) / $diamond->raprate * 100, 2) : 0;

            $total = $procurment_price + $total;
            $total_price = $total_price + $procurment_price;
            $total_carat = $total_carat + $carat;

            $total_approved = 0;//$this->Dashboard_Model->sumApproveOrderAprice();
            $total_confirm = 0;//!empty($total_approved->total_confirm) ? $total_approved->total_confirm : 0;
            $total_a_confirm = 0;//!empty($total_approved->total_a_confirm) ? $total_approved->total_a_confirm : 0;

            WishList::where('certificate_no', $diamond->certificate_no)->where('customer_id', $customer_id)->delete();

            $order_items_ids = $tablerecord = $customer_comment = '';

            if($diamond->color == "fancy")
            {
                $color = $diamond->fancy_intensity. ' ' . $diamond->fancy_overtone . ' '. $diamond->fancy_color;
            }
            else
            {
                $color = $diamond->color;
            }

            $loat_no_html = $diamond->id;

            $data_array = array(
                'customer_id' => $customer_id,
                'certificate_no' => $diamond->certificate_no,
                'sku' => $diamond->id,
                'diamond_type' => $diamond->diamond_type,
                'cart_rate' => $carat_price,
                'price' => $procurment_price,
                'orignal_price' => $supplier_price,
                'return_price' => $return_price,
                'ip' => $ip,
                'created_at' => $date,
            );
            $last_cart_id = Cart::insertGetId($data_array);

            $Checkhold = Order::where('certificate_no', $diamond->certificate_no)->where('customer_id', $customer_id)->where('hold', 1)->where('is_deleted', 0)->get();
            if(count($Checkhold) > 0)
            {
                Order::where('customer_id',$customer_id)->where('certificate_no',$diamond->certificate_no)->update([
                    'hold' => '0','hold_at' =>$date
                ]);
                $last_order_id = 0;
            }
            else
            {
                $data_array = array(
                    'customer_id' => $customer_id,

                    'certificate_no' => $diamond->certificate_no,
                    'ref_no' => $diamond->ref_no,
                    'diamond_type' => $diamond->diamond_type,
                    'hold' => '1',
                    'sale_discount' => $sale_discount,
                    'sale_price' => $sale_price,
                    'sale_rate' => $sale_rate,
                    'buy_price' => $buy_price,
                    'buy_rate' => $buy_rate,
                    'buy_discount' => $buy_discount,
                    'return_price' => $return_price,
                    'irm_no' =>  $data['irm_no'],
                    'customer_comment' => $data['cus_comment'],
                    'ip' => $ip,
                    'hold_at' =>$date,
                    'created_at' => $date,
                );
                $last_order_id = Order::insertGetId($data_array);

                $customer_comment = $data['cus_comment'];
                $order_items_ids .= $last_order_id.',';
                if($diamond->diamond_type == "L")
                {
                    DB::insert("INSERT INTO `orders_items` (`orders_id`,`customer_id`, `id`, `supplier_id`, `supplier_name`, `ref_no`, `availability`, `diamond_type`, `shape`, `carat`, `color`, `clarity`, `cut`, `polish`, `symmetry`, `fluorescence`, `lab`, `certificate_no`, `certificate_link`, `certificate_download`, `length`, `width`, `depth`, `location`, `city`, `country`, `milky`, `eyeclean`, `hna`, `depth_per`, `table_per`, `crown_angle`, `crown_height`, `pavilion_angle`, `pavilion_depth`, `discount`, `rap`, `orignal_rate`, `rate`, `net_dollar`, `key_symbols`, `fancy_color`, `fancy_intensity`, `fancy_overtone`, `image_status`, `cloud_image`, `image`, `video`, `heart`, `cloud_heart`, `arrow`, `cloud_arrow`, `asset`, `cloud_asset`, `canada_mark`, `cutlet`, `luster`, `gridle`, `gridle_per`, `girdle_thin`, `girdle_thick`, `shade`, `c_type`, `status`, `supplier_comments`, `culet_condition`, `hold_for`, `updated_at`, `created_at`, `is_delete`)
                                                SELECT '$last_order_id','$customer_id', `id`, `supplier_id`, `supplier_name`, `ref_no`, `availability`, 'L', `shape`, `carat`, `color`, `clarity`, `cut`, `polish`, `symmetry`, `fluorescence`, `lab`, `certificate_no`, `certificate_link`, `certificate_download`, `length`, `width`, `depth`, `location`, `city`, `country`, `milky`, `eyeclean`, `hna`, `depth_per`, `table_per`, `crown_angle`, `crown_height`, `pavilion_angle`, `pavilion_depth`, `discount`, '$diamond->raprate', `orignal_rate`, `rate`, `net_dollar`, `key_symbols`, `fancy_color`, `fancy_intensity`, `fancy_overtone`, `image_status`, `cloud_image`, `image`, `video`, `heart`, `cloud_heart`, `arrow`, `cloud_arrow`, `asset`, `cloud_asset`, `canada_mark`, `cutlet`, `luster`, `gridle`, `gridle_per`, `girdle_thin`, `girdle_thick`, `shade`, `c_type`, `status`, `supplier_comments`, `culet_condition`, `hold_for`, `updated_at`, '$date', `is_delete`
                    FROM diamond_labgrown WHERE certificate_no = '".$diamond->certificate_no."'");
                }
                else
                {
                    DB::insert("INSERT
                     INTO `orders_items` (`orders_id`,`customer_id`, `id`, `supplier_id`, `supplier_name`, `ref_no`, `availability`, `diamond_type`, `shape`, `carat`, `color`, `clarity`, `cut`, `polish`, `symmetry`, `fluorescence`, `lab`, `certificate_no`, `certificate_link`, `certificate_download`, `length`, `width`, `depth`, `location`, `city`, `country`, `milky`, `eyeclean`, `hna`, `depth_per`, `table_per`, `crown_angle`, `crown_height`, `pavilion_angle`, `pavilion_depth`, `discount`, `rap`, `orignal_rate`, `rate`, `net_dollar`, `key_symbols`, `fancy_color`, `fancy_intensity`, `fancy_overtone`, `image_status`, `cloud_image`, `image`, `video`, `heart`, `cloud_heart`, `arrow`, `cloud_arrow`, `asset`, `cloud_asset`, `canada_mark`, `cutlet`, `luster`, `gridle`, `gridle_per`, `girdle_thin`, `girdle_thick`, `shade`, `c_type`, `status`, `supplier_comments`, `culet_condition`, `hold_for`, `updated_at`, `created_at`, `is_delete`)
                                                SELECT '$last_order_id','$customer_id', `id`, `supplier_id`, `supplier_name`, `ref_no`, `availability`, 'W', `shape`, `carat`, `color`, `clarity`, `cut`, `polish`, `symmetry`, `fluorescence`, `lab`, `certificate_no`, `certificate_link`, `certificate_download`, `length`, `width`, `depth`, `location`, `city`, `country`, `milky`, `eyeclean`, `hna`, `depth_per`, `table_per`, `crown_angle`, `crown_height`, `pavilion_angle`, `pavilion_depth`, `discount`, '$diamond->raprate', `orignal_rate`, `rate`, `net_dollar`, `key_symbols`, `fancy_color`, `fancy_intensity`, `fancy_overtone`, `image_status`, `cloud_image`, `image`, `video`, `heart`, `cloud_heart`, `arrow`, `cloud_arrow`, `asset`, `cloud_asset`, `canada_mark`, `cutlet`, `luster`, `gridle`, `gridle_per`, `girdle_thin`, `girdle_thick`, `shade`, `c_type`, `status`, `supplier_comments`, `culet_condition`, `hold_for`, `updated_at`, '$date', `is_delete`
                    FROM diamond_natural WHERE certificate_no = '".$diamond->certificate_no."'");
                }
            }

            Cart::where('customer_id',$customer_id)->where('certificate_no',$diamond->certificate_no)->update([
                'is_delete'=>'1'
            ]);

            $tablerecord .= '<table width="100%" style="border:#CEC9C9 solid thin;padding: 10px;font-size: 13px;">
                    <tr>
                        <td width="25%">
                            <strong>'.$loat_no_html.'</strong>
                        </td>
                        <td width="30%">
                            <span><strong>'.$diamond->lab.': </strong> <strong> '.$diamond->certificate_no.'</strong></span>
                        </td>
                    <td width="30%" align="right"> <strong> $/CT &nbsp;$'. number_format($sale_rate, 2).'</strong></td>
                    </tr>
                    <tr>
                        <td colspan="2" width="70%">
                            <span style="font-weight: 600">'.$diamond->shape.' '.$carat.'CT '.$color.' '.$diamond->clarity.' '.$diamond->cut.' '.$diamond->polish.' '.$diamond->symmetry.' '.$diamond->fluorescence.'</span>
                        </td>
                        <td width="30%" align="right"><strong>Total &nbsp;$' . number_format($sale_price, 2) . '</strong></td>
                    </tr>
                </table>';

            $singlerecord = '<table width="100%" style="border:#CEC9C9 solid thin;padding: 10px;font-size: 13px;">
                <tr>
                    <td width="25%">
                        <span><a href="" style="text-decoration-color: #4f4f4f"><strong>'.$diamond->ref_no.'</strong></a></span>
                    </td>
                    <td width="30%">
                        <span><strong>'.$diamond->lab.': </strong><a href="" style="text-decoration-color: #4f4f4f"> <strong> '.$diamond->certificate_no.'</strong></a></span>
                    </td>
                    <td width="30%" align="right"> <strong> $/CT &nbsp;$'. number_format($diamond->orignal_rate, 2).'</strong></td>
                </tr>
                <tr>
                    <td colspan="2" width="70%">
                        <span style="font-weight: 600">'.$diamond->shape.' '.$carat.'CT '.$color.' '.$diamond->clarity.' '.$diamond->cut.' '.$diamond->polish.' '.$diamond->symmetry.' '.$diamond->fluorescence.'</span>
                    </td>
                    <td width="30%" align="right"><strong>Total &nbsp;$' . number_format($diamond->orignal_rate * $carat, 2) . '</strong></td>
                </tr>
            </table>';

            if(count($Checkhold) > 0)
            {
                $supplier_email_data = array();
                $supplier_email_data['firstname'] = $diamond->supplier_name;
                $supplier_email_data['text_message'] = $singlerecord;

                try {
                    Mail::send('emails.orders.hold-diamond-supplier', $supplier_email_data, function($message) use($suplier_email){
                        $message->from(\Cons::EMAIL_SUPPLIER, 'Supplier');
                        $message->to($suplier_email);
                            $message->cc(\Cons::EMAIL_SUPPLIER);
                            $message->subject("Hold Diamond Request Received On ". date('d-m-Y H') ." | ". env('APP_NAME'));
                        });
                } catch (\Throwable $th) {

                }
                $data['mail_hold-diamond-supplier'] = true;
            }
            $dprice = $total_price = 0;
            $discount_terms = $discount_row = '';
            if($customer->customer_type != 1){
                $temp_price = $total_price + $total_confirm;
                $temp_d_price = $dprice + $total_a_confirm;
                $pricechange = 0;//$this->Dashboard_Model->pricesettingadv($temp_d_price);
                $nondtotalamount = 0;//$temp_d_price * (1 + $pricechange->pricechange / 100) * (1 + $discount_user / 100);
                $savedamount = 0;//round($temp_price - $nondtotalamount, 2);

                if($savedamount > 0.1) {
                $discount_row = '<tr align="right">
                                    <td colspan="2" style="padding: 8px 0px;">
                                        <strong style="color: #002173;line-height: 1.7;font-size: 15px;text-transform: uppercase;">Consolidate All Orders &
                                        <span style="color: #ff0000;">SAVE $'.$savedamount.'*</span></strong>
                                    </td>
                                </tr>';

                $discount_terms = '<p style="font-size: 13px;line-height: 1.25;color: #4f4f4f;margin:7px 0px;">* Discounts are applicable when you consolidate all your orders (new & previous) in single invoice.</p>';
                }
            }

            if(!empty($customer_comment))
            {
                $tablerecord .= '<table width="100%" style="border:#CEC9C9 solid thin;padding: 10px;font-size: 13px;">
                <tr>
                    <td width="100%">
                        '.$customer_comment.'
                    </td>
                </tr>
                </table>';
            }

            $email_data['firstname'] = $firstname;
            $email_data['text_message'] = $tablerecord;
                //TODO::: Remove when Live
            Mail::send('emails.orders.hold-confirm-diamond-customer', $email_data, function($message) use($last_order_id, $customer){
                    $message->to(\Cons::EMAIL_SALE);
                    $message->subject('New order place please confirm - ' . $customer->user->email . ' #'.$last_order_id);
                });

            Mail::send('emails.orders.hold-confirm-diamond-customer', $email_data, function($message) use($customer){
                    $message->to($customer->user->email);
                    $message->subject('Thank you for your order '. date('d-m-Y') ." | ". env('APP_NAME'));
                });
            $data['mail_hold-confirm-diamond-customer'] = true;

            $data['success'] = true;
            return with('success');
        }
    }

    public function AdminOrderPlaceSave(Request $request){
        if($request->submit == 'Hold Order'){
            $data = $request->all();
            $certificate = $request->certificate;
            if($request->return){
                $data['return']=1;
            }
            else{
                $data['return']=0;
            }
            $order = $this->holdordersave($data);
            if($order == 'success'){
                return redirect('place-order')->with($order,'Order Hold By Sales Person For Certificate No = '.$certificate);
            }
            else{
                // dd('not success');
                return redirect('place-order')->with('warning',$order);
            }
        }
        else
        {
            $certificate = $request->certificate;
            $customer_id = $request->customer;

            $total = 0;
            $total_price = 0;
            $total_carat = 0;

            if($request->cus_comment == null){
                $request->cus_comment = 'Order Place By '.Auth::user()->firstname.' '. Auth::user()->lastname;
            }
            $ip = $_SERVER['REMOTE_ADDR'];
            $date = date('Y-m-d H:i:s');

            $diamond = DiamondNatural::select('*',DB::raw("(SELECT caratprice FROM rap_list WHERE IF(`shape_name` = 'ROUND', shape = 'ROUND', shape != 'ROUND') AND color_name = `color` AND clarity_name = IF(`clarity` = 'FL','IF', `clarity`) AND low_size <= carat AND high_size >= carat) as raprate"),
                DB::raw('(SELECT email FROM users WHERE id = supplier_id) as suplier_email'))->where('certificate_no',$certificate)->first();

            $customer = Customer::with('user')->where('cus_id',$customer_id)->first();
            $discount_user = $customer->discount;
            $lab_discount_user = $customer->lab_discount;
            $firstname = Auth::user()->firstname;
            $lastname = Auth::user()->lastname;

            if ($diamond->diamond_type == "L") {
                $cus_discount = $customer->lab_discount;
            } elseif ($diamond->diamond_type == "W") {
                $cus_discount = $customer->discount;
            }

            $suplier_email	= $diamond->suplier_email;

            $orignal_rate = $diamond->rate + (($diamond->rate * ($cus_discount)) / 100);
            $supplier_price = ($orignal_rate * $diamond->carat);

            $procurment_price = AppHelper::procurmentPrice($supplier_price);

            $return_price = round((1 / 100) * $procurment_price, 2);
            $return_price = 0;
            if($request->return == "1")
            {
                $return_price = number_format((1 / 100) * $procurment_price, 2);
            }
            $carat = $diamond->carat;

            $sale_price = $procurment_price + $return_price;
            $sale_rate = $sale_price / $diamond->carat;
            $sale_discount = !empty($diamond->raprate) ? round(($sale_rate - $diamond->raprate) / $diamond->raprate * 100, 2) : 0;

            $holdcheck = Order::where('certificate_no',$certificate)->whereIn('order_status',['PENDING','APPROVED'])->where('is_deleted','0')->where('hold','1')->first();
            if($holdcheck != null){
                if($customer_id == $holdcheck->customer_id){
                    if($request->return == "1"){
                        Order::where('certificate_no',$certificate)->where('is_deleted','0')->where('hold','1')->update(['hold' => '0','return_price' => $return_price,'created_at' => $date]);
                    }
                    else{
                        Order::where('certificate_no',$certificate)->where('is_deleted','0')->where('hold','1')->update(['hold' => '0','created_at' => $date]);
                    }
                    return redirect('place-order')->with('success','Order Place By Sales Person For Certificate No = '.$certificate);
                }
                else{
                    return redirect('place-order')->with('warning','Stone is Already Sold');
                }
            }


            if($diamond == null){
                $diamond = DiamondLabgrown::select('*',DB::raw("(SELECT caratprice FROM rap_list WHERE IF(`shape_name` = 'ROUND', shape = 'ROUND', shape != 'ROUND') AND color_name = `color` AND clarity_name = IF(`clarity` = 'FL','IF', `clarity`) AND low_size <= carat AND high_size >= carat) as raprate"),
                    DB::raw('(SELECT email FROM users WHERE id = supplier_id) as suplier_email'))->where('certificate_no',$certificate)->first();
            }
            if($diamond == null){
                return redirect('place-order')->with('warning','The Entered Certificate Number is not available!');
            }

            $ordercheck = Order::whereIn('order_status',['PENDING','APPROVED'])->where('certificate_no',$certificate)->first();
            if($ordercheck!= null){
                return redirect('place-order')->with('warning','Order Already Placed!');
            }



            $carat_price = $procurment_price / $diamond->carat;

            $supplier_price = $orignal_rate * $diamond->carat;

            $procurment_discount = !empty($diamond->raprate) ? round(($carat_price - $diamond->raprate) / $diamond->raprate * 100, 2) : 0;
            // $procurment_price = $supplier_price;


            $buy_rate = $diamond->orignal_rate;
            $buy_price = round($buy_rate * $carat, 5);
            $buy_discount = !empty($diamond->raprate) ? round(($buy_rate - $diamond->raprate) / $diamond->raprate * 100, 2) : 0;

            $total = $procurment_price + $total;
            $total_price = $total_price + $procurment_price;
            $total_carat = $total_carat + $carat;

            $total_approved = 0;//$this->Dashboard_Model->sumApproveOrderAprice();
            $total_confirm = 0;//!empty($total_approved->total_confirm) ? $total_approved->total_confirm : 0;
            $total_a_confirm = 0;//!empty($total_approved->total_a_confirm) ? $total_approved->total_a_confirm : 0;

            WishList::where('certificate_no', $diamond->certificate_no)->where('customer_id', $customer_id)->delete();

            $order_items_ids = $tablerecord = $customer_comment = '';

            if($diamond->color == "fancy")
            {
                $color = $diamond->fancy_intensity. ' ' . $diamond->fancy_overtone . ' '. $diamond->fancy_color;
            }
            else
            {
                $color = $diamond->color;
            }

            $loat_no_html = $diamond->id;

            $data_array = array(
                'customer_id' => $customer_id,
                'certificate_no' => $diamond->certificate_no,
                'sku' => $diamond->id,
                'diamond_type' => $diamond->diamond_type,
                'cart_rate' => $carat_price,
                'price' => $procurment_price,
                'orignal_price' => $supplier_price,
                'return_price' => $return_price,
                'ip' => $ip,
                'created_at' => $date,
            );
            $last_cart_id = Cart::insertGetId($data_array);

            $Checkhold = Order::where('certificate_no', $diamond->certificate_no)->where('customer_id', $customer_id)->where('hold', 1)->where('is_deleted', 0)->get();
            if(count($Checkhold) > 0)
            {
                Order::where('customer_id',$customer_id)->where('certificate_no',$diamond->certificate_no)->update([
                    'hold' => '0','approved_at' => $date,
                ]);
                $last_order_id = 0;
            }
            else
            {
                $data_array = array(
                    'customer_id' => $customer_id,
                    'certificate_no' => $diamond->certificate_no,
                    'ref_no' => $diamond->ref_no,
                    'diamond_type' => $diamond->diamond_type,
                    'sale_discount' => $sale_discount,
                    'sale_price' => $sale_price,
                    'sale_rate' => $sale_rate,
                    'buy_price' => $buy_price,
                    'buy_rate' => $buy_rate,
                    'buy_discount' => $buy_discount,
                    'return_price' => $return_price,
                    'irm_no' => $request->irm_no,
                    'customer_comment' => $request->cus_comment,
                    'approved_at' => $date,
                    'ip' => $ip,
                    'created_at' => $date,
                );
                $last_order_id = Order::insertGetId($data_array);

                $customer_comment = $request->cus_comment;
                $order_items_ids .= $last_order_id.',';
                if($diamond->diamond_type == "L")
                {
                    DB::insert("INSERT INTO `orders_items` (`orders_id`,`customer_id`, `id`, `supplier_id`, `supplier_name`, `ref_no`, `availability`, `diamond_type`, `shape`, `carat`, `color`, `clarity`, `cut`, `polish`, `symmetry`, `fluorescence`, `lab`, `certificate_no`, `certificate_link`, `certificate_download`, `length`, `width`, `depth`, `location`, `city`, `country`, `milky`, `eyeclean`, `hna`, `depth_per`, `table_per`, `crown_angle`, `crown_height`, `pavilion_angle`, `pavilion_depth`, `discount`, `rap`, `orignal_rate`, `rate`, `net_dollar`, `key_symbols`, `fancy_color`, `fancy_intensity`, `fancy_overtone`, `image_status`, `cloud_image`, `image`, `video`, `heart`, `cloud_heart`, `arrow`, `cloud_arrow`, `asset`, `cloud_asset`, `canada_mark`, `cutlet`, `luster`, `gridle`, `gridle_per`, `girdle_thin`, `girdle_thick`, `shade`, `c_type`, `status`, `supplier_comments`, `culet_condition`, `hold_for`, `updated_at`, `created_at`, `is_delete`)
                                                SELECT '$last_order_id','$customer_id', `id`, `supplier_id`, `supplier_name`, `ref_no`, `availability`, 'L', `shape`, `carat`, `color`, `clarity`, `cut`, `polish`, `symmetry`, `fluorescence`, `lab`, `certificate_no`, `certificate_link`, `certificate_download`, `length`, `width`, `depth`, `location`, `city`, `country`, `milky`, `eyeclean`, `hna`, `depth_per`, `table_per`, `crown_angle`, `crown_height`, `pavilion_angle`, `pavilion_depth`, `discount`, '$diamond->raprate', `orignal_rate`, `rate`, `net_dollar`, `key_symbols`, `fancy_color`, `fancy_intensity`, `fancy_overtone`, `image_status`, `cloud_image`, `image`, `video`, `heart`, `cloud_heart`, `arrow`, `cloud_arrow`, `asset`, `cloud_asset`, `canada_mark`, `cutlet`, `luster`, `gridle`, `gridle_per`, `girdle_thin`, `girdle_thick`, `shade`, `c_type`, `status`, `supplier_comments`, `culet_condition`, `hold_for`, `updated_at`, '$date', `is_delete`
                    FROM diamond_labgrown WHERE certificate_no = '".$diamond->certificate_no."'");
                }
                else
                {
                    DB::insert("INSERT INTO `orders_items` (`orders_id`,`customer_id`, `id`, `supplier_id`, `supplier_name`, `ref_no`, `availability`, `diamond_type`, `shape`, `carat`, `color`, `clarity`, `cut`, `polish`, `symmetry`, `fluorescence`, `lab`, `certificate_no`, `certificate_link`, `certificate_download`, `length`, `width`, `depth`, `location`, `city`, `country`, `milky`, `eyeclean`, `hna`, `depth_per`, `table_per`, `crown_angle`, `crown_height`, `pavilion_angle`, `pavilion_depth`, `discount`, `rap`, `orignal_rate`, `rate`, `net_dollar`, `key_symbols`, `fancy_color`, `fancy_intensity`, `fancy_overtone`, `image_status`, `cloud_image`, `image`, `video`, `heart`, `cloud_heart`, `arrow`, `cloud_arrow`, `asset`, `cloud_asset`, `canada_mark`, `cutlet`, `luster`, `gridle`, `gridle_per`, `girdle_thin`, `girdle_thick`, `shade`, `c_type`, `status`, `supplier_comments`, `culet_condition`, `hold_for`, `updated_at`, `created_at`, `is_delete`)
                                                SELECT '$last_order_id','$customer_id', `id`, `supplier_id`, `supplier_name`, `ref_no`, `availability`, 'W', `shape`, `carat`, `color`, `clarity`, `cut`, `polish`, `symmetry`, `fluorescence`, `lab`, `certificate_no`, `certificate_link`, `certificate_download`, `length`, `width`, `depth`, `location`, `city`, `country`, `milky`, `eyeclean`, `hna`, `depth_per`, `table_per`, `crown_angle`, `crown_height`, `pavilion_angle`, `pavilion_depth`, `discount`, '$diamond->raprate', `orignal_rate`, `rate`, `net_dollar`, `key_symbols`, `fancy_color`, `fancy_intensity`, `fancy_overtone`, `image_status`, `cloud_image`, `image`, `video`, `heart`, `cloud_heart`, `arrow`, `cloud_arrow`, `asset`, `cloud_asset`, `canada_mark`, `cutlet`, `luster`, `gridle`, `gridle_per`, `girdle_thin`, `girdle_thick`, `shade`, `c_type`, `status`, `supplier_comments`, `culet_condition`, `hold_for`, `updated_at`, '$date', `is_delete`
                    FROM diamond_natural WHERE certificate_no = '".$diamond->certificate_no."'");
                }
            }

            Cart::where('customer_id',$customer_id)->where('certificate_no',$diamond->certificate_no)->update([
                'is_delete'=>'1'
            ]);

            $tablerecord .= '<table width="100%" style="border:#CEC9C9 solid thin;padding: 10px;font-size: 13px;">
                    <tr>
                        <td width="25%">
                            <strong>'.$loat_no_html.'</strong>
                        </td>
                        <td width="30%">
                            <span><strong>'.$diamond->lab.': </strong> <strong> '.$diamond->certificate_no.'</strong></span>
                        </td>
                    <td width="30%" align="right"> <strong> $/CT &nbsp;$'. number_format($sale_rate, 2).'</strong></td>
                    </tr>
                    <tr>
                        <td colspan="2" width="70%">
                            <span style="font-weight: 600">'.$diamond->shape.' '.$carat.'CT '.$color.' '.$diamond->clarity.' '.$diamond->cut.' '.$diamond->polish.' '.$diamond->symmetry.' '.$diamond->fluorescence.'</span>
                        </td>
                        <td width="30%" align="right"><strong>Total &nbsp;$' . number_format($sale_price, 2) . '</strong></td>
                    </tr>
                </table>';

            $singlerecord = '<table width="100%" style="border:#CEC9C9 solid thin;padding: 10px;font-size: 13px;">
                <tr>
                    <td width="25%">
                        <span><a href="" style="text-decoration-color: #4f4f4f"><strong>'.$diamond->ref_no.'</strong></a></span>
                    </td>
                    <td width="30%">
                        <span><strong>'.$diamond->lab.': </strong><a href="" style="text-decoration-color: #4f4f4f"> <strong> '.$diamond->certificate_no.'</strong></a></span>
                    </td>
                    <td width="30%" align="right"> <strong> $/CT &nbsp;$'. number_format($diamond->orignal_rate, 2).'</strong></td>
                </tr>
                <tr>
                    <td colspan="2" width="70%">
                        <span style="font-weight: 600">'.$diamond->shape.' '.$carat.'CT '.$color.' '.$diamond->clarity.' '.$diamond->cut.' '.$diamond->polish.' '.$diamond->symmetry.' '.$diamond->fluorescence.'</span>
                    </td>
                    <td width="30%" align="right"><strong>Total &nbsp;$' . number_format($diamond->orignal_rate * $carat, 2) . '</strong></td>
                </tr>
            </table>';

            if(count($Checkhold) > 0)
            {
                $supplier_email_data = array();
                $supplier_email_data['firstname'] = $diamond->supplier_name;
                $supplier_email_data['text_message'] = $singlerecord;

                try {
                    Mail::send('emails.orders.hold-diamond-supplier', $supplier_email_data, function($message) use($suplier_email){
                        $message->from(\Cons::EMAIL_SUPPLIER, 'Supplier');
                        $message->to($suplier_email);
                            $message->cc(\Cons::EMAIL_SUPPLIER);
                            $message->subject("Hold Diamond Request Received On ". date('d-m-Y H') ." | ". env('APP_NAME'));
                        });
                } catch (\Throwable $th) {

                }
                $data['mail_hold-diamond-supplier'] = true;
            }
            $dprice = $total_price = 0;
            $discount_terms = $discount_row = '';
            if($customer->customer_type != 1){
                $temp_price = $total_price + $total_confirm;
                $temp_d_price = $dprice + $total_a_confirm;
                $pricechange = 0;//$this->Dashboard_Model->pricesettingadv($temp_d_price);
                $nondtotalamount = 0;//$temp_d_price * (1 + $pricechange->pricechange / 100) * (1 + $discount_user / 100);
                $savedamount = 0;//round($temp_price - $nondtotalamount, 2);

                if($savedamount > 0.1) {
                $discount_row = '<tr align="right">
                                    <td colspan="2" style="padding: 8px 0px;">
                                        <strong style="color: #002173;line-height: 1.7;font-size: 15px;text-transform: uppercase;">Consolidate All Orders &
                                        <span style="color: #ff0000;">SAVE $'.$savedamount.'*</span></strong>
                                    </td>
                                </tr>';

                $discount_terms = '<p style="font-size: 13px;line-height: 1.25;color: #4f4f4f;margin:7px 0px;">* Discounts are applicable when you consolidate all your orders (new & previous) in single invoice.</p>';
                }
            }

            if(!empty($customer_comment))
            {
                $tablerecord .= '<table width="100%" style="border:#CEC9C9 solid thin;padding: 10px;font-size: 13px;">
                <tr>
                    <td width="100%">
                        '.$customer_comment.'
                    </td>
                </tr>
                </table>';
            }

            $email_data['firstname'] = $firstname;
            $email_data['text_message'] = $tablerecord;

            //TODO::: Sales person email
            try {
            Mail::send('emails.orders.hold-confirm-diamond-customer', $email_data, function($message) use($last_order_id, $customer){
                    $message->to(\Cons::EMAIL_SALE);
                    $message->subject('New order place please confirm - ' . $customer->user->email . ' #'.$last_order_id);
                });
            } catch (\Throwable $th) {

            }

            $email_data['firstname'] = $customer->user->firstname;
            try {
            Mail::send('emails.orders.hold-confirm-diamond-customer', $email_data, function($message) use($customer){
                    $message->to($customer->user->email);
                    $message->subject('Thank you for your order '. date('d-m-Y') ." | ". env('APP_NAME'));
                });
            } catch (\Throwable $th) {

            }
            $data['mail_hold-confirm-diamond-customer'] = true;

            $data['success'] = true;
            return redirect('place-order')->with('success','Order Places SuccessFully For Certificate No = '.$certificate);
        }
    }

    public function customerList(Request $request)
    {
        $user_type = Auth::user()->user_type;
        $id = Auth::user()->id;

        $url = $request->segment(1);

        $customers = Admin::select('id')->where('manager',$id)->get()->pluck('id')->toArray();
        $customers[] = $id;

        $data['permission'] = AppHelper::userPermission($url);

        $data['title'] = "Platinum";
        $customer= Customer::with('user','user.manager')
                            ->whereHas('user',function($query){ $query->where('user_type', 2);})
                            ->where('customer_type', 1);
        if($user_type == 1)
        {

        }
        else
        {
            if($data['permission']->full == 0){
                $customer->whereHas('user.manager',function($query) use($customers){ $query->whereIn('users.added_by',$customers); });
            }
        }
        $data['customers'] = $customer->get()->sortBy('user.created_at');

        return view('admin.customer.customer-list')->with($data);
    }

    public function addCustomer()
    {
        return view('admin.customer.customer-add');
    }

    public function addNewCustomer(Request $request)
    {
        $request->validate([
            'firstname'=>'required',
            'lastname'=>'required',
            'mobile'=>'required',
            'email'=>'required|string|email',
            'password'=>['required',Password::min(8)->mixedCase()->numbers()->symbols()],
            'companyname'=>'required',
            'country'=>'required',
            'state'=>'required',
            // 'city' =>'required',
            // 'website'=>'required',
            // 'discount'=>'required',
            // 'lab_discount'=>'required',
            'customer_type'=>'required'
        ]);
        $usercheck = User::where('email',$request->email)->get();
        if(count($usercheck) > 0){
            return redirect('add-customer')->with('warning','The E-Mail You used is already registered!');
        }
        $user_data =array(
            'firstname'=>$request->firstname,
            'lastname'=>$request->lastname,
            'mobile'=>$request->mobile,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'companyname'=>$request->companyname,
            'user_type'=>2,
            'email_verify_code'=>Str::random(32),
            'email_verified_at'=>date_create(),
            'is_active'=>1,
            'created_at'=>date_create(),
        );
        $id = User::insertGetId($user_data);
        $customer_data =array(
            'cus_id'=>$id,
            'country'=>$request->country,
            'state'=>$request->state,
            'city'=>$request->city,
            'customer_type'=>$request->customer_type,
            'discount'=>$request->discount,
            'lab_discount'=>$request->lab_discount
        );
        Customer::insert($customer_data);
        return redirect('add-customer')->with('success','Customer Add Successsful');
    }

    public function goldCustomerList(Request $request)
    {
        $user_type = Auth::user()->user_type;
        $id = Auth::user()->id;

        $url = $request->segment(1);
        $data['permission'] = AppHelper::userPermission($url);

        $customers = Admin::select('id')->where('manager',$id)->get()->pluck('id')->toArray();
        $customers[] = $id;

        $data['title'] = "Gold";
        $customer = Customer::with('user','user.manager')
        ->whereHas('user',function($query) { $query->where('user_type', 2); $query->orderBy('created_at', 'asc');})
        ->where('customer_type', 2);
        if($user_type == 1)
        {

        }
        else
        {
            if($data['permission']->full == 0){
                $customer->whereHas('user.manager',function($query) use($customers){ $query->whereIn('users.added_by',$customers); });
            }
        }
        $data['customers'] = $customer->get();

        return view('admin.customer.customer-list')->with($data);
    }


    public function silverCustomerList(Request $request)
    {
        $user_type = Auth::user()->user_type;
        $id = Auth::user()->id;

        $url = $request->segment(1);
        $data['permission'] = AppHelper::userPermission($url);

        $customers = Admin::select('id')->where('manager',$id)->get()->pluck('id')->toArray();
        $customers[] = $id;

        $data['title'] = "Silver";
        $customer = Customer::with('user','user.manager')
            ->whereHas('user',function($query) { $query->where('user_type', 2); $query->where('is_delete',0);
             $query->orderBy('created_at', 'asc');})
            ->where('customer_type', 3);
        if($user_type == 1)
        {

        }
        else
        {
            if($data['permission']->full == 0){
                $customer->whereHas('user.manager',function($query) use($customers){ $query->whereIn('users.added_by',$customers); });
            }
        }
        $data['customers'] = $customer->get();

        return view('admin.customer.customer-list')->with($data);
    }


    public function customerEdit(Request $request)
    {
        $id = $request->id;

        $data['customer_detail'] = Customer::with('user','user.manager')
        ->whereHas('user',function($query) use($id) { $query->where('user_type', 2); $query->where('id',$id); })
        ->first();

        $data['sales'] = User::whereIn('user_type',[1,4])
        ->where('is_active',1)
        ->where('is_delete',0)
        ->get();

        $data['customermode'] = WhiteLabel::where('user_id', $id)->first();

        return view('admin.customer.customer-edit')->with($data);
    }

    public function updateCustomerProfile(Request $request)
    {
        $id = $request->id;
        $request->validate([
            'discount'=>['numeric'],
            'lab_discount'=>['numeric'],
            // 'shipingphone'=>['numeric','nullable'],
            'companytax'=>['max:20'],
            'companyno'=>['max:20'],
            // 'possportfile'=>'required',
            // 'com_reg_doc'=>'required',
        ]);

        $user = array(
                'firstname'=>$request->firstname,
                'lastname'=>$request->lastname,
                'companyname'=>$request->companyname,
                'mobile'=>$request->mobile,
            );

        if($request->customer_type == '4')
        {
            $user['is_active'] = 0;
        }
        else
        {
            $user['is_active'] = 1;
            $user['is_delete'] = 0;
        }

        $user['added_by'] = $request->sales_manager_id;
        User::where('id',$id)->update($user);

        $customer = Customer::where('cus_id',$id)->first();

        $com_reg_doc = '';
        $possportfile = '';

        if(!empty($request->file('possportfile')))
        {
            $possportfile = time() . '_' . $request->file('possportfile')->getClientOriginalName();
            $request->file('possportfile')->storeAs('acustomer_doc', $possportfile, 'public');
        }
        else
        {
            $possportfile = $customer->possportfile;
        }


        if(!empty($request->file('com_reg_doc')))
        {
            $com_reg_doc = time() . '_' . $request->file('com_reg_doc')->getClientOriginalName();
            $request->file('com_reg_doc')->storeAs('acustomer_doc', $com_reg_doc, 'public');
        }
        else
        {
            $com_reg_doc = $customer->com_reg_doc;
        }
        if(!empty($request->showsupplier)){
            $showsupplierprice=$request->showsupplier;
        }else{
            $showsupplierprice='0';
        }
        if(!empty($request->additionalemail)){
             $addemail=$request->additionalemail;
        }else{
            $addemail=null;
        }

        Customer::where('cus_id',$id)->update([
            'country'=>$request->country,
            'addemail'=>$addemail,
            'state'=>$request->state,
            'city'=>$request->city,
            'website' => $request->website,
            'customer_type' => $request->customer_type,
            'discount' => $request->discount,
            'showsupplier'=>$showsupplierprice,
            'lab_discount' => $request->lab_discount,
            'passport_id'=>$request->passportno,
            'address'=>$request->address,
            'com_reg_no'=>$request->companyno,
            'shiping_phone'=>$request->shipingphone,
            'shipping_address'=>$request->shipingaddress,
            'shiping_email'=>$request->shipingemail,
            'company_tax'=>$request->companytax,
            'api_key' => $request->api_key,
            'api_enable' => $request->api_enable,
            'port_of_discharge'=>$request->discharge,
            'passport_file'=>$request->possportfile,
            'com_reg_doc'=>$request->compnydoc,
            'consignee_buyer_name'=>$request->consignee_buyer_name,
        ]);

        return redirect('customer-edit/'.$id)->with('success','Customer Profile Update');
    }

    public function CustomerPassword(Request $request)
    {
        $id = $request->id;
        $data['customer_detail'] = User::where('id',$id)->first();
        return view('admin.customer.customer-password')->with($data);
    }

    public function updateCustomerPassword(Request $request)
    {
        $request->validate([
            'password'=>['required','confirmed',Password::min(8)->mixedCase()->numbers()->symbols()],
            'password_confirmation'=>['required',Password::min(8)->mixedCase()->numbers()->symbols()],
            ]);

        $new_pasword =$request->password;
        $confirm_password =Hash::make($request->password_confirmation);
        $id = $request->id;

        User::where('id',$id)->update([
           'password'=>$confirm_password,
       ]);
       return redirect('scustomer')->with('success','Password Update successful');
    }

    public function pendingCustomerList()
    {
        $data['customers'] = Customer::with('user','user.manager')
        ->whereHas('user',function($query){ $query->where('user_type', 2); $query->where('is_delete',0); })
        ->where('customer_type',4)
        ->get();
        return view('admin.customer.customer-pending-list')->with($data);
    }

    public function CustomerMoveToPending(Request $request)
    {
        $id = $request->id;
        User::where('id',$id)->update([
            'is_active'=> '0',
            'is_delete'=>'0'
        ]);
        $data['success'] = true;
		echo json_encode($data);
    }

    public function approveCustomer(Request $request)
    {
        $detail = '';
		$id = $request->id;

        $customer = User::with('customer')->where('id',$id)->first();

		$firstname = $customer->firstname;
		$lastname = $customer->lastname;

        User::where('id', $id)->update(array('is_active' => 1, 'email_verified_at' => date('Y-m-d H:i:s'), 'email_verify_code' => ''));
        Customer::where('cus_id', $id)->update(array('customer_type' => 3));

        try {
            Mail::send('emails.customer-approval', ['firstname'=> $customer->firstname, 'lastname'=> $customer->lastname, 'companyname' => $customer->companyname, 'email' => $customer->email], function($message) use($customer){
                $message->to($customer->email);
                $message->subject("$customer->firstname, Congrats! Your account is now fully verified. | ". config('app.name'));
            });
        } catch (\Throwable $th) {

        }
		$data['success'] = true;

		echo json_encode($data);
    }

    public function deletedCustomerList()
    {
        $data['customers'] = Customer::with('user')
        ->whereHas('user',function($query){ $query->where('user_type', 2); $query->where('is_delete',1); })
        ->where('customer_type',4)
        ->get();
        return view('admin.customer.customer-delete-list')->with($data);
    }

    public function deletedCustomer(Request $request)
    {
        $id = $request->id;
        User::where('id',$id)
        ->update([
            'is_delete'=>'1',
            'is_active'=>'0'
        ]);

        Customer::where('cus_id',$id)->update(['customer_type'=>'4']);

        return  redirect('scustomer')->with('success','Customer Delete Successful');
    }

    public function SupplierRequestCustomer(Request $request)
    {
        $data['user_id'] = $id = $request->id;
        $data['customer'] = User::with('customer')->where('id',$id)->first();

        $data['suppliers'] = Supplier::with('users')
        ->whereHas('users',function($query){ $query->orderBy('companyname', 'asc'); $query->where('user_type',3); $query->where('is_active',1); $query->where('is_delete',0); })
        ->leftJoin('supplier_requests', function($query) use ($id) {
            $query->on('supplier_requests.supplier_id', '=', 'suppliers.sup_id');
            $query->where('user_id','=',$id);
        })
        ->get();

        $data['supplier_request'] = DB::table('supplier_requests')
        ->where('user_id', $id)
        ->get();

        return view('admin.customer.customer-supplier-request')->with($data);
    }

    public function supplierRequestTrunon(Request $request)
    {
        $id = $request->customer_id;
        $data['customer'] = User::with('customer')->where('id',$id)->first();

        $data['suppliers'] = Supplier::with('users')->whereHas('users',function ($query){$query->where('user_type',3); $query->where('is_delete',0);})->get();
        $date = date('Y-m-d H:i:s');
        foreach($data['suppliers'] as $supplier)
        {
            DB::table('supplier_requests')->updateOrInsert(
                ['supplier_id' => $supplier->sup_id, 'user_id' => $id],
                [
                    'supplier_id'     => $supplier->sup_id,
                    'user_id' => $id,
                    'request_status' => 1,
                    'created_at' => $date
                ]
            );
        }

        $data['success'] = true;
        return json_encode($data);
    }

    public function supplierRequestTrunOff(Request $request)
    {
        $id = $request->customer_id;
        $data['customer'] = User::with('customer')->where('id',$id)->first();

        $data['suppliers'] = Supplier::with('users')->whereHas('users',function ($query){$query->where('user_type',3); $query->where('is_delete',0);})->get();

        $date = date('Y-m-d H:i:s');
        foreach($data['suppliers'] as $supplier)
        {
            DB::table('supplier_requests')->updateOrInsert(
                ['supplier_id' => $supplier->sup_id, 'user_id' => $id],
                [
                    'supplier_id'     => $supplier->sup_id,
                    'user_id' => $id,
                    'request_status' => 0,
                    'created_at' => $date
                ]
            );
        }

        $data['success'] = true;
        return json_encode($data);
    }

    public function supplierRequestTrun(Request $request)
    {
        $id = $request->id;
        $sup_id = $request->supplier_id;
        $user_id = $request->customer_id;
        $status = $request->status;

        $data['customer'] = User::with('customer')->where('id',$user_id)->first();

        $data['suppliers'] = Supplier::with('users')->whereHas('users',function ($query){$query->where('user_type',3); $query->where('is_delete',0);})->get();

        $date = date('Y-m-d H:i:s');
            DB::table('supplier_requests')->updateOrInsert(
                ['supplier_id' => $sup_id, 'user_id' => $user_id],
                [
                    'supplier_id'     => $sup_id,
                    'user_id' => $user_id,
                    'request_status' => $status,
                    'created_at' => $date
                ]
            );

        $data['success'] = true;
        return json_encode($data);
    }

    public function customerResendEmailVerify(Request $request)
    {
        $id = $request->id;
        $customers = User::with('customer')->where('id',$id)->first();

        $token = mt_rand() . time();
        User::where('id', $id)->update(['email_verify_code' => $token]);
        try {
            Mail::send('emails.customer_reverifiacation', ['firstname'=> $customers->firstname, 'lastname'=> $customers->lastname, 'link' => $token], function($message) use($customers){
                $message->to($customers->email);
                $message->subject("Please verify your email | ". config('app.name'));
            });
        } catch (\Throwable $th) {

        }
        $data['success'] = true;
        return json_encode($data);
    }

    public function MoveCustomer(Request $request)
    {
        $id = $request->id;

        User::where('id',$id)->update(['user_type' => 3]);

        $data = Customer::where('cus_id',$id)->first();

        $sup_table = array(
            'sup_id' => $id,
            // 'supplier_name' => $request->companyname,
            // 'diamond_type' => $request->diamond_type,
            'upload_mode' => "File",
            'country' => $data->country,
            'state' => $data->state,
            'city' => $data->city,
        );
        Supplier::insert($sup_table);

        Customer::where('cus_id',$id)->delete();

        return  redirect('pcustomer')->with('success','Customer move Successful');
    }

    public function addEditShippingDetails(Request $request)
    {
       $id = $request->id;
       $company_name = $request->comany_name;
       $country = $request->country;
       $state = $request->state;
       $district = $request->district;
       $address = $request->address;
       $pincode = $request->pincode;
       $gst_number = $request->gst_number;
       $supply_place = $request->supply_place;
       $phonenumber = $request->phonenumber;
       $company_tax = $request->company_tax;
       $port_of_discharge = $request->port_of_discharge;
       $attend_name = $request->attend_name;
       $customer_id = $request->customer_id;
       $customer_name = $request->customer_name;

       $data= ['company_name'=>$company_name,
        'address'=>$address,
        'country'=>$country,
        'state'=>$state,
        'city'=>$district,
        'pincode'=>$pincode,
        'gst_no'=>$gst_number,
        'place_of_supply'=>$supply_place,
        'phone_no'=>$phonenumber,
        'company_tax'=>$company_tax,
        'port_of_discharge'=>$port_of_discharge,
        'attend_name'=>$attend_name,
        'customer_id'=>$customer_id,
        'customer_name'=>$customer_name];
        $record=ShippingDestination::updateOrCreate(['add_id' =>$id], $data);

        if ($record) {
                $response_data = array(
                    'message' => 'record insert successfully',
                );
            }
        $json_response = json_encode($response_data);
        return $json_response;
    }

    public function ShowShippingDetails(Request $request) {

        $customerId = $request->customerId;
        $data = ShippingDestination::where('customer_id', $customerId)->get();
        $result = '';

        foreach ($data as $record) {
            $result .= '<tr>
                        <td>'.$record->company_name.'</td>
                        <td>'.$record->address.'</td>
                        <td>'.$record->pincode.'</td>
                        <td>'.$record->phone_no.'</td>
                        <td>'.$record->company_tax.'</td>
                        <td>'.$record->port_of_discharge.'</td>
                        <td>'.$record->attend_name.'</td>
                        <td>'.$record->gst_no.'</td>
                        <td>'.$record->place_of_supply.'</td>
                        <td>
                            <div class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input turnonoff" type="checkbox" value="1" name="showsupplier" data-val="'.$record->add_id.'" data-customer-id="'.$record->customer_id.'" id="flexSwitchDefault" '. ($record->by_default == 1 ? 'checked' : '') .' />
                            </div>
                        </td>
                        <td><button class="btn btn-primary btn-sm  shipping_details_edit" data-val="'.$record->add_id.'"  >Edit</button></td>
                        <td><button class="btn btn-warning btn-sm shipping_details_delete" data-default="'. $record->by_default .'" data-val="'.$record->add_id.'">Delete</button></td>
                    </tr>';
                }

        if (empty($result)) {
            $response_data= array(
                'empty' => true,
            );
        } else {
            $response_data = array(
                'result' => $result,
                'empty' => false,
            );
        }

        $json_response = json_encode($response_data);
        return $json_response;
    }
    public function getShippingDetails(Request $request){
        $id=$request->id;
        $data=ShippingDestination::where('add_id',$id)->first();
        $json_response = json_encode($data);
        return $json_response;
    }
    public function DeleteShippingDetails(Request $request){
        $id=$request->id;
        $data=ShippingDestination::find($id)->delete();
        $json_response = json_encode($data);
        return $json_response;
    }
    public function ShippingAddressDefault(Request $request)
    {
        $customerId = $request->customerId;
        $id = $request->id;
        ShippingDestination::where('customer_id', $customerId)->update(['by_default' => 0]);
        $data = ShippingDestination::where('add_id', $id)->where('customer_id', $customerId)->update(['by_default' => 1]);


        if (empty($data)) {
            $response_data = array(
                'empty' => true,
            );
        } else {
            $response_data = array(
                'result' => $data,
                'empty' => false,
            );
        }
        $json_response = json_encode($response_data);
        return $json_response;
    }


}

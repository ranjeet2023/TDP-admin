<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use DB;
use PDF;
use QrCode;
use Mail;
use Mailable;
use Maatwebsite\Excel\Facades\Excel;

use App\Exports\AllOrderExport;
use App\Helpers\AppHelper;

use App\Models\Admin;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Associates;
use App\Models\Supplier;
use App\Models\Pickups;
use App\Models\DiamondNatural;
use App\Models\DiamondLabgrown;
use App\Models\ShippingDestination;
use App\Models\CurrencyExchange;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Customer;
use App\Models\Cart;
use App\Models\TimelineCycle;
use App\Models\ImgVidRequest;

class AOrderController extends Controller
{
    public function orderList(Request $request)
    {
        $user_type = Auth::user()->user_type;
        $id = Auth::user()->id;

        $url = $request->segment(1);
        $data['permission'] = $permission = AppHelper::userPermission($url);

        $data['countries'] = OrderItem::select('country')->groupBy('country')->get();
        if(empty($data['permission']))
        {
            return redirect('admin')->with('success','Error');
        }

        if(!empty($permission) && ($user_type == 1 || $permission->full == 1)){
            $orders = Order::with('user','orderdetail','pickups:orders_id,status,export_number','qc_list:order_id,qc_comment')
            ->where('is_deleted',0)
            ->where('order_status','!=','RELEASED')
            ->orderBy('created_at', 'desc');
            if(!empty($request->country)){
                $country = $request->country;
                $orders->whereHas('orderdetail',function($query) use($country){$query->where('country','=',$country);});
            }
            if(!empty($request->hold)){
                $hold = $request->hold;
                $orders->where('hold','=',$hold);
            }
            $data['orders'] = $orders->get();

            $order_ids = array_column($data['orders']->toArray(), 'orders_id');
        }
        elseif(!empty($permission) && ($user_type == 5 || 6)){
            $orders = Order::with('user','orderdetail','pickups:orders_id,status,export_number','qc_list:order_id,qc_comment')
            ->whereHas('orderdetail.supplier',function($query) use($id){$query->where('users.added_by','=',$id);})
            ->where('is_deleted',0)
            ->where('order_status','!=','RELEASED')
            ->orderBy('created_at', 'desc');
            if(!empty($request->country)){
                $country = $request->country;
                $orders->whereHas('orderdetail',function($query) use($country){$query->where('country','=',$country);});
            }
            if(!empty($request->hold)){
                $hold = $request->hold;
                $orders->where('hold','=',$hold);
            }
            $data['orders'] = $orders->get();
            $order_ids = array_column($data['orders']->toArray(), 'orders_id');


        }
        else{
            return redirect('admin');
        }

            // dd($data['orders']);
        $getrowcheck = Pickups::with('orders')
                        ->whereHas('orders',function($query){$query->where('orders.is_deleted', 0); })
                        ->whereIn('pickups.orders_id', $order_ids)
                        ->get();

        $data['getrowcheck'] = array_column($getrowcheck->toArray(), 'orders_id');

        return view('admin.orders.order-list')->with($data);
    }

    public function AllOrderExcelDownload(Request $request){
        $id = $request->id;
        $id_array = explode(',',$id);
        $orders = Order::with('user','orderdetail')
            ->where('is_deleted',0)
            ->whereIn('orders_id',$id_array)
            ->where('order_status','!=','RELEASED')
            ->orderBy('created_at', 'desc')->get();
        $filename = date('Y-m-d-His').' - orders.csv';
        $result = Excel::store(new AllOrderExport($orders), $filename);
        $json["file_name"] = $filename;
		return json_encode($json);
    }

    public function allorderList(Request $request)
    {
        $user_type = Auth::user()->user_type;
        $id = Auth::user()->id;

        $url = $request->segment(1);

        $data['permission'] = $permission = AppHelper::userPermission($url);
        $data['countries'] = DB::table('orders_items')->select('country')->groupBy('country')->get();
        if(empty($data['permission']))
        {
            return redirect('admin')->with('success','Error');
        }

        if(!empty($permission) && ($user_type == 1 || $permission->full == 1)){
            $orders = DB::table('orders')
            ->select('orders.*','orders_items.*','users.companyname','pickups.status','pickups.export_number','qc_list.qc_comment')
            ->join('users', 'users.id', '=', 'customer_id')
            ->join('orders_items', 'orders_items.orders_id', '=', 'orders.orders_id')
            ->leftjoin('pickups','pickups.orders_id','=','orders.orders_id')
            ->leftjoin('qc_list','qc_list.order_id','=','orders.orders_id')
            // ->where('orders.is_deleted',0)
            ->where('orders.order_status','!=','RELEASED')
            ->orderBy('orders.created_at', 'desc');
            if(!empty($request->country)){
                $orders = $orders->where('country','=',$request->country);
            }
            $data['orders'] = $orders->get();

            $order_ids = array_column($data['orders']->toArray(), 'orders_id');

            $getrowcheck = DB::table('pickups')
                ->select('orders.orders_id')
                ->join('orders', 'pickups.orders_id', '=', 'orders.orders_id')
                // ->where('orders.is_deleted', 0)
                ->whereIn('pickups.orders_id', $order_ids)
                ->get();

            $data['getrowcheck'] = array_column($getrowcheck->toArray(), 'orders_id');
        }
        elseif(!empty($permission) && ($user_type == 5 || 6)){
            $orders = DB::table('orders')
            ->select('orders.*','orders_items.*','users.companyname','pickups.status','pickups.export_number','qc_list.qc_comment')
            ->join('orders_items', 'orders_items.orders_id', '=', 'orders.orders_id')
            ->leftjoin('pickups','pickups.orders_id','=','orders.orders_id')
            ->leftjoin('qc_list','qc_list.order_id','=','orders.orders_id')
            ->join('users', function($query) use($id){
                $query->on('users.id', '=', 'orders_items.supplier_id');
                $query->where('users.added_by','=',$id);
            })
            // ->where('orders.is_deleted',0)
            ->where('orders.order_status','!=','RELEASED')
            ->orderBy('orders.created_at', 'desc');
            if(!empty($request->country)){
                $orders = $orders->where('country','=',$request->country);
            }
            $data['orders'] = $orders->get();

            $order_ids = array_column($data['orders']->toArray(), 'orders_id');

            $getrowcheck = DB::table('pickups')
                ->select('orders.orders_id')
                ->join('orders', 'pickups.orders_id', '=', 'orders.orders_id')
                // ->where('orders.is_deleted', 0)
                ->whereIn('pickups.orders_id', $order_ids)
                ->get();

            $data['getrowcheck'] = array_column($getrowcheck->toArray(), 'orders_id');
        }
        else{
            return redirect('admin');
        }

        return view('admin.orders.order-list')->with($data);
    }

    public function orderListSales(Request $request){

        $user_type = Auth::user()->user_type;
        $id = Auth::user()->id;

        $url = $request->segment(1);

        $data['permission'] = $permission = AppHelper::userPermission($url);

        $data['countries'] = OrderItem::select('country')->groupBy('country')->get();
        if(empty($data['permission']))
        {
            return redirect('admin')->with('success','Error');
        }

        if(!empty($permission) && ($user_type == 1 || $permission->full == 1)){
            $orders = Order::with('user','orderdetail','pickups','qc_list')
            ->where('is_deleted',0)
            ->where('order_status','!=','RELEASED')
            ->orderBy('created_at', 'desc');
            if(!empty($request->country)){
                $country = $request->country;
                $orders->whereHas('orderdetail',function($query) use($country){$query->where('country','=',$country);});
            }
            if(!empty($request->hold)){
                $hold = $request->hold;
                $orders->where('hold','=',$hold);
            }
            $data['orders'] = $orders->get();

            $order_ids = array_column($data['orders']->toArray(), 'orders_id');
        }
        elseif(!empty($permission) && ($user_type == 5 || 6)){
            $orders = Order::with('user','orderdetail','pickups','qc_list')
            ->whereHas('user',function($query) use($id){$query->where('added_by','=',$id);})
            ->where('is_deleted',0)
            ->where('order_status','!=','RELEASED')
            ->orderBy('created_at', 'desc');
            if(!empty($request->country)){
                $country = $request->country;
                $orders->whereHas('orderdetail',function($query) use($country){$query->where('country','=',$country);});
            }
            if(!empty($request->hold)){
                $hold = $request->hold;
                $orders->where('hold','=',$hold);
            }
            $data['orders'] = $orders->get();

            $order_ids = array_column($data['orders']->toArray(), 'orders_id');


        }
        else{
            return redirect('admin');
        }

        $getrowcheck = Pickups::with('orders')
                        ->whereHas('orders',function($query){$query->where('orders.is_deleted', 0); })
                        ->whereIn('pickups.orders_id', $order_ids)
                        ->get();

        $data['getrowcheck'] = array_column($getrowcheck->toArray(), 'orders_id');

        return view('admin.orders.order-list-sales')->with($data);
    }

    public function cartList(Request $request){
        $user_type = Auth::user()->user_type;
        $id = Auth::user()->id;

        $url = $request->segment(1);
        $data['permission'] = $permission = AppHelper::userPermission($url);

        if(empty($data['permission']))
        {
            return redirect('admin')->with('success','Error');
        }

        if(!empty($permission) && ($user_type == 1 || $permission->full == 1)){
            // $data['customers'] = DB::select("select *, (select companyname from users where users.id = customer_id) as firstname from (select customer_id, created_at, count(id) from `cart` where `is_delete` = 0 order by created_at desc) as sub GROUP BY customer_id");
            // dd($data['customers']);

            $data['customers'] = Cart::select('customer_id',DB::RAW('count(id) as count'), 'cart.created_at')
            ->with('users:id,companyname,added_by,firstname','users.manager:id,firstname')
            ->with('customer:cus_id,country')
            ->where('is_delete',0)->groupBy('customer_id')->get();
        }
        elseif(!empty($permission) && ($user_type == 5 || 6)){

            $data['customers'] = Cart::select('*',DB::RAW('count(id) as count'))
            ->with('users:id,companyname,added_by,firstname','users.manager:id,firstname')
            ->with('customer:cus_id,country')
            ->whereHas('users',function($query) use($id){$query->where('added_by','=',$id);})
            ->where('is_delete',0)->groupBy('customer_id')->get();
        }
        else{
            return redirect('admin');
        }

        return view('admin.orders.cart-list')->with($data);
    }

    public function cartListDetails(Request $request){
        $customer_id = $request->customer_id;

        $cart_list = Cart::where('is_delete',0)->where('customer_id',$customer_id)->get();
        $customer = User::where('id',$customer_id)->first();

        $diamond_result = array();
        foreach ($cart_list as $diamond_detail) {
            if($diamond_detail->diamond_type == 'L')
            {
                $diamond_result[] = Cart::select('diamond_labgrown.*', 'cart.cart_rate', 'cart.price', 'cart.created_at as created_at_cart', DB::raw('(SELECT pricechange FROM markup_setting WHERE (net_dollar BETWEEN `min_range` and max_range)) as aditional_discount'),
                    DB::raw('(SELECT pricechange FROM price_markup_setting WHERE (net_dollar BETWEEN `min_range` and max_range)) as adi_cost'),
                    DB::raw("(SELECT caratprice FROM rap_list WHERE IF(`shape_name` = 'ROUND', shape = 'ROUND', shape != 'ROUND') AND color_name = `color` AND clarity_name = IF(`clarity` = 'FL','IF', `clarity`) AND low_size <= carat AND high_size >= carat) as raprate"))
                    ->join('diamond_labgrown','cart.certificate_no','=','diamond_labgrown.certificate_no')->where('cart.certificate_no',$diamond_detail->certificate_no)->where('cart.customer_id',$customer_id)->where('cart.is_delete',0)->first();
            }
            elseif($diamond_detail->diamond_type == 'W')
            {
                $diamond_result[] = Cart::select('diamond_natural.*', 'cart.cart_rate', 'cart.price', 'cart.created_at as created_at_cart', DB::raw('(SELECT pricechange FROM markup_setting WHERE (net_dollar BETWEEN `min_range` and max_range)) as aditional_discount'),
                        DB::raw('(SELECT pricechange FROM price_markup_setting WHERE (net_dollar BETWEEN `min_range` and max_range)) as adi_cost'),
                        DB::raw("(SELECT caratprice FROM rap_list WHERE IF(`shape_name` = 'ROUND', shape = 'ROUND', shape != 'ROUND') AND color_name = `color` AND clarity_name = IF(`clarity` = 'FL','IF', `clarity`) AND low_size <= carat AND high_size >= carat) as raprate"))
                    ->join('diamond_natural','cart.certificate_no','=','diamond_natural.certificate_no')->where('cart.certificate_no', $diamond_detail->certificate_no)->where('cart.customer_id',$customer_id)->where('cart.is_delete',0)->first();
            }
        }

        if(!empty($diamond_result))
        {
            usort($diamond_result, function($a, $b) {
                return strtotime($b->created_at_cart) <=> strtotime($a->created_at_cart);
            });

            $data['cart_list'] = $diamond_result;
        }

        $render_msg = '';
        $render_msg .= '<table class="table center table-striped table-bordered bulk_action"><thead><tr>'
                . '<th>Shape</th>'
                . '<th>SKU</th>'
                . '<th>Carat</th>'
                . '<th>Col</th>'
                . '<th>Clarity</th>'
                . '<th>Cut</th>'
                . '<th>Pol</th>'
                . '<th>Sym</th>'
                . '<th>Flo</th>'
                . '<th>Lab</th>'
                . '<th>Certificate</th>'
                . '<th>Depth</th>'
                . '<th>Table</th>'
                . '<th>Measurement</th>'
                . '<th>$/CT</th>'
                . '<th>Sell Price</th>'
                . '<th>Date</th>'
                . '<th>Status</th>'
                . '</tr></thead><tbody>';
        $total_a_price = $total_price= 0;
        foreach ($diamond_result as $sale_row) {
                    $render_msg .= '<tr >
                        <td><img width="25" height="25" src="'. asset("assets/images/shape/" . strtolower($sale_row->shape) .".png") .'"></td>';
                        if($sale_row->diamond_type == "L")
                        {
                            $render_msg .= '<td>LG-' . $sale_row->id . '</td>';
                        }
                        else
                        {
                            $render_msg .= '<td>' . $sale_row->id . '</td>';
                        }
                        $render_msg .= '<td>' . $sale_row->carat . '</td>
                        <td>' . $sale_row->color . '</td>
                        <td>' . $sale_row->clarity . '</td>
                        <td>' . $sale_row->cut . '</td>
                        <td>' . $sale_row->polish . '</td>
                        <td>' . $sale_row->symmetry . '</td>
                        <td>' . $sale_row->fluorescence . '</td>
                        <td>' . $sale_row->lab . '</td>
                        <td>' . $sale_row->certificate_no . '</td>
                        <td>' . $sale_row->depth_per . '%</td>
                        <td>' . $sale_row->table_per . '%</td>
                        <td>' . number_format($sale_row->length, 2) . '*' . number_format($sale_row->width, 2) . '*' . number_format($sale_row->depth, 2) . '</td>
                        <td>' . number_format($sale_row->cart_rate,2) . '</td>
                        <td>' . number_format($sale_row->price,2) . '</td>
                        <td>' . $sale_row->created_at_cart . '</td>
                        <td>' . $sale_row->irm_no . '</td>
                        <td>' . (($sale_row->is_delete == 1) ? '<span class="badge badge-danger">Not available</span>' : '<span class="badge badge-info">Available</span>') . '</td>
                    </tr>';
                }
        $render_msg .= "</tbody></table>";
        $responce_array['render_msg'] = $render_msg;

        $responce_array['customer_name'] = $customer->companyname;

        return json_encode($responce_array);
    }

    public function enquiryList(Request $request)
    {
        $user_type = Auth::user()->user_type;
        $id = Auth::user()->id;

        $customers = Admin::select('id')->where('manager',$id)->get()->pluck('id')->toArray();
        $customers[] = $id;

        $permission = AppHelper::userPermission($request->segment(1));

        if($user_type == 1 || $permission->full == 1){
            $data['customers'] = Order::select('orders.*',
                DB::raw('COUNT(IF(orders.order_status="PENDING" AND orders.hold="0",1, NULL)) as pending'),
                DB::raw('COUNT(IF(orders.order_status="REJECT",1, NULL)) as rejected'),
                DB::raw('COUNT(IF(orders.order_status="APPROVED",1, NULL)) as accepted'),
                DB::raw('COUNT(IF(orders.order_status="RELEASED",1, NULL)) as RELEASED'),
                DB::raw('COUNT(IF(orders.hold="1",1, NULL)) as hold'))
            ->with('user:id,companyname,added_by,firstname','user.manager:id,firstname')
            ->where('is_deleted',0)
            ->where('orders.order_status','!=', 'RELEASED')
            ->groupBy('customer_id')
            ->get()
            ->sortBy('user.companyname');
        }
        elseif($user_type == 4 || 5 || 6){
            $data['customers'] = Order::select('orders.*',
                DB::raw('COUNT(IF(orders.order_status="PENDING" AND orders.hold="0",1, NULL)) as pending'),
                DB::raw('COUNT(IF(orders.order_status="REJECT",1, NULL)) as rejected'),
                DB::raw('COUNT(IF(orders.order_status="APPROVED",1, NULL)) as accepted'),
                DB::raw('COUNT(IF(orders.order_status="RELEASED",1, NULL)) as RELEASED'),
                DB::raw('COUNT(IF(orders.hold="1",1, NULL)) as hold'))
            ->with('user:id,companyname,added_by,firstname','user.manager:id,firstname')
            ->whereHas('user',function($query) use($customers) {$query->whereIn('users.added_by',$customers);})
            ->where('orders.is_deleted',0)
            ->where('orders.order_status','!=', 'RELEASED')
            ->groupBy('orders.customer_id')
            ->get()
            ->sortBy('user.companyname');

        }
        else{
            return redirect('admin');
        }

        return view('admin.orders.order-enquiry')->with($data);
    }

    public function enquiryListDetail(Request $request)
    {
        $customer_id = $request->id;

        $user_type = Auth::user()->user_type;
        $id = Auth::user()->id;

        $customers = Admin::select('id')->where('manager',$id)->get()->pluck('id')->toArray();
        $customers[] = $id;

        $data['permission'] = $permission =AppHelper::userPermission('enquiry-list');

        if(!empty($permission) && ($user_type == 1 || $permission->full == 1)){
            $data['orders'] = Order::with('user','orderdetail','pickups','qc_list')
                                ->where('is_deleted',0)
                                ->where('hold',0)
                                ->where('order_status','!=','RELEASED')
                                ->where('customer_id',$customer_id)
                                ->orderBy('created_at', 'desc')
                                ->get();

            $order_ids = array_column($data['orders']->toArray(), 'orders_id');

            $getrowcheck = Order::with('pickups')->where('is_deleted', 0)
            ->whereHas('pickups',function($query)use($order_ids){$query->whereIn('orders_id', $order_ids); })
            ->get();

            $data['getrowcheck']  = array_column($getrowcheck->toArray(), 'orders_id');
        }
        elseif(!empty($permission) && ($user_type == 4||5||6)){

            $data['orders'] = Order::with('user','orderdetail','pickups','qc_list')
                ->whereHas('user',function($query) use($customers) {$query->whereIn('users.added_by',$customers);})
                                ->where('is_deleted',0)
                                ->where('hold',0)
                                ->where('order_status','!=','RELEASED')
                                ->where('customer_id',$customer_id)
                ->orderBy('created_at', 'desc')->get();

        $order_ids = array_column($data['orders']->toArray(), 'orders_id');

        $getrowcheck = Order::with('pickups')->where('is_deleted', 0)
            ->whereHas('pickups',function($query)use($order_ids){$query->whereIn('orders_id', $order_ids); })
            ->get();

        $data['getrowcheck']  = array_column($getrowcheck->toArray(), 'orders_id');
        }
        else{
            return redirect('admin');
        }

        if(count($data['orders']) > 0)
        {
            $data['customer'] = User::with('customer')->where('id',$customer_id)->first();
        }
        else{
            return redirect('enquiry-list');
        }

        return view('admin.orders.order-enquiry-list')->with($data);
    }

    public function AdminUpdatePriorityStatus(Request $request){
        $priority = $request->priority;
        $order_id = $request->order_id;

        Order::where('orders_id',$order_id)->update(['priority' => $priority]);

        $data['success'] = "priority changed to ".$priority ;
        return json_encode($data);
    }

    public function updateEnquiryStatus(Request $request){
        $order_id = $request->order_id;
        // $customer_id = $request->customer_id;
        $certi_no = $request->certi_no;
        $status = $request->status;
        $comment = $request->comment;

        $stonelocation =  $request->stonelocation;
        $certificatelocation = $request->certificatelocation;
        $bgm = $request->bgm;
        $eyeclean =$request->eyeclean;
        $milky = $request->milky;
        $brown =$request->brown;
        $green = $request->green;
        $hold = $request->hold;
        $sold = $request->sold;
        $hold = $request->hold;

        if($hold == "YES")
        {
            $hold = "Hold For other";
        }
        else
        {
            $hold ='Diamond Not Hold';
        }

        if($sold == "YES")
        {
            $sold = "Diamond Sold";
        }
        else
        {
            $sold = 'Diamond Not Sold';
        }
        $reject_reason = $sold.','.$hold;

        $user_id = Auth::user()->id;

        $order = Order::where('orders_id', $order_id)->first();

        if($status == "APPROVED")
        {
            $flow = "Supplier : Pending To Approve";
            Order::where('orders_id', $order_id)
            ->where('certificate_no',$certi_no)->update([
                'supplier_status' => $status,
                'supplier_comment' => $comment,
                'stone_location' => $stonelocation,
                'certificate_location' => $certificatelocation,
                'bgm' => $bgm,
                'eyeclean'=> $eyeclean,
                'milky' => $milky,
                'brown' => $brown,
                'green' => $green,
                'updated_by' => $user_id,
            ]);
        }
        else
        {
            $flow = "Supplier : Pending To Reject";
            Order::where('orders_id', $order_id)
            ->where('certificate_no',$certi_no)->update([
            'supplier_status' => $status,
            'supplier_reject_reson' => $reject_reason,
            'supplier_reject_comment' => $comment,
            'updated_by' => $user_id,
            ]);
        }
        //TimelineCycle::insert([
        //    'order_id' => $order->orders_id,
          //  'certificate_no' => $order->certificate_no,
          //  'user_id' => Auth::user()->id,
          //  'flow' => $flow,
          //  'days_count' => intval((time() - strtotime($order->created_at))/(60*60*24)),
          //  'created_at' => date('Y-m-d H:i:s'),
        //]);

        $data['success'] = true;
		echo json_encode($data);
    }

    public function portEnquiryStatus(Request $request){
        $id = $request->id;
        $port = $request->port;
        Order::where('orders_id',$id)->update(['port' => $port,'updated_at' => date('Y-m-d H:i:s')]);
        $data['success'] = 'Port Added Successfully';
        return json_encode($data);
    }

    Public function AdminInvoiceGeneratedStatus(Request $request){
        $invoice_id = $request->invoice_id;
        DB::table('invoices_perfoma')->where('invoice_id',$invoice_id)->update(['invoice_generate_status'=> 1]);
        $data['success'] = 'Mark as Generated!';
        return json_encode($data);
    }

    public function ViewOrderDetail(Request $request)
    {
        $orders_id = $request->id;

        $value = Order::with('user','orderdetail','pickups','qc_list')
        ->where('orders_id',$orders_id)
        ->first();

        $detail = '';
        if (!empty($value)) {
            $detail = '<div class="d-flex flex-column flex-xl-row">
                        <div class="d-print-none border border-dashed border-gray-300 card-rounded h-lg-100 min-w-md-350px p-9 bg-lighten me-2">
                            <div class="mw-300px">';
                                $detail .= '<img class="changeimage" src="'.$value->orderdetail->image.'" style="border-radius: 20px;padding: 3px;border-radius: 20px;padding: 3px;width: 100%; }"/>';

                                $detail .= '<div class="d-flex col-lg-12 col-md-12" style="padding-left: 2px;cursor:pointer;">';
                                        if(!empty($value->orderdetail->image))
                                            $detail .= '<div class="col-md-2 col-sm-2 col-xs-2" style="width:80px;height:100px;padding: 11px;"><img class="tile clickimage" src="'.$value->orderdetail->image.'" style="border-radius: 6px;height: 40px;">&nbsp;&nbsp;Image</div>';
										if(!empty($value->orderdetail->cloud_heart))
											$detail .= '<div class="col-md-2 col-sm-2 col-xs-2" style="width:80px;height:100px;padding: 11px;"><img class="tile clickimage" src="'.$value->orderdetail->cloud_heart.'" style="width: 60px;height: 60px;">&nbsp;&nbsp;Heart</div>';
										if(!empty($value->orderdetail->cloud_arrow))
											$detail .= '<div class="col-md-2 col-sm-2 col-xs-2" style="width:80px; height:100px;padding: 11px;"><img class="tile clickimage" src="'.$value->orderdetail->cloud_arrow.'" style="width: 60px;height: 60px;">&nbsp;&nbsp;Arrow</div>';
										if(!empty($value->orderdetail->cloud_asset))
											$detail .= '<div class="col-md-2 col-sm-2 col-xs-2" style="width:80px; height:100px;padding: 11px;"><img class="tile clickimage" src="'.$value->orderdetail->cloud_asset.'" style="width: 60px;height: 60px;">&nbsp;&nbsp;Asset</div>';
										if(!empty($value->orderdetail->video))
											$detail .= '<div class="col-md-2 col-sm-2 col-xs-2" style="width:80px; height:100px;padding: 11px;"><a target="_blank" href="'.$value->orderdetail->video.'"><img class="tile" src="' . asset("assets/images/videoicon.png") . '" style="width: 40px;height: 40px;">&nbsp;&nbsp;video</a></div>';
						$detail .= '</div>
                            </div>
                        </div>
                        <div class="d-print-none border border-dashed border-gray-300 card-rounded h-lg-100 min-w-md-250px p-9 bg-lighten me-2">
                            <h6 class="mb-2 fw-boldest text-gray-600 text-hover-primary">Detail</h6>
                            <div class="mw-300px">
                                <div class="d-flex flex-stack mb-3">
                                    <div class="fw-bold pe-10 text-gray-600 fs-7">Stock No</div>
                                    <div class="text-end fw-bolder fs-6 text-gray-800">' . $value->ref_no . '</div>
                                </div>
                                <div class="d-flex flex-stack mb-3">
                                    <div class="fw-bold pe-10 text-gray-600 fs-7">Shape</div>
                                    <div class="text-end fw-bolder fs-6 text-gray-800">' . $value->orderdetail->shape . '</div>
                                </div>
                                <div class="d-flex flex-stack mb-3">
                                    <div class="fw-bold pe-10 text-gray-600 fs-7">Carat</div>
                                    <div class="text-end fw-bolder fs-6 text-gray-800">' . $value->orderdetail->carat . '</div>
                                </div>
                                <div class="d-flex flex-stack mb-3">
                                    <div class="fw-bold pe-10 text-gray-600 fs-7">Color</div>
                                    <div class="text-end fw-bolder fs-6 text-gray-800">' . $value->orderdetail->color . '</div>
                                </div>
                                <div class="d-flex flex-stack mb-3">
                                    <div class="fw-bold pe-10 text-gray-600 fs-7">Clarity</div>
                                    <div class="text-end fw-bolder fs-6 text-gray-800">' . $value->orderdetail->clarity . '</div>
                                </div>
                                <div class="d-flex flex-stack mb-3">
                                    <div class="fw-bold pe-10 text-gray-600 fs-7">Cut</div>
                                    <div class="text-end fw-bolder fs-6 text-gray-800">' . $value->orderdetail->cut . '</div>
                                </div>
                                <div class="d-flex flex-stack mb-3">
                                    <div class="fw-bold pe-10 text-gray-600 fs-7">Polish</div>
                                    <div class="text-end fw-bolder fs-6 text-gray-800">' . $value->orderdetail->polish . '</div>
                                </div>
                                <div class="d-flex flex-stack mb-3">
                                    <div class="fw-bold pe-10 text-gray-600 fs-7">Symmetry</div>
                                    <div class="text-end fw-bolder fs-6 text-gray-800">' . $value->orderdetail->symmetry . '</div>
                                </div>
                                <div class="d-flex flex-stack mb-3">
                                    <div class="fw-bold pe-10 text-gray-600 fs-7">Fluorescence</div>
                                    <div class="text-end fw-bolder fs-6 text-gray-800">' . $value->orderdetail->fluorescence . '</div>
                                </div>
                                <div class="d-flex flex-stack mb-3">
                                    <div class="fw-bold pe-10 text-gray-600 fs-7">Lab</div>
                                    <div class="text-end fw-bolder fs-6 text-gray-800">' . $value->orderdetail->lab . '</div>
                                </div>
                                <div class="d-flex flex-stack mb-3">
                                    <div class="fw-bold pe-10 text-gray-600 fs-7">Certificate</div>
                                    <div class="text-end fw-bolder fs-6 text-gray-800">' . $value->certificate_no . '</div>
                                </div>
                                <div class="d-flex flex-stack mb-3">
                                    <div class="fw-bold pe-10 text-gray-600 fs-7">Last Updated</div>
                                    <div class="text-end fw-bolder fs-6 text-gray-800">' . $value->hold_at . '</div>
                                </div>
                                <div class="d-flex flex-stack mb-3">
                                    <div class="fw-bold pe-10 text-gray-600 fs-7">Manager</div>
                                    <div class="text-end fw-bolder fs-6 text-gray-800">' . @$value->purchase_manager . '</div>
                                </div>
                            </div>
                        </div>
                        <div class="d-print-none border border-dashed border-gray-300 card-rounded h-lg-100 min-w-md-250px p-9 bg-lighten me-2">
                            <h6 class="mb-2 fw-boldest text-gray-600 text-hover-primary">Price</h6>
                            <div class="mw-300px">
                                <div class="d-flex flex-stack">
                                    <div class="fw-bold pe-10 text-gray-600 fs-7">Rap</div>
                                    <div class="text-end fw-bolder fs-6 text-gray-800">' . $value->orderdetail->rap . '</div>
                                </div>
                                <div class="d-flex flex-stack">
                                    <div class="fw-bold pe-10 text-gray-600 fs-7">Discount (%):</div>
                                    <div class="text-end fw-bolder fs-6 text-gray-800">' . $value->buy_rate . '</div>
                                </div>
                                <div class="d-flex flex-stack">
                                    <div class="fw-bold pe-10 text-gray-600 fs-7">$/Ct</div>
                                    <div class="text-end fw-bolder fs-6 text-gray-800">' . $value->buy_rate . '</div>
                                </div>
                                <div class="d-flex flex-stack">
                                    <div class="fw-bold pe-10 text-gray-600 fs-7">Net Price</div>
                                    <div class="text-end fw-bolder fs-6 text-gray-800">' . $value->buy_price . '</div>
                                </div>
                            </div>
                            <h6 class="mb-2 fw-boldest text-gray-600 text-hover-primary  mt-5">Measurements</h6>
                            <div class="mw-300px">
                                <div class="d-flex flex-stack">
                                    <div class="fw-bold pe-10 text-gray-600 fs-7">Measurements</div>
                                    <div class="text-end fw-bolder fs-6 text-gray-800">' . number_format($value->orderdetail->length, 2) . ' x ' . number_format($value->orderdetail->width, 2) . ' x ' . number_format($value->orderdetail->depth, 2) . '</div>
                                </div>
                                <div class="d-flex flex-stack">
                                    <div class="fw-bold pe-10 text-gray-600 fs-7">Depth</div>
                                    <div class="text-end fw-bolder fs-6 text-gray-800">' . $value->orderdetail->depth_per . '</div>
                                </div>
                                <div class="d-flex flex-stack">
                                    <div class="fw-bold pe-10 text-gray-600 fs-7">Table</div>
                                    <div class="text-end fw-bolder fs-6 text-gray-800">' . $value->orderdetail->table_per . '</div>
                                </div>
                                <div class="d-flex flex-stack">
                                    <div class="fw-bold pe-10 text-gray-600 fs-7">Crown Angle</div>
                                    <div class="text-end fw-bolder fs-6 text-gray-800">' . $value->orderdetail->crown_angle . '</div>
                                </div>
                                <div class="d-flex flex-stack">
                                    <div class="fw-bold pe-10 text-gray-600 fs-7">Crown Height</div>
                                    <div class="text-end fw-bolder fs-6 text-gray-800">' . $value->orderdetail->crown_height . '</div>
                                </div>
                                <div class="d-flex flex-stack">
                                    <div class="fw-bold pe-10 text-gray-600 fs-7">Pavilion Angle</div>
                                    <div class="text-end fw-bolder fs-6 text-gray-800">' . $value->orderdetail->pavilion_angle . '</div>
                                </div>
                                <div class="d-flex flex-stack">
                                    <div class="fw-bold pe-10 text-gray-600 fs-7">Pavilion Depth</div>
                                    <div class="text-end fw-bolder fs-6 text-gray-800">' . $value->orderdetail->pavilion_depth . '</div>
                                </div>
                                <div class="d-flex flex-stack">
                                    <div class="fw-bold pe-10 text-gray-600 fs-7">Girdle</div>
                                    <div class="text-end fw-bolder fs-6 text-gray-800"></div>
                                </div>
                                <div class="d-flex flex-stack">
                                    <div class="fw-bold pe-10 text-gray-600 fs-7">Culet</div>
                                    <div class="text-end fw-bolder fs-6 text-gray-800"></div>
                                </div>
                                <div class="d-flex flex-stack">
                                    <div class="fw-bold pe-10 text-gray-600 fs-7">Eye Clean</div>
                                    <div class="text-end fw-bolder fs-6 text-gray-800"></div>
                                </div>
                                <div class="d-flex flex-stack">
                                    <div class="fw-bold pe-10 text-gray-600 fs-7">Milky</div>
                                    <div class="text-end fw-bolder fs-6 text-gray-800"></div>
                                </div>
                                <div class="d-flex flex-stack">
                                    <div class="fw-bold pe-10 text-gray-600 fs-7">Shade</div>
                                    <div class="text-end fw-bolder fs-6 text-gray-800"></div>
                                </div>
                                <div class="d-flex flex-stack">
                                    <div class="fw-bold pe-10 text-gray-600 fs-7">Customer Comment</div>
                                    <div class="text-end fw-bolder fs-6 text-gray-800">' . $value->customer_comment . '</div>
                                </div>
                                <div class="d-flex flex-stack">
                                    <div class="fw-bold pe-10 text-gray-600 fs-7">Customer ION</div>
                                    <div class="text-end fw-bolder fs-6 text-gray-800">' . $value->irm_no . '</div>
                                </div>
                            </div>
                        </div>
                        <div class="d-print-none border border-dashed border-gray-300 card-rounded h-lg-100 min-w-md-250px p-9 bg-lighten me-2">
                            <h6 class="mb-2 fw-boldest text-gray-600 text-hover-primary">Supplier Status</h6>
                            <div class="mw-300px">
                                <div class="d-flex flex-stack">
                                    <div class="fw-bold pe-10 text-gray-600 fs-7">stone</div>
                                    <div class="text-end fw-bolder fs-6 text-gray-800">' . $value->stone_location . '</div>
                                </div>
                                <div class="d-flex flex-stack">
                                    <div class="fw-bold pe-10 text-gray-600 fs-7">certificate:</div>
                                    <div class="text-end fw-bolder fs-6 text-gray-800">' . $value->certificate_location . '</div>
                                </div>
                                <div class="d-flex flex-stack">
                                    <div class="fw-bold pe-10 text-gray-600 fs-7">bgm</div>
                                    <div class="text-end fw-bolder fs-6 text-gray-800">' . $value->bgm . '</div>
                                </div>
                                <div class="d-flex flex-stack">
                                    <div class="fw-bold pe-10 text-gray-600 fs-7">eyeclean</div>
                                    <div class="text-end fw-bolder fs-6 text-gray-800">' . $value->eyeclean . '</div>
                                </div>
                                <div class="d-flex flex-stack">
                                    <div class="fw-bold pe-10 text-gray-600 fs-7">milky</div>
                                    <div class="text-end fw-bolder fs-6 text-gray-800">' . $value->milky . '</div>
                                </div>
                                <div class="d-flex flex-stack">
                                    <div class="fw-bold pe-10 text-gray-600 fs-7">brown</div>
                                    <div class="text-end fw-bolder fs-6 text-gray-800">' . $value->brown . '</div>
                                </div>
                                <div class="d-flex flex-stack">
                                    <div class="fw-bold pe-10 text-gray-600 fs-7">green</div>
                                    <div class="text-end fw-bolder fs-6 text-gray-800">' . $value->green . '</div>
                                </div>
                                <div class="d-flex flex-stack">
                                    <div class="fw-bold pe-10 text-gray-600 fs-7">comment</div>
                                    <div class="text-end fw-bolder fs-6 text-gray-800">' . $value->supplier_comment . '</div>
                                </div>
                                <div class="d-flex flex-stack">
                                    <div class="fw-bold pe-10 text-gray-600 fs-7">reject reason</div>
                                    <div class="text-end fw-bolder fs-6 text-gray-800">' . $value->reject_reason . '</div>
                                </div>
                            </div>

                            <h6 class="mb-2 fw-boldest text-gray-600 text-hover-primary mt-5">Qc Comment</h6>
                            <div class="mw-300px">
                                <div class="d-flex flex-stack">
                                    <div class="fw-bold pe-10 text-gray-600 fs-7">QC Comment</div>
                                    <div class="text-end fw-bolder fs-6 text-gray-800">' . optional($value->qc_list)->qc_comment . '</div>
                                </div>
                                <div class="d-flex flex-stack">
                                    <div class="fw-bold pe-10 text-gray-600 fs-7">Pickup Status:</div>
                                    <div class="text-end fw-bolder fs-6 text-gray-800">' . optional($value->pickups)->status . '</div>
                                </div>
                            </div>
                        </div>
                    </div>';
            $data['detail'] = $detail;
        } else {
            $data['error'] = false;
        }

        echo json_encode($data);
    }

    public function OrderReverseDiamond(Request $request)
    {
        $orders_id = $request->orders_id;
		$diamond = OrderItem::where('orders_id', $orders_id)->first();

		$loatno = $shape = $weight = $color = $clarity = $lab = '';
		if(!empty($diamond))
		{
			$set = array('order_status' => 'PENDING');
            Order::where('orders_id', $orders_id)->update($set);
			$data['success'] = true;
		}
		else
		{
			$data['success'] = false;
		}
		echo json_encode($data);
    }

    public function OrderReleaseDiamond(Request $request)
    {
        $orders_id = $request->orders_id;
        $customer_id = $request->customer_id;
        $orders_ids = (explode(",", $orders_id));

		$diamonds = OrderItem::whereIn('orders_id', $orders_ids)->where('customer_id', $customer_id)->get();

		// $loatno = $shape = $weight = $color = $clarity = $lab = '';
		if(!empty($diamonds))
		{
            foreach($diamonds as $diamond)
            {
                $set = array('status' => '0');
                if($diamond->diamond_type == "W")
                {
                    DiamondNatural::where('certificate_no', $diamond->certificate_no)->update($set);
                }
                elseif($diamond->diamond_type == "L")
                {
                    DiamondLabgrown::where('certificate_no', $diamond->certificate_no)->update($set);
                }
            }

            $set = array('order_status' => 'RELEASED');
            Order::whereIn('orders_id', $orders_ids)
                    ->where('customer_id', $customer_id)->update($set);
			$data['success'] = true;
		}
		else
		{
			$data['success'] = false;
		}
		echo json_encode($data);
    }

    public function adminInternalConfirmation(Request $request){
        $order_id = $request->order_id;
        $status = $request->status;
        Order::where('orders_id',$order_id)->update(['internal_confirmation' => $status]);
        $data['success'] = true;
        return json_encode($data);
    }

    public function updateOrderStatus(Request $request)
    {
        $customer_id = $request->customer_id;
		$orders_id = $request->orders_id;
		$order_status = $request->order_status;
		$certino = $request->certino;
		$comment = $request->comment;
        $date = date('Y-m-d H:i:s');
        if($order_status == 'QCRETURN'){
            $update = array("updated_at" => $date,'status' => 'PENDING','updated_by' => Auth::user()->id);
            Pickups::where('orders_id', $orders_id)->update($update);
        }

		$diamond = OrderItem::where('orders_id', $orders_id)->first();
        $body = $diamond->lab.'-'.$diamond->certificate_no.' '.$diamond->shape.' '.$diamond->carat.' '.$diamond->color.' '.$diamond->clarity;
        $supplier = User::where('id',$diamond->supplier_id)->select('mobile','firstname')->first();

        if($order_status == 'APPROVED'){
            $body .= ' diamond is approved';
            $title = 'Order Diamond Approved';
        }elseif($order_status == 'REJECT'){
            $body .= ' diamond is rejected';
            $title = 'Order Diamond Rejected';
            $template_name = 'customer_reject_sent_supp';
            $variables = [$supplier->firstname,$certino];
            $notification = Apphelper::Whatsapp_message($supplier->mobile,$template_name,$variables);
        }

        $return = AppHelper::setNotification($customer_id,$title,$body,$date);
        if($return == 'success')
        {
            $loatno = $shape = $weight = $color = $clarity = $lab = '';
            if(!empty($diamond))
            {
                $singlerecord = '<tr style="padding: 15px 15px;" height="30">
                                    <td style="border: #000 solid thin;font-weight: bold;">' . $diamond->ref_no . '</td>
                                    <td style="border: #000 solid thin;font-weight: bold;">' . $diamond->shape . '</td>
                                    <td style="border: #000 solid thin;font-weight: bold;">' . $diamond->carat . '</td>
                                    <td style="border: #000 solid thin;font-weight: bold;">' . $diamond->color . '</td>
                                    <td style="border: #000 solid thin;font-weight: bold;">' . $diamond->clarity . '</td>
                                    <td style="border: #000 solid thin;font-weight: bold;">$' . $diamond->orignal_rate . '</td>
                                    <td style="border: #000 solid thin;font-weight: bold;">$' . round($diamond->orignal_rate * $diamond->carat, 2). '</td>
                                    <td style="border: #000 solid thin;font-weight: bold;">' . $diamond->lab . '</td>
                                    <td style="border: #000 solid thin;font-weight: bold;">' . $diamond->certificate_no . '</td>
                                </tr>';

                // if ($statsusid == "3") {
                // 	if($diamond->diamond_type == "L")
                // 	{
                // 		$this->db->query("UPDATE `lab_diamond_master` SET is_delete = 1, `Location` = 17 WHERE Certi_NO = '" . $cerino . "'");
                // 	}
                // 	else
                // 	{
                // 		$this->db->query("UPDATE `diamond_master` SET is_delete = 1, `Location` = 17 WHERE Certi_NO = '" . $cerino . "'");
                // 	}
                // 	$comment = 'Approved';
                // 	$this->db->query("UPDATE `conform_goods` SET `status` = '2' WHERE `orders_id` != '" . $id . "' AND `certi_no`='$cerino' AND `is_deleted`='0' "); //multiple diamond
                // }

                // if($statsusid == "2"){

                // 	$sale_query = $this->db->query("SELECT email, brokeremail, cc_email, (SELECT email FROM admin WHERE id = sales_person) as salesemail FROM supplier WHERE supplier_name = '$cname' ");
                // 	$sale_result = $sale_query->result();

                // 	$config['protocol'] = 'sendmail';
                // 	$config['mailpath'] = '/usr/sbin/sendmail';
                // 	$config['charset'] = 'utf-8';
                // 	$config['wordwrap'] = TRUE;
                // 	$config['mailtype'] = 'html';
                // 	$this->email->initialize($config);

                // 	$this->email->to($sale_result[0]->email);
                // 	$this->email->cc(array($sale_result[0]->salesemail, $sale_result[0]->brokeremail));
                // 	$this->email->from('supplier@thediamondport.com');
                // 	$this->email->subject("You have a release request No ".$id);
                // // 	$this->email->message('<div style="text-align: center;">
                // // 										<table class="tablepad" style="border: #000 solid thin;  border-collapse: collapse;" >
                // // 											<tr height="30">
                // // 												<td style="border: #000 solid thin; ">Stock Id</td>
                // // 												<td style="border: #000 solid thin; ">Shape</td>
                // // 												<td style="border: #000 solid thin; ">Carat</td>
                // // 												<td style="border: #000 solid thin; ">Color</td>
                // // 												<td style="border: #000 solid thin; ">Clarity</td>
                // // 												<td style="border: #000 solid thin; ">PPC</td>
                // // 												<td style="border: #000 solid thin; ">Total</td>
                // // 												<td style="border: #000 solid thin; ">Lab</td>
                // // 												<td style="border: #000 solid thin; ">Report No.</td>
                // // 											</tr>
                // // 											'.$singlerecord.'
                // // 										</table>
                // // 									</div>');
                // // 	//$this->email->send();
                // 	$this->db->query("UPDATE `hold_diamonds` SET `confirm_status` = 4 WHERE Certi_NO = '" . $cerino . "'");
                // 	$comment = 'Rejected';
                // }

                if($order_status=='QCREJECT'){
                    $set = array('supplier_status' => $order_status, 'order_status' => $order_status);
                    DB::table('qc_list')->updateOrInsert([
                        'order_id' => $orders_id,
                    ],[
                        'qc_comment' => $comment,
                    ]);
                }
                else
                {
                    $set = array('order_status' => $order_status , 'reject_reason' => $comment);// 'supplier_status' => $order_status,
                }
                Order::where('orders_id', $orders_id)->update($set);
                $data['success'] = true;
            }
            else{
                $data['success'] = false;
            }
        }
		else
		{
            $data['success'] = false;
		}
		echo json_encode($data);
    }

    public function updateHoldStatus(Request $request)
    {
        $customer_id = $request->customer_id;
		$orders_id = $request->orders_id;
		$order_status = $request->order_status;
		$certino = $request->certino;
		$comment = $request->comment;

		$diamond = OrderItem::where('orders_id', $orders_id)->first();

		$loatno = $shape = $weight = $color = $clarity = $lab = '';
		if(!empty($diamond))
		{
			$singlerecord = '<tr style="padding: 15px 15px;" height="30">
								<td style="border: #000 solid thin;font-weight: bold;">' . $diamond->ref_no . '</td>
								<td style="border: #000 solid thin;font-weight: bold;">' . $diamond->shape . '</td>
								<td style="border: #000 solid thin;font-weight: bold;">' . $diamond->carat . '</td>
								<td style="border: #000 solid thin;font-weight: bold;">' . $diamond->color . '</td>
								<td style="border: #000 solid thin;font-weight: bold;">' . $diamond->clarity . '</td>
								<td style="border: #000 solid thin;font-weight: bold;">$' . $diamond->orignal_rate . '</td>
								<td style="border: #000 solid thin;font-weight: bold;">$' . round($diamond->orignal_rate * $diamond->carat, 2). '</td>
								<td style="border: #000 solid thin;font-weight: bold;">' . $diamond->lab . '</td>
								<td style="border: #000 solid thin;font-weight: bold;">' . $diamond->certificate_no . '</td>
							</tr>';

			// if ($statsusid == "3") {
			// 	if($diamond->diamond_type == "L")
			// 	{
			// 		$this->db->query("UPDATE `lab_diamond_master` SET is_delete = 1, `Location` = 17 WHERE Certi_NO = '" . $cerino . "'");
			// 	}
			// 	else
			// 	{
			// 		$this->db->query("UPDATE `diamond_master` SET is_delete = 1, `Location` = 17 WHERE Certi_NO = '" . $cerino . "'");
			// 	}
			// 	$comment = 'Approved';
			// 	$this->db->query("UPDATE `conform_goods` SET `status` = '2' WHERE `orders_id` != '" . $id . "' AND `certi_no`='$cerino' AND `is_deleted`='0' "); //multiple diamond
			// }

			// if($statsusid == "2"){

			// 	$sale_query = $this->db->query("SELECT email, brokeremail, cc_email, (SELECT email FROM admin WHERE id = sales_person) as salesemail FROM supplier WHERE supplier_name = '$cname' ");
			// 	$sale_result = $sale_query->result();

			// 	$config['protocol'] = 'sendmail';
			// 	$config['mailpath'] = '/usr/sbin/sendmail';
			// 	$config['charset'] = 'utf-8';
			// 	$config['wordwrap'] = TRUE;
			// 	$config['mailtype'] = 'html';
			// 	$this->email->initialize($config);

			// 	$this->email->to($sale_result[0]->email);
			// 	$this->email->cc(array($sale_result[0]->salesemail, $sale_result[0]->brokeremail));
			// 	$this->email->from('supplier@thediamondport.com');
			// 	$this->email->subject("You have a release request No ".$id);
			// // 	$this->email->message('<div style="text-align: center;">
			// // 										<table class="tablepad" style="border: #000 solid thin;  border-collapse: collapse;" >
			// // 											<tr height="30">
			// // 												<td style="border: #000 solid thin; ">Stock Id</td>
			// // 												<td style="border: #000 solid thin; ">Shape</td>
			// // 												<td style="border: #000 solid thin; ">Carat</td>
			// // 												<td style="border: #000 solid thin; ">Color</td>
			// // 												<td style="border: #000 solid thin; ">Clarity</td>
			// // 												<td style="border: #000 solid thin; ">PPC</td>
			// // 												<td style="border: #000 solid thin; ">Total</td>
			// // 												<td style="border: #000 solid thin; ">Lab</td>
			// // 												<td style="border: #000 solid thin; ">Report No.</td>
			// // 											</tr>
			// // 											'.$singlerecord.'
			// // 										</table>
			// // 									</div>');
			// // 	//$this->email->send();
			// 	$this->db->query("UPDATE `hold_diamonds` SET `confirm_status` = 4 WHERE Certi_NO = '" . $cerino . "'");
			// 	$comment = 'Rejected';
			// }

            if($order_status == 'REJECT')
            {
                $set = array('order_status' => $order_status, 'hold_status' => $order_status, 'reject_reason' => $comment);
            }
            else
            {
                $set = array('hold_status' => $order_status, 'reject_reason' => $comment);
            }
            Order::where('orders_id', $orders_id)->update($set);
			$data['success'] = true;
		}
		else
		{
			$data['success'] = false;
		}
		echo json_encode($data);
    }

    public function UpdateOrderPrice(Request $request) {

        $orders_id = $request->orders_id;
        $sale_price = $request->sale_price;
        $buy_price = $request->buy_price;
        $carat = $request->carat;

        $data['success'] = false;
        if(!empty($sale_price) && !empty($buy_price))
		{
            $value = Order::with('orderdetail')
            ->where('is_deleted',0)
            ->where('orders_id', $orders_id)
            ->first();

            if($sale_price < $value->buy_price)
            {
                $data['message'] = "Sale Price is less then Buy price";
                $data['success'] = false;
            }
            elseif($buy_price > $value->sale_price)
            {
                $data['message'] = "Buy Price is grater then Sale price";
                $data['success'] = false;
            }
            else
            {
                if(!empty($carat))
                {
                    $sale_rate = $sale_price / $carat;
                    $buy_rate = $buy_price / $carat;
                }
                $set_array = array('buy_price' => $buy_price, 'buy_rate' => $buy_rate, 'sale_price' => $sale_price, 'sale_rate' => $sale_rate, 'updated_by' => Auth::user()->id);
                Order::where('orders_id', $orders_id)->update($set_array);

                $data['success'] = true;
                $data['message'] = "Price Updated Successfully.";
            }
		}
		else
		{
			$data['error'] = "Price is empty";
		}
		return json_encode($data);
    }

    public function confirmToSupplierPopup(Request $request) {
		$date = date('m-d-Y');

		$render_msg = '';
		$orders_id = $request->orders_id;
		$orders_ids = (explode(",", $orders_id));

        $getrowcheck = Pickups::with('orders')
            ->whereHas('orders',function($query){ $query->where('orders.is_deleted', 0); })
            ->whereIn('orders_id',$orders_ids)
            ->count();

		if ($getrowcheck == 0) {

            $orders = Order::with('orderdetail')
            ->where('is_deleted', 0)
            ->whereIn('orders_id',$orders_ids)
            ->get();

            $render_msg .= '<table class="table center table-striped table-bordered bulk_action"><thead>'
				. '<th>Shape</th>'
				. '<th style="width: 150px;">Pickup Date</th>'
				. '<th>Location</th>'
				. '<th>Twizzer Video</th>'
				. '<th>SKU</th>'
				. '<th>Carat</th>'
				. '<th>Col</th>'
				. '<th>Clarity</th>'
				. '<th>Cut</th>'
				. '<th>Pol</th>'
				. '<th>Sym</th>'
				. '<th>Flo</th>'
				. '<th>Lab</th>'
				. '<th>Certificate</th>'
                . '<th>Table</th>'
				. '<th>Depth</th>'
				. '<th>Sale %</th>'
				. '<th>Sale Price</th>'
				. '<th>Buy Dis</th>'
				. '<th>Buy Price</th>'
				. '</thead><tbody>';

            $totalstone = $TotalCarat = $TotalAPerCarat = $TotalAPrice = 0;
            foreach ($orders as $value) {
				$net_price = $value->sale_price;
				$carat_price = round($value->sale_price / $value->orderdetail->carat, 2);
				$discount_main = ($value->orderdetail->rap != 0) ? (round(($carat_price - $value->orderdetail->rap) / $value->orderdetail->rap * 100, 2)) : '0';

				// carat Price change
				$a_carat_price = $value->rate;
				$a_net_price = round($a_carat_price * $value->orderdetail->carat, 2);
				$a_discount_main = ($value->orderdetail->rap != 0) ? (round(($a_carat_price - $value->orderdetail->rap) / $value->orderdetail->rap * 100, 2)) : '0';
                $TotalCarat += $value->orderdetail->carat;
                $TotalAPerCarat += $value->buy_rate;
                $TotalAPrice += $value->buy_price;

				if (!empty($value->a_price) && $value->a_discount != "") {
					$a_discount_main = $value->a_discount;
					$a_carat_price = $value->orderdetail->rate;
					$a_net_price = $value->a_price;
				}

				$discount_input = '<span id="disc_lbl_' . $value->orders_id . '">' . $discount_main . '%</span><input class="form-control discount_change_input" id="discount_change_' . $value->orders_id . '" data-net="' . $net_price . '"  data-carat="' . $value->orderdetail->carat . '" data-rap="' . $value->orderdetail->rap . '"  data-id="' . $value->orders_id . '"     value="' . $discount_main . '" type="text" size="4" style="min-width:95px;display:none;">';
				$a_discount_input = '<span id="a_disc_lbl_' . $value->orders_id . '">' . $a_discount_main . '%</span><input class="form-control a_discount_change_input" id="a_discount_change_' . $value->orders_id . '" data-net="' . $a_net_price . '"  data-carat="' . $value->orderdetail->carat . '" data-rap="' . $value->orderdetail->rap . '"  data-id="' . $value->orders_id . '" value="' . $a_discount_main . '" type="text" size="4" style="min-width:95px;display:none;">';
				$netprice_input = '';//'<span id="price_hidden_' . $value->orders_id . '">$' . $net_price . '</span><input class="form-control price_change_input" id="price_change_' . $value->orders_id . '" data-net="' . $net_price . '"  data-carat="' . $value->carat . '" data-rap="' . $value->C_Rap . '" data-id="' . $value->orders_id . '"   value="' . $net_price . '" type="number" size="4" style="min-width:110px;display:none;">';
				$a_netprice_input = '';//'<span id="a_price_hidden_' . $value->orders_id . '">$' . $a_net_price . '</span><input class="form-control a_price_change_input" id="a_price_change_' . $value->orders_id . '" data-net="' . $a_net_price . '"  data-carat="' . $value->carat . '" data-rap="' . $value->C_Rap . '" data-id="' . $value->orders_id . '"   value="' . $a_net_price . '" type="number" size="4" style="min-width:110px;display:none;">';
				$datechange = '<input name="pickup_date" class="date-picker pickup_date form-control" value=' . $date . ' type="text" id=' . $value->orders_id . '  >';


				$render_msg .= '<tr class="pickup_row" data-id="'.$value->orders_id.'" >
                    <td><img width="25" height="25" src="' . asset("assets/images/shape/" . strtolower($value->orderdetail->shape) .".png") . '"></td>
                    <td>' . $datechange . '</td>
                    <td>
                    <select name="city" class="city form-select">
                        <option value="">Select Location</option>
                        <option value="Surat">Surat</option>
                        <option value="Mumbai">Mumbai</option>
                        <option value="Hongkong">Hongkong</option>
                        <option value="USA">USA</option>
                        <option value="Direct Ship Hongkong">Direct Ship Hongkong</option>
                        <option value="Direct Ship USA">Direct Ship USA</option>
                        <option value="CANADA">CANADA</option>
                        <option value="ISRAEL">ISRAEL</option>
                        <option value="UK">UK</option>
                        <option value="UAE">UAE</option>
                        <option value="BELGIUM">BELGIUM</option>
                    </select>
                    </td>
                    <td><input type="checkbox" class="form-check-input twizzer_video" id="twizzer_video" ></td>
                    <td>' . $value->orderdetail->id . '</td>
                    <td>' . $value->orderdetail->carat . '</td>
                    <td>' . $value->orderdetail->color . '</td>
                    <td>' . $value->orderdetail->clarity . '</td>
                    <td>' . $value->orderdetail->cut . '</td>
                    <td>' . $value->orderdetail->polish . '</td>
                    <td>' . $value->orderdetail->symmetry . '</td>
                    <td>' . $value->orderdetail->fluorescence . '</td>
                    <td>' . $value->orderdetail->lab . '</td>
                    <td>' . $value->certificate_no . '</td>
                    <td>' . $value->orderdetail->table_per . '%</td>
                    <td>' . $value->orderdetail->depth_per . '%</td>

                    <td>' . $value->sale_discount . '</td>
                    <td>' . $value->sale_price . '</td>
                    <td>' . $value->buy_discount . '</td>
                    <td>' . $value->buy_price . '</td>
				</tr>';
                $totalstone++;
			}
			$render_msg .= "</tbody></table>";
			$data['render_msg'] = $render_msg;
            $data['totalstone'] = $totalstone;
            $data['TotalCarat'] = $TotalCarat;
            $data['TotalAPerCarat'] = $TotalAPerCarat;
            $data['TotalAPrice'] = $TotalAPrice;
		} else {
			$data['error'] = false;
		}

		echo json_encode($data);
	}

    public function confirmToSupplier(Request $request) {
        $temp_data = $request->data;
        $array_values = json_decode($temp_data);

        $detail = '';
        if(!empty($array_values))
        {
            $date = date('Y-m-d H:i:s');

            // $export_price = $this->Model->ci_get_where_row('admin_setting', array('parameter' => 'export_price'));
            // if($export_price != ''){
                // 	$export_price = $export_price->value;
                // }else{
                    $export_price = 0;
                    // }


            foreach ($array_values as $getid) {
                $orders_id = $getid->id;
                $qc_done = Pickups::where('orders_id',$orders_id)->first();
                if($qc_done == null){
                    $pickupdate = date('Y-m-d', strtotime($getid->dateval));
                    $tabledata = '';
                    $row = Order::select('orders.*','orders.created_at as orders_created_at' ,'orders_items.*', DB::raw("(SELECT caratprice FROM rap_list WHERE IF(`shape_name` = 'ROUND', shape = 'ROUND', shape != 'ROUND') AND color_name = `color` AND clarity_name = IF(`clarity` = 'FL','IF', `clarity`) AND low_size <= carat AND high_size >= carat) as raprate"))
                    ->join('orders_items', 'orders_items.orders_id', '=', 'orders.orders_id')
                    ->where('orders.is_deleted', 0)
                    ->where('orders.orders_id', $orders_id)
                    ->first();

                    if(!empty($getid->twizzer_video) && $getid->twizzer_video == 1){
                        ImgVidRequest::insert([
                            'cus_id'=> $row->customer_id,
                            'certificate_no'=> $row->certificate_no,
                            'sup_id'=> $row->supplier_id,
                            'diamond_type'=> $row->diamond_type,
                            'comment'=> 'Twizzer Video Request',
                            'created_at' => date('Y-m-d H:i:s'),
                        ]);
                    }

                    // TimelineCycle::insert([
                    //     'order_id' => $row->orders_id,
                    //     'certificate_no' => $row->certificate_no,
                    //     'user_id' => Auth::user()->id,
                    //     'flow' => 'Requested For QC',
                    //     'days_count' => intval((time() - strtotime($row->orders_created_at))/(60*60*24)),
                    //     'created_at' => date('Y-m-d H:i:s'),
                    // ]);
                    $tabledata = '  <table width="100%" style="border:#CEC9C9 solid thin;padding: 10px;font-size: 16px;">
                                        <tr>
                                            <td width="25%">
                                                <span><a href="" style="text-decoration-color: #4f4f4f"><strong>'.$row->ref_no.'</strong></a></span>
                                            </td>
                                            <td width="30%">
                                                <span><strong>'.$row->lab.': </strong><a href="" style="text-decoration-color: #4f4f4f"> <strong> '.$row->certificate_no.'</strong></a></span>
                                            </td>
                                            <td width="30%" align="right"> <strong> $/CT &nbsp;$'. number_format($row->orignal_rate, 2).'</strong></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" width="70%">
                                                <span style="font-weight: 600">'.$row->shape.' '.$row->carat.'CT '.$row->color.' '.$row->clarity.' '.$row->cut.' '.$row->polish.' '.$row->symmetry.' '.$row->fluorescence.'</span>
                                            </td>
                                            <td width="30%" align="right"><strong>Total &nbsp;$' . number_format($row->orignal_rate * $row->carat, 2) . '</strong></td>
                                        </tr>
                                    </table>';
                    $supplier = User::where('id',$row->supplier_id)->first();
                    $email_data['location'] = $getid->city;
                    $email_data['table_html'] = $tabledata;
                    $email_data['firstname'] = $supplier->firstname;

                    $email_attachments['sup_email'] = $supplier->email;
                    $email_attachments['certificate'] = $row->certificate_no;
                    $email_attachments['lab'] = $row->lab;

                    try {
                        Mail::send('emails.qc_request', $email_data, function($message) use($email_attachments){
                            $message->from(\Cons::EMAIL_SUPPLIER, 'Supplier');
                            $message->to($email_attachments['sup_email']);
                            $message->cc(\Cons::EMAIL_SUPPLIER);
                            $message->subject("Please Send Stone For QC- ". $email_attachments['lab'] ." - ". $email_attachments['certificate'] ." | ". config('app.name'));
                        });
                    } catch (\Throwable $th) {

                    }

                    if($getid->city == 'Direct Ship Hongkong'){
                        $city ='Hongkong';
                    }
                    elseif($getid->city == 'Direct Ship USA'){
                        $city ='USA';
                    }
                    elseif($getid->city == 'Mumbai' || $getid->city == 'Surat' ){
                        $city = 'Mumbai';
                    }
                    else{
                        $city = $getid->city;
                    }
                    $data_pickup = array(
                        'orders_id' => $orders_id,
                        'cerificate_no' => $row->certificate_no,
                        'customer_id' => $row->customer_id,
                        'updated_by' => Auth::user()->id,
                        'expected_delivery_at' => $pickupdate,
                        'location' => $city,
                        'status' => 'PENDING',
                        'destination' => $city,
                        'created_at' => $date,
                    );
                    Pickups::insert($data_pickup);

                    $flag = true;
                }
                else{
                    $flag = false;
                }
            }
            if($flag == true){
                $data['success'] = "QC Request Send to supplier successfully.";
            }
            elseif($flag == false){
                $data['error'] = "QC Request Already Sent!";
            }
        }
        else
        {
            $data['error'] = "Fail to confirm to supplier.";
        }
        return json_encode($data);
    }

    public function invoicePopupPrepare(Request $request) {
		$date = date('m-d-Y');

		$render_msg = '';
		$certi_no = $request->certi_no;
        $customer_id = $request->customer_id;
        // $customer_data = Customer::where('cus_id', $customer_id)->first();
        // $discount_user = $customer_data->discount;

        $orders_id = (explode(",", $request->orders_id));
        $getrowcheck = Pickups::whereIn('pickups.orders_id', $orders_id)->count();

        if ($getrowcheck == count($orders_id)) {

            $associate = Associates::all();

            $addresses = ShippingDestination::where('customer_id',$customer_id)->get();

            $get_value = Order::with('orderdetail')->whereIn('orders.orders_id',$orders_id);

            if($customer_id != 0)
            {
                $get_value->where('customer_id', $customer_id);
            }
            $get_value = $get_value->get();

            $render_msg = '';
            $render_msg .= '<table class="table table-condensed table-hover"><thead><tr class="fw-bolder fs-6 text-gray-800 px-7">'
                    . '<th>Shape</th>'
                    . '<th>SKU</th>'
                    . '<th>Carat</th>'
                    . '<th>Col</th>'
                    . '<th>Clarity</th>'
                    . '<th>Cut</th>'
                    . '<th>Pol</th>'
                    . '<th>Sym</th>'
                    . '<th>Flo</th>'
                    . '<th>Lab</th>'
                    . '<th>Measurement</th>'
                    . '<th>Certificate</th>'
                    . '<th>Depth</th>'
                    . '<th>Table</th>'
                    . '<th>Discount</th>'
                    . '<th>Price</th>'
                    . '</tr></thead><tbody>';

            $total_a_price = $total_price= 0;
            foreach ($get_value as $sale_row) {
                $discount_main = $sale_row->sale_discount;
                $net_price = $sale_row->sale_price;
                $b_price = $sale_row->buy_price;

                $total_a_price += $b_price;
                $total_price += $net_price;

                $render_msg .= '<tr >
                    <td><img width="25" height="25" src="'. asset("assets/images/shape/" . strtolower($sale_row->orderdetail->shape) .".png") .'"> ' . $sale_row->orderdetail->shape . '</td>
                    ';
                    if($sale_row->diamond_type == "L")
                    {
                        $render_msg .= '<td>LG-' . $sale_row->orderdetail->id . '</td>';
                    }
                    else
                    {
                        $render_msg .= '<td>' . $sale_row->orderdetail->id . '</td>';
                    }
                    $render_msg .= '<td>' . $sale_row->orderdetail->carat . '</td>
                    <td>' . $sale_row->orderdetail->color . '</td>
                    <td>' . $sale_row->orderdetail->clarity . '</td>
                    <td>' . $sale_row->orderdetail->cut . '</td>
                    <td>' . $sale_row->orderdetail->polish . '</td>
                    <td>' . $sale_row->orderdetail->symmetry . '</td>
                    <td>' . $sale_row->orderdetail->fluorescence . '</td>
                    <td>' . $sale_row->orderdetail->lab . '</td>
                    <td>' . number_format($sale_row->orderdetail->length, 2) . '*' . number_format($sale_row->orderdetail->width, 2) . '*' . number_format($sale_row->orderdetail->depth, 2) . '</td>
                    <td>' . $sale_row->certificate_no . '</td>
                    <td>' . $sale_row->orderdetail->depth_per . '%</td>
                    <td>' . $sale_row->orderdetail->table_per . '%</td>
                    <td>' . $discount_main . '%</td>
                    <td>$' . number_format($net_price, 2) . '</td>
                </tr>';
            }
            $render_msg .= "</tbody></table>";
            $responce_array['render_msg'] = $render_msg;

            // $pricechange = $this->Orderhistory_Model->pricesettingadv($total_a_price);
            // $new_price = $total_a_price * (1 + $pricechange->pricechange / 100) * (1 + $discount_user / 100);
            // $d = round($total_price - $new_price, 2);
            $responce_array['discountamount'] = 0;//($d > 0) ? $d : 0;
            $responce_array['totalstone'] = count($get_value);
            $responce_array['associate'] = $associate;
            $responce_array['addresses'] = $addresses;
        } else {
            $responce_array['error'] = false;
        }
        echo json_encode($responce_array);
	}

    public function salesinvoicePopupPrepare(Request $request){
        $date = date('m-d-Y');

		$render_msg = '';
        $invoice_no = $request->invoiceno;

        $theassociates = Invoice::with('associates')->where('invoice_number',$invoice_no)->first();
        $responce_array['theassociate'] = $theassociates->associates->name;

        $orders_id = (explode(",", $request->orderid));

        $customers = Customer::with('user')->where('customer_type','!=',4)->get();
        $responce_array['customers'] = $customers;

        $get_value = Order::with('orderdetail')->whereIn('orders_id',$orders_id)->get();
        $render_msg = '';
        $render_msg .= '<table class="table table-condensed table-hover"><thead><tr class="fw-bolder fs-6 text-gray-800 px-7">'
                . '<th>Shape</th>'
                . '<th>SKU</th>'
                . '<th>Carat</th>'
                . '<th>Col</th>'
                . '<th>Clarity</th>'
                . '<th>Cut</th>'
                . '<th>Pol</th>'
                . '<th>Sym</th>'
                . '<th>Flo</th>'
                . '<th>Lab</th>'
                . '<th>Measurement</th>'
                . '<th>Certificate</th>'
                . '<th>Depth</th>'
                . '<th>Table</th>'
                . '<th>Discount</th>'
                . '<th>Price</th>'
                . '</tr></thead><tbody>';

        $total_a_price = $total_price= 0;
        foreach ($get_value as $sale_row) {
            $discount_main = $sale_row->sale_discount;
            $net_price = $sale_row->sale_price;
            $b_price = $sale_row->buy_price;

            $total_a_price += $b_price;
            $total_price += $net_price;

            $render_msg .= '<tr >
                <td><img width="25" height="25" src="'. asset("assets/images/shape/" . strtolower($sale_row->orderdetail->shape) .".png") .'"> ' . $sale_row->orderdetail->shape . '</td>
                ';
                if($sale_row->diamond_type == "L")
                {
                    $render_msg .= '<td>LG-' . $sale_row->orderdetail->id . '</td>';
                }
                else
                {
                    $render_msg .= '<td>' . $sale_row->orderdetail->id . '</td>';
                }
                $render_msg .= '<td>' . $sale_row->carat . '</td>
                <td>' . $sale_row->orderdetail->color . '</td>
                <td>' . $sale_row->orderdetail->clarity . '</td>
                <td>' . $sale_row->orderdetail->cut . '</td>
                <td>' . $sale_row->orderdetail->polish . '</td>
                <td>' . $sale_row->orderdetail->symmetry . '</td>
                <td>' . $sale_row->orderdetail->fluorescence . '</td>
                <td>' . $sale_row->orderdetail->lab . '</td>
                <td>' . number_format($sale_row->orderdetail->length, 2) . '*' . number_format($sale_row->orderdetail->width, 2) . '*' . number_format($sale_row->orderdetail->depth, 2) . '</td>
                <td>' . $sale_row->certificate_no . '</td>
                <td>' . $sale_row->orderdetail->depth_per . '%</td>
                <td>' . $sale_row->orderdetail->table_per . '%</td>
                <td>' . $discount_main . '%</td>
                <td>$' . number_format($net_price, 2) . '</td>
            </tr>';
        }
        $render_msg .= "</tbody></table>";
        $responce_array['render_msg'] = $render_msg;

        // $pricechange = $this->Orderhistory_Model->pricesettingadv($total_a_price);
        // $new_price = $total_a_price * (1 + $pricechange->pricechange / 100) * (1 + $discount_user / 100);
        // $d = round($total_price - $new_price, 2);
        $responce_array['discountamount'] = 0;//($d > 0) ? $d : 0;
        $responce_array['totalstone'] = count($get_value);

        return json_encode($responce_array);
    }

    public function salesinvoiceCreate(Request $request){
        $associate = Associates::where('name',$request->associate)->first();

        $invoice_id = Invoice::where('invoice_number',$request->invoiceno)->select('invoice_id')->where('is_deleted',0)->first();

        $orders_id = (explode(",", $request->orderid));
        $extra_save = !empty($request->extra_save) ? $request->extra_save : '0';
		$extra_discount = !empty($request->discount_extra_order) ? $request->discount_extra_order : '0';
		$extra_discount = $extra_discount + $extra_save;
        $data['discount_extra_order'] = $extra_discount;
        $data['shipping'] = $request->shipping;

        $shipping_charge = $shipping_charge_ups = $shipping_charge_fx = 0;

        $data['consignee'] = 0;

        $shipping_charge = $request->shipping_charge;
        $data['invoice_number'] =  $request->invoiceno;

        $insurrance = $amount_description = '';
		if($request->shipping == 'MA-Express')
		{
			$amount_description = "BY Future Generali";
			$insurrance = "CIF";
		}
		elseif($request->shipping == 'JK-MALCA AMIT')
		{
			$amount_description = "BY JK MALCA AMIT";
			$insurrance = "C&F";
		}
        elseif($request->shipping == 'BVC')
		{
			$amount_description = "BY BVC";
			$insurrance = "FOB";
		}elseif($data['consignee'] == 1)
        {
            $insurrance = "FOB";
        }
        $customer_id = $request->customer;
        $customer = Customer::with('user')->where('cus_id', $customer_id)->first();
        $markup = $customer->discount;

        $pdfname = $customer->user->firstname . '_sales_invoice' . time() . '.pdf';

        $data['shipping_address'] = $customer->shipping_address;
        $data['companyname'] = $customer->user->companyname;
        $data['mobile'] = $customer->user->mobile;
        $data['email'] = $customer->user->email;
        $data['firstname'] = $customer->user->firstname;
        $data['lastname'] = $customer->user->lastname;

        $data['consignee_buyer_name'] = $customer->consignee_buyer_name;
        $data['shiping_email'] = $customer->user->email;
        $data['pre_carriage'] = $request->shipping;
        $data['portof_dischargeuser'] = $customer->port_of_discharge;
        $data['finaldestination'] = $customer->country;
        $data['importref'] = $customer->company_tax;
        $data['amount_description'] = $amount_description;


        $data['as_name'] = $associate->name;
        $data['as_mobile'] = $associate->mobile;
        $data['as_email'] = $associate->email;
        $data['as_address'] = $associate->address;
        $data['ac_no'] = $associate->account_number;
        $data['as_port_loading'] = $associate->port_loading;
        $data['as_carrier_place'] = $associate->carrier_place;
        $data['bank_name'] = $associate->bank_name;
        $data['bank_address'] = $associate->bank_address;
        $data['swift_code'] = $associate->swift_code;
        $data['inter_bank_address'] = $associate->intermediary_bank;
        $data['inter_swift_code'] = $associate->intermediary_swift_code;
        $data['ad_code'] = $associate->address_code;

        $data['shipping_charge'] = !empty($shipping_charge) ? $shipping_charge : 0;

        $diamond_type_lab = $diamond_type_natural = $StoneId = $diamond_html = '';
        $total_amount = $totalcarat = $hsn = $pcs = 0;

        $date = date('Y-m-d H:i:s');

        foreach ($orders_id as $orders) {
            $get_value = Order::with('orderdetail')->where('orders.orders_id', $orders);
            $get_value = $get_value->get();

            foreach ($get_value as $sale_row) {
                $pcs++;
                if($sale_row->diamond_type == "L")
                {
                    $StoneId .= $rk_id = $sale_row->orderdetail->id . ",";
                    $hsn = $associate->hsn_code_lab;
                    $diamond_type_lab = "Lab Grown";
                }
                else
                {
                    $StoneId .= $rk_id = $sale_row->orderdetail->id . ",";
                    $hsn = ($sale_row->orderdetail->carat > 0.99) ? $associate->hsn_code_natural : $associate->hsn_code_natural_one;
                    $diamond_type_natural = "Natural";
                }

                $carat_price = round($sale_row->sale_rate, 2);
                $net_price = round($sale_row->sale_price, 2);

                $totalcarat = $totalcarat + $sale_row->orderdetail->carat;
                $total_amount = $total_amount + $net_price;
            }
        }
        $wordnumber = '';
        $final_amount = round(($total_amount + $shipping_charge) - $extra_discount, 2);
        $wordnumber = AppHelper::convert_number_to_words($final_amount);

        $data['pcs'] = $pcs;
        $data['totalcarat'] = $totalcarat;
        $data['insurrance'] = $insurrance;
        $data['final_amount'] = $final_amount;
        $data['date'] = date('Y-m-d');
        $data['wordnumber'] = $wordnumber;

        $update = DB::table('invoices')->where('invoice_number',$request->invoiceno)->where('is_deleted',0)->update(['sales_invoice_pdf' => $pdfname]);

        $pcs = 0;
        foreach ($orders_id as $orders) {
            $get_value = Order::with('orderdetail')->where('orders_id', $orders);
            $get_value = $get_value->get();

            foreach ($get_value as $sale_row) {
                $pcs++;
                if($sale_row->diamond_type == "L")
                {
                    $StoneId .= $rk_id = $sale_row->orderdetail->id . ",";
                    $hsn = $associate->hsn_code_lab;
                    $diamond_type_lab = "Lab Grown";
                }
                else
                {
                    $StoneId .= $rk_id = $sale_row->orderdetail->id . ",";
                    $hsn = ($sale_row->orderdetail->carat > 0.99) ? $associate->hsn_code_natural : $associate->hsn_code_natural_one;
                    $diamond_type_natural = "Natural";
                }

                $carat_price = round($sale_row->sale_rate, 2);
                $net_price = round($sale_row->sale_price, 2);

                $totalcarat = $totalcarat + $sale_row->orderdetail->carat;
                $total_amount = $total_amount + $net_price;

                $diamond_html .= '<tr>
                        <td align="center">' . $pcs . '</td>
                        <td >' . $sale_row->orderdetail->lab . '-' . $sale_row->certificate_no . ' ' . $sale_row->orderdetail->shape . ' ' . $sale_row->orderdetail->color . '-' . $sale_row->orderdetail->clarity . '-' . $sale_row->orderdetail->cut . '-' . $sale_row->orderdetail->polish . '-' . $sale_row->orderdetail->symmetry .'-' . $sale_row->orderdetail->fluorescence .' &nbsp;| '. number_format($sale_row->orderdetail->length, 2) . '*' . number_format($sale_row->orderdetail->width, 2) . '*' . number_format($sale_row->orderdetail->depth, 2) . '</td>
                        <td style="border-left:2px solid #333;" align="center">' . $hsn .'</td>
                        <td style="border-left:2px solid #333;" align="center">1</td>
                        <td style="border-left:2px solid #333;" align="center">' . $sale_row->orderdetail->carat . '</td>
                        <td style="border-left:2px solid #333;" align="center">$' . $carat_price . '</td>
                        <td style="border-left:2px solid #333;" align="center">$' . $net_price . '</td>
                    </tr>';

            }
        }

        if(!empty($diamond_type_natural) && !empty($diamond_type_lab))
        {
            $data['diamondsname'] = $diamond_type_natural.' & '.$diamond_type_lab;
        }
        else
        {
            $data['diamondsname'] = $diamond_type_natural.' '.$diamond_type_lab;
        }

        $data['diamond_html'] = $diamond_html;

        $pdf = PDF::loadView('admin.orders.order_template', $data);
        // download PDF file with download method
        $pdf->save('assets/sales_invoices/' . $pdfname);

        $update = Invoice::where('invoice_number',$request->invoiceno)->where('is_deleted',0)->update(['sales_invoice_pdf' => $pdfname]);

        $responce_array["success"] = true;

        return json_encode($responce_array);

    }

    public function invoiceCreate(Request $request) {

        $certi_no = $request->certi_no;
        $customer_id = $request->customer_id;
        $customer = Customer::with('user')->where('cus_id', $customer_id)->first();
        $markup = $customer->discount;
        $local = $request->local;

        if(date('m') > 3){
			$year = date('y')."-".date('y')+ 1;
		}
		else{
            $year = date('y')- 1 ."-".date('y');
		}

        $associate = Associates::find($request->associate);

        $orders_id = (explode(",", $request->orders_id));

        $extra_save = !empty($request->extra_save) ? $request->extra_save : '0';
		$extra_discount = !empty($request->discount_extra_order) ? $request->discount_extra_order : '0';
		$extra_discount = $extra_discount + $extra_save;

        $data['discount_extra_order'] = $extra_discount;

        $data['shipping'] = $request->shipping;
        $shipping_charge = $shipping_charge_ups = $shipping_charge_fx = '';

        $shipping_charge = $request->shipping_charge;

        $data['consignee'] = $request->consignee;
        // $garnd_totaldis = round((($amount + $shipping_charge) - $discount_extra_order), 2);
        $ex_rate = 0;
        if($associate->id == \Cons::ASSOCIATE_AUS_ASSOC_ID){

            $aud_rate = CurrencyExchange::where('currency_name','AUD')->pluck('currency_rate')->first();
            $aud_rate = (double)round($aud_rate,3);

            $aus_invoice = Invoice::where('invoice_number','LIKE','%TDP'.date('ymd').'%')->where('is_deleted',0)->orderBy('invoice_id', 'desc')->take(1)->first();
            if($aus_invoice != null){
                $aus_invoice_no = substr($aus_invoice->invoice_number, 10);
                $invoicenumber = !empty($aus_invoice_no) ? (sprintf("%01d", $aus_invoice_no+1)) : 01;
            }
            else{
                $invoicenumber = '1';
            }
            $data['invoice_number'] = 'TDP'.date('ymd').'-'.$invoicenumber;
        }
        elseif($local == 1){
            $local_invoice = Invoice::where('invoice_number','LIKE','%LOC%')->where('is_deleted',0)->orderBy('invoice_id', 'desc')->take(1)->first();
            if($local_invoice != null){
                $local_invoice_no = substr($local_invoice->invoice_number, 3);
                $invoicenumber = !empty($local_invoice_no) ? (sprintf("%02d", $local_invoice_no+1)) : 01;
            }
            else{
                $invoicenumber = '01';
            }
            $data['invoice_number'] = 'TDPLOC-'.$invoicenumber.'/'.$year;
        }
        else{
            $invoice = Invoice::where('invoice_number','NOT LIKE','%LOC%')->where('invoice_number','NOT LIKE','%TDP%')->orderBy('invoice_id', 'desc')->take(1)->first();
            $invoicenumber = !empty($invoice->invoice_number) ? ($invoice->invoice_number + 1) : 1;
            $data['invoice_number'] = str_pad($invoicenumber, 3, "0", STR_PAD_LEFT);
        }

        $address = ShippingDestination::where('add_id',$request->address_id)->first();

        $data['consignee_no'] = $request->consignee_no;

        if($associate->id == \Cons::ASSOCIATE_AUS_ASSOC_ID){
            $pdfname = 'TDP'.date('ymd').'-'.$invoicenumber.'_'.time(). '.pdf';
        }elseif($local == 1){
            $pdfname = 'TDPLOC-'.$invoicenumber.'_'.time(). '.pdf';
        }
        else{
            $pdfname = $customer->user->firstname . '_invoice' . time() . '.pdf';
        }

		$insurrance = $amount_description = '';
		if($request->shipping == 'MA-Express')
		{
			$amount_description = "BY Future Generali";
			$insurrance = "CIF";
		}
		elseif($request->shipping == 'JK-MALCA AMIT')
		{
			$amount_description = "BY JK MALCA AMIT";
			$insurrance = "C&F";
		}
        elseif($request->shipping == 'BVC')
		{
			$amount_description = "BY BVC";
			$insurrance = "FOB";
		}elseif($data['consignee'] == 1)
        {
            $insurrance = "FOB";
        }

        $state_code = substr($address->gst_no, 0, 2);

        $pan_no = substr($address->gst_no, 2, 10);

        $data['pan_no'] = $pan_no;
        $data['shipping_address'] = $address->address;
        $data['firstname'] = $customer->user->firstname;
        $data['lastname'] = $customer->user->lastname;
        $data['companyname'] = $address->company_name;
        $data['mobile'] = $address->phone_no;
        $data['email'] = $customer->user->email;
        $data['attendie'] = $address->attend_name;
        $data['cus_gst'] = $address->gst_no;
        $data['cus_state'] = $address->state;
        $data['cus_state_code'] = $state_code;
        $data['cus_POS'] = $address->place_of_supply;
        $data['date1'] = date('d-M-y');

        $data['consignee_buyer_name'] = $address->attend_name;
        $data['shiping_email'] = $customer->user->email;
        $data['pre_carriage'] = $request->shipping;
        $data['portof_dischargeuser'] = $address->port_of_discharge;
        $data['finaldestination'] = $address->country;
        $data['importref'] = $address->company_tax;
        $data['amount_description'] = $amount_description;

        $data['as_name'] = $associate->name;
        $data['as_mobile'] = $associate->mobile;
        $data['as_email'] = $associate->email;
        $data['as_address'] = $associate->address;
        $data['ac_no'] = $associate->account_number;
        $data['as_port_loading'] = $associate->port_loading;
        $data['as_carrier_place'] = $associate->carrier_place;
        $data['bank_name'] = $associate->bank_name;
        $data['branch_name'] = $associate->branch_name;
        $data['bank_address'] = $associate->bank_address;
        $data['ifsc_code'] = $associate->ifsc_code;
        $data['swift_code'] = $associate->swift_code;
        $data['inter_bank_address'] = $associate->intermediary_bank;
        $data['inter_swift_code'] = $associate->intermediary_swift_code;
        $data['bsb_code'] = $associate->bsb_code;
        $data['ad_code'] = $associate->address_code;
        $data['as_gst_no'] = $associate->gst_no;
        $data['as_state'] = $associate->state;

        $data['shipping_charge'] = $shipping_charge = !empty($shipping_charge) ? $shipping_charge : 0;

        $diamond_type_lab = $diamond_type_natural = $StoneId = $diamond_html = $aus_diamond_html = '';
        $total_amount = $carat_price_inr = $final_amount_inr = $final_total = $final_total_inr = $total_aus_amount = $gst = $aus_gst = $clearing_charge = $clearing_charge_aud = $total_amount_inr = $total_amount_aus = $totalcarat = $hsn = $pcs = 0;
        $date = date('Y-m-d H:i:s');
        $title = 'Invoice Created Successfully!';
        if($data['consignee'] == 1)
        {
            $invoice_no = $request->consignee_no;
        }
        else{
            $invoice_no = 'TDP -'.$data['invoice_number'];
        }
        $body = 'Invoice Number : '.$invoice_no.' Created successfully';
        AppHelper::setNotification($customer_id,$title,$body,$date);



        $hsn_natural = $hsn_lab = $natural_val = $lab_val = 0;

        foreach ($orders_id as $orders) {
            $get_value = Order::with('orderdetail')->where('orders_id', $orders)->where('customer_id', $customer_id)->where('is_deleted', 0)->get();

            foreach ($get_value as $sale_row) {
                $pcs++;
                if($local == 1){
                    $ex_rate = $sale_row->exchange_rate;
                }
                if($sale_row->diamond_type == "L")
                {
                    $StoneId .= $rk_id = $sale_row->orderdetail->id . ",";
                    $hsn = $associate->hsn_code_lab;
                    $hsn_lab = $associate->hsn_code_lab;
                    $diamond_type_lab = "Lab Grown";
                    $d_type="L";

                    $carat_price =  round(($sale_row->sale_rate*$ex_rate), 2);
                    $net_amount = round($carat_price*$sale_row->orderdetail->carat, 2);
                    $lab_val = $lab_val + $net_amount;
                }
                else
                {
                    $StoneId .= $rk_id = $sale_row->orderdetail->id . ",";
                    $hsn = ($sale_row->orderdetail->carat > 0.99) ? $associate->hsn_code_natural : $associate->hsn_code_natural_one;
                    $hsn_natural = ($sale_row->orderdetail->carat > 0.99) ? $associate->hsn_code_natural : $associate->hsn_code_natural_one;
                    $diamond_type_natural = "Natural";
                    $d_type="N";

                    $carat_price =  round(($sale_row->sale_rate*$ex_rate), 2);
                    $net_amount = round($carat_price*$sale_row->orderdetail->carat, 2);
                    $natural_val = $natural_val + $net_amount;
                }

                if($associate->id == \Cons::ASSOCIATE_AUS_ASSOC_ID){
                    $net_amount = round(($carat_price*$sale_row->orderdetail->carat)*$aud_rate, 2);
                }elseif($local == 1){
                    if($sale_row->exchange_rate == 0){
                        $carat_price_inr =  round($sale_row->sale_rate*1, 2);
                    }
                    else{
                        $carat_price_inr = round($sale_row->sale_rate*$sale_row->exchange_rate, 2);
                    }
                }
                else{
                    $carat_price = round($sale_row->sale_rate, 2);
                    $net_price = round($sale_row->sale_price, 2);
                }

                $net_amount_inr = round($carat_price_inr*$sale_row->orderdetail->carat, 2);



                if($local == 1){
                    $diamond_html .=   '<tr>
                                            <td align="center">' . $pcs . '</td>
                                            <td style="border-left:2px solid #333;">' . $sale_row->orderdetail->lab . '-' . $sale_row->certificate_no . ' ' . $sale_row->orderdetail->shape . ' ' . $sale_row->orderdetail->color . '-' . $sale_row->orderdetail->clarity . '-' . $sale_row->orderdetail->cut . '-' . $sale_row->orderdetail->polish . '-' . $sale_row->orderdetail->symmetry .'-' . $sale_row->orderdetail->fluorescence .' &nbsp;| '. number_format($sale_row->orderdetail->length, 2) . '*' . number_format($sale_row->orderdetail->width, 2) . '*' . number_format($sale_row->orderdetail->depth, 2) . '</td>
                                            <td style="border-left:2px solid #333;" align="center">'.$hsn.'</td>
                                            <td style="border-left:2px solid #333;" align="center">'.$sale_row->orderdetail->carat.' Carat</td>
                                            <td style="border-left:2px solid #333;" align="center">'.$carat_price_inr.'</td>
                                            <td style="border-left:2px solid #333;" align="center">Carat</td>
                                            <td style="border-left:2px solid #333;" align="right">' . $net_amount_inr . '</td>
                                        </tr>';
                }
                else{
                    $diamond_html .= '<tr>
                            <td align="center">' . $pcs . '</td>
                            <td >' . $sale_row->orderdetail->lab . '-' . $sale_row->certificate_no . ' ' . $sale_row->orderdetail->shape . ' ' . $sale_row->orderdetail->color . '-' . $sale_row->orderdetail->clarity . '-' . $sale_row->orderdetail->cut . '-' . $sale_row->orderdetail->polish . '-' . $sale_row->orderdetail->symmetry .'-' . $sale_row->orderdetail->fluorescence .' &nbsp;| '. number_format($sale_row->orderdetail->length, 2) . '*' . number_format($sale_row->orderdetail->width, 2) . '*' . number_format($sale_row->orderdetail->depth, 2) . '</td>
                            <td style="border-left:2px solid #333;" align="center">' . $hsn .'</td>
                            <td style="border-left:2px solid #333;" align="center">1</td>
                            <td style="border-left:2px solid #333;" align="center">' . $sale_row->orderdetail->carat . '</td>
                            <td style="border-left:2px solid #333;" align="center">$' . $carat_price . '</td>
                            <td style="border-left:2px solid #333;" align="center">$' . $net_amount . '</td>
                        </tr>';
                }
                if($associate->id == \Cons::ASSOCIATE_AUS_ASSOC_ID){

                    $aus_diamond_html .='<tr style="border-bottom:1px solid #333;line-height:30px !important;valign:middle;">
                                            <td>' . $sale_row->orderdetail->lab . '-' . $sale_row->certificate_no . '-' . $sale_row->orderdetail->shape . '-' . $sale_row->orderdetail->color . '-' . $sale_row->orderdetail->clarity . '-' . $sale_row->orderdetail->cut . '-' . $sale_row->orderdetail->polish . '-' . $sale_row->orderdetail->symmetry .'-' . $sale_row->orderdetail->fluorescence .' &nbsp;| '. number_format($sale_row->orderdetail->length, 2) . '*' . number_format($sale_row->orderdetail->width, 2) . '*' . number_format($sale_row->orderdetail->depth, 2) .' - '. $d_type . '</td>
                                            <td align="center">' . $sale_row->orderdetail->carat . '</td>
                                            <td align="right">' . round($carat_price*$aud_rate,2) . '</td>
                                            <td align="right">' . $net_amount . '</td>
                                        </tr>';
                }


                $totalcarat = $totalcarat + $sale_row->orderdetail->carat;
                $total_amount = $total_amount + $net_amount;
                $total_amount_inr = $total_amount_inr + $net_amount_inr;

                if($associate->id == \Cons::ASSOCIATE_AUS_ASSOC_ID){


                    $total_aus_amount = $total_aus_amount + $net_amount;
                }
            }
        }


        $wordnumber = '';
        $igst =(($total_amount_inr * \Cons::IGST) / 100);
        $sgst =(($total_amount_inr * \Cons::SGST) / 100);
        $cgst =(($total_amount_inr * \Cons::CGST) / 100);


        if($associate->id == \Cons::ASSOCIATE_AUS_ASSOC_ID){
            $aus_gst = (($total_aus_amount * \Cons::AUS_GST) / 100);
            $aus_gst_save = ((($total_aus_amount * \Cons::AUS_GST) / 100)/$aud_rate);
            if($total_aus_amount < 10000){
                $clearing_charge_aud = \Cons::AUS_CLEARING_CHARGE_LESS;
                $clearing_charge = (\Cons::AUS_CLEARING_CHARGE_LESS)/$aud_rate;
            }
            else{
                $clearing_charge_aud = \Cons::AUS_CLEARING_CHARGE_MORE;
                $clearing_charge = (\Cons::AUS_CLEARING_CHARGE_MORE)/$aud_rate;
            }
            $final_amount = round(($total_amount + $shipping_charge + $clearing_charge + $aus_gst_save) - $extra_discount, 2);
            $total_amount_aus = round(($total_aus_amount + $shipping_charge + $clearing_charge_aud + $aus_gst) - $extra_discount, 2);

            $gst = round($aus_gst_save,2);
            $gst_save = round($aus_gst_save,2);
            $ex_rate = $aud_rate;
        }
        elseif($local == 1){
            if($state_code != 24){
                $gst = $igst;
                $final_amount = (($total_amount + $shipping_charge + $clearing_charge) - $extra_discount) + ($gst/$ex_rate);
                $final_amount_inr = (($total_amount_inr + $shipping_charge + $clearing_charge) - $extra_discount) + $gst;
            }
            else{
                $gst = $cgst + $sgst;
                $final_amount = (($total_amount + $shipping_charge + $clearing_charge) - $extra_discount) + ($gst/$ex_rate);
                $final_amount_inr = (($total_amount_inr + $shipping_charge + $clearing_charge) - $extra_discount) + $gst;
            }
            $gst_save = $gst/$ex_rate;
        }
        else{
            $final_amount = round(($total_amount + $shipping_charge + $clearing_charge) - $extra_discount, 2);
            $gst = 0;
            $gst_save = 0;
        }
        if($local == 1){
            $round_off = number_format((round($final_amount_inr) - $final_amount_inr),2);
            $final_total_inr = round($final_amount_inr);
        }
        else{
            $round_off = number_format((round($final_amount) - $final_amount),2);
            $final_total = round($final_amount);
        }
        if($local == 1){
            $wordnumber = AppHelper::convert_number_to_words($final_total_inr);
        }
        else{
            $wordnumber = AppHelper::convert_number_to_words($final_amount);
        }

        $data['total_aus_amount'] = $total_aus_amount;
        $data['aus_gst'] = $aus_gst;
        $data['clearing_charge_aud'] = $clearing_charge_aud;
        $data['total_amount_aus'] = $total_amount_aus;

        $data['total_amo'] = (float)$lab_val+ (float)$natural_val;
        $data['total_igst'] = 0;
        $data['total_cgst'] = 0;
        $data['total_sgst'] = 0;
        $data['total_tax'] = 0;
        if($state_code != 24){
            $lab_igst = (($lab_val*\Cons::IGST) / 100);
            $natural_igst = (($natural_val*\Cons::IGST) / 100);
            $data['total_tax'] = $data['total_igst'] = (float)$lab_igst + (float)$natural_igst;
        }
        else{
            $lab_cgst = (($lab_val*\Cons::CGST) / 100);
            $lab_sgst = (($lab_val*\Cons::SGST) / 100);
            $natural_cgst = (($natural_val*\Cons::CGST) / 100);
            $natural_sgst = (($natural_val*\Cons::SGST) / 100);
            $data['total_cgst'] = (float)$lab_cgst + (float)$natural_cgst;
            $data['total_sgst'] = (float)$lab_sgst + (float)$natural_sgst;
            $data['total_tax'] = (float)$data['total_cgst'] + (float)$data['total_sgst'];
        }
        $data['tax_words'] = ucwords(AppHelper::convert_number_to_words(number_format($data['total_tax'],2)));

        if($local == 1){
            $tax_html = '';
            $taxation_html = '';
            if($state_code != 24){
                $tax_html ='<tr>
                                <td></td>
                                <td style="border-left:2px solid #333;" align="right"><b><i>IGST</i></b></td>
                                <td style="border-left:2px solid #333;" align="center"></td>
                                <td style="border-left:2px solid #333;" align="center"></td>
                                <td style="border-left:2px solid #333;" align="center"></td>
                                <td style="border-left:2px solid #333;" align="center"></td>
                                <td style="border-left:2px solid #333;"  align="right">'. number_format($igst,2) . '</td>
                            </tr>';
                if($hsn_lab != 0){
                    $taxation_html .='<tr>
                                        <td style="border-top:2px solid #333;">'.$hsn_lab.'</td>
                                        <td style="border-left:2px solid #333;border-top:2px solid #333;" align="right">'.number_format($lab_val,2).'</td>
                                        <td style="border-left:2px solid #333;border-top:2px solid #333;" align="center">1.50%</td>
                                        <td style="border-left:2px solid #333;border-top:2px solid #333;" align="right">'.number_format($lab_igst,2).'</td>
                                        <td style="border-left:2px solid #333;border-top:2px solid #333;" align="right">'.number_format($lab_igst,2).'</td>
                                    </tr>';
                }

                if($hsn_natural != 0){
                    $taxation_html .='<tr>
                                        <td style="border-top:2px solid #333;">'.$hsn_natural.'</td>
                                        <td style="border-left:2px solid #333;border-top:2px solid #333;" align="right">'.number_format($natural_val,2).'</td>
                                        <td style="border-left:2px solid #333;border-top:2px solid #333;" align="center">1.50%</td>
                                        <td style="border-left:2px solid #333;border-top:2px solid #333;" align="right">'.number_format($natural_igst,2).'</td>
                                        <td style="border-left:2px solid #333;border-top:2px solid #333;" align="right">'.number_format($natural_igst,2).'</td>
                                    </tr>';
                }

            }
            else{
                $tax_html ='<tr>
                                <td></td>
                                <td style="border-left:2px solid #333;" align="right"><b><i>CGST</i></b></td>
                                <td style="border-left:2px solid #333;" align="center"></td>
                                <td style="border-left:2px solid #333;" align="center"></td>
                                <td style="border-left:2px solid #333;" align="center"></td>
                                <td style="border-left:2px solid #333;" align="center"></td>
                                <td style="border-left:2px solid #333;"  align="right">'. number_format($cgst,2) . '</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td style="border-left:2px solid #333;" align="right"><b><i>SGST</i></b></td>
                                <td style="border-left:2px solid #333;" align="center"></td>
                                <td style="border-left:2px solid #333;" align="center"></td>
                                <td style="border-left:2px solid #333;" align="center"></td>
                                <td style="border-left:2px solid #333;" align="center"></td>
                                <td style="border-left:2px solid #333;"  align="right">'. number_format($sgst,2) . '</td>
                            </tr>';
                if($hsn_lab != 0){
                    $taxation_html .='<tr>
                                        <td style="border-top:2px solid #333;">'.$hsn_lab.'</td>
                                        <td style="border-left:2px solid #333;border-top:2px solid #333;" align="right">'.number_format($lab_val,2).'</td>
                                        <td style="border-left:2px solid #333;border-top:2px solid #333;" align="center">0.75%</td>
                                        <td style="border-left:2px solid #333;border-top:2px solid #333;" align="right">'.number_format($lab_cgst,2).'</td>
                                        <td style="border-left:2px solid #333;border-top:2px solid #333;" align="center">0.75%</td>
                                        <td style="border-left:2px solid #333;border-top:2px solid #333;" align="right">'.number_format($lab_sgst,2).'</td>
                                        <td style="border-left:2px solid #333;border-top:2px solid #333;" align="right">'.number_format($lab_cgst+$lab_sgst,2).'</td>
                                    </tr>';
                }
                if($hsn_natural != 0){
                    $taxation_html .='<tr>
                                        <td style="border-top:2px solid #333;">'.$hsn_natural.'</td>
                                        <td style="border-left:2px solid #333;border-top:2px solid #333;" align="right">'.number_format($natural_val,2).'</td>
                                        <td style="border-left:2px solid #333;border-top:2px solid #333;" align="center">0.75%</td>
                                        <td style="border-left:2px solid #333;border-top:2px solid #333;" align="right">'.number_format($natural_cgst,2).'</td>
                                        <td style="border-left:2px solid #333;border-top:2px solid #333;" align="center">0.75%</td>
                                        <td style="border-left:2px solid #333;border-top:2px solid #333;" align="right">'.number_format($natural_sgst,2).'</td>
                                        <td style="border-left:2px solid #333;border-top:2px solid #333;" align="right">'.number_format($natural_cgst+$natural_sgst,2).'</td>
                                    </tr>';
                }
            }
            $data['tax_html'] =$tax_html;
            $data['taxation_html'] =$taxation_html;
        }

        $data['pcs'] = $pcs;
        $data['totalcarat'] = $totalcarat;
        $data['insurrance'] = $insurrance;
        $data['final_amount'] = $final_amount;
        $data['final_amount_inr'] = $final_amount_inr;
        $data['igst'] = $igst;
        $data['round_off'] = $round_off;
        $data['final_total'] = $final_total;
        $data['final_total_inr'] = $final_total_inr;
        $data['date'] = date('Y-m-d');
        $data['rupees'] = '₹ &#8377; &#x20B9; ';

        $data['wordnumber'] = ucwords($wordnumber);

        if($associate->id == \Cons::ASSOCIATE_AUS_ASSOC_ID){
            $invoice_number = 'TDP'.date('ymd').'-'.$invoicenumber;
        }
        elseif($local == 1){
            $invoice_number = 'LOC'.$invoicenumber;
        }
        else{
            $invoice_number = $invoicenumber;
        }
        $orderdata = array(
			"customer_id" => $customer_id,
			"shipping_address" => strtolower($customer->shipping_address),
			"pre_carriage" => $request->shipping,
			"port_of_discharge" => $address->port_of_discharge,
			"final_destination" => $address->country,
			// "ref_no" => $loats,
            "orders_id" => implode(',', $orders_id),
			"certificate_no" => $certi_no,
			// "discount" => $discount,
			"invoice_number" => $invoice_number,
			"bill_invoice_pdf" => $pdfname,
			"amount" => $total_amount,
			"shipping_charge" => $shipping_charge,
			"clearing_charge" => $clearing_charge,
			"gst" => $gst_save,
			"total_amount" => $final_amount,
			"ex_rate" => $ex_rate,
			"invoice_status" => 0,
			"discount_extra" => $extra_discount,
            "associates_id" => $associate->id,
            "invoice_number_display" => '',
            "created_at" => $date,
		);
        $invoice_id = Invoice::insertGetId($orderdata);

        foreach ($orders_id as $orders) {
            $get_value = Order::with('orderdetail')->where('orders_id', $orders)->where('customer_id', $customer_id)->where('is_deleted', 0)->get();
            foreach ($get_value as $sale_row) {
                if($local == 1){
                    if($sale_row->exchange_rate == 0){
                        $carat_price =  round($sale_row->sale_rate*1, 2);
                    }
                    else{
                        $carat_price = round($sale_row->sale_rate*$sale_row->exchange_rate, 2);
                    }
                }
                else{
                    $carat_price = round($sale_row->sale_rate, 2);
                    $net_price = round($sale_row->sale_price, 2);
                }

                $net_amount = round($carat_price*$sale_row->orderdetail->carat, 2);
                $invoice_items = array(
                    "customer_id" => $customer_id,
                    "invoice_id" => $invoice_id,
                    'orders_id' => $orders,
                    "ref_no" => $sale_row->ref_no,
                    "certificate_no" => $sale_row->certificate_no,
                    "rate" => $carat_price,
                    "net_dollar" => $net_amount,
                    "discount" => 0,
                    "created_at" => $date,
                );
                InvoiceItem::insert($invoice_items);
            }
        }
        Pickups::whereIn('orders_id',$orders_id)->update(['invoice_number' => $invoice_number]);

        // Order::whereIn('orders_id', $orders_id)->update(array("is_deleted" => 1));

        if(!empty($diamond_type_natural) && !empty($diamond_type_lab))
        {
            $data['diamondsname'] = $diamond_type_natural.' & '.$diamond_type_lab;
        }
        else
        {
            $data['diamondsname'] = $diamond_type_natural.' '.$diamond_type_lab;
        }

        $data['diamond_html'] = $diamond_html;
        if($associate->id == \Cons::ASSOCIATE_AUS_ASSOC_ID){
            $data['aus_diamond_html'] = $aus_diamond_html;
        }

        if($associate->id == \Cons::ASSOCIATE_AUS_ASSOC_ID){
            $pdf = PDF::loadView('admin.template.invoice_aus_template', $data);
            $pdf->save('assets/invoices/' . $pdfname);
        }
        elseif($local == 1){
            $pdf = PDF::loadView('admin.template.invoice_local_rk', $data);
            $pdf->save('assets/invoices/' . $pdfname);
        }
        else
        {
            $pdf = PDF::loadView('admin.orders.order_template', $data);
            $pdf->save('assets/invoices/' . $pdfname);
        }
        // download PDF file with download method

		// $this->Orderhistory_Model->updateInvoiceNoPickup($certi, $invoice_number, $userid);

        $responce_array["pdf_invoice"] = 'assets/invoices/' . $pdfname;
        $responce_array["success"] = true;
		echo json_encode($responce_array);
    }

    public function invoiceCancel(Request $request) {
        $invoice_id = $request->invoice_id;
        $reason = $request->reason;
        // ALTER TABLE `invoices` ADD `orders_id` TEXT NULL DEFAULT NULL AFTER `ref_no`;
        $invoice = Invoice::where('invoice_id', $invoice_id)->first();
        $orders_id = explode(',', $invoice->orders_id);
        Order::whereIn('orders_id', $orders_id)->update(array("is_deleted" => 0));
        Pickups::whereIn('orders_id', $orders_id)->update(array("invoice_number" => NULL));
        Invoice::where('invoice_id', $invoice_id)->update(array("is_deleted" => 1 , "reason" => $reason));

        InvoiceItem::where('invoice_id', $invoice_id)->update(["is_deleted" => 1]);

        $responce["success"] = true;
		echo json_encode($responce);
    }

    public function PerfomaInvoicePrepare(Request $request) {
		$date = date('m-d-Y');

		$render_msg = '';
		$certi_no = $request->certi_no;
        $customer_id = $request->customer_id;
        // $customer_data = Customer::where('cus_id', $customer_id)->first();
        // $discount_user = $customer_data->discount;

        $orders_id = (explode(",", $request->orders_id));
        // $getrowcheck = Pickups::whereIn('pickups.orders_id', $orders_id)->count();

        // if ($getrowcheck == count($orders_id)) {

            $associate = Associates::all();

            $addresses = ShippingDestination::where('customer_id',$customer_id)->get();


            $get_value = Order::with('orderdetail')->whereIn('orders_id',$orders_id);
            if($customer_id != 0)
            {
                $get_value->where('customer_id', $customer_id);
            }
            $get_value = $get_value->get();

            $render_msg = '';
            $render_msg .= '<table class="table table-condensed table-hover"><thead><tr class="fw-bolder fs-6 text-gray-800 px-7">'
                    . '<th>Shape</th>'
                    . '<th>SKU</th>'
                    . '<th>Carat</th>'
                    . '<th>Col</th>'
                    . '<th>Clarity</th>'
                    . '<th>Cut</th>'
                    . '<th>Pol</th>'
                    . '<th>Sym</th>'
                    . '<th>Flo</th>'
                    . '<th>Lab</th>'
                    . '<th>Measurement</th>'
                    . '<th>Certificate</th>'
                    . '<th>Depth</th>'
                    . '<th>Table</th>'
                    . '<th>Discount</th>'
                    . '<th>Price</th>'
                    . '</tr></thead><tbody>';

            $total_a_price = $total_price= 0;
            foreach ($get_value as $sale_row) {
                $discount_main = $sale_row->sale_discount;
                $net_price = $sale_row->sale_price;
                $b_price = $sale_row->buy_price;

                $total_a_price += $b_price;
                $total_price += $net_price;

                $render_msg .= '<tr >
                    <td><img width="25" height="25" src="'. asset("assets/images/shape/" . strtolower($sale_row->orderdetail->shape) .".png") .'"> ' . $sale_row->orderdetail->shape . '</td>
                    ';
                    if($sale_row->diamond_type == "L")
                    {
                        $render_msg .= '<td>LG-' . $sale_row->orderdetail->id . '</td>';
                    }
                    else
                    {
                        $render_msg .= '<td>' . $sale_row->orderdetail->id . '</td>';
                    }
                    $render_msg .= '<td>' . $sale_row->orderdetail->carat . '</td>
                    <td>' . $sale_row->orderdetail->color . '</td>
                    <td>' . $sale_row->orderdetail->clarity . '</td>
                    <td>' . $sale_row->orderdetail->cut . '</td>
                    <td>' . $sale_row->orderdetail->polish . '</td>
                    <td>' . $sale_row->orderdetail->symmetry . '</td>
                    <td>' . $sale_row->orderdetail->fluorescence . '</td>
                    <td>' . $sale_row->orderdetail->lab . '</td>
                    <td>' . number_format($sale_row->orderdetail->length, 2) . '*' . number_format($sale_row->orderdetail->width, 2) . '*' . number_format($sale_row->orderdetail->depth, 2) . '</td>
                    <td>' . $sale_row->certificate_no . '</td>
                    <td>' . $sale_row->orderdetail->depth_per . '%</td>
                    <td>' . $sale_row->orderdetail->table_per . '%</td>
                    <td>' . $discount_main . '%</td>
                    <td>$' . number_format($net_price, 2) . '</td>
                </tr>';
            }
            $render_msg .= "</tbody></table>";
            $responce_array['render_msg'] = $render_msg;

            // $pricechange = $this->Orderhistory_Model->pricesettingadv($total_a_price);
            // $new_price = $total_a_price * (1 + $pricechange->pricechange / 100) * (1 + $discount_user / 100);
            // $d = round($total_price - $new_price, 2);
            $responce_array['discountamount'] = 0;//($d > 0) ? $d : 0;
            $responce_array['totalstone'] = count($get_value);
            $responce_array['associate'] = $associate;
            $responce_array['addresses'] = $addresses;
        // } else {
        //     $responce_array['error'] = false;
        // }
        echo json_encode($responce_array);
	}

    public function PerfomaCreate(Request $request) {

        $certi_no = $request->certi_no;
        $customer_id = $request->customer_id;
        $customer = Customer::with('user')->where('cus_id', $customer_id)->first();
        $markup = $customer->discount;

        $associate = Associates::find($request->associate);

        $orders_id = (explode(",", $request->orders_id));

        $extra_save = !empty($request->extra_save) ? $request->extra_save : '0';
		$extra_discount = !empty($request->discount_extra_order) ? $request->discount_extra_order : '0';
		$extra_discount = $extra_discount + $extra_save;

        $data['discount_extra_order'] = $extra_discount;

        $data['shipping'] = $request->shipping;
        $shipping_charge = $shipping_charge_ups = $shipping_charge_fx = 0;

        $shipping_charge = !empty($request->shipping_charge) ? $request->shipping_charge : 0;

        $data['consignee'] = $request->consignee;
        // $garnd_totaldis = round((($amount + $shipping_charge) - $discount_extra_order), 2);

        $invoice = DB::table('invoices_perfoma')->orderBy('invoice_id', 'desc')->take(1)->first();

		$invoicenumber = !empty($invoice->invoice_number) ? ($invoice->invoice_number + 1) : 1;
		$data['invoice_number'] = 'PER'.str_pad($invoicenumber, 3, "0", STR_PAD_LEFT);

        $data['consignee_no'] = $request->consignee_no;

        $address = ShippingDestination::where('add_id',$request->address_id)->first();

        $pdfname = $customer->user->firstname . '_Per_invoice' . time() . '.pdf';

		$insurrance = $amount_description = '';
		if($request->shipping == 'MA-Express')
		{
			$amount_description = "BY Future Generali";
			$insurrance = "CIF";
		}
		elseif($request->shipping == 'JK-MALCA AMIT')
		{
			$amount_description = "BY JK MALCA AMIT";
			$insurrance = "C&F";
		}
        elseif($request->shipping == 'BVC')
		{
			$amount_description = "BY BVC";
			$insurrance = "FOB";
		}elseif($data['consignee'] == 1)
        {
            $insurrance = "FOB";
        }

        $data['shipping_address'] = $address->address;
        $data['companyname'] = $address->user->companyname;
        $data['mobile'] = $address->user->mobile;
        $data['email'] = $address->user->email;
        $data['firstname'] = $address->user->firstname;
        $data['lastname'] = $address->user->lastname;

        $data['consignee_buyer_name'] = $address->attend_name;
        $data['shiping_email'] = $customer->user->email;
        $data['pre_carriage'] = $request->shipping;
        $data['portof_dischargeuser'] = $address->port_of_discharge;
        $data['finaldestination'] = $address->country;
        $data['importref'] = $address->company_tax;
        $data['amount_description'] = $amount_description;


        $data['as_name'] = $associate->name;
        $data['as_mobile'] = $associate->mobile;
        $data['as_email'] = $associate->email;
        $data['as_address'] = $associate->address;
        $data['ac_no'] = $associate->account_number;
        $data['as_port_loading'] = $associate->port_loading;
        $data['as_carrier_place'] = $associate->carrier_place;
        $data['bank_name'] = $associate->bank_name;
        $data['bank_address'] = $associate->bank_address;
        $data['swift_code'] = $associate->swift_code;
        $data['inter_bank_address'] = $associate->intermediary_bank;
        $data['inter_swift_code'] = $associate->intermediary_swift_code;
        $data['ad_code'] = $associate->address_code;

        $data['shipping_charge'] = $shipping_charge;

        $diamond_type_lab = $diamond_type_natural = $StoneId = $diamond_html = '';
        $total_amount = $totalcarat = $hsn = $pcs = 0;

        foreach ($orders_id as $orders) {
            $get_value = Order::with('orderdetail')->where('orders_id', $orders)->where('customer_id', $customer_id)->where('is_deleted', 0)->get();

            foreach ($get_value as $sale_row) {
                $pcs++;
                if($sale_row->diamond_type == "L")
                {
                    $StoneId .= $rk_id = $sale_row->orderdetail->id . ",";
                    $hsn = $associate->hsn_code_lab;
                    $diamond_type_lab = "Lab Grown";
                }
                else
                {
                    $StoneId .= $rk_id = $sale_row->orderdetail->id . ",";
                    $hsn = ($sale_row->orderdetail->carat > 0.99) ? $associate->hsn_code_natural : $associate->hsn_code_natural_one;
                    $diamond_type_natural = "Natural";
                }

                $carat_price = round($sale_row->sale_rate, 2);
                $net_price = round($sale_row->sale_price, 2);

                $totalcarat = $totalcarat + $sale_row->orderdetail->carat;
                $total_amount = $total_amount + $net_price;
            }
        }
        $wordnumber = '';
        $final_amount = round(($total_amount + $shipping_charge) - $extra_discount, 2);
        $wordnumber = AppHelper::convert_number_to_words($final_amount);

        $data['pcs'] = $pcs;
        $data['totalcarat'] = $totalcarat;
        $data['insurrance'] = $insurrance;
        $data['final_amount'] = $final_amount;
        $data['date'] = date('Y-m-d');
        $data['wordnumber'] = $wordnumber;

        $orderdata = array(
			"customer_id" => $customer_id,
			"shipping_address" => strtolower($address->address),
			"pre_carriage" => $request->shipping,
			"port_of_discharge" => $address->port_of_discharge,
			"final_destination" => $address->country,
			// "ref_no" => $loats,
            "orders_id" => implode(',', $orders_id),
			"certificate_no" => $certi_no,
			// "discount" => $discount,
			"invoice_number" => $invoicenumber,
			"bill_invoice_pdf" => $pdfname,
			"amount" => $total_amount,
			"shipping_charge" => $shipping_charge,
			"total_amount" => $final_amount,
			"invoice_status" => 0,
			"discount_extra" => $extra_discount,
            "associates_id" => $associate->id,
            "invoice_number_display" => '',
            "created_at" => date('Y-m-d H:i:s'),
		);
        $invoice_id = DB::table('invoices_perfoma')->insertGetId($orderdata);

        Order::whereIn('orders_id',$orders_id)->update(['proforma_invoice'=>$invoicenumber]);
        $pcs = 0;
        foreach ($orders_id as $orders) {
            $get_value = Order::with('orderdetail')->where('orders.orders_id', $orders)->where('orders.customer_id', $customer_id)->where('orders.is_deleted', 0)->get();

            foreach ($get_value as $sale_row) {
                $pcs++;
                if($sale_row->diamond_type == "L")
                {
                    $StoneId .= $rk_id = $sale_row->orderdetail->id . ",";
                    $hsn = $associate->hsn_code_lab;
                    $diamond_type_lab = "Lab Grown";
                }
                else
                {
                    $StoneId .= $rk_id = $sale_row->orderdetail->id . ",";
                    $hsn = ($sale_row->orderdetail->carat > 0.99) ? $associate->hsn_code_natural : $associate->hsn_code_natural_one;
                    $diamond_type_natural = "Natural";
                }

                $carat_price = round($sale_row->sale_rate, 2);
                $net_price = round($sale_row->sale_price, 2);

                $totalcarat = $totalcarat + $sale_row->orderdetail->carat;
                $total_amount = $total_amount + $net_price;

                $diamond_html .= '<tr>
                        <td align="center">' . $pcs . '</td>
                        <td >' . $sale_row->orderdetail->lab . '-' . $sale_row->certificate_no . ' ' . $sale_row->orderdetail->shape . ' ' . $sale_row->orderdetail->color . '-' . $sale_row->orderdetail->clarity . '-' . $sale_row->orderdetail->cut . '-' . $sale_row->orderdetail->polish . '-' . $sale_row->orderdetail->symmetry .'-' . $sale_row->orderdetail->fluorescence .' &nbsp;| '. number_format($sale_row->orderdetail->length, 2) . '*' . number_format($sale_row->orderdetail->width, 2) . '*' . number_format($sale_row->orderdetail->depth, 2) . '</td>
                        <td style="border-left:2px solid #333;" align="center">' . $hsn .'</td>
                        <td style="border-left:2px solid #333;" align="center">1</td>
                        <td style="border-left:2px solid #333;" align="center">' . $sale_row->orderdetail->carat . '</td>
                        <td style="border-left:2px solid #333;" align="center">$' . $carat_price . '</td>
                        <td style="border-left:2px solid #333;" align="center">$' . $net_price . '</td>
                    </tr>';

                // $invoice_items = array(
                //     "customer_id" => $customer_id,
                //     "invoice_id" => $invoice_id,
                //     'orders_id' => $orders,
                //     "ref_no" => $sale_row->ref_no,
                //     "certificate_no" => $sale_row->certificate_no,
                //     "rate" => $carat_price,
                //     "net_dollar" => $net_price,
                //     "discount" => 0,
                //     "created_at" => date('Y-m-d H:i:s'),
                // );
                // InvoiceItem::insert($invoice_items);
            }
        }
        // Order::whereIn('orders_id', $orders_id)->update(array("is_deleted" => 1));

        if(!empty($diamond_type_natural) && !empty($diamond_type_lab))
        {
            $data['diamondsname'] = $diamond_type_natural.' & '.$diamond_type_lab;
        }
        else
        {
            $data['diamondsname'] = $diamond_type_natural.' '.$diamond_type_lab;
        }

        $data['diamond_html'] = $diamond_html;

        $pdf = PDF::loadView('admin.orders.order_template', $data);
        // download PDF file with download method
        $pdf->save('assets/invoices/' . $pdfname);

		// $this->Orderhistory_Model->updateInvoiceNoPickup($certi, $invoice_number, $userid);

        $responce_array["pdf_invoice"] = 'assets/invoices/' . $pdfname;
        $responce_array["success"] = true;
		echo json_encode($responce_array);
    }

    public function PerfomaList(Request $request)
    {
        $user_type = Auth::user()->user_type;
        $id = Auth::user()->id;

        $permission = AppHelper::userPermission($request->segment(1));

        if(!empty($permission) && ($user_type == 1 || $permission->full == 1)){
            $data['invoice'] = DB::table('invoices_perfoma')
                ->select('invoices_perfoma.*','users.companyname')
                ->join('users', 'users.id', '=', 'customer_id')
                ->where('invoices_perfoma.is_delete','=','0')
                ->orderBy('invoices_perfoma.created_at', 'desc')
                ->paginate(100);
        }
        elseif(!empty($permission) && ($user_type == 4||5||6))
        {
            $data['invoice'] = DB::table('invoices_perfoma')
                ->select('invoices_perfoma.*','users.companyname')
                ->join('users', function ($query) use ($id){
                    $query->on('users.id', '=', 'customer_id');
                    $query->where('users.added_by', '=', $id);

                })->where('invoices_perfoma.is_delete','=','0')
                ->orderBy('invoices_perfoma.created_at', 'desc')
                ->paginate(100);
        }

        return view('admin.perfoma-invoice-list')->with($data);
    }

    public function DeleteInvoice(Request $request)
    {
        $user_type = Auth::user()->user_type;
        if($user_type == 1|| 4 || 5 || 6)
        {
        $invoice_id = $request->id;
        DB::table('invoices_perfoma')->where('invoice_id',$invoice_id)->update([
            'is_delete'=>'1',
        ]);
        }

        return redirect()->back()->with('mes','Invoice Delete Successful');
    }

    public function holdList(Request $request)
    {
        $user_type = Auth::user()->user_type;
        $id = Auth::user()->id;

        $permission = AppHelper::userPermission($request->segment(1));

        $users_id = Admin::select('id')->where('manager',$id)->get()->pluck('id')->toArray();
        $users_id[] = $id;

        $customer_id = $request->id;
        $data['customer'] = User::with('customer')->where('id',$customer_id)->first();

        if(!empty($permission) && ($user_type == 1 || $permission->full == 1)){
            $data['holddata'] = Order::with('user','orderdetail','pickups','qc_list')
            ->where('is_deleted',0)
            ->where('hold',1)
            ->where('order_status','!=','RELEASED')
            ->where('customer_id',$customer_id)
            ->orderBy('created_at', 'desc')
            ->get();
        }
        elseif(!empty($permission) && ($user_type == 4 || 5 || 6)){
            $data['holddata'] = Order::with('user','orderdetail','pickups','qc_list')
            ->where('is_deleted',0)
            ->where('hold',1)
            ->where('order_status','!=','RELEASED')
            ->where('customer_id',$customer_id)
            // ->whereHas('user',function($query) use($id) { $query->where('added_by',$id ); })
            ->whereHas('user',function($query) use($users_id) { $query->whereIn('users.added_by',$users_id); })
            ->orderBy('created_at', 'desc')
            ->get();
        }
        else{
            return redirect('admin');
        }
        return view('admin.orders.order-hold-list')->with($data);
    }

    public function invoiceList(Request $request)
    {
        $user_type = Auth::user()->user_type;
        $array['id'] = $id = Auth::user()->id;
        $data['cus'] = '';
        $data['send'] = '';
        $data['status'] = '';
        $data['from_date'] = '';
        $data['to_date'] = '';

        $data['permission'] = $permission = AppHelper::userPermission($request->segment(1));

        $invoices = Invoice::with('associates','customers');
        $data['senders']=associates::groupby('name')->get();
        if(!empty($permission) && ($user_type == 1 || $permission->full == 1)){
            $data['customers'] = User::where('user_type','=','2')->select('companyname','id')->orderBy('companyname','asc')->get();
        }
        elseif(!empty($permission) && ($user_type == 4 || 5 || 6)){
            $invoices->whereHas('customers',function($query) use($id) { $query->where('added_by',$id ); });
            $data['customers'] = User::where('user_type','=','2')->where('added_by','=',$id)->select('companyname','id')->orderBy('companyname','asc')->get();
        }
        else{
            return redirect('admin');
        }

        if($request->ismethod('post')){
            $customer = $request->customer;
            $sender = $request->sender;
            $status = $request->status;
            $from_date = $request->from_date;
            $to_date = $request->to_date;
            $data['cus'] = $customer;
            $data['send'] = $sender;
            $data['status'] = $status;
            $data['from_date'] = $from_date;
            $data['to_date'] = $to_date;

            $invoices->when($status == 'deleted', function ($query) use ($status) {
                return $query->where('is_deleted',1);
            });
            $invoices->when($status == 'not deleted' || $status == null , function ($query) use ($status) {
                return $query->where('is_deleted',0);
            });
            $invoices->when($status == 'all', function ($query) use ($status) {
                return $query->whereIn('is_deleted',[0,1] );
            });
            $invoices->when($customer, function ($query) use ($customer) {
                return $query->whereHas('customers',function($query) use($customer) { $query->where('id',$customer ); });
            });
            $invoices->when($from_date != null , function ($query) use ($from_date) {
                return $query->whereDate('created_at','>=', $from_date);
            });
            $invoices->when($to_date != null , function ($query) use ($to_date) {
                return $query->whereDate('created_at','<=', $to_date);
            });
            $invoices->when($sender != null, function ($query) use ($sender) {
                return $query->whereHas('associates',function($query) use($sender) { $query->where('id',$sender ); });
            });
        }
        else{
            $invoices = $invoices->where('is_deleted',0);
        }
        $data['invoice'] =  $invoices->orderBy('created_at', 'desc')
                            ->paginate(100);

        return view('admin.invoice-list')->with($data);
    }

    public function SendMailToCustomer(Request $request){
        $invoice_no = $request->invoice_no;
        $customer_id = $request->customer_id;
        $data['invoice'] = Invoice::where('invoice_id',$invoice_no)->first();
        $data['customer'] = User::where('id',$customer_id)->first();
        $orders_ids = explode(',',$data['invoice']->orders_id);
        if($data['customer'] != null){
            $manager_email = User::select('email')->where('id',$data['customer']->added_by)->first();
        }

        $array=[];

        $array['subject'] = "Invoice: TDP -". $data['invoice']->invoice_number ." of ". count($orders_ids) ." stones | ". config('app.name');
        $array['customer_email'] = $data['customer']->email;
        $array['manager_email'] = !empty($manager_email->email) ? $manager_email->email : '';
        $array['invoice_document'] = public_path('assets/invoices/'.$data['invoice']->bill_invoice_pdf);

        $tabledata = '';
        $rows = Order::select('orders.*', 'orders_items.*', DB::raw("(SELECT caratprice FROM rap_list WHERE IF(`shape_name` = 'ROUND', shape = 'ROUND', shape != 'ROUND') AND color_name = `color` AND clarity_name = IF(`clarity` = 'FL','IF', `clarity`) AND low_size <= carat AND high_size >= carat) as raprate"))
        ->join('orders_items', 'orders_items.orders_id', '=', 'orders.orders_id')
        ->whereIn('orders.orders_id', $orders_ids)
        ->get();
        foreach($rows as $row)
        {
            $singledata =    '<table width="100%" style="border:#CEC9C9 solid thin; margin-top:10px;border-radius: 10px; padding: 10px;font-size: 14px;">
                                <tr>
                                <td width="25%">
                                <span><a href="'. url('') .'" style="text-decoration-color: #4f4f4f"><strong>'.$row->id.'</strong></a></span>
                                </td>
                                <td width="30%">
                                        <span><strong>'.$row->lab.': </strong><a href="'. url('') .'" style="text-decoration-color: #4f4f4f"> <strong> '.$row->certificate_no.'</strong></a></span>
                                    </td>
                                    <td width="30%" align="right"> <strong> $/CT &nbsp;$'. number_format($row->sale_rate, 2).'</strong></td>
                                </tr>
                                <tr>
                                    <td colspan="2" width="70%">
                                    <span style="font-weight: 600">'.$row->shape.' '.$row->carat.'CT '.$row->color.' '.$row->clarity.' '.$row->cut.' '.$row->polish.' '.$row->symmetry.' '.$row->fluorescence.'</span>
                                    </td>
                                    <td width="30%" align="right"><strong>Total &nbsp;$' . number_format($row->sale_price, 2) . '</strong></td>
                                </tr>
                            </table>';
            $tabledata .= $singledata;
        }

        try {
        $data['tabledata'] = $tabledata;

        Mail::send('emails.invoice_resend',$data, function($message) use($array){
            $message->to($array['customer_email']);
                if(!empty($array['manager_email'])) { $message->cc($array['manager_email']); }
                $message->cc(\Cons::EMAIL_SALE);
            $message->attach($array['invoice_document']);
            $message->subject($array['subject']);
        });

            Invoice::where('invoice_id',$invoice_no)->where('is_deleted','0')->update(['resend_mail'=>1]);

        $data['success'] = 'Mail Sent!';
        } catch (\Throwable $th) {
            $data['success'] = 'Mail not sent!';
        }

        return json_encode($data);
    }

    public function invoiceListDiamonds(Request $request){
        $invoice_id = $request->invoice_id;
        $orders = Invoice::where('invoice_id',$invoice_id)->first();
        $orders_id = explode(',',$orders->orders_id);
        $orders_details = Order::with('orderdetail','pickups')->whereIn('orders_id',$orders_id)->get();
        $detail = '';
        if (!empty($orders)) {
            $detail .= '<table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th class="column-title"></th>
                                    <th class="column-title">Supplier</th>
                                    <th class="column-title">Ref No.</th>
                                    <th class="column-title">Export No</th>
                                    <th class="column-title">Location</th>
                                    <th class="column-title">SKU</th>
                                    <th class="column-title">Shape</th>
                                    <th class="column-title">Carat</th>
                                    <th class="column-title">Color</th>
                                    <th class="column-title">Clarity</th>
                                    <th class="column-title">Cut</th>
                                    <th class="column-title">Polish</th>
                                    <th class="column-title">Symmetry</th>
                                    <th class="column-title">Lab</th>
                                    <th class="column-title">Certificate</th>
                                    <th class="column-title">$/Ct</th>
                                    <th class="column-title">Sell Price</th>
                                </tr>
                            </thead>
                            <tbody>';
                            if (!empty($orders_details)) {
                                foreach($orders_details as $order){
                                    $detail.='<tr>
                                            <td><input type="checkbox" name="orders" data-orderid="'. $order->orders_id .'"data-invoiceno="'. $order->pickups->invoice_number .'" class="orderscheck"></td>
                                            <td>'.$order->orderdetail->supplier_name.'</td>
                                            <td>'.$order->ref_no.'</td>
                                            <td>'.$order->pickups->export_number.'</td>
                                            <td>'.$order->orderdetail->country.'</td>
                                            <td>'.$order->orderdetail->id.'</td>
                                            <td>'.$order->orderdetail->shape.'</td>
                                            <td>'.$order->orderdetail->carat.'</td>
                                            <td>'.$order->orderdetail->color.'</td>
                                            <td>'.$order->orderdetail->clarity.'</td>
                                            <td>'.$order->orderdetail->cut.'</td>
                                            <td>'.$order->orderdetail->polish.'</td>
                                            <td>'.$order->orderdetail->symmetry.'</td>
                                            <td>'.$order->orderdetail->lab.'</td>
                                            <td>'.$order->certificate_no.'</td>
                                            <td>'.number_format(($order->sale_price/$order->orderdetail->carat),2).'</td>
                                            <td>'.number_format($order->sale_price,2).'</td>
                                        </tr>';
                                };
                            }
            $detail.='</tbody>
                        </table>';

            $data['detail'] = $detail;
        }
        else
        {
            $data['error'] = false;
        }
        echo json_encode($data);
    }

    public function TrackOrder(Request $request)
    {
        $invoice_no = $request->invoice_id;
        $track_no = $request->track_no;
        $data['date']=date('d-m-Y');

        $data['tracking_no'] = $track_no;
        $data['customer'] = Invoice::with('customers')->where('invoice_id',$invoice_no)->first();
        $customer_id = $data['customer']->customers->id;
        $data['address'] = Customer::with('user')->whereHas('user',function($query) use($customer_id){ $query->where('id',$customer_id); })->first();

        $title = 'Tracking Number Updated!';
        $body = 'Tracking Number Entered Is:'.$track_no;
        $date = date('Y-m-d H:i:s');
        $array=[];

        $array['subject'] = "Tracking Details - ". $data['customer']->pre_carriage ." - ". $track_no ." | ". config('app.name');
        $array['customer_email'] = $data['customer']->customers->email;

        try {
            Mail::send('emails.tracking_mail',$data, function($message) use($array){
                $message->to($array['customer_email']);
                $message->cc(\Cons::EMAIL_SALE);
                $message->subject($array['subject']);
            });

            Invoice::where('invoice_id',$invoice_no)->update(['tracking_no' => $track_no]);

            AppHelper::setNotification($customer_id,$title,$body,$date);

            $response['success'] = true;

        }catch (\Throwable $th) {
            $response['success'] = 'Mail not sent!';
        }
    return json_encode($response);

    }

    public function PaymentStatus(Request $request)
    {
        $payment = $request->payment;
        $invoice_id = $request->invoice_id;
        $amount = $request->amount;
        $payment_date = $request->payment_date;
        if($amount == 0){
            $set = ['payment'=>$payment,'payment_date'=>$payment_date];
        }
        else{
            $set = ['payment2'=>$payment,'payment_date2'=>$payment_date];
        }
        Invoice::where('invoice_id',$invoice_id)->update($set);

        $data['success'] = true;
		echo json_encode($data);
    }

    public function logisticsSaveQrCode(Request $request)
    {
        $certi = $request->certi;

        $qr = QrCode::generate($certi, 'assets/qrcodes/' . $certi . '.svg');//->format('png');


        // QRcode::png($certi, "./assets/uploads/" . $certi . ".png");
        $data['success'] = true;
        echo json_encode($data);
    }

    // public function releaseList()
    // {
    //     echo "a";
    //     die;
    //     $user_type = Auth::user()->user_type;
    //     $array['id'] = $id = Auth::user()->id;

    //     $array['url'] = $segment = 'enquiry-list';

    //     $permission = AppHelper::userPermission('enquiry-list');

    //     if(!empty($permission) && ($user_type == 1 || $permission->full == 1)){
    //         $data['customers'] = Order::select('orders.*','users.id', 'users.companyname',
    //             DB::raw('COUNT(IF(orders.order_status="REJECT",1, NULL)) as rejected'),
    //             DB::raw('COUNT(IF(orders.order_status="RELEASED",1, NULL)) as RELEASED'),
    //             DB::raw('COUNT(IF(orders.hold="1",1, NULL)) as hold'))
    //         ->with('user')
    //         ->whereHas('user',function($query){ $query->orderBy('users.companyname', 'ASC'); })
    //         ->where('orders.is_deleted',0)
    //         ->where('orders.order_status', '=', 'RELEASED')
    //         ->groupBy('orders.customer_id')
    //         ->get();
    //     }
    //     elseif(!empty($permission) && ($user_type == 4 || 5 || 6)){
    //         $data['customers'] = Order::select('orders.*','users.id', 'users.companyname',
    //             DB::raw('COUNT(IF(orders.order_status="PENDING" AND orders.hold="0",1, NULL)) as pending'),
    //             DB::raw('COUNT(IF(orders.order_status="REJECT",1, NULL)) as rejected'),
    //             DB::raw('COUNT(IF(orders.order_status="APPROVED",1, NULL)) as accepted'),
    //             DB::raw('COUNT(IF(orders.order_status="RELEASED",1, NULL)) as RELEASED'),
    //             DB::raw('COUNT(IF(orders.hold="1",1, NULL)) as hold'))
    //         ->with('user')
    //         ->whereHas('user',function($query) use($id){ $query->where('added_by',$id); $query->orderBy('users.companyname', 'ASC'); })
    //         ->where('orders.is_deleted',0)
    //         ->where('orders.order_status', '=', 'RELEASED')
    //         ->groupBy('orders.customer_id')
    //         ->orderBy('users.companyname', 'ASC')
    //         ->get();
    //     }
    //     else{
    //         return redirect('admin');
    //     }

    //     return view('admin.order-enquiry')->with($data);
    // }

    public function ReleaseListDetail(Request $request)
    {
        $user_type = Auth::user()->user_type;
        $id = Auth::user()->id;

        $permission = AppHelper::userPermission($request->segment(1));

        $customer_id = $request->id;

        $data['customer'] = User::with('customer')->where('id',$customer_id)->first();

        if(!empty($permission) && ($user_type == 1 || $permission->full == 1)){
            $data['orders'] = Order::with('user','orderdetail')
                ->where('is_deleted', 0)
                ->where('order_status','=','RELEASED')
                ->where('customer_id',$customer_id)
                ->orderBy('created_at', 'desc')
                ->get();
        }
        elseif(!empty($permission) && ($user_type == 4||5||6)){
            $data['orders'] = Order::with('user','orderdetail')
                ->whereHas('user',function ($query) use ($id){ $query->where('added_by','=',$id); })
                ->where('is_deleted', 0)
                ->where('order_status','=','RELEASED')
                ->where('customer_id',$customer_id)
                ->orderBy('created_at', 'desc')
                ->get();

        }
        else{
            return redirect('admin');
        }
        return view('admin.orders.released-list')->with($data);
    }

    public function AdminUpdateExchangeRate(Request $request)
    {
        $user_type = Auth::user()->user_type;
        $id = Auth::user()->id;

        $exchange_rate = $request->exchange_rate;
        $order_id = $request->id;

        Order::where('orders_id',$order_id)->update(['exchange_rate' => $exchange_rate]);

        $data['success'] = "exchange rate changed to ".$exchange_rate ;
        return json_encode($data);
    }

    public function holdReminderMessage(){
        $previous_day = date('Y-m-d H:i:s', strtotime('-1 day', strtotime(date('Y-m-d H:i:s'))));
        $between_hours = date('Y-m-d H:i:s', strtotime('-25 hours', strtotime(date('Y-m-d H:i:s'))));
        $holdorders = Order::with('user:id,added_by','user.manager:id,mobile,firstname')->where('hold','1')->where('hold_at','>',$between_hours)->where('hold_at','<',$previous_day)->get();
        foreach($holdorders as $val)
        {
            $template_name = 'customer_reject_sent_supp';
            $variables = [$val->user->manager->firstname,$val->certificate_no];
            Apphelper::Whatsapp_message($val->user->manager->mobile,$template_name,$variables);
        }
        dd('successs');
    }
}

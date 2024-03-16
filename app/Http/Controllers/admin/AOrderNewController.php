<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\AppHelper;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Pickups;
use App\Models\OrderComment;
use App\Models\TimelineCycle;

class AOrderNewController extends Controller
{
    //
    public function orderListNew(Request $request)
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
            $orders = Order::with('user','orderdetail','order_comment:order_id,created_at','pickups:orders_id,status,export_number','qc_list:order_id,qc_comment')
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
            $orders = Order::with('user','orderdetail','order_comment:order_id,created_at','pickups:orders_id,status,export_number','qc_list:order_id,qc_comment')
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

        $getrowcheck = Pickups::with('orders')
                        ->whereHas('orders',function($query){$query->where('orders.is_deleted', 0); })
                        ->whereIn('pickups.orders_id', $order_ids)
                        ->get();

        $data['getrowcheck'] = array_column($getrowcheck->toArray(), 'orders_id');

        return view('admin.orders.order-list-new')->with($data);
    }
    public function orderListComment(Request $request){
       $orderid=$request->order_id;
       $comment=$request->comment;
       $status=$request->status;
       $user_id=Auth::user()->id;

       $data= [
        'order_id'=>$orderid,
        'user_id'=>$user_id,
        'status'=>$status,
        'comment'=>$comment
       ];
       OrderComment::insert($data);
       return response()->json([
        'success'=>true,
        'message'=>"Data Inserted Successfully",
     ],201);
    }

    public function customerOrderApprove(Request $request){
         $order_id = $request->order_id;
         $status = $request->status;
         $comment=$request->comments;
         Order::where('orders_id',$order_id)->update(['order_status' => $status,'customer_comment'=>$comment]);
         $data['success'] = true;
         return json_encode($data);
    }

    public function updateEnquiryStatusNew(Request $request){
        $order_id = $request->order_id;
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
        TimelineCycle::insert([
            'order_id' => $order->orders_id,
            'certificate_no' => $order->certificate_no,
            'user_id' => Auth::user()->id,
            'flow' => $flow,
            'days_count' => intval((time() - strtotime($order->created_at))/(60*60*24)),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $data['success'] = true;
		echo json_encode($data);
    }

    public function ordercomment(Request $request){
        $order_id =$request->order_id;
        $data=OrderComment::with('users:id,firstname,lastname')->where('order_id',$order_id)->orderBy('created_at', 'desc')->get();
        $result="";
        if(count($data)>0){
            foreach($data as $record){
                if($record->status=='Completed'){
                    $status='<span class="badge badge-light-success">Completed</span>';
                }else{
                    $status='<span class="badge badge-light-primary">In Progress</span>';
                }
                $result .= '<tr class="odd snipcss0-7-196-197">
                                <td><div class="badge rounded-pill bg-light text-dark">' . $record->users->firstname.'</div></td>
                                <td  class="fs-5 fw-semibold mb-2 text-dark">' . ucfirst($record->comment) . '</td>
                                <td  class=" snipcss0-8-197-201  text-dark">' . $status . '</td>
                                <td  class=" snipcss0-8-197-201">' .date('d M Y, h:i a', strtotime($record->created_at))  . '</td>
                        </tr>';
                  }
        }else{
            $result .= '<b>No Record Found</b>';
        }
        return json_encode($result);
    }

    public function AdminUpdateExchangeRateNew(Request $request)
    {
        $user_type = Auth::user()->user_type;
        $id = Auth::user()->id;

        $exchange_rate = $request->exchange_rate;
        $order_id = $request->id;

        Order::where('orders_id',$order_id)->update(['exchange_rate' => $exchange_rate]);

        $data['success'] = "exchange rate changed to ".$exchange_rate ;
        return json_encode($data);
    }
}

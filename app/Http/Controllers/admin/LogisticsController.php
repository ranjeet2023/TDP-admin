<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\AppHelper;
use Illuminate\Support\Facades\Auth;
use App\Exports\EdiDeclaration;
use Maatwebsite\Excel\Facades\Excel;
use Location;
use DB;
use PDF;

use App\Models\User;
use App\Models\Order;
use App\Models\Associates;
use App\Models\Customer;
use App\Models\ExportList;
use App\Models\Pickups;
use App\Models\ExportInvoice;
use App\Models\OrderItem;

class LogisticsController extends Controller
{
    public function checkPickup(Request $request) {

        $orders_id = $request->orders_id;
		$orders_ids = (explode(",", $orders_id));

        $getrowcheck = Order::with('pickups')
        ->whereHas('pickups',function($query) use($order_ids){ $query->whereIn('pickups.orders_id', $orders_ids); })
        ->where('orders.is_deleted', 0)
        ->count();

        return $getrowcheck;
    }
    public function pickupList(Request $request)
    {
        $data['pickup_data'] = Order::with('orderdetail','pickups','qc_list')
        ->whereHas('pickups',function($query){ $query->where('status', 'PENDING'); $query->where('export_number','=',''); })
        ->orderBy('created_at', 'desc')
        ->get();

        return view('admin.logistic.pickup-list')->with($data);
    }

    public function EditPickups(Request $request){
        $pickup_id = $request->pickup_id;
        $buy_rate = $request->value;
        $certificate_no = $request->certino;
        $location = $request->location;
        $pickup_date= $request->pickup_date;
        if($pickup_date == null){
            Pickups::where('pickup_id',$pickup_id)->update(['location'=>$location , 'current_location' => $location]);
        }
        else{
            Pickups::where('pickup_id',$pickup_id)->update(['location'=>$location , 'current_location' => $location , 'expected_delivery_at' => $pickup_date]);
            Order::where('certificate_no',$certificate_no)->update(['buy_price' => $buy_rate]);
        }

        $data['success'] = 'Changes Done Successfully!';
        return json_encode($data);
    }

    public function logisticsPickupDone(Request $request)
    {

        $detail = '';
		$ids = $request->ids;
        $order_id = $request->order_id;
		$location = $request->location;
		$qc_review_status = $request->qc_review_status;
		$qc_done_status = $request->qc_done_status;
        $status = $request->status;
		$date = date('Y-m-d H:i:s');

        $customer = Order::select('customer_id')->where('orders_id',$order_id)->first();
        $customer_id = $customer->customer_id;
        $title = 'Stone Recieved!';
        $body = 'Stone Recieved and on Hand!';
        AppHelper::setNotification($customer_id,$title,$body,$date);

		$set_array = array("updated_at" => $date, 'status' => 'PICKUP_DONE', 'updated_by' => Auth::user()->id);
		// if ($location == 'Direct Ship Hongkong') {
		// 	$set_array['location'] = 'Hongkong';
		// 	$set_array['status'] = 'PENDING';
		// }
        Pickups::where('pickup_id', $ids)->update($set_array);

		// $Add_data = array(
		// 	'certi_no' => '0',
		// 	'confirm_goods_id' => '0',
		// 	'user_id' => $this->session->userid,
		// 	'module' => 'Pickup Done',
		// 	'comment' => 'Pickup status: PICKUP_DONE city: '.$location.'',
		// 	'created_at' => date("Y-m-d H:i:s")
		// );
		// $this->Model->ci_live_id('diamond_history', $Add_data);

		$data['success'] = "Return done successfully!";
		echo json_encode($data);
    }


    public function logisticsStonedetails(Request $request){
        $orders_id = $request->orders_id;
        $certificate = $request->certificate;
        $pickup_id = $request->pickup_id;
        $value = Order::with('orderdetail','user')
                ->where('orders_id',$orders_id)
                ->first();
        $detail = '';
        if (!empty($value)) {
            $detail = '<div class="d-flex flex-column flex-xl-row">
                            <div class="d-print-none border border-dashed border-gray-300 card-rounded h-lg-100 min-w-md-350px p-9 bg-lighten me-2">
                                <div class="mw-300px">';
                                    $detail .= '<img class="changeimage" src="'.$value->orderdetail->image.'" style="border-radius: 20px;padding: 3px;border-radius: 20px;padding: 3px;width: 100%; }"/>';

                                    $detail .= '<div class="col-lg-12 col-md-12" style="padding-left: 2px;cursor:pointer;">';
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
                                        <div class="text-end fw-bolder fs-6 text-gray-800">' . $value->orderdetail->certificate_no . '</div>
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
                                        <div class="fw-bold pe-10 text-gray-600 fs-7">Customer C</div>
                                        <div class="text-end fw-bolder fs-6 text-gray-800">' . $value->customer_comment . '</div>
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
                                </div>
                                <div class="mw-300px">
                                    <div class="d-flex flex-stack">
                                        <div class="fw-bold pe-10 text-gray-600 fs-7">reject reason</div>
                                        <div class="text-end fw-bolder fs-6 text-gray-800">' . $value->reject_reason . '</div>
                                    </div>
                                </div>

                            </div>
                        </div>';

            $data['detail'] = $detail;
        }
        else
        {
            $data['error'] = false;
        }
        return json_encode($data);
    }

    public function QcReviewInoutUpdate(Request $request)
    {
        $comment = $request->comment;
        $status = $request->status;
        $order_id = $request->order_id;

        $qc_array = array(
            'orders_id' => $order_id,
            'updated_by' => Auth::user()->id,
            'qc_status' => $status,
            'comment' => $comment,
            'created_at' => date('Y-m-d H:i:s'),
        );
        DB::table('quality_checks')->insert($qc_array);

        $data['success'] = true;
        echo json_encode($data);
    }

    public function ReturnList()
    {
        $data['pickup_data'] = Order::with('orderdetail','pickups')
        ->whereHas('pickups',function($query){ $query->where('pickups.status', 'QCRETURN'); $query->where('export_number','=',''); })
        ->orderBy('created_at', 'desc')
        ->get();

        return view('admin.logistic.return-list')->with($data);
    }

	public function ExportList(Request $request){
		$user_id = auth::user()->id;
        $user_type = Auth::user()->user_type;

        $data['permission'] = $permission = AppHelper::userPermission($request->segment(1));

        if(!empty($permission) && ($user_type == 1 || $permission->full == 1)){
            $data['exports'] = ExportList::where('export_list.is_delete','=','0')->orderBy('export_list.created_at','desc')->get();
            $data['invoices'] = ExportInvoice::select('export_invoice.*',
                                    DB::RAW('(SELECT firstname FROM users WHERE users.id=export_invoice.updated_by) as updated_by_user'),
                                    DB::RAW('(SELECT name FROM associates WHERE associates.id=export_invoice.from_associate) as from_associate_name'),
                                    DB::RAW('(SELECT name FROM associates WHERE associates.id=export_invoice.to_associate) as to_associate_name'),
                                    )
                                ->where('is_delete',0)->get();
            // dd($data['invoices']);
        }
        else
        {
            return redirect('admin');
        }

		return view('admin.logistic.export-list')->with($data);
	}

    public function ExportListdiamond(Request $request){
        $exp_no = $request->exp_no;
        $orders = Order::select('ref_no','orders_id','certificate_no','sale_price','customer_comment')
                  ->with('orderdetail:orders_id,supplier_name,shape,id,carat,color,clarity,cut,polish,symmetry,fluorescence,lab,carat','pickups:orders_id,invoice_number,export_invoice')
                  ->whereHas('pickups',function($query)use($exp_no){ $query->where('export_number',$exp_no); })->get();

        $detail = '';
        if (!empty($orders)) {
            $detail .= '<table class="table table-striped table-bordered exporderdetails">
                            <thead>
                                <tr>
                                    <th class="column-title"></th>
                                    <th class="column-title">Supplier</th>
                                    <th class="column-title">Shape</th>
                                    <th class="column-title">SKU</th>
                                    <th class="column-title">Ref No.</th>
                                    <th class="column-title">Carat</th>
                                    <th class="column-title">Color</th>
                                    <th class="column-title">Clarity</th>
                                    <th class="column-title">Cut</th>
                                    <th class="column-title">Polish</th>
                                    <th class="column-title">Symmetry</th>
                                    <th class="column-title">Fluorescence</th>
                                    <th class="column-title">Lab</th>
                                    <th class="column-title">Certificate</th>
                                    <th class="column-title">$/Ct</th>
                                    <th class="column-title">Sell Price</th>
                                    <th class="column-title">Comment</th>
                                    <th class="column-title">Invoice</th>
                                    <th class="column-title">Export Invoice</th>
                                </tr>
                            </thead>
                            <tbody>';
                            if (!empty($orders)) {
                                foreach($orders as $order){
                                    $detail.='
                                    <tr>
                                        <td><input type="checkbox" name="orders" data-orderid="'. $order->orders_id .'" class="orderscheck"></td>
                                        <td>'.$order->orderdetail->supplier_name.'</td>
                                        <td>'.$order->orderdetail->shape.'</td>
                                        <td>'.$order->orderdetail->id.'</td>
                                        <td>'.$order->ref_no.'</td>
                                        <td>'.$order->orderdetail->carat.'</td>
                                        <td>'.$order->orderdetail->color.'</td>
                                        <td>'.$order->orderdetail->clarity.'</td>
                                        <td>'.$order->orderdetail->cut.'</td>
                                        <td>'.$order->orderdetail->polish.'</td>
                                        <td>'.$order->orderdetail->symmetry.'</td>
                                        <td>'.$order->orderdetail->fluorescence.'</td>
                                        <td>'.$order->orderdetail->lab.'</td>
                                        <td>'.$order->certificate_no.'</td>
                                        <td>'.number_format(($order->sale_price/$order->orderdetail->carat),2).'</td>
                                        <td>'.number_format($order->sale_price,2).'</td>
                                        <td>'.$order->customer_comment.'</td>
                                        <td>'.(($order->pickups->invoice_number == null && $order->proforma_invoice != 0) ? 'per-'.$order->proforma_invoice : $order->pickups->invoice_number ) .'</td>
                                        <td>';
                                        if($order->pickups->export_invoice != null){
                                            $detail.='<a href= "'.url('/assets/export_invoice/',$order->pickups->export_invoice) .'" target="_blank"><button type="button" class="btn btn-warning btn-icon btn-sm"><i class="fa fa-download"></i></button></a>';
                                        }
                            $detail.='  </td>
                                    </tr>';
                                };
                            }
                $detail.='  </tbody>
                        </table>';

            $data['detail'] = $detail;
        }
        else
        {
            $data['error'] = false;
        }
        echo json_encode($data);
    }

    public function reffOrderExport(Request $request){
        $export_no = $request->export_no;
        $reff_no = $request->reff_no;
        $result = ExportList::where('reff_no',$reff_no)->where('is_delete','0')->first();
        if($result == null){
            ExportList::where('export_number',$export_no)->where('is_delete','0')->update(['reff_no'=>$reff_no]);
            $data['success'] = 'Export Status Updated!';
        }
        else{
            $data['error'] = 'This Number Is already Registered!';
        }
		echo json_encode($data);
    }

    public function UpdateExportStatus(Request $request){
        $export_no = $request->export_no;
        $ex_status = $request->ex_status;
        $receive_date = $request->receive_date;
        ExportList::where('export_number',$export_no)->where('is_delete','0')->update(['ex_status' => $ex_status , 'receive_date' => $receive_date]);
        $data['success'] = true;
		echo json_encode($data);
    }

	public function CancelExport(Request $request){
		$export_no = $request->export_no;
		$prev_location = Pickups::select('previous_location','destination')->where('export_number','=',$export_no)->first();

		$user_id = auth::user()->id;

		$update  = [
			'is_delete' => 1 ,
			'updated_by'=> $user_id,
            'updated_at'=> date('Y-m-d H:i:s') ];


		ExportList::where('export_number','=',$export_no)->update($update);

		Pickups::where('export_number','=',$export_no)->update(['export_number' => NULL,'status' => 'PICKUP_DONE','destination' => $prev_location->previous_location,'previous_location' => '']);

        $data['success'] = "Export Canceled Succesfully";

        return json_encode($data);
	}

	public function DownloadExport(Request $request){
		$filename=$request->id;
		return response()->download(public_path('assets/export/'.$filename));
	}

    public function directshippmentConfirmation(Request $request){
        $location = $request->location;
        $invoice = $request->invoice;
        $pickupid = $request->pickupid;
            $changes = Pickups::where('pickup_id',$pickupid)->update(['status'=>'IN_TRANSIT' , 'destination' => $location]);
            if($changes == 1){
                $data['success'] = 'Direct shippment Done!';
            }
            else{
                $data['success'] = 'Something Went Wrong!';
            }
        return json_encode($data);
    }

	public function GenerateMemo(Request $request)
    {

        $data['consignment'] = $request->consignee;
        $export_no = $request->exportno;
		if(date('m') > 3){
			$year = date('y')."-".date('y')+ 1;
		}
		else{
            $year = date('y')- 1 ."-".date('y');
		}
		$data['year'] = $year;
        if($data['consignment'] == 1){
            $exp_no = 'EXPCON-'.$export_no.'/'.$year;
            $sheetname = 'EDI DATA EXPCON - '.$export_no;
            $extra= ' ON CONSIGNMENT BASIS';
        }
        else{
            $exp_no = 'EXP-'.$export_no.'/'.$year;
            $sheetname = 'EDI DATA EXP - '.$export_no;
            $extra='';
        }
        $order_id = explode(',',$request->order_id);
        $invoice_empty = Pickups::whereIn('orders_id',$order_id)->where('invoice_number','=',NULL)->get();

        $qc_list_empty = DB::table('qc_list')->whereIn('order_id',$order_id)->count();

        // if(count($invoice_empty) == 0){
            if(count($order_id) == $qc_list_empty)
            {
            $export = ExportList::where('export_number','=',$exp_no)->where('is_delete',0)->first();
            if($export == null){
                $consignment = $request->consignee;
                $generated_by = $request->generated_by;
                $customer_id = $request->customer;
                $data['weight_box'] = $request->weight_box;
                $data['shipping_charge'] = $request->shipping_charge;
                $data['customer'] = Customer::with('user')->whereHas('user',function($query) use($customer_id){ $query->where('users.id',$customer_id); })->first();
                $annexure_file_name = $export_no.'_annexure_'.time().'.pdf';
                $annexureA_file_name = $export_no.'_annexure-A_'.time().'.pdf';
                $declaration_file_name = $export_no.'_declaration_'.time().'.pdf';
                $add_rap_file_name = $export_no.'_add_rap_'.time().'.pdf';
                $pack_list_file_name = $export_no.'_pack_list_'.time().'.pdf';
                $invoice_file_name = $export_no.'_invoice_'.time().'.pdf';
                $filename_edi_excel = $export_no.'_edi_data'.time().'.xlsx';
                $time = date('Y-m-d H:i:s');
                $count = count($order_id);
                $date = date('d-m-Y');
                $natural_ordered_all = Order::with('orderdetail')->whereHas('orderdetail',function($query)use($order_id) {$query->whereIn('orders_id',$order_id); $query->where('diamond_type','=','W'); });

                $data['order_stones_natural'] = $natural_ordered_all->get();
                $data['natural_diamond_carat'] = $natural_ordered_all->sum('carat');
                $data['natural_diamond_pcs'] = $natural_ordered_all->count();
                $data['natural_diamond_rate'] = 0;
                $data['natural_diamond_net_value'] = 0;
                if($data['natural_diamond_pcs'] > 0){
                    foreach($data['order_stones_natural'] as $natural_stone){
                            if($request->consignee == 0 && ($customer_id == \Cons::ASSOCIATE_HK_ID || $customer_id == \Cons::ASSOCIATE_USA_ID)){
                            $data['natural_diamond_rate'] += (round($natural_stone->sale_price,2)-15)/$natural_stone->carat;
                            $data['natural_diamond_net_value'] += (round($natural_stone->sale_price,2))-15;
                        }
                        else{
                            // $data['natural_diamond_rate'] += round($natural_stone->sale_rate,2);
                            $data['natural_diamond_net_value'] += round($natural_stone->sale_price,2);
                        }
                    }
                    $data['natural_diamond_rate'] = $data['natural_diamond_net_value']/$data['natural_diamond_carat'];
                }

                $lab_ordered_all = Order::with('orderdetail')->whereHas('orderdetail',function($query)use($order_id) {$query->whereIn('orders_id',$order_id); $query->where('diamond_type','=','L'); });

                $data['order_stones_lab'] = $lab_ordered_all->get();
                $data['lab_diamond_carat'] = $lab_ordered_all->sum('carat');
                $data['lab_diamond_pcs'] = $lab_ordered_all->count();
                $data['lab_diamond_rate'] = 0;
                $data['lab_diamond_net_value'] = 0;
                if($data['lab_diamond_pcs'] > 0){
                    foreach($data['order_stones_lab'] as $lab_stone){
                            if($request->consignee == 0 && ($customer_id == \Cons::ASSOCIATE_HK_ID || $customer_id == \Cons::ASSOCIATE_USA_ID)){
                            $data['lab_diamond_rate'] += (round($lab_stone->sale_price,2)-15)/$lab_stone->carat;
                            $data['lab_diamond_net_value'] += (round($lab_stone->sale_price,2))-15;
                        }
                        else{
                            // $data['lab_diamond_rate'] += round($lab_stone->sale_rate,2);
                            $data['lab_diamond_net_value'] += round($lab_stone->sale_price,2);
                        }
                    }
                    $data['lab_diamond_rate'] = $data['lab_diamond_net_value']/$data['lab_diamond_carat'];
                }

                $lab_array = json_decode(json_encode($data['order_stones_lab']), true);
                $natural_array = json_decode(json_encode($data['order_stones_natural']), true);

                $object = array_merge( $natural_array , $lab_array);

                $data['total_carat'] = $data['natural_diamond_carat'] + $data['lab_diamond_carat'];
                $data['subtotal_amount'] =$data['natural_diamond_net_value'] + $data['lab_diamond_net_value'];
                $data['total_amount'] = $data['subtotal_amount'] + $request->shipping_charge;
                $data['wordnumber'] = AppHelper::convert_number_to_words($data['total_amount']);

                $data['pre_carriage'] = $request->pre_carriage;


                $data['date'] = $date;
                $data['exp_no'] = $export_no;

                $data['broker_name'] = $request->brokername;

                $associate_id = $request->associate;

                $data['associate'] = associates::where('id',$associate_id)->first();

                Pickups::whereIn('orders_id',$order_id)->update(['export_number' => $exp_no,"export_no" => $export_no]);

                $dataarray = array(
                    "export_no" => $export_no,
                    "export_number" => $exp_no,
                    "stone_count" => $count,
                    "generated_by" => $generated_by,
                    "customer" => $customer_id,
                    "shipping_charge" => $request->shipping_charge,
                    "total_amount" => $data['total_amount'],
                    "annexure" => $annexure_file_name,
                    "annexure_A" => $annexureA_file_name,
                    "declaration" => $declaration_file_name,
                    "address_rap" => $add_rap_file_name,
                    "packaging_list" => $pack_list_file_name,
                    "invoice" => $invoice_file_name,
                    "edi_excel" => $filename_edi_excel,
                    "created_at" => date('Y-m-d H:i:s'),
                );
                ExportList::insert($dataarray);

                $result_edi = Excel::store(new EdiDeclaration($object,$exp_no,$sheetname,$extra,$data['customer']->user->companyname,$consignment), ($filename_edi_excel), 'export_folder');


                $export_invoice_pdf = PDF::loadview('admin.template.export_invoice',$data);
                $export_packing_list_pdf = PDF::loadview('admin.template.export_packing_list',$data)->setPaper('a4', 'landscape');
                $export_add_rap_pdf = PDF::loadview('admin.template.export_address_rap',$data);
                $export_declaration_pdf = PDF::loadview('admin.template.export_declaration',$data);
                $export_annexure_A_pdf = PDF::loadview('admin.template.export_annexure-A',$data);
                $export_annexure_pdf = PDF::loadview('admin.template.export_annexure');

                // // save pdf

                $export_invoice_pdf->save('assets/export/'. $invoice_file_name);
                $export_packing_list_pdf->save('assets/export/'. $pack_list_file_name);
                $export_add_rap_pdf->save('assets/export/'. $add_rap_file_name);
                $export_declaration_pdf->save('assets/export/'. $declaration_file_name);
                $export_annexure_A_pdf->save('assets/export/'. $annexureA_file_name);
                $export_annexure_pdf->save('assets/export/'. $annexure_file_name);

                $data['success'] = "Export Generate successfully!";
            }
            else{
                $data['warning'] = "Export already generated!";
            }
        }
            else
            {
                $data['warning'] = "Some of stone not qc Done. Please scan and check QC comment.";
        }
        // }
        // else{
        //     $data['warning'] = "Generate Invoice First!";
        // }
        // $data['pdf'] = '';
		return json_encode($data);
    }

}


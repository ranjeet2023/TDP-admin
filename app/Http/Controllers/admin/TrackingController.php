<?php

namespace App\Http\Controllers\admin;


use App\Http\Controllers\Controller;
use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Helpers\AppHelper;
use DB;
use PDF;

use App\Exports\EdiDeclaration;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\Associates;
use App\Models\Customer;
use App\Models\ExportList;
use App\Models\Pickups;
use App\Models\ShippingDestination;
use App\Models\ExportInvoice;
use App\Models\OrderItem;
use App\Models\Order;
use App\Models\TimelineCycle;
use App\Models\QCList;

class TrackingController extends Controller
{

    public function inOutList(Request $request){
        $data['status'] = '';
        $data['desti'] = '';
        $data['search'] = "";
        $trackings = Pickups::with('orders','orderitems','qc_list','orders.user','orders.customer','invoices:invoice_number,final_destination');
        if($request->ismethod('post') == true){
            $submit = $request->submit;
            $status = $request->status;
            $destination = $request->destination;
			$search = $request->search;
            if($search != null){
                $certificate_ids = explode(" ",$search);
			    $trackings = $trackings->whereHas('orders',function($query)use($certificate_ids){ $query->whereIn('orders.certificate_no',$certificate_ids);}) ;
            }
            if($request->search != null){
                $data['search'] = $request->search." ";
            }

            $data['status'] = $status;
            $data['desti'] = $destination;

            $trackings->when($status != null, function ($query) use ($status) {
                return $query->where('pickups.status',$status);
            });
            $trackings->when($status == null, function ($query) use ($status) {
                return $query->whereNotIn('pickups.status',['REACHED','QCRETURN']);
            });
            $trackings->when($destination != null, function ($query) use ($destination) {
                return $query->where('pickups.destination',$destination);
            });

        }
        else{
            $trackings = $trackings->whereNotIn('pickups.status',['REACHED','QCRETURN']);
        }
        $data['trackings'] = $trackings->orderBy('pickups.created_at','desc')->get();
        $data['associates'] = Associates::select('id','name')->get();
        $data['destinations'] = Pickups::select('destination')->groupBy('destination')->get();
		$data['customers'] = Customer::with('user')->whereHas('user',function($query){ $query->orderby('companyname','ASC');})->get();

        return view('admin.logistic.in-out-list')->with($data);
    }

    Public function trackingStatusUpdate(Request $request){
        $status = $request->status;
        $order_id = $request->order_id;
        $date = date('Y-m-d H:i:s');
        if($status == 'PICKUP_DONE'){
            $pickup = Pickups::where('orders_id',$order_id)->where('previous_location','')->first();
            if($pickup != null){
                // TimelineCycle::insert([
                //     'order_id' => $pickup->orders_id,
                //     'certificate_no' => $pickup->cerificate_no,
                //     'user_id' => Auth::user()->id,
                //     'flow' => 'Recieved For QC',
                //     'days_count' => intval((time() - strtotime($pickup->created_at))/(60*60*24)),
                //     'created_at' => date('Y-m-d H:i:s'),
                // ]);
            }
            Pickups::where('orders_id',$order_id)->update(['status'=>$status, 'updated_at' => $date ]);
            $data['success'] = "Received successfully!";
        }
        elseif($status == 'QCREVIEW'){
            $comment = $request->comment;
            $order = Order::with('pickups:orders_id,updated_at')->where('orders_id',$order_id)->first();
            DB::table('qc_list')->updateOrInsert([
                'order_id' => $order_id,
            ],[
                'qc_comment' => $comment,
                'created_at' => $date,
            ]);
            TimelineCycle::insert([
                'order_id' => $order_id,
                'certificate_no' => $order->certificate_no,
                'user_id' => Auth::user()->id,
                'flow' => 'Done QC',
                'days_count' => intval((time() - strtotime($order->pickups->updated_at))/(60*60*24)),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            $data['success'] = "QC Comment Added!";
        }
        elseif($status == 'bulk_receive'){
            $order_id = explode(',',$order_id);
            $pickups = Pickups::wherein('orders_id',$order_id)->where('previous_location','')->get();
            foreach($pickups as $pickup){
                // TimelineCycle::insert([
                //     'order_id' => $pickup->orders_id,
                //     'certificate_no' => $pickup->cerificate_no,
                //     'user_id' => Auth::user()->id,
                //     'flow' => 'Recieved For QC',
                //     'days_count' => intval((time() - strtotime($pickup->created_at))/(60*60*24)),
                //     'created_at' => date('Y-m-d H:i:s'),
                // ]);
            }
            Pickups::wherein('orders_id',$order_id)->update(['status'=>'PICKUP_DONE', 'updated_at' => $date ]);
            $data['success'] = "Received successfully!";

        }
        elseif($status == 'QCRETURN'){
            Pickups::where('orders_id',$order_id)->update(['status'=>$status, 'updated_at' => $date ]);
            $data['success'] = "Return successfully!";

        }
        elseif($status == 'REACHED'){
            $orders_id = explode(',',$order_id);
            foreach($orders_id as $order_id){
                $prev_destination = Pickups::where('orders_id',($order_id))->select('destination')->first();
                Pickups::where('orders_id',$order_id)->update(['status'=>$status, 'updated_at' => $date,'previous_location'=>$prev_destination->destination ]);
            }
            $data['success'] = "Send To Customer successfully!";
        }
        elseif($status = 'EDIT_INOUT'){
            $location = $request->location;
            $destination = $request->destination;
            Pickups::where('orders_id',$order_id)->update(['location' => $location,'destination' => $destination, 'updated_at' => $date]);
            $data['success'] = "In Out Edited successfully!";

        }
        return json_encode($data);
    }

    Public function trackingExport(Request $request){

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

        $prev_destination = Pickups::where('orders_id',($order_id['0']))->select('destination')->first();

        $qc_list_empty = DB::table('qc_list')->whereIn('order_id',$order_id)->count();

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
                $data['address'] = ShippingDestination::with('user')->where('customer_id',$customer_id)->where('by_default',1)->first();
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
                $natural_ordered_all = OrderItem::with('orders')->whereIn('orders_id',$order_id)->where('diamond_type','=','W');
                $data['order_stones_natural'] = $natural_ordered_all->get();
                $data['natural_diamond_carat'] = $natural_ordered_all->sum('carat');
                $data['natural_diamond_pcs'] = $natural_ordered_all->count();

                $data['natural_diamond_rate'] = 0;
                $data['natural_diamond_net_value'] = 0;
                if($data['natural_diamond_pcs'] > 0){
                    foreach($data['order_stones_natural'] as $natural_stone){
                        if($request->consignee == 0 && ($customer_id == \Cons::ASSOCIATE_HK_ID || $customer_id == \Cons::ASSOCIATE_USA_ID || $customer_id == \Cons::ASSOCIATE_AUS_ID)){
                            // $data['natural_diamond_rate'] += (round(($natural_stone->orders->sale_rate),2)-15);
                            $data['natural_diamond_net_value'] += (round(($natural_stone->orders->sale_rate * $natural_stone->carat),2))-15;
                        }
                        else{
                            // $data['natural_diamond_rate'] += round($natural_stone->sale_rate,2);
                            $data['natural_diamond_net_value'] += round(($natural_stone->orders->sale_rate * $natural_stone->carat),2);
                        }
                    }
                    $data['natural_diamond_rate'] = $data['natural_diamond_net_value']/$data['natural_diamond_carat'];
                }
                $lab_ordered_all = OrderItem::with('orders')->whereIn('orders_id',$order_id)->where('diamond_type','=','L');

                $data['order_stones_lab'] = $lab_ordered_all->get();
                $data['lab_diamond_carat'] = $lab_ordered_all->sum('carat');
                $data['lab_diamond_pcs'] = $lab_ordered_all->count();

                $data['lab_diamond_rate'] = 0;
                $data['lab_diamond_net_value'] = 0;
                if($data['lab_diamond_pcs'] > 0){
                    foreach($data['order_stones_lab'] as $lab_stone){
                        if($request->consignee == 0 && ($customer_id == \Cons::ASSOCIATE_HK_ID || $customer_id == \Cons::ASSOCIATE_USA_ID || $customer_id == \Cons::ASSOCIATE_AUS_ID)){
                            // $data['lab_diamond_rate'] += (round($lab_stone->orders->sale_rate,2)-15);
                            $data['lab_diamond_net_value'] += (round(($lab_stone->orders->sale_rate * $lab_stone->carat),2))-15;
                        }
                        else{
                            // $data['lab_diamond_rate'] += round($lab_stone->sale_rate,2);
                            $data['lab_diamond_net_value'] += round(($lab_stone->orders->sale_rate * $lab_stone->carat),2);
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

                $orders = Order::with('qc_list:order_id,created_at')->select('orders.orders_id','orders.certificate_no','orders.port')->wherein('orders_id',$order_id)->get();

                foreach($orders as $order){
                    // TimelineCycle::insert([
                    //     'order_id' => $order->orders_id,
                    //     'certificate_no' => $order->certificate_no,
                    //     'user_id' => Auth::user()->id,
                    //     'flow' => 'Exported To '.$data['customer']->country,
                    //     'days_count' => intval((time() - strtotime($order->qc_list->created_at))/(60*60*24)),
                    //     'created_at' => date('Y-m-d H:i:s'),
                    // ]);

                    $data['warning'] = '';
                    if($order->port == null){
                        $data['warning'] .= "Port Has Not Added!".$order->certificate_no;
                        // return json_encode($data);
                    }
                }
                $data['associate'] = associates::where('id',$associate_id)->first();
                if($customer_id == \Cons::ASSOCIATE_HK_ID || $customer_id == \Cons::ASSOCIATE_USA_ID || $customer_id == \Cons::ASSOCIATE_AUS_ID){
                    $tracking_array = ["export_number" => $exp_no ,'status' => 'IN_TRANSIT','destination' =>$data['customer']->country,'previous_location'=>$prev_destination->destination];
                }
                else{
                    $tracking_array = ["export_number" => $exp_no ,'status' => 'REACHED','destination' =>$data['customer']->country,'previous_location'=>$prev_destination->destination];
                }

                Pickups::whereIn('orders_id',$order_id)->update($tracking_array);

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

                $result_edi = Excel::store(new EdiDeclaration($object, $exp_no, $sheetname, $extra, $customer_id, $consignment), ($filename_edi_excel), 'export_folder');


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

    public function ExportInvoicePopup(Request $request){
        $render_msg = '';
        $orderid = $request->orderid;
        $array_order = explode(',',$orderid);
        $diamonds = Order::with('orderdetail')->whereIn('orders_id',$array_order)->get();

        if(count($diamonds) > 0){
            $associate = Associates::all();
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
            foreach ($diamonds as $sale_row) {
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
            $responce_array['associate'] = $associate;
            $responce_array['orderid'] = $orderid;
        }
        else{
            $responce_array['error'] = false;
        }
        return json_encode($responce_array);
    }

    public function ExportInvoice(Request $request){
        $pdfdataname = date('ymd')."_".time();

        $pdfname = date('ymd')."_".time() .'.pdf';
        $orderid = $request->orderid;

        $array_order = explode(',',$orderid);
        $no_of_stones = count($array_order);

        $prev_destination = Pickups::where('orders_id',($array_order['0']))->select('destination')->first();

        $associate_id = $request->associate;
        $customer_id = $request->customer;
        $discount_extra_order = $request->discount_extra_order;
        $shipping_charge = $request->shipping_charge;

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
		}
        else{
            $amount_description = $request->shipping;
			$insurrance = "";
        }

        $customer = Associates::select('*','name as companyname')->where('id',$customer_id)->first();

        $associate = Associates::where('id',$associate_id)->first();

        $diamond_natural = $diamond_html = $diamond_lab = '';

        $total_amount = $totalcarat = $hsn = $pcs = 0;

        foreach($array_order as $order_id){
            $order_detail = Order::with('orderdetail')->where('orders_id',$order_id)->first();

            if($order_detail->diamond_type == 'L'){
                $hsn = $customer->hsn_code_lab;
                $diamond_lab ='LAB GROWN';
            }
            else{
                $hsn = ($order_detail->orderdetail->carat > 0.99) ? $customer->hsn_code_natural : $customer->hsn_code_natural_one;
                $diamond_natural ='NATURAL';
            }

            $totalcarat = $totalcarat + $order_detail->orderdetail->carat;
            $total_amount = $total_amount + round($order_detail->sale_price, 2);

            $pcs++;

            $diamond_html .= '<tr>
                                <td align="center">' . $pcs . '</td>
                                <td >' . $order_detail->orderdetail->lab . '-' . $order_detail->certificate_no . ' ' . $order_detail->orderdetail->shape . ' ' . $order_detail->orderdetail->color . '-' . $order_detail->orderdetail->clarity . '-' . $order_detail->orderdetail->cut . '-' . $order_detail->orderdetail->polish . '-' . $order_detail->orderdetail->symmetry .'-' . $order_detail->orderdetail->fluorescence .' &nbsp;| '. number_format($order_detail->orderdetail->length, 2) . '*' . number_format($order_detail->orderdetail->width, 2) . '*' . number_format($order_detail->orderdetail->depth, 2) . '</td>
                                <td style="border-left:2px solid #333;" align="center">' . $hsn .'</td>
                                <td style="border-left:2px solid #333;" align="center">1</td>
                                <td style="border-left:2px solid #333;" align="center">' . $order_detail->orderdetail->carat . '</td>
                                <td style="border-left:2px solid #333;" align="center">$' . round($order_detail->sale_rate, 2) . '</td>
                                <td style="border-left:2px solid #333;" align="center">$' . round($order_detail->sale_price, 2) . '</td>
                            </tr>';

        }

        $extra_discount = !empty($discount_extra_order) ? $discount_extra_order : '0';

        $final_amount = round(($total_amount + $shipping_charge) - $extra_discount, 2);
        $wordnumber = AppHelper::convert_number_to_words($final_amount);

        if(!empty($diamond_natural) && !empty($diamond_lab))
        {
            $response_array['diamondsname'] = $diamond_natural.' & '.$diamond_lab;
        }
        else
        {
            $response_array['diamondsname'] = $diamond_natural.' '.$diamond_lab;
        }

        $response_array['pcs'] = $pcs;

        $response_array['final_amount'] = $final_amount;
        $response_array['date'] = date('Y-m-d');
        $response_array['wordnumber'] = $wordnumber;

        $response_array['shipping_charge'] = !empty($shipping_charge) ? $shipping_charge : 0;
        $response_array['discount_extra_order'] = $extra_discount;
        $response_array['totalcarat'] = $totalcarat;

        $response_array['diamond_html'] = $diamond_html;
        $response_array['insurrance'] = $insurrance;

        $response_array['companyname'] = $associate->name;
        $response_array['shipping_address'] = $associate->address;
        $response_array['mobile'] = $associate->mobile;
        $response_array['shiping_email'] = $associate->email;
        $response_array['firstname'] = '';

        $response_array['amount_description'] = $amount_description;
        $response_array['pre_carriage'] = $request->shipping;
        $response_array['as_carrier_place'] = 'Carrier';//$associate->carrier_place;
        $response_array['as_port_loading'] = 'HONG KONG';//$associate->port_loading;
        $response_array['portof_dischargeuser'] = 'HENDERSON/KENTUCKY';
        $response_array['finaldestination'] = 'United States';

        $response_array['ac_no'] = $customer->account_number;
        $response_array['bank_name'] = $customer->bank_name;
        $response_array['bank_address'] = $customer->bank_address;
        $response_array['swift_code'] = $customer->swift_code;
        $response_array['inter_bank_address'] = $customer->intermediary_bank;
        $response_array['inter_swift_code'] = $customer->intermediary_swift_code;
        $response_array['ad_code'] = $customer->address_code;

        $response_array['invoice_number'] = date('ymd');
        $response_array['importref'] = '87-2869232';//$customer->company_tax;

        $response_array['as_name'] = $customer->companyname;
        $response_array['as_address'] = $customer->address;
        $response_array['as_mobile'] = $customer->mobile;
        $response_array['as_email'] = $customer->email;
        $response_array['consignee'] = 0;



        $pdf = PDF::loadView('admin.orders.order_template', $response_array);
        $success = $pdf->save('assets/export_invoice/' . $pdfname);

        if($success == true){
            $array = array(
                "export_invoice_no" =>  $pdfdataname,
                "no_of_stones" =>  $no_of_stones,
                "from_associate" =>  $customer_id,
                "to_associate" =>  $associate_id,
                "updated_by" => Auth::user()->id,
                "orders_id" =>  $orderid,
                "created_at" => date('Y-m-d H:i:s'),
            );
            ExportInvoice::insert($array);

            Pickups::whereIn('orders_id',$array_order)->update(['export_invoice'=>$pdfdataname, 'status' => 'IN_TRANSIT' ,'destination' => $associate->country,'previous_location'=>$prev_destination->destination]);
            $data['success'] = 'Invoice Generated Successfully.';
        }
        else{
            $data['error'] = 'Cannot Generate Invoice.';
        }
		return json_encode($data);

    }
    public function CancelExportInvoice(Request $request){
        $order_id = $request->order_id;
        $array_order = explode(',',$order_id);
        $status = $request->status;
        $exportinvoiceno = $request->exportinvoiceno;
        $expinvoiceid = $request->expinvoiceid;
        $date = date('Y-m-d H:i:s');
        if($status == 'cancel-export-invoice'){
            $prev_location = Pickups::select('previous_location','destination')->where('export_invoice',$exportinvoiceno)->first();
            Pickups::whereIn('orders_id',$array_order)->update(['destination'=>$prev_location->previous_location,'status'=>'PICKUP_DONE','previous_location'=>'','export_invoice'=>'','updated_by'=>Auth::user()->id,'updated_at'=>$date]);
            ExportInvoice::where('exoprt_invoice_id',$expinvoiceid)->update(['is_delete' => 1,'updated_by'=>Auth::user()->id,'updated_at'=>$date]);
            $data['success'] = 'Export Invoice Cancelled Successfully';
        }
        return json_encode($data);
    }

}

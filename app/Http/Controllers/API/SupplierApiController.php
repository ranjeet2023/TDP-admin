<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

use App\Models\Supplier;
use App\Models\User;
use App\Models\DiamondNatural;
use App\Models\DiamondLabgrown;
use App\Models\StockUploadReport;
use App\Models\DiamondInvalid;
use App\Models\DiamondUnapprove;
use App\Models\ViewDiamondDetail;
use App\Models\Order;
use App\Models\SupplierInvoice;
use App\Models\Notification;

use DB;
use Session;
use Hash;

class SupplierApiController extends Controller
{
    public function SupplierDashboard(Request $exception)
    {
        $id = Auth::user()->id;
        $stock_report = StockUploadReport::where('supplier_id',$id)->latest()->first();
        $data['update_at'] = !empty($stock_report->file_updated_at)? $stock_report->file_updated_at:'';
        $data['upload_mode'] = !empty($stock_report->upload_mode) ? $stock_report->upload_mode: '';
        $supplier_detail = Supplier::where('sup_id',$id)->first();
        $data['diamond_type'] = $diamond_type =  $supplier_detail->diamond_type;

        if($diamond_type == 'Natural')
        {
             $data['diamond_count'] = DiamondNatural::where('supplier_id',$id)->where('is_delete',0)->count();
        }
        else
        {
             $data['diamond_count'] = DiamondLabgrown::where('supplier_id',$id)->where('is_delete',0)->count();
        }

        $order_diamond = Order::query()
        ->select('orders.*', 'orders_items.*')
        ->join('orders_items', 'orders_items.orders_id', '=', 'orders.orders_id')
        ->where('orders.is_deleted', 0)
        ->where('orders.hold', 0)
        ->where('orders_items.supplier_id', $id)
        ->get();

        $data['order_diamond_count'] = $order_diamond->count();

        $hold_dimond = Order::query()
            ->select('orders.*', 'orders_items.*')
            ->join('orders_items', 'orders_items.orders_id', '=', 'orders.orders_id')
            ->where('orders.is_deleted', 0)
            ->where('orders.hold', 1)
            ->where('orders_items.supplier_id', $id)
            ->get();

        $data['hold_diamond_count'] = $hold_dimond ->count();

        $data['diamond_click_count'] = ViewDiamondDetail::where('supplier_id',$id)
        ->where('is_delete',0)
        ->groupBy('customer_id')
        ->groupBy('certificate_no')
        ->get()
        ->count();

        $data['supplier_manager_details'] = array(
            'supplier_email'=>"supplier@thediamondport.com",
            'supplier_contact'=>'7698097905'
        );

        return response()->json([
            'success' => true,
            'message' => 'Supplier Dashboard ',
            'user_type' => 'Supplier',
            'data' => $data,
        ], 200);
    }

    public function SupplierProfile(Request $exception)
    {
        $user_id =  auth()->user()->id;
        $data = User::with('supplier')->where('id',$user_id)->first();
        return response()->json([
            'success' => true,
            'message' => 'Supplier Profile ',
            'user_type' => 'Supplier',
            'data' => $data,
        ], 200);
    }

    public function SupplierProfileUpdate(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'firstname' => 'required|min:2|max:25',
            'lastname' => 'required|min:3|max:25',
            'mobile' => 'required|max:15',
            'c_register_upload' => 'mimes:pdf,jpeg,png,jpg',
            'c_partnet_doc' => 'mimes:pdf,jpeg,png,jpg',
            'c_register_no' => 'required|min:5|max:25',
            'c_partnet_name' => 'required|min:2|max:50',
            'address' => 'required',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required'
        ]);

        if ($validate->fails()) {
            $message = $validate->errors();
            return response()->json([
                'success' => false,
                'message' => $message
            ]);
        } else {
            $supplier_id = Auth::user()->id;

            $suppliers = Supplier::where('sup_id', $supplier_id)->first();
            $c_register_upload = '';
            $c_partnet_doc = '';

            if (!empty($request->file('c_register_upload'))) {
                $c_register_upload = str_replace(array(' ', ',', '+', '&', '=', '(', ')'), '-', time() . $request->file('c_register_upload')->getClientOriginalName());
                $request->file('c_register_upload')->storeAs('suppliers_doc', $c_register_upload, 'public');
            } else {
                $c_register_upload = $suppliers->compnay_registration_document;
            }

            if (!empty($request->file('c_partnet_doc'))) {
                $c_partnet_doc = str_replace(array(' ', ',', '+', '&', '=', '(', ')'), '-', time() . $request->file('c_partnet_doc')->getClientOriginalName());
                $request->file('c_partnet_doc')->storeAs('suppliers_doc', $c_partnet_doc, 'public');
            } else {
                $c_partnet_doc = $suppliers->compnay_partner_document;
            }

            User::where('id', $supplier_id)
                ->update([
                    'firstname' => $request->firstname,
                    'lastname' => $request->lastname,
                    'mobile' => $request->mobile,
                ]);

            Supplier::where('sup_id', $supplier_id)
                ->update([
                    'compnay_registration_document' => $c_register_upload,
                    'compnay_partner_document' => $c_partnet_doc,
                    'compnay_registration_number' => $request->c_register_no,
                    'compnay_partner_name' => $request->c_partnet_name,
                    'address' => $request->address,
                    'country' => $request->country,
                    'state' => $request->state,
                    'city' => $request->city,
                    'company_name' => $request->company_name,
                    'bank_name' => $request->bank_name,
                    'account_holder_name' => $request->account_holder_name,
                    'ifsc_code' => $request->ifsc_code,
                    'account_number' => $request->account_number,
                    'branch_name' => $request->branch_name
                ]);
            return response()->json([
                'success' => true,
                'message' => "Profile Update Successful",
            ]);
        }
    }

    public function SupplerChangePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cpassword' => 'required',
            'newpassword' => ['required', Password::min(8)->mixedCase()->numbers()->symbols()],
        ]);

        $supplier_id = Auth::user()->id;
        $cpassword = $request->cpassword;
        $newpassword = hash::make($request->newpassword);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
            ]);
        } else {
            $get_password = User::where('id', $supplier_id)->first();
            $password_check = Hash::check($cpassword, $get_password->password);

            if ($password_check) {
                User::where('id', $supplier_id)->update([
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

    public function SearchDiamondList(Request $request)
    {

        $supplier_id = Auth::user()->id;
        $supplier_data = User::with('supplier')->where('id', $supplier_id)->first();

        $diamond_type = $supplier_data['supplier']['diamond_type'];
        if ($diamond_type == 'Natural') {
            $table = 'diamond_natural';
        } else {
            $table = 'diamond_labgrown';
        }

        $result_query = DB::table($table)->select(
            '*',
            DB::raw("(SELECT caratprice FROM rap_list WHERE IF(`shape_name` = 'ROUND', shape = 'ROUND', shape != 'ROUND') AND color_name = `color` AND clarity_name = IF(`clarity` = 'FL','IF', `clarity`) AND low_size <= carat AND high_size >= carat) as raprate")
        )
            ->where('carat', '>', 0.17)
            ->where('orignal_rate', '>', 50)
            ->where('supplier_id', $supplier_id);

        if (!empty($request->stoneid) && $request->stoneid != 'undefined') {
            $postdata = strtoupper($request->stoneid);
            $stoneid = str_replace('LG', '', $postdata);
            $result_query->where(function ($query) use ($stoneid) {
                $stoneids = explode(",", $stoneid);
                $query->orWhereIn('id', $stoneids);
                $query->orWhereIn('certificate_no', $stoneids);
            });
        } else if (!empty($request->certificateid) && $request->certificateid != 'undefined') {
            $postdata = $request->certificateid;
            $certificate_no = explode(",", $postdata);
            $result_query->whereIn('certificate_no', $certificate_no);
        } else {
            if (!empty($request->min_carat) && !empty($request->max_carat)) {
                $min_carat = (float)$request->min_carat;
                $max_carat = (float)$request->max_carat;
                if (!empty($request->min_carat) && !empty($request->max_carat)) {
                    $result_query->where('carat', '>=', $min_carat);
                }

                if (!empty($request->min_carat) && !empty($request->max_carat)) {
                    $result_query->where('carat', '<=', $max_carat);
                }
            } else {
                $result_query->where('carat', '>', 0.17);
                $result_query->where('carat', '<', 99.99);
            }

            if (!empty($request->shape)) {
                $result_query->whereIn('shape', explode(",", $request->shape));
            }

            if (!empty($request->clarities)) {
                $result_query->whereIn('clarity', explode(",", $request->clarities));
            }

            if (!empty($request->fancyorwhite)) {
                $result_query->where('color', 'fancy');
            } else {
                $result_query->where('color', '!=', 'fancy');
                if (!empty($request->color)) {
                    $result_query->whereIn('color', explode(",", $request->color));
                } else {
                    $result_query->whereIn('color', array('D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'OP', 'QR', 'ST', 'UV', 'WX', 'YZ'));
                }
            }

            if (!empty($request->cuts)) {
                $cut_array = explode(",", $request->cuts);
                $cut_array[] = '';
                $result_query->whereIn('cut', $cut_array);
            }

            if (!empty($request->polishes)) {
                $result_query->whereIn('polish', explode(",", $request->polishes));
            }

            if (!empty($request->symmetries)) {
                $result_query->whereIn('symmetry', explode(",", $request->symmetries));
            }

            if (!empty($request->fluorescences)) {
                $result_query->whereIn('fluorescence', explode(",", $request->fluorescences));
            }

            if (!empty($request->lab)) {
                $result_query->whereIn('lab', explode(",", $request->lab));
            }

            if (!empty($request->location)) {
                $result_query->whereIn('country', explode(",", $request->location));
            }
        }

        $result_query->where('location', 1);
        $result_query->where('status', '0');
        $result_query->where('is_delete', 0);

        $result = $result_query->paginate();

        $updatedItems = $result->getCollection();
        $diamond = array();

        if (!empty($updatedItems)) {
            foreach ($updatedItems as $value) {
                $carat = $value->carat;
                $b_price = 0;
                $carat_price = $value->orignal_rate;
                $net_price = $carat_price * $carat;
                $discount_main = !empty($value->raprate) ? round(($carat_price - $value->raprate) / $value->raprate * 100, 2) : 0;


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
                $d_result['rate'] = (string)$carat_price;
                $d_result['net_price'] = (string)$net_price;
                $d_result['discount_main'] = number_format($discount_main, 2);
                $d_result['raprate'] = $value->raprate;

                $d_result['country'] = $value->country;
                $d_result['image'] = $value->image;
                $d_result['video'] = $value->video;
                $d_result['certi_link'] = $value->certificate_link;

                $diamond[] = $d_result;
            }
            $result->setCollection(collect($diamond));
            return response()->json([
                'success' => true,
                'message' => "Stone Found!",
                'data' => $result
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => "No Record Found!!"
            ]);
        }
    }

    public function SupplieUploadHistory(Request $exception)
    {
        $supplier_id = Auth::user()->id;
        $data = User::with('supplier')->where('id', $supplier_id)->first();
        if ($data->is_active == 1) {
            $detail_responce = StockUploadReport::where('supplier_id',$supplier_id)->orderBy('created_at', 'desc')->limit(20)->get();
            if(!empty($detail_responce)) {
                return response()->json([
                    'success' => true,
                    'message' => "List Found!",
                    'data' => $detail_responce
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No Data found'
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Account not activate',
            ]);
        }
    }

    public function SupplierDiamond(Request $request)
    {
        $supplier_id = Auth::user()->id;
        $data = User::with('supplier')->where('id', $supplier_id)->first();
        if ($data->is_active == 1) {
            if ($data['supplier']['stock_status'] == 'ACTIVE') {
                if ($data['supplier']['diamond_type'] == 'Natural') {
                    $natural_diamond = DiamondNatural::where('supplier_id', $supplier_id)->where('is_delete', 0)->get();
                    $natural_diamond_count = $natural_diamond->count() . '  Natural Diamond Found';

                    return response()->json([
                        'success' => true,
                        'message' => $natural_diamond_count,
                        'data' => $natural_diamond
                    ]);
                } else {
                    $labgrown_diamond = DiamondLabgrown::where('supplier_id', $supplier_id)->where('is_delete', 0)->paginate();
                    $labgrown_diamond_count = $labgrown_diamond->count() . '  Labgrown Diamond Found';

                    return response()->json([
                        'success' => true,
                        'message' => $labgrown_diamond_count,
                        'data' => $labgrown_diamond
                    ]);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Stock Status Not'
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Account not activate',
            ]);
        }
    }

    public function InvalidDaimond()
    {
        $supplier_id = Auth::user()->id;
        $invalid_diamond = DiamondInvalid::select('supplier_id', 'supplier_name', 'reason', 'availability', 'ref_no', 'shape', 'carat', 'color', 'clarity', 'cut', 'polish', 'symmetry', 'fluorescence', 'lab', 'certificate_no', 'certificate_link', 'certificate_download', 'length', 'width', 'depth', 'location', 'city', 'milky', 'c_type', 'eyeclean', 'hna', 'depth_per', 'table_per', 'crown_angle', 'crown_height', 'pavilion_angle', 'pavilion_depth', 'discount', 'rap', 'orignal_rate', 'rate', 'net_dollar', 'key_symbols', 'fancy_intensity', 'fancy_overtone', 'image', 'video', 'heart', 'arrow', 'asset', 'canada_mark', 'cutlet', 'gridle', 'gridle_per', 'girdle_thin', 'girdle_thick', 'cert_comment', 'shade', 'luster', 'supplier_comments', 'culet_condition', 'country')
            ->where('supplier_id', $supplier_id)
            ->get();
        $invalid_diamond_count = $invalid_diamond->count() . ' Invalid Diamond Found';
        return response()->json([
            'success' => true,
            'message' => $invalid_diamond_count,
            'data' => $invalid_diamond
        ]);
    }

    public function Orders()
    {
        $supplier_id  = Auth::user()->id;

        $order_diamond = Order::query()
            ->select('orders.*', 'orders_items.*')
            ->join('orders_items', 'orders_items.orders_id', '=', 'orders.orders_id')
            ->where('orders.is_deleted', 0)
            ->where('orders.hold', 0)
            ->where('orders_items.supplier_id', $supplier_id)
            ->get();

        $count = $order_diamond->count() . "  Diamond On Your Order List";
        return response()->json([
            'success' => true,
            'message' => $count,
            'data' => $order_diamond
        ]);
    }

    public function HoldDiamond()
    {
        $supplier_id = Auth::user()->id;

        $order_diamond = Order::query()
            ->select('orders.*', 'orders_items.*')
            ->join('orders_items', 'orders_items.orders_id', '=', 'orders.orders_id')
            ->where('orders.is_deleted', 0)
            ->where('orders.hold', 1)
            ->where('orders_items.supplier_id', $supplier_id)
            ->get();

        $count = $order_diamond->count() . "  Diamond Hold";

        return response()->json([
            'success' => true,
            'message' => $count,
            'data' => $order_diamond
        ]);
    }

    public function InvoiceList()
    {
        $id = Auth::user()->id;
        $invoice_list = SupplierInvoice::where('sup_id', $id)->get();
        if(!empty($invoice_list)) {
            return response()->json([
                'success' => true,
                'message' => "Invoice List",
                'data' => $invoice_list
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No Data found'
            ]);
        }
    }

    public function AproveReject(Request $request)
    {
        $order_id = $request->orders_id;
        $certi_no = $request->certificate_no;
        $status = $request->status;
        $comment = $request->comment;

        $stonelocation =  $request->stonelocation;
        $certificatelocation = $request->certificatelocation;
        $bgm = $request->bgm;
        $eyeclean = $request->eyeclean;
        $milky = $request->milky;
        $brown = $request->brown;
        $green = $request->green;

        $hold = $request->hold;
        if ($hold == "YES") {
            $hold = "Hold For other";
        } else {
            $hold = 'Diamond Not Hold';
        }

        $sold = $request->sold;

        if ($sold == "YES") {
            $sold = "Diamond Sold";
        } else {
            $sold = 'Diamond Not Sold';
        }
        $reject_reason = $sold . ',' . $hold;

        $supplier_id = Auth::user()->id;
        $supplier_data = Supplier::join('users', 'users.id', '=', 'suppliers.sup_id')->where('sup_id', $supplier_id)->first();

        $supplier_email = $supplier_data->email;

        if ($status == "APPROVED") {
             Order::query()
                ->where('orders_id', $order_id)
                ->where('certificate_no', $certi_no)
                ->update([
                    'supplier_status' => $status,
                    'supplier_comment' => $comment,
                    'stone_location' => $stonelocation,
                    'certificate_location' => $certificatelocation,
                    'bgm' => $bgm,
                    'eyeclean' => $eyeclean,
                    'milky' => $milky,
                    'brown' => $brown,
                    'green' => $green,
                ]);

            return response()->json([
                'success' => true,
                'message' => "Diamond Approve Successful",
            ]);
        } else {
            Order::query()
                ->where('orders_id', $order_id)
                ->where('certificate_no', $certi_no)->update([
                    'supplier_status' => $status,
                    'supplier_reject_reson' => $reject_reason,
                    'supplier_reject_comment' => $comment
                ]);

            return response()->json([
                'success' => true,
                'message' => "Diamond Reject Successful",
            ]);
        }

        $singlerecord = '<table width="100%" style="border:#CEC9C9 solid thin;padding: 10px;font-size: 13px;">
                    <tr>
                        <td>Certificate No</td><td>' . $certi_no . '</td>
					</tr>
					<tr>
                        <td>status</td><td>' . $status . '</td>
					</tr>
                    <tr>
                        <td>Diamond location</td><td>' . $stonelocation . '</td>
					</tr>
                    <tr>
                        <td>certificate location</td><td>' . $certificatelocation . '</td>
					</tr>
                    <tr>
                        <td>comment</td><td>' . $comment . '</td>
					</tr>
                    <tr>
                        <td>bgm</td><td>' . $bgm . '</td>
					</tr>
                    <tr>
                        <td>eye clean</td><td>' . $eyeclean . '</td>
					</tr>
				</table>';

        $supplier_email_data = array();
        $supplier_email_data['firstname'] = $supplier_data->companyname;
        $supplier_email_data['text_message'] = $singlerecord;

        try {
            if ($status == "APPROVED") {
                Mail::send('emails.orders.hold-diamond-supplier-approved', $supplier_email_data, function ($message) use ($supplier_email, $request) {
                    $message->to($supplier_email);
                    $message->cc(\Cons::EMAIL_SUPPLIER);
                    $message->subject("Hold Diamond Approved #" . $request->certi_no . " | " . env('APP_NAME'));
                });
            } else {
                Mail::send('emails.orders.hold-diamond-supplier-reject', $supplier_email_data, function ($message) use ($supplier_email, $request) {
                    $message->to($supplier_email);
                    $message->cc(\Cons::EMAIL_SUPPLIER);
                    $message->subject("Hold Diamond Rejected #" . $request->certi_no . " | " . env('APP_NAME'));
                });
            }
        } catch (\Throwable $th) {
        }
    }

    public function Supplierlogout()
    {
        $customer_id = Auth::user()->id;

        DB::table('oauth_access_tokens')->where('user_id', $customer_id)->delete();
        Session::flush();

        return response()->json([
            'success' => true,
            'message' => 'Logout Successful',
        ]);
    }

    public function Suppliernotification(Request $exception)
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

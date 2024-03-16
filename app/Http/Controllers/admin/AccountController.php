<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;

use DB;
use Carbon\carbon;
use App\Models\User;
use App\Models\Customer;
use App\Models\Account;
use App\Models\Supplier;
use App\Models\InvoiceItem;
use App\Models\OrderItem;

class AccountController extends Controller
{

    public function purchaseList(Request $request)
    {
        $data['supplier_id'] = '';
        $data['from_carat'] = '';
        $data['to_carat'] = '';
        $data['from_date'] = '';
        $data['to_date'] = '';
        $data['status'] = '';

        $purchase = InvoiceItem::select('invoice_items.*',DB::RAW('(SELECT invoice_number FROM invoices WHERE invoices.invoice_id = invoice_items.invoice_id) AS invoice_number'))
        ->with('orders:orders_id,return_price,buy_rate,buy_price','orders_items:orders_id,supplier_name,country,shape,color,carat,clarity,cut,polish,polish,symmetry,fluorescence,lab')
        ->orderBy('invoice_items.created_at', 'desc')
        ->where('invoice_items.is_deleted',0);
        if($request->ismethod('post')){
            $supplier = $request->supplier;
            $from_carat = $request->from_carat;
            $to_carat = $request->to_carat;
            $from_date = $request->from_date;
            $to_date = $request->to_date;

            $data['supplier_id'] = $supplier;
            $data['from_carat'] = $from_carat;
            $data['to_carat'] = $to_carat;
            $data['from_date'] = $from_date;
            $data['to_date'] = $to_date;

            $purchase->when($supplier, function ($query) use ($supplier) {
                $query->whereHas('orders_items', function ($query) use ($supplier)  {
                    return $query->where('supplier_id','=',$supplier);
                });
            });
            $purchase->when($from_carat != null , function ($query) use ($from_carat) {
                $query->whereHas('orders_items', function ($query) use ($from_carat)  {
                    return $query->where('orders_items.carat','>=', $from_carat);
                });
            });
            $purchase->when($to_carat != null , function ($query) use ($to_carat) {
                $query->whereHas('orders_items', function ($query) use ($to_carat)  {
                    return $query->where('orders_items.carat','<=', $to_carat);
                });
            });
            $purchase->when($from_date != null , function ($query) use ($from_date) {
                return $query->where('invoice_items.created_at','>=', $from_date);
            });
            $purchase->when($to_date != null , function ($query) use ($to_date) {
                return $query->where('invoice_items.created_at','<=', $to_date);
            });
        }
        $data['purchase'] = $purchase->paginate(500);
        $data['suppliers'] = OrderItem::groupBy('supplier_id')->orderBy('supplier_name','asc')->get();

        return view('admin.account.purchase-list')->with($data);
    }

    public function purchaseBill(Request $request){
        $data['supplier_id'] = '';
        $data['from_date'] = '';
        $data['to_date'] = '';
        $data['status'] = '';
        $bills = Account::with('users')->where('is_delete',0);
        if($request->ismethod('post')){
            $supplier = $request->supplier;
            $from_date = $request->from_date;
            $to_date = $request->to_date;
            $status = $request->status;

            $data['supplier_id'] = $supplier;
            $data['from_date'] = $from_date;
            $data['to_date'] = $to_date;
            $data['status'] = $status;
            $bills->when($supplier, function ($query) use ($supplier) {
                return $query->where('supplier_id', $supplier);
            });
            $bills->when($from_date != null , function ($query) use ($from_date) {
                return $query->where('date','>', $from_date);
            });
            $bills->when($to_date != null , function ($query) use ($to_date) {
                return $query->where('date','<', $to_date);
            });
            // $bills->when($to_date != null && $from_date != null, function ($query) use ($to_date,$from_date) {
            //     return $query->where('date','>', $from_date);
            // });
            $bills->when($status, function ($query) use ($status) {
                return $query->where('status', $status);
            });
        }
        $data['bills'] = $bills->orderBy('date','desc')->get();
        $data['count'] = $bills->count();
        $data['suppliers'] =  with('users')->whereHas('users',function($query){ $query->where('is_delete','=','0'); $query->orderBy('companyname','asc');})->get();

        return view('admin.account.purchase-bill')->with($data);
    }

    public function deletePurchaseBill(Request $request){
        $bill_id = $request->bill;
        $delete = Account::where('bill_id',$bill_id)->update(['is_delete' => 1]);

        $data['success'] = 'Bill Deleted Successfully!';

        return json_encode($data);
    }

    public function UpdatePurchaseBill(Request $request){
        $status = $request->status;
        $bill_id = $request->bill_id;
        if($status == 'comment'){
            $comment = $request->comment;
            Account::where('bill_id',$bill_id)->update(['comment' => $comment]);
            $data['success'] = "Comment added to This Bill !";

        }
        else{
            $paid_amount =$request->paid_amount;
            $paid_date =$request->datetimepicker;
            $status =$request->paid_status;

                $update = array("status" => $status,'paid_amount' => $paid_amount,'paid_date' => $paid_date);
                Account::where('bill_id',$bill_id)->update($update);
                $data['success'] = 'Bill Updated Successfully!';
        }

        return json_encode($data);

    }

    public function purchaseBillForm(){
        $data['suppliers'] = Supplier::with('users')->whereHas('users',function($query){ $query->where('is_delete','=','0'); $query->orderBy('companyname','asc');})->get();

        return view('admin.account.purchase-bill-form')->with($data);
    }

    public function purchaseBillSave(Request $request){
        $validatedData = $request->validate([
            'amount'=>'required|numeric',
            'invoice'=>'required',
            'image'=>'required|max:10000|mimes:jpg,jpeg,png',
        ]);
        $invoice = $request->invoice;
        $record = Account::where('invoice_no',$invoice)->first();
        if(($record) == null){

            $image = $request->image;
            if($image){
                $imagename = time().'.'.$image->getClientOriginalExtension();
                $request->file('image')->storeAs('purchase_bill',$imagename,'public');
            }
            $supplier_id = $request->supplier_id;
            $amount = $request->amount;
            $date = $request->date;
            $comment = $request->comment;
            $user_id = Auth::user()->id;
            $date = str_replace('/', '-', $date);
            $date = date('Y-m-d', strtotime($date));
            $array = [
                'supplier_id' => $supplier_id,
                'image' => $imagename,
                'amount' => $amount,
                'date' => $date,
                'invoice_no' => $invoice,
                'comment' => $comment,
                'created_by' => $user_id
            ];
            Account::insert($array);

            return redirect('purchase-bill-form')->with('success','Purchase Bill added Successful');
        }
        else{
            return redirect('purchase-bill-form')->with('update','Purchase Bill Already added ! ');
        }
    }

    public function salesReport(Request $request){
        $data['from_date'] = '';
        $data['to_date'] = '';

        $shapewisearray = InvoiceItem::join('orders_items','orders_items.orders_id','=','invoice_items.orders_id')->select('shape',DB::raw('count(*) as total'))->groupBy('shape')->where('is_deleted',0);

        $countrywisearray = InvoiceItem::join('customers','customers.cus_id','=','invoice_items.customer_id')->select('country',DB::raw('count(*) as total'))->groupBy('country')->where('is_deleted',0);


        $sales = InvoiceItem::select('invoice_items.*',DB::RAW('(SELECT invoice_number FROM invoices WHERE invoices.invoice_id = invoice_items.invoice_id) AS invoice_number'))
        ->with('orders:orders_id,sale_price,buy_price','orders_items:orders_id,supplier_name,country,shape,color,carat,clarity,lab,certificate_no','invoices','customers:cus_id,port_of_discharge','pickups:orders_id,location')->where('invoice_items.is_deleted',0);
        if($request->ismethod('post')){
            $from_date = $request->from_date;
            $to_date = $request->to_date;
            $data['from_date'] = $from_date;
            $data['to_date'] = $to_date;

            $sales->when($from_date != null , function ($query) use ($from_date) {
                return $query->whereDate('created_at','>=', $from_date);
            });
            $shapewisearray->when($from_date != null , function ($query) use ($from_date) {
                return $query->whereDate('invoice_items.created_at','>=', $from_date);
            });
            $countrywisearray->when($from_date != null , function ($query) use ($from_date) {
                return $query->whereDate('invoice_items.created_at','>=', $from_date);
            });
            $sales->when($to_date != null , function ($query) use ($to_date) {
                return $query->whereDate('created_at','<=', $to_date);
            });
            $shapewisearray->when($to_date != null , function ($query) use ($to_date) {
                return $query->whereDate('invoice_items.created_at','<=', $to_date);
            });
            $countrywisearray->when($to_date != null , function ($query) use ($to_date) {
                return $query->whereDate('invoice_items.created_at','<=', $to_date);
            });
        }

        $shapewisearray = $shapewisearray->get();
        $countrywisearray = $countrywisearray->get();

        foreach($shapewisearray as $value){
            $shapeall[] = $value->shape;
            $shapedata[] = $value->total;
        }

        $data['shapeall'] = json_encode($shapeall);
        $data['shapedata'] = json_encode($shapedata);

        foreach($countrywisearray as $value){
            $countryall[] = $value->country;
            $countrydata[] = $value->total;
        }

        $data['countryall'] = json_encode($countryall);
        $data['countrydata'] = json_encode($countrydata);


        $data['sales'] = $sales->get();

        $data['sales'] = $sales->latest()->paginate(5);
        $data['total_pcs'] = $sales->count();


        return view('admin.account.sales-report')->with($data);
    }
}

<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Validation\Validator;
use Illuminate\Http\Request;
use DB;

use App\Models\returnDiamond;
use App\Models\OrderItem;
use App\Models\Order;

class ReturnDiamondController extends Controller
{
    Public function returnDiamondList(){
        $data['returns'] =  returnDiamond::with('orderItems','orderItems.customer','orderItems.supplier','orderItems.invoiceNo','orderItems.exportNo','orderItems.orders')->get();

        return view('admin.return.return-diamond-list')->with($data);
    }

    Public Function AddreturnDiamond(){
        return view('admin.return.add-return-diamond');
    }

    Public function SearchReturnDiamond(Request $request){
        $certificate = $request->certificate;
        $diamonds = Order::with('orderdetail','orderdetail.customer','orderdetail.invoiceNo','orderdetail.exportNo')->wherehas('orderdetail',function($query)use($certificate){$query->where('certificate_no',$certificate); })->get();
                    // select('orders_items.*','orders.*',DB::RAW('(SELECT companyname FROM users WHERE orders_items.customer_id = users.id) AS customer'),DB::RAW('(SELECT invoice_number FROM pickups WHERE pickups.orders_id = orders_items.orders_id) AS invoice_no'),DB::RAW('(SELECT export_number FROM pickups WHERE pickups.orders_id = orders_items.orders_id) AS export_no'))


        $detail = '';
        if (count($diamonds) > 0) {
            $detail .= '
                <div class="col-md-12 mt-5 table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th class="column-title">Customer</th>
                                <th class="column-title">Invoice Number</th>
                                <th class="column-title">Export Number</th>
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
                                <th class="column-title">Lab</th>
                                <th class="column-title">Certificate</th>
                                <th class="column-title">Sale Discount</th>
                                <th class="column-title">Sell Price</th>
                                <th class="column-title">Sale $/Ct</th>
                                <th class="column-title">Buy Discount</th>
                                <th class="column-title">Buy Price</th>
                                <th class="column-title">Buy $/Ct</th>
                            </tr>
                        </thead>
                        <tbody>';
                        if (!empty($diamonds)) {
                            foreach($diamonds as $diamond){
                                $detail.='
                                <tr>
                                    <td>'.$diamond->orderdetail->customer->companyname .'</td>
                                    <td>'.$diamond->orderdetail->invoiceNo->invoice_number .'</td>
                                    <td>'.$diamond->orderdetail->exportNo->export_number .'</td>
                                    <td>'.$diamond->orderdetail->supplier_name.'</td>
                                    <td>'.$diamond->orderdetail->shape.'</td>
                                    <td>'.$diamond->orderdetail->id.'</td>
                                    <td>'.$diamond->ref_no.'</td>
                                    <td>'.$diamond->orderdetail->carat.'</td>
                                    <td>'.$diamond->orderdetail->color.'</td>
                                    <td>'.$diamond->orderdetail->clarity.'</td>
                                    <td>'.$diamond->orderdetail->cut.'</td>
                                    <td>'.$diamond->orderdetail->polish.'</td>
                                    <td>'.$diamond->orderdetail->symmetry.'</td>
                                    <td>'.$diamond->orderdetail->lab.'</td>
                                    <td>'.$diamond->certificate_no.'</td>
                                    <td>'.number_format(($diamond->sale_discount),2).'</td>
                                    <td>'.number_format($diamond->sale_price,2).'</td>
                                    <td>'.number_format(($diamond->sale_price/$diamond->orderdetail->carat),2).'</td>
                                    <td>'.number_format(($diamond->buy_discount),2).'</td>
                                    <td>'.number_format($diamond->buy_price,2).'</td>
                                    <td>'.number_format(($diamond->buy_price/$diamond->orderdetail->carat),2).'</td>
                                </tr>';
                            };
                        }
                    $detail.='  </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-md-5 mt-5">
                        <label for="title"> Purchase Date<span class="text-danger">*</span></label>
                        <input type="date" class="form-control form-control-solid" name="purchase_date" required>
                    </div>
                    <div class="col-md-5 offset-md-1 mt-5">
                        <label for="title"> Return Initiated Date<span class="text-danger">*</span></label>
                        <input type="date" class="form-control form-control-solid" name="return_initiated_date" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5 mt-5">
                        <label for="title"> Return Date<span class="text-danger">*</span></label>
                        <input type="date" class="form-control form-control-solid" name="return_date" required>
                    </div>
                    <div class="col-md-5 offset-md-1 mt-5">
                        <label for="title"> Return Paid Amount<span class="text-danger">*</span></label>
                        <input type="text" name="return_paid_amount" class="form-control" placeholder="Return Paid Amount" required>
                    </div>
                </div>
                <div class="col-md-2  mt-5">
                    <input type="submit" value="Submit" class="btn btn-success btn-sm">
                </div> ';

            $data['detail'] = $detail;
        }
        else
        {
            $data['detail'] = ' <div class="col-md-12 mt-5">
                                    No Result Found!
                                </div>';
            $data['error'] = false;
        }
        echo json_encode($data);
    }

    public function AddReturnDiamondSave(Request $request){
        $validatedData = $request->validate([
            'certificate'=>'required',
            'purchase_date'=>'required',
            'return_initiated_date'=>'required',
            'return_date'=>'required',
            'return_paid_amount'=>'required',
        ]);
        $certificate = $request->certificate;
        $purchase_date = $request->purchase_date;
        $return_initiated_date = $request->return_initiated_date;
        $return_date = $request->return_date;
        $return_paid_amount = $request->return_paid_amount;
        returnDiamond::insert([
            'certificate_no' => $certificate,
            'purchase_date' => $purchase_date,
            'return_initiated_date' => $return_initiated_date,
            'return_date' => $return_date,
            'return_paid_amount' => $return_paid_amount
        ]);
        return redirect('add-return-diamond')->with('success','New Return Diamond added Successful');
    }
}

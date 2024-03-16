<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;

use PDF;
use Mail;
use App\Helpers\AppHelper;

use App\Models\DiamondNatural;
use App\Models\DiamondLabgrown;
use App\Models\DailyReporting;
use App\Models\CurrencyExchange;
use App\Models\Invoice;
use App\Models\Cart;
use App\Models\Notification;


class ExtendedController extends Controller
{
    Public function currencyExchange(Request $request)
    {
        $user_type = Auth::user()->user_type;
        $id = auth::user()->id;

        $data['permission'] = AppHelper::userPermission($request->segment(1));

        if($user_type == 1 OR $data['permission']->full == 1)
        {
            $data['price'] = CurrencyExchange::get();
        }
        else{
            return redirect('admin');
        }

        return View('admin.extra.currencyExchange')->with($data);
    }

    Public function PostCurrencyExchange(request $request){
        foreach($request->toArray() as $key => $value)
        {
            CurrencyExchange::where('currency_id', $key)->update(array('currency_rate' => $value));
        }

        return redirect("currency-exchange")->with('success','Price Updated Successful');
    }

    public function currencyExchangeSave(Request $request){

        $date = date('Y-m-d H:i:s');

        $curl = curl_init();

        curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.fastforex.io/fetch-multi?from=USD&to=AUD%2CEUR%2CMYR%2CGBP%2CCAD%2CINR&api_key=c66aa9a1c5-ae81b5fc3f-rnhx28",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "accept: application/json"
        ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        $newresponse = json_decode($response, true); ## Create array from response
        $base = $newresponse['base'];
            CurrencyExchange::updateorinsert(['currency_name' =>$base],['currency_rate'=>'1','date'=> $date]);
        $sheet_data = $newresponse['results'];

        foreach($sheet_data as $key=>$value){
            CurrencyExchange::updateorinsert(['currency_name' =>$key],['currency_rate'=>$value,'date'=> $date]);
        }

        dd('done');
    }

    Public function dailyReporting(Request $request){
        $user_type = Auth::user()->user_type;
        $id = auth::user()->id;

        $data['username']='';

        $permission = AppHelper::userPermission($request->segment(1));

        if($user_type == 1 || $permission->full == 1){
            $reports = DailyReporting::with('users')->where('daily_reporting.is_delete',0)->orderBy('created_at','desc');
            if($request->isMethod('post')){
                $username=$request->username;
                if($username != null){
                    $reports->where('user_id',$username);
                }

                $data['username'] = $username;
            }
            $data['reports'] = $reports->get();
            $data['users'] = DailyReporting::groupBy('user_id')->with('users:id,firstname')->get();
        }
        else{
            $data['reports'] = DailyReporting::with('users:id,firstname')->where('daily_reporting.is_delete',0)->orderBy('created_at','desc')->where('user_id',$id)->get();
        }

        return view('admin.extra.daily-reporting')->with($data);
    }

    Public function dailyReportPost(Request $request){
        $user_type = Auth::user()->user_type;
        $id = auth::user()->id;

        DailyReporting::insert([
            'user_id' => $id,
            'task' => $request->task,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        return redirect()->back()->with('success','Report Has Been Added');
    }

    public function updateDailyReporting(Request $request){
        $id = $request->id;

        DailyReporting::where('report_id',$id)->update(['is_delete'=>1,'updated_at' => date('Y-m-d H:i:s')]);

        $data['success'] = "Report Is Deleted!";

        return json_encode($data);
    }

    public function dailyCheckList(){
        $data['invoices'] = Invoice::with('associates:id,name','customers:id,companyname')->where('is_deleted',0)->where(function($query){$query->where('tracking_no','');$query->orWhere('payment',0); })->orderBy('invoice_number','desc')->paginate(100);

        return view('admin.daily-check-list')->with($data);
    }

    public function CartMail(){
        $date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s').' -3 days'));
        $records = Cart::select('customer_id',DB::RAW('count(id) as count'))->with('users')->where('created_at','<',$date)->where('mail_send',0)->where('is_delete',0)->groupBy('customer_id')->get();
        if(count($records) > 0){

            foreach($records as $record){
                $orders = Cart::with('users')->where('created_at','<',$date)->where('is_delete',0)->where('mail_send',0)->where('customer_id',$record->customer_id)->get();
                $text_message = '';
                $certificate_ids = [];
                if(count($orders) > 0){
                    foreach($orders as $order){
                        if($order->diamond_type == 'W'){
                            $diamond = DiamondNatural::where('certificate_no',$order->certificate_no)->where('is_delete',0)->first();
                        }
                        else{
                            $diamond = DiamondLabgrown::where('certificate_no',$order->certificate_no)->where('is_delete',0)->first();
                        }
                        $certificate_ids[] = $order->certificate_no;
                        if($diamond != null){
                            $text_message .= '<table width="100%" style="border:#CEC9C9 solid thin;padding: 10px;font-size: 13px;margin-top:10px;">
                                                <tr>
                                                    <td width="25%">
                                                        <strong>'.$diamond->lab.' </strong>
                                                    </td>
                                                    <td width="30%">
                                                        <strong> Certificate No : '.$diamond->certificate_no.'</strong>
                                                    </td>
                                                <td width="30%" align="right"> <strong> $/CT - $'. number_format($order->cart_rate, 2).'</strong></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" width="70%">
                                                        <span style="font-weight: 600">'.$diamond->shape.'&nbsp;&nbsp;'.$diamond->carat.'CT&nbsp;&nbsp;'.$diamond->color.'&nbsp;&nbsp;'.$diamond->clarity.'&nbsp;&nbsp;'.$diamond->cut.'&nbsp;&nbsp;'.$diamond->polish.'&nbsp;&nbsp;'.$diamond->symmetry.'&nbsp;&nbsp;'.$diamond->fluorescence.'</span>
                                                    </td>
                                                    <td width="30%" align="right"><strong>Total - $' . number_format($order->price, 2) . '</strong></td>
                                                </tr>
                                            </table>';
                        }
                    }
                    $customer_email = $record->users->email;
                    $data['customer'] = $record->users->firstname.' '.$record->users->lastname;
                    $data['text_message'] = $text_message;
                    if(!empty($text_message)){
                        try {
                            Mail::send('emails.cart_mail', $data, function($message) use($customer_email){
                                $message->to($customer_email);
                                // $message->cc(\Cons::EMAIL_SUPPLIER);
                                $message->subject("Please Check The Cart | ". env('APP_NAME'));
                            });
                        } catch (\Throwable $th) {
                            return print_r('Email Not Send');
                        }
                    }
                    if(count($certificate_ids) > 0){
                        cart::whereIn('certificate_no',$certificate_ids)->update(['mail_send' => 1]);
                        print_r('mail send to customer:'.$record->users->firstname.' '.$record->users->lastname);
                        print_r('<br/>');
                        print_r('------------------------------------------------------------------------------------------------------------------------------');
                        print_r('<br/>');
                    }
                    else{
                        print_r('email sent');
                    }
                }
                else{
                    return print_r('Mail Send To All Customers!');
                }
            }
        }
        else{
            return print_r('Mail Send To All Customers!');
        }
    }

    public function template(){
        $pdfname = 'tdploc_1.pdf';
        $pdf = PDF::loadView('admin.template.invoice_local');
        $pdf->save(public_path('/assets/invoices/' . $pdfname));
        print_r('done');
        die();
    }

    public function GetNotification(){
        $id=Auth::user()->id;
        if(Auth::user()->user_type == 1){
            $data['notifications'] = Notification::with('createdBy:id,firstname,lastname')->get();
        }
        else{
            $data['notifications'] = Notification::with('createdBy:id,firstname,lastname','createdBy.manager')->whereHas('createdBy.manager',function($query) use($id){ $query->where('users.added_by',$id); })->get();
        }
        return json_encode($data);
    }

    public function SendMessages(){
        $message_send = AppHelper::Whatsapp_message('918401221887','hello_world');
        dd($message_send);
    }
}

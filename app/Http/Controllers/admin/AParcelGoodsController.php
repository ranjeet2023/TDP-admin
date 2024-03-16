<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\AppHelper;
use App\Models\ParcelComment;
use Mail;

use App\Models\ParcelGood;

class AParcelGoodsController extends Controller
{
    Public function ParcelGoodsList(Request $request){
        $id = Auth::user()->id;

        $url = $request->segment(1);
        $data['permission'] = $permission = AppHelper::userPermission($url);
        if(empty($data['permission']))
        {
            return redirect('admin')->with('success','Error');
        }

        if( $permission->full == 1){
            $data['parcels'] = ParcelGood::with('customers:id,companyname')->orderBy('created_at','desc')->paginate(100);
        }
        else
        {
            $data['parcels'] = ParcelGood::with('customers:id,companyname')->whereHas('user',function($query) use($id) { $query->where('users.added_by',$id);})->orderBy('created_at','desc')->paginate(100);
            // $data['customers'] = $customers->whereHas('user',function($query) use($id) { $query->where('users.added_by',$id);})->get()->sortBy('user.companyname');
        }
        return view('admin.parcel.parcel-goods-list')->with($data);
    }

    public function UpdateParcelGoods(Request $request){
        $status = $request->status;
        $id = $request->id;
        $sku=$request->sku;
        $user_id= Auth::user()->id;
        $user_type= Auth::user()->user_type;

        if($user_type==1){
            $usertype='Admin';
        }elseif($user_type==4){
            $usertype='Seles manager';
        }elseif($user_type==5){
            $usertype='Supplier manager';
        }elseif($user_type==6){
            $usertype="Accountant";
        }

        if($status == 'comment_add'){
            $comment = $request->comment;
            ParcelComment::insert(['user_id'=>$user_id,'user_type'=>$usertype,'parcel_id'=>$id,'comment' => $comment,'sku'=>$sku]);
            $data['success'] = 'Comment added SuccessFully';
        }
        elseif($status == 'price_change'){
            $price = $request->price;
            ParcelGood::where('id',$id)->update(['price' => $price]);
            $data['success'] = true;
        }

        return json_encode($data);
    }

    public function AdminSendMailParcel(Request $request){
        $id = $request->id;
        $parcel = ParcelGood::with('customers:id,email,firstname')->where('id',$id)->first();

        $parceldetail = '<table width="100%" style="border:#CEC9C9 solid thin;padding: 10px;font-size: 13px;">
                <tr>
                    <td width="25%">
                        <span><strong>Color :'. $parcel->color .'</strong></span>
                    </td>
                    <td width="30%" align="center">
                        <span><strong> Clarity : '.$parcel->clarity .' </strong></span>
                    </td>
                    <td width="30%" align="right"> <strong> Cut : '.$parcel->cut.'</strong></td>
                </tr>
                <tr>
                    <td width="35%">
                        <span style="font-weight: 600"> Pieces : '.$parcel->pcs . '</span>
                    </td>
                    <td width="30%" align="center">
                        <span><strong> Carat : '.$parcel->carat .' </strong></span>
                    </td>
                    <td width="30%" align="right"> <strong> Price : '.$parcel->price.'</strong></td>
                </tr>
            </table>
            <table width="100%" style="border:#CEC9C9 solid thin;padding: 10px;font-size: 13px;">
                <tr>
                    <td width="100%">
                        <strong>Comment : '.$parcel->comments.'</strong>
                    </td>
                </tr>
                </table>';

        $customer_email = $parcel->customers->email;
        $email_data = array();
        $email_data['firstname'] = $parcel->customers->firstname;
        $email_data['price'] = $parcel->price;
        $email_data['parceldetail'] = $parceldetail;

        try {
            Mail::send('emails.parcels.parcel-goods-confirm', $email_data, function($message) use($customer_email){
                    $message->to($customer_email);
                    $message->cc(\Cons::EMAIL_SALE);
                    $message->subject("Confirm the Order On Parcel Goods | ". date('d-m-Y H') ." | ". env('APP_NAME'));
                });
            $data['success'] = 'Mail sent SuccessFully!';
        } catch (\Throwable $th) {
            $data['error'] = 'Mail Cannot Be Sent!';
        }

        return json_encode($data);
    }

    public function parcelcomment(Request $request){
        $parcel_id =$request->parcel_id;
        $data=ParcelComment::where('parcel_id',$parcel_id)->orderBy('created_at', 'desc')->get();
        $result="";
        if(count($data)>0){
            $id=1;
            foreach($data as $record){
                $result .= '<tr class="odd snipcss0-7-196-197">
                                <td  class="align-items-center snipcss0-8-197-201">' . $id . '</td>
                                <td  class="align-items-center snipcss0-8-197-201">' . $record->user_type . '</td>
                                <td  class="align-items-center snipcss0-8-197-201 text-capitalize text-dark">' . $record->comment . '</td>
                                <td  class="align-items-center snipcss0-8-197-201">' .date('d M Y, h:i a', strtotime($record->created_at))  . '</td>
                        </tr>';
                    $id++;
                  }
        }else{
            $result .= '<b>No Record Found</b>';
        }
        return json_encode($result);
    }
}

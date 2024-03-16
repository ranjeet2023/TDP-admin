<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\LeadsSendEmail;
use App\Mail\MyMail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Helpers\AppHelper;

use App\Models\UserHasPermission;
use App\Models\LeadsComment;
use App\Models\Lead;
use App\Models\User;
use App\Models\EmailTemplate;
use Maatwebsite\Excel\Concerns\ToArray;


class LeadsController extends Controller
{
    //
    public function LeadsList(Request $request)
    {
        $user_type = Auth::user()->user_type;
        $user_id  = Auth::user()->id;

        $url = $request->segment(1);

        $data['permission'] = AppHelper::userPermission($url);


        $leads = Lead::orderBy('created_at','desc')->with('createdbyuser:id,firstname,lastname,companyname','assigntouser:id,firstname,lastname,companyname')->where('is_delete',0);

        if($user_type == 1 ){

        }else{
            if($data['permission']->full == 0){
                 $leads->where('assign_to', $user_id);
            }
        }

        if($request->ismethod('post')){
            $firstname = $request->firstname;
            $lastname = $request->lastname;
            $phoneno = $request->phoneno;
            $email = $request->email;
            $country = $request->country;

            $leads->when($firstname != null, function ($query) use ($firstname) {
                return $query->where('firstname','like','%'.$firstname.'%');
            });
            $leads->when($lastname != null, function ($query) use ($lastname) {
                return $query->where('lastname','like','%'.$lastname.'%');
            });
            $leads->when($phoneno != null, function ($query) use ($phoneno) {
                return $query->where('phone_number','like','%'.$phoneno.'%');
            });
            $leads->when($email != null, function ($query) use ($email) {
                return $query->where('email','like','%'.$email.'%');
            });
            $leads->when($country != null, function ($query) use ($country) {
                return $query->where('country','like','%'.$country.'%');
            });

            $data['leads'] = $leads->paginate(500);
            $data['firstname'] = $firstname;
            $data['lastname'] = $lastname;
            $data['phoneno'] = $phoneno;
            $data['email'] = $email;
            $data['country'] = $country;

        }
        $data['leads'] = $leads->paginate(500);

        if($request->ismethod('post')){
            return json_encode($data);
        }
        return view('admin.customer.leads-list')->with($data);
    }
    public function LeadsReport(Request $request)
    {

        $user_type = Auth::user()->user_type;
        $user_id  = Auth::user()->id;
        $url = $request->segment(1);

        $data['permission'] = AppHelper::userPermission($url);

        $leads = Lead::orderBy('created_at','desc')->with('createdbyuser:id,firstname,lastname,companyname')->where('is_delete',0)->distinct();

            if($user_type == 1 ){
            }else{
                if($data['permission']->full == 0){
                    $leads->where('assign_to', $user_id);
                }
            }

          if ($request->isMethod('post')) {

            $query = LeadsComment::with('leads','createdBy:id,firstname,lastname,companyname')->selectRaw('created_by,type, COUNT(*) as count')->groupBy('created_by','type');

            if($user_type == 1 ){

            }else{
                if($data['permission']->full == 0){
                    $query->where('created_by',$user_id);
                }
            }

            if(!empty($request->countryfilter)){
                $country = $request->countryfilter;
                $leads = Lead::where('country', $country)->pluck('id')->toArray();
                        $query->whereIn('leads_id',$leads);
            }

            if(!empty($request->user)){
                    $user = $request->user;
                   $leads = Lead::where('firstname', $user)->pluck('id')->toArray();
                        $query->whereIn('leads_id',$leads);
            }

            if(!empty($request->startdate)){
                  $startOfDay = Carbon::now()->startOfDay();
                   $endOfDay = Carbon::now()->endOfDay();
                   $startdate = $request->startdate;
                  $enddate = $request->enddate;
                  $query->whereBetween('follow_up_date', [$startdate . ' ' . $startOfDay->toTimeString(),$enddate . ' ' . $endOfDay->toTimeString()]);
            }

            $data=$query->get();

                if(!empty($data)){
                    return response()->json([
                        'records' => $data,
                    ]);
                }
        }
        $data['leads']=$leads->get();

        return view('admin.customer.leads-report')->with($data);
    }
    public function LeadsCommentAdd(Request $request){
        $user_id = Auth::user()->id;
        $to_email = Auth::user()->email;
        $lead_id = $request->lead_id;
        $comment = $request->comment;
        $followup_date = $request->followup_date;
        $date=date('Y-m-d H:i:s');
        $from_email="from@gmail.com";
        $email_body="dasdasdas asddasdas dasdd";

        if (!empty($followup_date)) {
            $reminder_date = Carbon::parse($followup_date);
            $delay = $reminder_date->diffInMinutes(Carbon::now());
            Mail::to($to_email)
                ->later(now()->addMinutes($delay), new MyMail($comment, $email_body, $from_email));
        }

        $data['flag'] = LeadsComment::insert([
                        'type'=>1,
                        'leads_id' => $lead_id,
                        'created_by' => $user_id,
                        'comment' => $comment,
                        'follow_up_date' => $followup_date,
                    ]);
        return json_encode($data);
    }
    public function AddNewLeads(){
        $data['staff'] = User::whereIn('user_type',[1,4,5,6])->get();

        return view('admin.customer.leads-add')->with($data);
    }

    public function AddNewLeadsPost(Request $request){
        $date=date('Y-m-d H:i:s');

        $request->validate([
            'firstname'=>'required',
            'lead_type'=>'required',
            'email'=>['required','email'],
            'mobile_number'=>'required',
            'country'=>'required',
            'created_by'=>'required',
            'lead_status'=>'required',
            'company_name'=>'required',
        ]);
        $leads_data =array(
            'firstname'=>$request->firstname,
            'lastname'=>$request->lastname,
            'type'=>$request->lead_type,
            'date_of_birth'=>$request->date_of_birth,
            'email'=>$request->email,
            'additional_email'=>$request->additional_email,
            'mobile_number'=>$request->mobile_number,
            'phone_number'=>$request->phone_number,
            'additional_mobile_number'=>$request->additional_mobile_number,
            'country'=>$request->country,
            'state'=>$request->state,
            'city'=>$request->city,
            'fax_number'=>$request->fax_number,
            'created_by_userID'=>$request->created_by,
            'assign_to'=>$request->assign_to,
            'lead_status'=>$request->lead_status,
            'last_contacted'=>$request->last_contacted,
            'company_name'=>$request->company_name,
            'website_url'=>$request->website_url,
            'associated_company'=>$request->associated_company,
            'created_at'=>$date,
        );
        Lead::insert($leads_data);

        return redirect('add-new-leads')->with('success','New Lead Added!');
    }

    public function LeadsCommentsShow(Request $request){
        $lead_id = $request->lead_id;

        $comments = LeadsComment::with(['createdBy' => function($query){$query->select( 'id' ,'companyname'); }])->where('leads_id',$lead_id)->orderby('com_id','desc')->get();

        $detail = '';
        if (!empty($comments)) {
            $detail .= '<table class="table table-striped table-bordered exporderdetails" style="width:50%">
                            <thead>
                                <tr>
                                   <th class="column-title">Comment</th>
                                    <th class="column-title">Created By</th>
                                    <th class="column-title">Follow Up Date</th>
                                </tr>
                            </thead>
                            <tbody>';
                            if (!empty($comments)) {
                                foreach($comments as $comment){
                                    $detail.='
                                    <tr>
                                        <td><pre>'.$comment->comment.'</pre></td>
                                        <td>'.$comment->createdBy->companyname.'</td>
                                        <td>'.$comment->follow_up_date.'</td>';
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
        return json_encode($data);

    }

    public function LeadsConvert(Request $request){
        $id = $request->lead_id;
        Lead::where('id',$id)->update(['is_delete' => 1]);
        $data['success'] = "leads Convert successfully!";
        return json_encode($data);
    }

    public function LeadsEdit(Request $request){
        $id = $request->id;

        $data['lead'] = Lead::where('id',$id)->first();

        $data['sales'] =  User::whereIn('user_type',[1,4,5,6])
        ->where('is_active',1)
        ->where('is_delete',0)
        ->get();

        return view('admin.customer.leads-edit')->with($data);
    }

    public function LeadsEditPost(Request $request){
        $id = $request->id;

        $date=date('Y-m-d H:i:s');

        $request->validate([
            'firstname'=>'required',
            'lead_type'=>'required',
            'email'=>['required','email'],
            'mobile_number'=>'required',
            'country'=>'required',
            'created_by'=>'required',
            'assign_to'=>'required',
            'lead_status'=>'required',
            'company_name'=>'required',
        ]);
        $leads_data =array(
            'firstname'=>$request->firstname,
            'lastname'=>$request->lastname,
            'type'=>$request->lead_type,
            'date_of_birth'=>$request->date_of_birth,
            'email'=>$request->email,
            'additional_email'=>$request->additional_email,
            'mobile_number'=>$request->mobile_number,
            'phone_number'=>$request->phone_number,
            'additional_mobile_number'=>$request->additional_mobile_number,
            'country'=>$request->country,
            'state'=>$request->state,
            'city'=>$request->city,
            'fax_number'=>$request->fax_number,
            'created_by_userID'=>$request->created_by,
            'assign_to'=>$request->assign_to,
            'lead_status'=>$request->lead_status,
            'last_contacted'=>$request->last_contacted,
            'company_name'=>$request->company_name,
            'website_url'=>$request->website_url,
            'associated_company'=>$request->associated_company,
            'created_at'=>$date,
        );
        Lead::where('id',$id)->update($leads_data);

        return redirect('leads-list')->with('success','Lead Updated! ');

    }

    public function LeadsSendEmail(Request $request)
    {

        $lead_id = $request->input('lead_id');
        $user_id = Auth::user()->id;
        $email_subject = $request->input('subject');
        $to_email = $request->input('r_name');

        $email_body = $request->input('content');
        $email_body_view = view('sendemail')->with('email_body', $email_body);

        $from_email = 'sender@example.com';
        $date=date('Y-m-d H:i:s');

        LeadsComment::insert([
            'type'=>2,
            'leads_id' => $lead_id,
            'created_by' => $user_id,
            'comment' => "Email-".$to_email."\nSubject-".$email_subject,
            'follow_up_date' => $date,
        ]);

        Mail::send('sendemail', ['email_body' => $email_body_view], function($message) use ($to_email, $email_subject, $from_email) {
            $message->to($to_email)
                    ->subject($email_subject)
                    ->from($from_email);
        });

        return response()->json(['message' => 'Email sent successfully']);
    }

    public function LeadsReportDetail(Request $request)
    {
            $user_type = Auth::user()->user_type;
            $user_id  = Auth::user()->id;
            $url = $request->segment(1);

            $details = $request->input('detail');
            $country = $request->input('country');
            $date = $request->input('date');
            $userid=$request->input('id');

            $data['permission'] = AppHelper::userPermission($url);

            $query = LeadsComment::with('leads:id,firstname,lastname,email,mobile_number,country,company_name','createdBy:id,firstname,lastname,companyname')->where('created_by',$userid)->where('type',$details);

            if($user_type == 1 ){

            }else{
                if($data['permission']->full == 0){
                    $query->where('created_by',$user_id);
                }
            }

            if (!empty($country)) {
                $query->whereHas('leads', function ($query) use ($country) {
                    $query->where('country', $country);
                });
            }
            if (!empty($user)) {
                $query->whereHas('leads', function ($query) use ($user) {
                    $query->where('firstname', $user);
                });
            }

            if(!empty($request->startdate)){
                $startOfDay = Carbon::now()->startOfDay();
                $endOfDay = Carbon::now()->endOfDay();
                $startdate = $request->startdate;
                $enddate = $request->enddate;
                $query->whereBetween('follow_up_date', [$startdate . ' ' . $startOfDay->toTimeString(),$enddate . ' ' . $endOfDay->toTimeString()]);
            }

        $data = $query->get();

        return view('admin.customer.leads-report-details')->with('data', $data);
    }
    public function LeadsUserDetail(Request $request){

        $id=$request->id;

        $user_type = Auth::user()->user_type;
        $user_id  = Auth::user()->id;
        $url = $request->segment(1);

        $data['permission'] = AppHelper::userPermission($url);

        $result['record'] = LeadsComment::with('createdby:id,firstname,lastname,companyname,user_type','leads:id,firstname,lastname,email,mobile_number,phone_number,country,state,city,fax_number,website_url,associated_company,date_of_birth,company_name,type')->first();

        if($result['record']==null){
            $result['record']->whereHas('leads', function ($query) use ($id) {
                $query->where('id', $id);
            });
        }else{
            $result['record']->where('leads_id', $id);
        }
        $query = LeadsComment::where('leads_id',$id)->orderBy('follow_up_date','desc');

        if($user_type == 1 ){

        }else{
            if($data['permission']->full == 0){
                $query->where('created_by',$user_id);
            }
        }
        $result['leads_comment']=$query->get();


        return view('admin.customer.leads-reports-user-details')->with('result',$result);

    }


    public function EmailTemplate(Request $request)
    {
        $user_id=Auth::user()->id;

        $id=$request->lead_id;

            if ($request->isMethod('post')) {
                $request->validate([
                    'name' => 'required',
                    'subject' => 'required',
                    'content' => 'required',
                ]);

                $data = [
                    'user_id' => $user_id,
                    'name' => $request->name,
                    'subject' => $request->subject,
                    'message' => $request->content,
                ];

                $template = EmailTemplate::updateOrCreate(['id' => $id], $data);

                if ($template) {
                    $response_data = [
                        'empty' => true,
                    ];
                } else {
                    $response_data = [
                        'empty' => false,
                    ];
                }
           $json_response = json_encode($response_data);
           return $json_response;
        }
        return view('admin.customer.leads-email-tamplate');
    }
    public function LeadsEmailTemplate(){

        $data = EmailTemplate::with('createdbyuser:id,firstname,lastname')->distinct('user_id')->get();

        $options = '';
        $options.='<option  value="">Select Template</option>';
        foreach ($data as $result) {
            $options .= '<option value ='.$result->id.'>'.$result->name. '</option>';
        }
        if (empty($options)) {
            $response_data = array(
                'result'=>null,
                'empty' => true,
            );
        } else {
            $response_data = array(
                'result' => $options,
                'empty' => false,
            );
        }
        $json_response = json_encode($response_data);
        return $json_response;
    }


    public function LeadsTemplateShow(Request $request)
     {
            $id = $request->data;
            $query = EmailTemplate::orderBy('id', 'desc');

            if (!empty($id)) {
                $result = $query->where('id',$id)->first();
            }else{
                $result = $query->get();
            }
            if ($result->count() == 0) {
                $response_data = array(
                    'result'=>null,
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
    public function LeadsTemplateDelete(Request $request)
        {
        $id=$request->data;

         if (!empty($id)) {
            EmailTemplate::find($id)->delete();
            $response_data = array(
                'empty' => true,
            );
            } else {
                $response_data = array(
                    'empty' => false,
                );
            }
            $json_response = json_encode($response_data);
            return $json_response;
        }

   }


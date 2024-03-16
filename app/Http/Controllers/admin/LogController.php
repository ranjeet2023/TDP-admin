<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Illuminate\Support\Carbon;

use App\Helpers\AppHelper;

use App\Models\Log;
use App\Models\Apilog;
use App\Models\PageVisits;
use App\Models\SearchLog;

class LogController extends Controller
{

    public function loginHistoryCustomer(Request $request)
    {

        $user_type = auth::user()->user_type;
        $user_id = auth::user()->id;

        $permission = AppHelper::userPermission($request->segment(1));

        if($user_type == 1 OR $permission->full == 1){
            $loginHistoryData = Log::where('login_history.user_type',1)->with('user');
        }
        elseif($user_type == 4||5||6){
            $loginHistoryData = Log::with('user')->whereHas('user',function($query) use($user_id){ $query->where('added_by',$user_id); })
                                        ->where('user_type',2);
        }
        else{
            return redirect('admin');
        }
        if( $request->has('id') ) {
            $loginHistoryData = $loginHistoryData->where('userid',$request->id);
        }
        $data['loginHistoryData'] = $loginHistoryData->orderBy('lastlogin', 'desc')->paginate(100);
        return view('admin.log.login-history')->with($data);
    }

    public function loginHistorySupplier(Request $request)
    {
        $user_type = auth::user()->user_type;
        $user_id = auth::user()->id;

        $permission = AppHelper::userPermission($request->segment(1));

        if($user_type == 1 OR $permission->full == 1){
            $data['loginHistoryData'] = Log::with('user')->where('login_history.user_type',3)->orderBy('lastlogin', 'desc')->paginate(100);
        }
        elseif($user_type ==  6||5||4)
        {
            $data['loginHistoryData'] = Log::with('user')->whereHas('user',function($query)use($user_id){ $query->where('added_by','=',$user_id); })
                                        ->where('user_type',3)
                                        ->orderBy('lastlogin', 'desc')
                                        ->paginate(100);
        }
        else{
            return redirect('admin');
        }
        return view('admin.log.login-history')->with($data);
    }

    public function loginHistoryStaff(Request $request)
    {
        $user_type = auth::user()->user_type;
        $user_id = auth::user()->id;

        if($user_type == 1 || $user_id == 1 || $user_id == 442){
            $data['loginHistoryData'] = Log::with('staff')->whereIn('user_type',array(1,4,5))->orderBy('lastlogin', 'desc')->paginate(100);
        }
        else {
            return redirect('admin');
        }

        return view('admin.log.login-history')->with($data);
    }

    public function loginHistorytotal( Request $request)

    {
        $user_type = auth::user()->user_type;
        $user_id = auth::user()->id;

        $permission = AppHelper::userPermission($request->segment(1));
        if ($request->isMethod('post')) {

            $startdate=$request->startdate;
            $enddate=$request->enddate;

            if($user_type == 1 OR $permission->full == 1){
                $loginHistoryData = Log::where('login_history.user_type',1)->with('user');
            }
            elseif($user_type == 4||5||6){
                $loginHistoryData = Log::with('user')->whereHas('user',function($query) use($user_id){ $query->where('added_by',$user_id); })
                                            ->where('user_type',2);
            }
            else{
                return redirect('admin');
            }
            if($request->has('id') ) {
                $loginHistoryData = $loginHistoryData->where('userid',$request->id);
            }

            $startOfDay = Carbon::now()->startOfDay();
            $endOfDay = Carbon::now()->endOfDay();

            $data = $loginHistoryData->whereBetween('lastlogin', [$startdate . ' ' . $startOfDay->toTimeString(),$enddate . ' ' . $endOfDay->toTimeString()])
                    ->groupBy('userid')
                    ->select('*', DB::raw('count(*) as login_count'), DB::raw('MAX(lastlogin) as last_login_date'))
                    ->get();


            $response= "";
            if(!empty($data)) {
                foreach($data as $record){
                    $response.='<tr>
                        <td><a href="">'.$record->user->companyname.'</a></td>
                        <td>'.$record->last_login_date.'</td>
                        <td>'.$record->ip.'</td>
                        <td>'.$record->city.'</td>
                        <td>'.$record->country.'</td>
                        <td>';
                            if($record->user->user_type == 1){
                                $response .= "Admin";
                            } elseif($record->user->user_type == 2){
                                $response .= "Customer";
                            } elseif($record->user->user_type == 3){
                                $response .= "Supplier";
                            }
                    $response .= '</td>
                        <td>'.$record->login_count.'</td>
                        <td>'.$record->browser.'</td>
                    </tr>';
                }
            }else{
                $response .='<tr><td colspan="100%">No Record Found!!</td></tr>';
            }
            return json_encode($response);
        }
        return view('admin.log.login-history-total');
    }
    public function ApiLog(Request $request)
    {
        $user_type = auth::user()->user_type;
        $user_id = auth::user()->id;

        $permission = AppHelper::userPermission($request->segment(1));

        if($user_type == 1 || $permission->full == 1){
            $data['logData'] = Apilog::select('*',DB::raw('max(search_date) as maxdate'))->with('user')->groupBy('customer_id')->orderBy('search_date', 'desc')->paginate(100);
        }
        elseif($user_type == 4||5||6){
            $data['logData'] = Apilog::select('*',DB::raw('max(search_date) as maxdate'))->with('user')
            ->whereHas('user',function ($query) use ($user_id){ $query->where('users.added_by','=',$user_id); })
            ->groupBy('customer_id')->orderBy('search_date', 'desc')->paginate(100);
        }

        return view('admin.log.api-history')->with($data);
    }

    public function PageVisits(){
        $data['records'] = PageVisits::with('users')->orderBy('created_at','desc')->paginate(50);

        return view('admin.log.page-visits')->with($data);
    }

    public function ApiLogDetail(Request $request)
    {
        $user_type = auth::user()->user_type;
        $user_id = auth::user()->id;

        $customer_id = base64_decode($request->id);

        $permission = AppHelper::userPermission('api-history');

        if($user_type == 1 || $permission->full == 1){
            $data['logData'] = Apilog::with('user')->where('customer_id', $customer_id)->orderBy('search_date', 'desc')->paginate(100);
        }
        elseif($user_type == 4||5||6){
            $data['logData'] = Apilog::with('user')
            ->whereHas('user',function ($query) use ($user_id){ $query->where('added_by','=',$user_id);})
            ->groupBy('customer_id')->orderBy('search_date', 'desc')->paginate(100);
        }
        return view('admin.log.api-log-detail')->with($data);
    }

    public function Searchhistory(Request $request){


            $id=auth::user()->id;
            $user_type=Auth::user()->user_type;
            $permission = AppHelper::userPermission($request->segment(1));

            if($user_type == 1 OR $permission->full == 1){
                $data['logData'] = SearchLog::with('users:id,firstname,lastname,companyname')->select('user_id')->orderBy('search_date', 'desc')->distinct()->paginate(100);
            }
            elseif($user_type == 4||5||6){
                $data['logData'] = SearchLog::with('users')->whereHas('users',function($query) use($id){ $query->where('added_by',$id); })->orderBy('search_date', 'desc')->paginate(100);
            }
            else{
                return redirect('admin');
            }
            if($request->isMethod('post'))
            {
                $id=$request->userid;
                if(!empty($id)){
                    $data= SearchLog::with('users')->orderBy('search_date', 'desc')->where('user_id',$id)->paginate(100);
                }else{
                    $data= SearchLog::with('users')->orderBy('search_date', 'desc')->paginate(100);
                }
                $response="";
                foreach($data as $value){
                    $companyname = !empty($value->users->companyname) ? $value->users->companyname : '';
                    $response.='<tr>
                                <td style="cursor: pointer;" class="capital_user ViewDetail_User" id="'.$companyname.'">'.$companyname.'</td>
                                <td>'.$value->search_date.'</td>
                                <td>'.$value->ip.'</td>
                                <td>'.$value->diamond_type.'</td>
                                <td>'.$value->certificate_no.'</td>
                                <td>'.$value->carat.'</td>
                                <td>'.$value->shape.'</td>
                                <td>'.$value->color.'</td>
                                <td>'.$value->clarity.'</td>
                                <td>'.$value->cut.'</td>
                                <td>'.$value->polish.'</td>
                                <td>'.$value->symmetry.'</td>
                                <td>'.$value->fluorescence.'</td>
                                <td>'.$value->lab.'</td>';
                                //  <td><a href="'.url('api-history-detail').'/'.base64_encode($value->customer_id).'" class="btn btn-xm btn-primary"> View </a></td>
                            '</tr>';
                }
                return json_encode($response);
            }
        return view('admin.log.search-history')->with($data);
    }

}

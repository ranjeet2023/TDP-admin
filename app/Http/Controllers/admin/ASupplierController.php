<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Session;
use DB;
use Mail;
use App\Imports\ExcelImport;
use App\Imports\NaturalDiamondImport;
use App\Imports\UsersImport;
use App\Helpers\AppHelper;

use App\Models\User;
use App\Models\pricesetting;
use App\Models\Supplier;
use App\Models\Customer;
use App\Models\Order;
use App\Models\DiamondNatural;
use App\Models\DiamondLabgrown;
use App\Models\SupplierInvoice;
use App\Models\StockUploadReport;
use App\Models\DiamondUnapprove;
use App\Models\DiamondInvalid;
use App\Models\SupplierStatusHistory;

use Maatwebsite\Excel\Facades\Excel;
use Intervention\Image\ImageManagerStatic as Image;

class ASupplierController extends Controller
{
    // public function __construct()
	// {
	// 	if(!Auth::check()){
    //         return redirect("login")->withSuccess('You are not allowed to access');
    //     }
	// }

    public function suppliersPendingList()
    {
        $data['suppliers'] = Supplier::with('users')
        ->whereHas('users',function($query){ $query->where('user_type',3); $query->where('is_active',0); $query->where('is_delete',0); })
        ->get();
        $data['users'] = User::whereIn('user_type',[1,4,5,6])->get();

        return view('admin.supplier.suppliers-pending-list')->with($data);
    }

    public function supplierPendingPopup(request $request){
        $id=$request->id;
        $data['pending_supplier']=supplier::where( 'sup_id','=',$id )->first();
        $data['staff'] = User::select('users.id','users.firstname')
        ->whereIn('user_type',[1,4,5])
        ->where('is_active',1)
        ->where('is_delete',0)
        ->get();

        echo json_encode($data);
    }

    public function postPopupPendingSupplier(request $request){
        $type=$request->type;
        $staff = $request->staff;
        $id= $request->id;

        User::where('id',$id)->update([
            'added_by' => $staff,
            'is_active' => 1
        ]);

        Supplier::where('sup_id',$id)->update([
            'diamond_type' => $type
        ]);

        return json_encode(true);
    }


    public function pendingSupplierDelete(Request $request)
    {
        $id = $request->id;
        User::where('id',$id)->update(array('is_delete'=>'1'));
        return redirect('deleted-supplier-list')->with('success','Supplier Delete Successful');
    }
    public function SupplierMovePending(Request $request)
    {
        $id = $request->id;
        User::where('id',$id)->update([
            'is_active'=>'0',
            'is_delete'=>'0'
        ]);
        DiamondLabgrown::where('supplier_id', $id)->update(['is_delete' => 1]);
        DiamondNatural::where('supplier_id', $id)->update(['is_delete' => 1]);

        return back()->with('success', "Supplier Move to Pending List");
    }

    public function ActivateSupplierAccount(Request $request)
    {
        $id = $request->id;
        User::where('id',$id)->update([
            'is_active'=>'1',
            'is_delete'=>'0'
        ]);

        return back()->with('success', "Account Active successful");
    }

    public function pendingSupplierFollowup(Request $request){
        $sup_id = $request->sup_id;
        $comment = $request->comment;
        $followed_up_by = $request->followed_up_by;
        SupplierStatusHistory::insert([
            'sup_id' => $sup_id,
            'updated_by' => $followed_up_by,
            'feedback' => $comment,
            'status' => 'FOLLOW_UP',
            'date' => date('Y-m-d H:i:s')
        ]);

        return json_encode('success');
    }

    public function followupDetails(Request $request){
        $sup_id = $request->sup_id;
        $followups = SupplierStatusHistory::with('updatedBy:id,firstname,lastname')->where('sup_id',$sup_id)->where('status','FOLLOW_UP')->orderBy('date','desc')->get();
        $detail = '';
        if (!empty($followups)) {
            $detail .= '<table class="table table-striped table-bordered exporderdetails">
                            <thead>
                                <tr>
                                    <th class="column-title">Manager Name</th>
                                    <th class="column-title">Comment</th>
                                    <th class="column-title">Followed Up On</th>
                                </tr>
                            </thead>
                            <tbody>';
                            if (!empty($followups)) {
                                foreach($followups as $value){
                                    $detail.='
                                                <tr>
                                                    <td>'.$value->updatedBy->firstname.' '.$value->updatedBy->lastname .'</td>
                                                    <td>'.$value->feedback.'</td>
                                                    <td>'.date('Y-M-d',strtotime($value->date)).'</td>
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

    public function supplierDeleteList()
    {
        $data['suppliers'] = Supplier::with('users')->whereHas('users',function($query){ $query->where('is_delete',1); })->get();
        return view('admin.supplier.suppliers-delete-list')->with($data);
    }

    public function expiredreport(Request $request){
        $user_type = Auth::user()->user_type;
        $array['id'] = $id = Auth::user()->id;

        $permission = AppHelper::userPermission($request->segment(1));

	    $days_ago = date('Y-m-d H:i:s', strtotime('-1 days', strtotime(date('Y-m-d'))));

        $suppliers = StockUploadReport::with('supplier:sup_id,supplier_name,diamond_type')
                     ->whereRaw('id IN (SELECT MAX(id) FROM stock_upload_report GROUP BY supplier_id)')
                     ->WhereHas('supplier',function($query){ $query->where('stock_status','ACTIVE'); })
                     ->Where('created_at','<', $days_ago)
                     ->orderBy('supplier_id','ASC');
        if(!empty($permission) && ($user_type == 1 || $permission->full == 1)){
            $data['suppliers'] = $suppliers->get();
        }
        elseif(!empty($permission) && ($user_type == 4 || 5 || 6)){
            $data['suppliers'] = $suppliers->whereHas('users.manager',function($query)use($id){ $query->where('users.added_by',$id); })->get();
        }
        else{
            return redirect('admin');
        }
        return view('admin.supplier.expired-report')->with($data);
    }

    public function addSuppliers(){

        return view('admin.supplier.add-suppliers');
    }

    public function suppliersList(Request $request)
    {

        $user_type = Auth::user()->user_type;
        $id = auth::user()->id;

        $data = array();
        $data['permission'] = $permission = AppHelper::userPermission($request->segment(1));

        $suppliers = Supplier::with('users')
            ->whereHas('users',function($query){$query->where('email_verified_at', '!=', Null); $query->where('user_type',3); $query->where('is_active',1); $query->where('is_delete',0); $query->orderBy('companyname', 'asc');});

        if($user_type != 1 && $permission->full != 1){
            $suppliers->whereHas('users',function($query)use($id){ $query->where('users.added_by',$id); });
        }

        $data['suppliers'] = $suppliers->get();

        $data['countries'] = Supplier::with('users')->select('country')->groupBy('country')->get();

        $data['managers'] = User::whereIn('user_type',[1,4,5])->where('is_active',1)->where('is_delete',0)->get();

        return view('admin.supplier.suppliers-list')->with($data);
    }

    public function getSuppliersList(Request $request){
        $id = Auth::user()->id;

        $user_type = Auth::user()->user_type;

        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length");

        $permission = AppHelper::userPermission('suppliers-list');

        $searchval = $request->get("search")['value'];

        $sales_person_name =$request->get('sales_person_name');
        $supplier_id =$request->get('supplier_id');
        $type =$request->get('type');
        $status =$request->get('status');
        $kyc_status =$request->get('kyc_status');
        $country =$request->get('country');

        $arr['columnName'] ='sup_id';
        $arr['columnSortOrder'] =$request->get('order')[0]['dir'];

        $count = Supplier::with('users')
            ->whereHas('users',function($query) use($arr) { $query->where('email_verified_at', '!=', Null); $query->where('user_type',3); $query->where('is_active', 1); $query->where('is_delete', 0); $query->orderBy($arr['columnName'],$arr['columnSortOrder']); });

        $totalRecords = $count->count();

        $records = Supplier::select('sup_id','compnay_registration_document','diamond_type','cron_link','upload_mode','markup','stock_status','return_allow',DB::raw('(SELECT invalid_diamond from stock_upload_report WHERE id = (SELECT max(id) FROM stock_upload_report WHERE supplier_id = suppliers.sup_id GROUP BY supplier_id)) as invalid_diamond'),
                DB::raw('(SELECT conflicted from stock_upload_report WHERE id = (SELECT max(id) FROM stock_upload_report WHERE supplier_id = suppliers.sup_id GROUP BY supplier_id)) as conflicted_diamond'),
                DB::raw("IF ((suppliers.upload_mode = 'File') , (SELECT info FROM stock_upload_report WHERE supplier_id = suppliers.sup_id and is_delete = 0 Order By created_at Desc LIMIT 1), (SELECT info FROM stock_upload_report WHERE supplier_id = suppliers.sup_id and is_delete = 0 Order By created_at Desc LIMIT 1)) as file")
                )
                ->with('users:id,companyname,created_at,added_by','users.manager:id,firstname')
                ->when($searchval != "", function ($query) use($searchval) {
                    return $query->whereHas('users',function ($query) use($searchval) { $query->where('companyname', 'like', '%' .$searchval . '%'); });
                })
                ->when($sales_person_name != "", function ($query) use($sales_person_name) {
                    return $query->whereHas('users',function ($query) use($sales_person_name) { $query->where('added_by',$sales_person_name); });
                })
                ->when($supplier_id, function ($query) use ($supplier_id) {
                    return $query->whereHas('users',function ($query) use($supplier_id) { $query->where('id',$supplier_id); });
                })
                ->when($type != null, function ($query) use($type) {
                    return $query->where('diamond_type',$type);
                })
                ->when($status != null && $status == 0, function ($query) {
                    return $query->where('stock_status', 'ACTIVE');
                })
                ->when($status != null && $status == 1, function ($query) {
                    return $query->where('stock_status', 'INACTIVE');
                })
                ->when($kyc_status != null && $kyc_status == 0, function ($query) {
                    return $query->where('compnay_registration_document' ,'!=', null);
                })
                ->when($kyc_status != null && $kyc_status == 1, function ($query) {
                    return $query->where('compnay_registration_document',null);
                })
                ->when($country != null , function ($query) use ($country){
                    return $query->where('country','=', $country);
                })
                ->whereHas('users',function($query){ $query->where('email_verified_at', '!=', Null); $query->where('user_type',3); $query->where('is_active', 1); $query->where('is_delete', 0);});



        $data['records'] = $records->orderBy($arr['columnName'],$arr['columnSortOrder'])->skip($start)->take($rowperpage)->get();
        $totalRecordswithFilter =   $count->when($searchval != "", function ($query) use($searchval) {
                                        return $query->whereHas('users',function ($query) use($searchval) { $query->where('companyname', 'like', '%' .$searchval . '%'); });
                                    })
                                    ->when($sales_person_name != "", function ($query) use($sales_person_name) {
                                        return $query->whereHas('users',function ($query) use($sales_person_name) { $query->where('added_by',$sales_person_name); });
                                    })
                                    ->when($supplier_id, function ($query) use ($supplier_id) {
                                        return $query->whereHas('users',function ($query) use($supplier_id) { $query->where('id',$supplier_id); });
                                    })
                                    ->when($type != null, function ($query) use($type) {
                                        return $query->where('diamond_type',$type);
                                    })
                                    ->when($status != null && $status == 0, function ($query) {
                                        return $query->where('stock_status', 'ACTIVE');
                                    })
                                    ->when($status != null && $status == 1, function ($query) {
                                        return $query->where('stock_status', 'INACTIVE');
                                    })
                                    ->when($kyc_status != null && $kyc_status == 0, function ($query) {
                                        return $query->where('compnay_registration_document' ,'!=', null);
                                    })
                                    ->when($kyc_status != null && $kyc_status == 1, function ($query) {
                                        return $query->where('compnay_registration_document',null);
                                    })
                                    ->when($country != null , function ($query) use ($country){
                                        return $query->where('country','=', $country);
                                    })->count();
        $data_arr = array();
        $sno = $start+1;

        foreach($data['records'] as $record){
            $date = date('Y-m-d H:i:s', $record->users->created_at->timestamp);
            if ($record->upload_mode == "FTP")
            {
                $upload_mode = '<td><a href="'. url('suppliers-stock-refresh/'.$record->users->id) .'" target="_blank" >' . $record->upload_mode . '</a></td>';
            }
            elseif ($record->upload_mode == "custom API" || $record->upload_mode == "custom FTP")
            {
                $upload_mode = '<td><a href="'. $record->cron_link .'" target="_blank" >'. $record->upload_mode .'</a></td>';
            }
            else
            {
                $upload_mode = '<td><a href="'. asset('uploads/stocks_upload/'.$record->file) .'" target="_blank">'. $record->upload_mode .'</a></td>';
            }
            if($record->diamond_type == 'Natural')
            {
                $record->table = "diamond_natural";
            }
            else{
                $record->table = "diamond_labgrown";
            }
            $path = asset("/supplier_files/".$record->folder_name."/".$record->file);
            $data_arr[] = array(
                'plush' => '<i class="fa fa-plus" data-id="'.$record->users->id.'" data-name="'.$record->users->companyname.'"></i>',
                "id" => $record->sup_id,
                "editpermission" => $permission->edit,
                "deletepermission" => $permission->delete,
                "kyc" => (!empty($record->compnay_registration_document) ?
                '<div class="cursor-pointer symbol symbol-30px" data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                    <i class="fa fa-check checkforkyc" data-bs-toggle="tooltip" data-bs-placement="right" style="color: green;"></i>
                </div>
                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-primary fw-bold py-4 fs-1 w-275px kycdropdown" data-kt-menu="true">
                    <div class="menu-item px-3">
                        <div class="d-flex flex-column">
                            <div class="fw-bolder d-flex align-items-center fs-6">
                                <a href="/uploads/suppliers_doc/'.$record->compnay_registration_document.'" target="_blank">KYC document</a>
                            </div>
                        </div>
                    </div>
                </div>' :  ""),
                "suppliers" => $record->users->companyname,
                "upload_mode" => $upload_mode,
                "diamond_type" => $record->diamond_type,
                "stock_status" => ($record->stock_status == 'ACTIVE') ? '<span class="badge badge-primary">ACTIVE</span>' : '<span class="badge badge-danger">INACTIVE</span>',
                "valid" => '<span class="badge badge-success badge-lg validcount" style="cursor:pointer" id="sup'.$record->sup_id.'" data-table="'.$record->table.'" data-supplier_id="'.$record->sup_id.'">Count</span>',
                "invalid" => '<a href="'.url('suppliers-invalid-diamond/'.$record->sup_id).'" target="_blank" class="menu-link px-3 text-danger">'.$record->invalid_diamond.'</a>',
                "conflicted" => $record->conflicted_diamond,
                "return_allow" => ($record->return_allow == 1) ? 'Yes' : 'No',
                "staffname" => optional($record->users->manager)->firstname,
                "markup" => $record->markup,
                "created_at" => $date,
                "stonesshow" => (!empty($record->upload_mode)) ? '<a href="'.url('suppliers-all-diamond/'.$record->sup_id).'"><button class="btn btn-warning btn-icon" type="button" id="suppliersdiamond"><i class="fa fa-search"></i></button></a>' : '' ,
                "file" => ($record->upload_mode == "FTP" || $record->upload_mode == "File" ) ? '<a href= "'.route('supplier.downloadSuppliersFile',$record->sup_id).'" target="_blank"><button type="button" class="btn btn-info btn-icon"><i class="fa fa-download"></i></button></a>' : '',
            );
        }

        $response = array(
                    "draw" => intval($draw),
                    "iTotalRecords" => $totalRecords,
                    "iTotalDisplayRecords" => $totalRecordswithFilter,
                    "aaData" => $data_arr,
                );

        return json_encode($response);
    }

    // public function getsuppliersList(Request $request)
    // {
    //     $id = auth::user()->id;

    //     $data['permission'] = $permission = AppHelper::userPermission('suppliers-list');

    //     $user_type = Auth::user()->user_type;
    //     $draw = $request->get('draw');
    //     $start = $request->get("start");
    //     $rowperpage = $request->get("length"); // Rows display per page

    //     $columnIndex_arr = $request->get('order');
    //     $columnName_arr = $request->get('columns');

    //     $order_arr = $request->get('order');
    //     $search_arr = $request->get('search');

    //     $columnIndex = $columnIndex_arr[0]['column']; // Column index
    //     $columnName = 'id';//$columnName_arr[$columnIndex]['data']; // Column name

    //     $columnSortOrder = $order_arr[0]['dir']; // asc or desc
    //     $searchValue = $search_arr['value']; // Search value

    //     $supplier_manager = $request->sales_person_name;
    //     $kyc_status = $request->kyc_status;
    //     $supplier_id = $request->supplier_id;
    //     $type = $request->type;
    //     $status = $request->status;
    //     $country = $request->country;
    //     $start_date = $request->start_date;

    //     $arr['columnName'] =$columnName;
    //     $arr['columnSortOrder'] =$columnSortOrder;

    //     // Total records
    //     $count = Supplier::with('users')
    //         ->whereHas('users',function($query) use($arr) { $query->where('email_verified_at', '!=', Null); $query->where('user_type',3); $query->where('is_active', 1); $query->where('is_delete', 0); $query->orderBy($arr['columnName'],$arr['columnSortOrder']); });

    //     $totalRecords = $count->count();

    //     $records = Supplier::select('*',DB::raw('(SELECT invalid_diamond from stock_upload_report WHERE id = (SELECT max(id) FROM stock_upload_report WHERE supplier_id = suppliers.sup_id GROUP BY supplier_id)) as invalid_diamond'),
    //             DB::raw('(SELECT conflicted from stock_upload_report WHERE id = (SELECT max(id) FROM stock_upload_report WHERE supplier_id = suppliers.sup_id GROUP BY supplier_id)) as conflicted_diamond'),
    //             DB::raw("IF ((suppliers.upload_mode = 'File') , (SELECT info FROM stock_upload_report WHERE supplier_id = suppliers.sup_id and is_delete = 0 Order By created_at Desc LIMIT 1), (SELECT info FROM stock_upload_report WHERE supplier_id = suppliers.sup_id and is_delete = 0 Order By created_at Desc LIMIT 1)) as file")
    //             )
    //             ->with('users','users.manager:id,firstname')
    //             ->whereHas('users',function($query){ $query->where('email_verified_at', '!=', Null); $query->where('user_type',3); $query->where('is_active', 1); $query->where('is_delete', 0);})
    //             ->when(($user_type !=1 AND $data['permission']->full != 1), function ($query) use ($id) {
    //                 return $query->whereHas('users',function($query)use($id){ $query->where('added_by',$id); });
    //             })
    //             ->when($type, function ($query) use ($type) {
    //                 return $query->where('diamond_type', $type);
    //             })
    //             ->when($supplier_manager, function ($query) use ($supplier_manager) {
    //                 return $query->whereHas('users',function ($query) use($supplier_manager) { $query->where('added_by',$supplier_manager); });
    //             })
    //             ->when($supplier_id, function ($query) use ($supplier_id) {
    //                 return $query->whereHas('users',function ($query) use($supplier_id) { $query->where('id',$supplier_id); });
    //             })
    //             ->when($status != "" && $status == 0, function ($query) {
    //                 return $query->where('stock_status', 'ACTIVE');
    //             })
    //             ->when($status == 1, function ($query) {
    //                 return $query->where('stock_status', 'INACTIVE');
    //             })
    //             ->when($kyc_status != "" && $kyc_status == 0, function ($query) {
    //                 return $query->where('compnay_registration_document' ,'!=', null);
    //             })
    //             ->when($kyc_status == 1, function ($query) {
    //                 return $query->where('compnay_registration_document',null);
    //             })
    //             ->when($country != '' && $country != null , function ($query) use ($country){
    //                 return $query->where('country','=', $country);
    //             })
    //             ->when($start_date != '' , function ($query) use ($start_date){
    //                 return $query->where('updated_at','>', $start_date);
    //             })

    //             ->skip($start)
    //             ->take($rowperpage);

    //     if(empty($request->input('search.value')))
    //     {
    //         $records = $records->get();
    //         $totalRecordswithFilter = $count->when(($user_type !=1 AND $data['permission']->full != 1), function ($query) use ($id) {
    //             return $query->whereHas('users',function($query)use($id){ $query->where('added_by',$id); });
    //         })
    //         ->when($type, function ($query) use ($type) {
    //             return $query->where('diamond_type', $type);
    //         })
    //         ->when($supplier_manager, function ($query) use ($supplier_manager) {
    //             return $query->whereHas('users',function ($query) use($supplier_manager) { $query->where('added_by',$supplier_manager); });
    //         })
    //         ->when($supplier_id, function ($query) use ($supplier_id) {
    //             return $query->whereHas('users',function ($query) use($supplier_id) { $query->where('id',$supplier_id); });
    //         })
    //         ->when($status != "" && $status == 0, function ($query) {
    //             return $query->where('stock_status', 'ACTIVE');
    //         })
    //         ->when($status == 1, function ($query) {
    //             return $query->where('stock_status', 'INACTIVE');
    //         })
    //         ->when($kyc_status != "" && $kyc_status == 0, function ($query) {
    //             return $query->where('compnay_registration_document' ,'!=', null);
    //         })
    //         ->when($kyc_status == 1, function ($query) {
    //             return $query->where('compnay_registration_document',null);
    //         })->count();
    //     }
    //     else
    //     {
    //         $records = $records->whereHas('users',function ($query) use($searchValue) { $query->where('companyname', 'like', '%' .$searchValue . '%'); })->get();

    //         $totalRecordswithFilter = $count->when(($user_type !=1 AND $data['permission']->full != 1), function ($query) use ($id) {
    //             return $query->whereHas('users',function($query)use($id){ $query->where('added_by',$id); });
    //         })
    //         ->whereHas('users',function ($query) use($searchValue) { $query->where('companyname', 'like', '%' .$searchValue . '%'); })
    //         ->when($type, function ($query) use ($type) {
    //             return $query->where('diamond_type', $type);
    //         })
    //         ->when($supplier_manager, function ($query) use ($supplier_manager) {
    //             return $query->whereHas('users',function ($query) use($supplier_manager) { $query->where('added_by',$supplier_manager); });
    //         })
    //         ->when($supplier_id, function ($query) use ($supplier_id) {
    //             return $query->whereHas('users',function ($query) use($supplier_id) { $query->where('id',$supplier_id); });
    //         })
    //         ->when($status != "" && $status == 0, function ($query) {
    //             return $query->where('stock_status', 'ACTIVE');
    //         })
    //         ->when($status == 1, function ($query) {
    //             return $query->where('stock_status', 'INACTIVE');
    //         })
    //         ->when($kyc_status != "" && $kyc_status == 0, function ($query) {
    //             return $query->where('compnay_registration_document' ,'!=', null);
    //         })
    //         ->when($kyc_status == 1, function ($query) {
    //             return $query->where('compnay_registration_document',null);
    //         })->count();
    //     }

    //     $data_arr = array();
    //     $sno = $start+1;
    //     $folder_name='';

    //     foreach($records as $record){
    //         $date = date('Y-m-d H:i:s', $record->users->created_at->timestamp);
    //         if ($record->upload_mode == "FTP")
    //         {
    //             $upload_mode = '<td><a href="'. url('suppliers-stock-refresh/'.$record->users->id) .'" target="_blank" >' . $record->upload_mode . '</a></td>';
    //         }
    //         elseif ($record->upload_mode == "custom API" || $record->upload_mode == "custom FTP")
    //         {
    //             $upload_mode = '<td><a href="'. $record->cron_link .'" target="_blank" >'. $record->upload_mode .'</a></td>';
    //         }
    //         else
    //         {
    //             $upload_mode = '<td><a href="'. asset('uploads/stocks_upload/'.$record->file) .'" target="_blank">'. $record->upload_mode .'</a></td>';
    //         }
    //         if($record->diamond_type == 'Natural')
    //         {
    //             $record->table = "diamond_natural";
    //         }
    //         else{
    //             $record->table = "diamond_labgrown";
    //         }
    //         $path = asset("/supplier_files/".$record->folder_name."/".$record->file);
    //         $data_arr[] = array(
    //             'plush' => '<i class="fa fa-plus" data-id="'.$record->users->id.'" data-name="'.$record->users->companyname.'"></i>',
    //             "id" => $record->sup_id,
    //             "editpermission" => $permission->edit,
    //             "deletepermission" => $permission->delete,
    //             "kyc" => (!empty($record->compnay_registration_document) ?
    //             '<div class="cursor-pointer symbol symbol-30px" data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
    //                 <i class="fa fa-check checkforkyc" data-bs-toggle="tooltip" data-bs-placement="right" style="color: green;"></i>
    //             </div>
    //             <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-primary fw-bold py-4 fs-1 w-275px kycdropdown" data-kt-menu="true">
    //                 <div class="menu-item px-3">
    //                     <div class="d-flex flex-column">
    //                         <div class="fw-bolder d-flex align-items-center fs-6">
    //                             <a href="/uploads/suppliers_doc/'.$record->compnay_registration_document.'" target="_blank">KYC document</a>
    //                         </div>
    //                     </div>
    //                 </div>
    //             </div>' :  ""),
    //             "suppliers" => $record->users->companyname,
    //             "upload_mode" => $upload_mode,
    //             "diamond_type" => $record->diamond_type,
    //             "stock_status" => ($record->stock_status == 'ACTIVE') ? '<span class="badge badge-primary">ACTIVE</span>' : '<span class="badge badge-danger">INACTIVE</span>',
    //             "valid" => '<span class="badge badge-success badge-lg validcount" style="cursor:pointer" id="sup'.$record->sup_id.'" data-table="'.$record->table.'" data-supplier_id="'.$record->sup_id.'">Count</span>',
    //             "invalid" => '<a href="'.url('suppliers-invalid-diamond/'.$record->sup_id).'" target="_blank" class="menu-link px-3 text-danger">'.$record->invalid_diamond.'</a>',
    //             "conflicted" => $record->conflicted_diamond,
    //             "return_allow" => ($record->return_allow == 1) ? 'Yes' : 'No',
    //             "staffname" => optional($record->users->manager)->firstname,
    //             "markup" => $record->markup,
    //             "created_at" => $date,
    //             "stonesshow" => (!empty($record->upload_mode)) ? '<a href="'.url('suppliers-all-diamond/'.$record->sup_id).'"><button class="btn btn-warning btn-icon" type="button" id="suppliersdiamond"><i class="fa fa-search"></i></button></a>' : '' ,
    //             "file" => ($record->upload_mode == "FTP" || $record->upload_mode == "File" ) ? '<a href= "'.route('supplier.downloadSuppliersFile',$record->sup_id).'" target="_blank"><button type="button" class="btn btn-info btn-icon"><i class="fa fa-download"></i></button></a>' : '',
    //         );
    //     }

    //     $response = array(
    //         "draw" => intval($draw),
    //         "iTotalRecords" => $totalRecords,
    //         "iTotalDisplayRecords" => $totalRecordswithFilter,
    //         "aaData" => $data_arr,
    //     );

    //     echo json_encode($response);
    //     exit;
    // }

    public function downloadSuppliersFile(Request $request){
        $id=$request->id;
        $record = Supplier::select('*',DB::raw("IF ((suppliers.upload_mode = 'File') , (SELECT info FROM stock_upload_report WHERE supplier_id = suppliers.sup_id Order By created_at Desc LIMIT 1), (SELECT info FROM stock_upload_report WHERE supplier_id = suppliers.sup_id and is_delete = 0 Order By created_at Desc LIMIT 1)) as file"))->where('sup_id',$id)->first();

        if($record->upload_mode == 'FTP'){
            // return response()->download(base_path('supplier_files/'.$record->folder_name.'/'.$record->file));
            return response()->download(public_path('uploads/stocks_upload/'.$record->file));
        }
        else{
            return response()->download(public_path('uploads/stocks_upload/'.$record->file));
        }
    }

    public function downloadImages(Request $request){
        $id = $request->id;
        $table = Supplier::select('diamond_type')->where('sup_id',$id)->first();
        if($table->diamond_type == 'Natural'){
            $images = DiamondNatural::select('image','certificate_no')->where('image','!=','')->where('supplier_id','=',$id)->get();
        }
        else{
            $images = DiamondLabgrown::select('image','certificate_no')->where('image','!=','')->where('supplier_id','=',$id)->get();
        }
        foreach($images as $image){

            $filename = basename($image->image);
            $extension = (pathinfo($filename,PATHINFO_EXTENSION));
            Image::make($image->image)->save(public_path('assets/test/' . $image->certificate_no .".".$extension));
            // $image->i->move(public_path('assets/test/' . $image->cerificate_no. '.' . $extension));
        }
    }

    public function suppliersalldiamond(Request $request){
        $id = $request->id;
        $data['supplier'] = User::with('supplier')->where('is_delete',0)->where('id',$id)->first();

        if($data['supplier']->supplier->diamond_type == 'Natural'){
            $data['diamonds'] = DiamondNatural::sortable()->select('*')->selectRaw("(SELECT caratprice FROM rap_list WHERE IF(`shape_name` = 'ROUND', shape = 'ROUND', shape != 'ROUND') AND color_name = `color` AND clarity_name = IF(`clarity` = 'FL','IF', `clarity`) AND low_size <= carat AND high_size >= carat) as raprate")
                                ->where('supplier_id',$id)
                                ->where('is_delete','=','0')
                                ->orderBy('carat','desc')
                                ->paginate(100);
            $data['stonecount'] = DiamondNatural::where('supplier_id',$id)->where('is_delete','=','0')->count();
            $data['imagescount'] = DiamondNatural::where('supplier_id',$id)->where('image','!=','')->where('is_delete','=','0')->count();
            $data['videoscount'] = DiamondNatural::where('supplier_id',$id)->where('video','!=','')->where('is_delete','=','0')->count();

        }
        else{
            $data['diamonds'] = DiamondLabgrown::sortable()->select('*')->selectRaw("(SELECT caratprice FROM rap_list WHERE IF(`shape_name` = 'ROUND', shape = 'ROUND', shape != 'ROUND') AND color_name = `color` AND clarity_name = IF(`clarity` = 'FL','IF', `clarity`) AND low_size <= carat AND high_size >= carat) as raprate")
                                ->where('supplier_id',$id)
                                ->where('is_delete','=','0')
                                ->orderBy('carat','desc')
                                ->paginate(100);
            $data['stonecount'] = DiamondLabgrown::where('supplier_id',$id)->where('is_delete','=','0')->count();
            $data['imagescount'] = DiamondLabgrown::where('supplier_id',$id)->where('image','!=','')->where('is_delete','=','0')->count();
            $data['videoscount'] = DiamondLabgrown::where('supplier_id',$id)->where('video','!=','')->where('is_delete','=','0')->count();
        }

        return view('admin.supplier.suppliers-all-diamond')->with($data);
    }

    public function validDiamondCount(Request $request){
        $table = $request->table;
        $supplier_id = $request->supplier_id;

        $data['diamond']= DB::table($table)->where('supplier_id','=',$supplier_id)->where('is_delete', 0)->count();

        return json_encode($data);
    }

    public function supplierPassword(Request $request)
    {
        $id = $request->id;
        $data['supplier'] = Supplier::where('sup_id', $id)->firstOrFail();

        return view('admin.supplier.supplier-password')->with($data);
    }

    public function updateSupplierPassword(Request $request)
    {
        $request->validate([
            'password'=>['required','confirmed',Password::min(8)->mixedCase()->numbers()->symbols()],
            'password_confirmation'=>['required',Password::min(8)->mixedCase()->numbers()->symbols()],
        ]);

        $newpassword = $request->password;
        $confirmpassword = $request->password_confirmation;

        $id = $request->id;
        if($newpassword == $confirmpassword)
        {
            Supplier::join('users','users.id','=','suppliers.sup_id')->where('users.id',$id)->update(['password'=>Hash::make($newpassword)]);
            return redirect('suppliers-list')->with('update','Password Update Successful !');
        }
        else
        {
            return redirect('suppliers-list')->with('failed',' Password Not Match');
        }
    }

	public function suppliersInvalidDiamond(Request $request)
    {
        $id = $request->id;
        $data['supplier'] = Supplier::with('users')->where('sup_id', $id)->firstOrFail();
        $data['diamonds'] = DiamondInvalid::where('supplier_id',$id)->get();

        return view('admin.supplier.suppliers-invalid-diamond')->with($data);
    }

    public function inDiamondDetail(Request $request)
    {
        $certi_no = $request->certificate_no;
        $value = DiamondInvalid::where('certificate_no',$certi_no)
        ->first();

        if(!empty($value)) {
			$carat = $value->carat;
			$color = $value->color;

			$detail = '<div class="modal-dialog modal-dialog-centered mw-900px"><div class="modal-content">
						<div class="modal-header p-5">
							<h5 class="modal-title">Diamond Detail</h5>
							<div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-times"></i></div>
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-12">
									<h4 class="modal-title">Basic Detail:</h4>
									<hr class="my-1">
								</div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-md-6"><span class="fw-bold text-dark">Last updated Date</span> : ' . $value->updated_at . '</div>
                                <div class="col-md-6"><span class="fw-bold text-dark">Reason</span> : ' . $value-> reason. ' </div>
                            </div>
							<div class="row mb-1">
                                <div class="col-md-4"><span class="fw-bold text-dark">Stone No</span> : LG' . $value->id . '</div>
                                <div class="col-md-4"><span class="fw-bold text-dark">Certificate</span> : ' . $value->certificate_no . '</div>
                                <div class="col-md-4"><span class="fw-bold text-dark">Shape</span> : ' . $value->shape . '</div>
                            </div>
							<div class="row mb-1">
                                <div class="col-md-4"><span class="fw-bold text-dark">Color</span> : ' . $value->color . '</div>
                                <div class="col-md-4"><span class="fw-bold text-dark">Clarity</span> : ' . $value->clarity . '</div>
                                <div class="col-md-4"><span class="fw-bold text-dark">Carat</span> : ' . $carat . '</div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><span class="fw-bold text-dark">Cut</span> : ' . $value->cut . '</div>
                                <div class="col-md-4"><span class="fw-bold text-dark">Polish</span> : ' . $value->polish . '</div>
                                <div class="col-md-4"><span class="fw-bold text-dark">Symmetry</span> : ' . $value->symmetry . '</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4"><span class="fw-bold text-dark">Fluorescence</span> : ' . $value->fluorescence . '</div>
                            </div>

                            <div class="row">
								<div class="col-12">
                                    <h4 class="modal-title">Fancy Color:</h4>
                                    <hr class="my-1">
								</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4"><span class="fw-bold text-dark">Fancy color</span> : ' . $value->fancy_color . '</div>
                                <div class="col-md-4"><span class="fw-bold text-dark">Fancy intensity</span> : ' . $value->fancy_intensity . '</div>
                                <div class="col-md-4"><span class="fw-bold text-dark">Fancy overtone</span> : ' . $value->fancy_overtone . '</div>
                            </div>

                            <div class="row">
								<div class="col-12">
                                    <h4 class="modal-title">Extra detail:</h4>
                                    <hr class="my-1">
								</div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><span class="fw-bold text-dark">Measurement</span> : ' . $value->length . '*' . $value->width . '*' . $value->depth . '</div>
                                <div class="col-md-4"><span class="fw-bold text-dark">Table %</span> : ' . $value->table_per . '</div>
                                <div class="col-md-4"><span class="fw-bold text-dark">Depth %</span> : ' . $value->depth_per . '</div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><span class="fw-bold text-dark">C.Height</span> : ' . $value->crown_height . '</div>
                                <div class="col-md-4"><span class="fw-bold text-dark">C.Angle</span> : ' . $value->crown_angle . '</div>
                                <div class="col-md-4"><span class="fw-bold text-dark">P.Height</span> : ' . $value->pavilion_depth . '</div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><span class="fw-bold text-dark">P.Angle</span> : ' . $value->pavilion_angle . '</div>
                                <div class="col-md-4"><span class="fw-bold text-dark">Eye Clean</span> : ' . $value->eyeclean . '</div>
                                <div class="col-md-4"><span class="fw-bold text-dark">Key to Symbol</span> : ' . $value->key_symbols . '</div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><span class="fw-bold text-dark">Country</span> : ' . $value->country . '</div>
                                <div class="col-md-4"><span class="fw-bold text-dark">City</span> : ' . $value->city . '</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4"><span class="fw-bold text-dark">Milky</span> : ' . $value->milky . '</div>
                                <div class="col-md-4"><span class="fw-bold text-dark">Luster</span> : ' . $value->luster . '</div>
                                <div class="col-md-4"><span class="fw-bold text-dark">Shade</span> : ' . $value->shade . '</div>
                            </div>

                            <div class="row">
								<div class="col-12">
                                    <h4 class="modal-title">Price Detail:</h4>
                                    <hr class="my-1">
								</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4"><span class="fw-bold text-dark">Per Carat Price</span> : ' .  $value->orignal_rate . '</div>
                                <div class="col-md-4"><span class="fw-bold text-dark">Net Price</span> : ' .  $value->net_dollar . '</div>
                            </div>
                        </div>
                        <div class="modal-footer text-center">
                            <button type="button" class="btn btn-success btn-embossed bnt-square" data-bs-dismiss="modal"><i class="fa fa-check"></i> Ok</button>
                        </div>
                    </div>';
			$data['success'] = $detail;
		}
        echo json_encode($data);
    }


    public function activateSuppliers(Request $request)
    {
        $detail = '';
		$id = $request->id;

        // $this->db->select('*, (SELECT CONCAT(fname, " ", lname) AS purchase_manager FROM admin WHERE admin.id = supplier.sales_person limit 1) as purchase_manager, (SELECT info FROM `supplier_report` WHERE supplier_report.supplier_id = supplier.id ORDER BY created_date desc LIMIT 1) as info');
		$supplier = User::with('supplier')->where('sup_id',$id)->first();
        if(count($supplier) > 0)
        {
            $firstname = $supplier->firstname;
            $lastname = $supplier->lastname;
            $supplier_name = $supplier->supplier->supplier_name;

            $email_verified_at = $supplier->email_verified_at;
            if($email_verified_at)
            {
                User::where('id', $id)->update(array('is_active' => '1'));

                try {
                    Mail::send('emails.supplier-approval', ['firstname'=> $supplier->firstname, 'lastname'=> $supplier->lastname, 'companyname' => $supplier->companyname, 'email' => $supplier->email], function($message) use($supplier){
                        $message->to($supplier->email);
                        $message->cc(\Cons::EMAIL_SUPPLIER);
                        $message->subject("Your account is now active. | ". config('app.name'));
                    });
                } catch (\Throwable $th) {

                }
                $this->SupplierRequestEntry($id);

                $data['success'] = true;
            }
            else
            {
                $data['success'] = false;
                $data['error'] = "Please verify Email address";
            }
        }
		else
        {
            $data['success'] = false;
        }

		echo json_encode($data);
    }

    public function supplierInvoice(Request $request)
    {
        $id = $request->id;
        $data['user_id'] = $id;
        $data['supplier_invoice'] = SupplierInvoice::where('sup_id',$id)->get();
        return view('admin.supplier.invoice-list')->with($data);
    }

    public function SupInvoice(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page
        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');
        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        // Total records
        $totalRecords = SupplierInvoice::select('count(*) as allcount')->count();
        $totalRecordswithFilter = SupplierInvoice::select('count(*) as allcount')->where('file_name', 'like', '%' .$searchValue . '%')->count();
        // Fetch records

        $records = SupplierInvoice::orderBy($columnName,$columnSortOrder)
            // ->where('sup_id',$id)
            ->where('supplier_invoices.file_name', 'like', '%' .$searchValue . '%')
            ->select('supplier_invoices.*')
            ->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = array();

        $sno = $start+1;

        foreach($records as $record){
            // $id = $record->id;
            $sup_id = $record->sup_id;
            $file_name = $record->file_name;
            $created_at = $record->created_at;

            $data_arr[] = array(
                // "id" => $id,
                "sup_id" => $sup_id,
                "file_name" =>$file_name,
                "created_at"=>$created_at
            );
        }

        $response = array(

            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );

        echo json_encode($response);
        exit;

    }

    public function InvoiceDownload(Request $request)
    {
        $id = $request->id;
        $data = SupplierInvoice::where('id',$id)->first();
        $file = $data->file_name;
        $file_name = public_path('uploads/supplier_invoice/'.$file);

    }

    public function supplierEdit(Request $request)
    {
        $id = $request->id;
        $data['supplier'] = Supplier::with('users')->where('sup_id',$id)->first();

        $data['approvedorders'] = Order::with('orderdetail:orders_items_id,supplier_id')->where('order_status','APPROVED')->whereHas('orderdetail',function($query) use($id){ $query->where('supplier_id',$id); })->count();
        $data['rejectedorders'] = Order::with('orderdetail:orders_items_id,supplier_id')->whereIn('order_status',['REJECT','RELEASED'])->whereHas('orderdetail',function($query) use($id){ $query->where('supplier_id',$id); })->count();

        $data['active'] = SupplierStatusHistory::where('status','ACTIVE')->where('comment','!=','')->where('sup_id',$id)->orderBy('date','desc')->first();
        $data['inactive'] = SupplierStatusHistory::where('status','INACTIVE')->where('comment','!=','')->where('sup_id',$id)->orderBy('date','desc')->first();

        $data['status_history'] = SupplierStatusHistory::with('users','updatedBy')->where('sup_id',$id)->orderBy('date','desc')->get();

        $data['salesperson'] = array();

        $data['staff'] = User::whereIn('user_type',[1,4,5])
        ->where('is_active',1)
        ->where('is_delete',0)
        ->get();

        return view('admin.supplier.suppliers-edit')->with($data);
    }

	public function supplierEditSave(Request $request)
    {

        $sup_id = $request->sup_id;
        $validatedData = $request->validate([
            'markup' => 'numeric',
            'compnay_registration_document'=>'max:10000|mimes:pdf',
            'compnay_partner_document'=>'max:10000|mimes:pdf',
        ]);

        $supplier = Supplier::where('sup_id',$sup_id)->first();

        $compnay_registration_document = '';
        $compnay_partner_document = '';

        if(!empty($request->file('compnay_registration_document')))
        {
            $compnay_registration_document = str_replace(array(' ', ',', '+','&','=','(',')'), '-', time().$request->file('compnay_registration_document')->getClientOriginalName());
            $request->file('compnay_registration_document')->storeAs('suppliers_doc',$compnay_registration_document,'public');
        }
        else
        {
            $compnay_registration_document = $supplier->compnay_registration_document;
        }

        if(!empty($request->file('compnay_partner_document')))
        {
            $compnay_partner_document = str_replace(array(' ', ',', '+','&','=','(',')'), '-', time().$request->file('compnay_partner_document')->getClientOriginalName());
            $request->file('compnay_partner_document')->storeAs('suppliers_doc', $compnay_partner_document, 'public');
        }
        else
        {
            $compnay_partner_document = $supplier->compnay_partner_document;
        }

        $data_user = array(
            'companyname'=>$request->company,
            'added_by'=>$request->sales_person,
            'mobile'=>$request->telphone,
            'firstname'=>$request->firstname,
            'lastname'=>$request->lastname,
        );
        User::where('id',$sup_id)->update($data_user);

        if($supplier->stock_status != $request->stock_status){
            SupplierStatusHistory::insert([
                'sup_id' => $sup_id,
                'updated_by' => Auth::user()->id,
                'comment' => $request->reason,
                'feedback' => '',
                'status' => $request->stock_status,
                'date' => date('Y-m-d H:i:s')
            ]);
        }

        if($request->feedback != null){
            SupplierStatusHistory::insert([
                'sup_id' => $sup_id,
                'updated_by' => Auth::user()->id,
                'comment' => '',
                'feedback' => $request->feedback,
                'status' => 'FOLLOW_UP',
                'date' => date('Y-m-d H:i:s')
            ]);
        }

        $data = array(
            'diamond_type' => $request->diamond_type,
            'hold_allow' => $request->hold_allow,
            'stock_status' => $request->stock_status,
            // 'reason' => $reason,
            // 'inactive_date' => $inactive_date,
            'return_allow' => $request->return_allow,
            'memo_allow' => $request->memo_allow,
            'markup' => $request->markup,
            'address' => $request->address,
            'upload_mode' => $request->upload_mode,
            'ftp_host' => $request->ftp_host,
            'ftp_username' => $request->ftp_username,
            'ftp_password' => $request->ftp_password,
            'ftp_port' => $request->ftp_port,
            'folder_name' => $request->folder_name,
            'supplier_name' => $request->company,
            'address'=> $request->address,
            'website'=>$request->website,
            'country'=>$request->country,
            'state'=>$request->state,
            'city'=>$request->city,
            'zipcode'=>$request->pincode,
            'compnay_registration_number'=>$request->compnay_registration_number,
            'compnay_partner_name'=>$request->compnay_partner_name,
            'compnay_registration_document'=>$compnay_registration_document,
            'compnay_partner_document'=>$compnay_partner_document,
            'designation'=>$request->designation,
            'broker_name'=>$request->brokername,
            'broker_email'=>$request->brokeremail,
            'boker_phone'=>$request->bokerphone,
        );
		Supplier::where('sup_id', $sup_id)->update($data);

        if($request->stock_status == 'INACTIVE')
        {
            if($request->diamond_type == 'Natural')
            {
                DiamondNatural::where('supplier_id', $sup_id)->update(['is_delete' => 1]);
            }

            if($request->diamond_type == 'Lab Grown')
            {
                DiamondLabgrown::where('supplier_id', $sup_id)->update(['is_delete' => 1]);
            }
        }

        return redirect("suppliers-edit/$sup_id")->with('success','Supplier Updated Successful');
    }

    public function DeleteSupplier(Request $request)
    {
        $id = $request->id;
        User::where('id',$id)->update([
            'is_delete'=>'1',
            'is_active'=>'0'
        ]);
        DiamondLabgrown::where('supplier_id', $id)->update(['is_delete' => 1]);
        DiamondNatural::where('supplier_id', $id)->update(['is_delete' => 1]);

        return redirect('deleted-supplier-list')->with('success','Supplier Delete Successful');
    }

    public function addNewSupplier(Request $request)
    {

        $request->validate([
            'email'=>'required|unique:users,email',
            'password'=>'required|min:8',
            'companyname'=>'required|unique:users,companyname',
            'telphone'=>'required',
            'firstname'=>'required',
            'lastname'=>'required',
            'country'=>'required',
            "state"=>'required',
            'city'=>'required',
            'stock_status'=>'required',
            'upload_mode'=>'',
        ]);

        $sup_user_table = array(
            'email'=> $request->email,
            'password'=>Hash::make($request->password),
            'companyname'=>$request->companyname,
            'mobile' => $request->telphone,
            'firstname'=>$request->firstname,
            'lastname'=>$request->lastname,
            // 'email_verify_code'=>Str::random(32),
            'email_verified_at'=>date_create(),
            'user_type' => 3,
            'is_active' => 1,
            'created_at' => date_create(),
        );

        $insert = User::insertGetId($sup_user_table);
        $sup_table = array(
            'sup_id'=>$insert,
            'stock_status'=>$request->stock_status,
            'diamond_type'=>$request->diamond_type,
            'supplier_name'=>$request->companyname,
            'diamond_type'=> $request->diamond_type,
            'upload_mode'=> $request->upload_mode,
            'country'=>$request->country,
            'state'=>$request->state,
            'city'=>$request->city,

        );
        Supplier::insert($sup_table);
        $this->SupplierRequestEntry($insert);

        return redirect('add-suppliers')->with('update','Supplier Add Successful');
    }

    public function GetSupplierUploadReport(Request $request)
    {
		$debug = '';
		$html = '';
		$id = $request->id;
		$detail_responce = StockUploadReport::where('supplier_id',$id)->orderBy('created_at', 'desc')->limit(30)->get();

		$html .= '<table class="table table-striped table-bordered">
					<tr class="headings">
						<th class="column-title">File</th>
						<th class="column-title">No of Diamond</th>
						<th class="column-title">Valid</th>
						<th class="column-title">invalid</th>
						<th class="column-title">Added</th>
						<th class="column-title">Updated</th>
						<th class="column-title">Conflicted</th>
						<th class="column-title">Uploaded Date</th>
						<th class="column-title">File Update Date</th>
					</tr>';

        if(!empty($detail_responce)) {
            foreach ($detail_responce as $value) {
                $html .= '<tr>
                            <td class="capital_user">
                                ' . $value->upload_mode. ' '. $value->info;
                                if($value->upload_mode != 'custom API' && $value->upload_mode != 'API'){
                $html .=            '<a href="'. (!empty($value->info) ? url('uploads/stocks_upload/'.$value->info) : "#") .'" target="_blank">
                                        <span class="svg-icon svg-icon-primary svg-icon-2x" title="Download Stock File">
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill-rule="evenodd" fill="none">
                                                    <rect x="0" y="0" width="24" height="24"></rect>
                                                    <path d="M3.5,21 L20.5,21 C21.3284271,21 22,20.3284271 22,19.5 L22,8.5 C22,7.67157288 21.3284271,7 20.5,7 L10,7 L7.43933983,4.43933983 C7.15803526,4.15803526 6.77650439,4 6.37867966,4 L3.5,4 C2.67157288,4 2,4.67157288 2,5.5 L2,19.5 C2,20.3284271 2.67157288,21 3.5,21 Z" fill="#000000" opacity="0.3"></path>
                                                    <path d="M14.8875071,12.8306874 L12.9310336,12.8306874 L12.9310336,10.8230161 C12.9310336,10.5468737 12.707176,10.3230161 12.4310336,10.3230161 L11.4077349,10.3230161 C11.1315925,10.3230161 10.9077349,10.5468737 10.9077349,10.8230161 L10.9077349,12.8306874 L8.9512614,12.8306874 C8.67511903,12.8306874 8.4512614,13.054545 8.4512614,13.3306874 C8.4512614,13.448999 8.49321518,13.5634776 8.56966458,13.6537723 L11.5377874,17.1594334 C11.7162223,17.3701835 12.0317191,17.3963802 12.2424692,17.2179453 C12.2635563,17.2000915 12.2831273,17.1805206 12.3009811,17.1594334 L15.2691039,13.6537723 C15.4475388,13.4430222 15.4213421,13.1275254 15.210592,12.9490905 C15.1202973,12.8726411 15.0058187,12.8306874 14.8875071,12.8306874 Z" fill="#000000"></path>
                                                </g>
                                            </svg>
                                        </span>
                                    </a>';
                                }
                $html .=    '</td>
                            <td class="capital_user">' . $value->no_of_stone . '</td>
                            <td class="capital_user">' . $value->valid_diamond . '</td>
                            <td class="capital_user">' . $value->invalid_diamond . '</td>
                            <td class="capital_user">' . $value->added . '</td>
                            <td class="capital_user">' . $value->updated . '</td>
                            <td class="capital_user">' . $value->conflicted . '</td>
                            <td>' . $value->created_at . '</td>
                            <td>' . $value->file_updated_at . '</td>
                        </tr>';
                // $curr = date('d-m-Y');
                // $ddate = date("d-m-Y", strtotime($value->created_date));
                // if ($curr == $ddate) {

                // } else {
                // 	$debug .= '<tr>
                // 		<td class="capital_user">' . $value->info . '</td>
                // 		<td class="capital_user">' . $value->no_of_stone . '</td>
                // 		<td class="capital_user">' . $value->active . '</td>
                // 		<td class="capital_user">' . $value->inactive . '</td>
                // 		<td>' . $value->created_date . '</td>
                // 		<td>' . $value->file_update_date . '</td>
                // 	</tr>';
                // }
            }
        }

		$html .= '</table>';
		$data['detail'] = $html;
		// $data['debug'] = $debug;
		echo json_encode($data);
	}

    public function supplierMarkup(Request $request)
    {
        $id = $request->id;
        $data['supplier'] = Supplier::where('sup_id', $id)->firstOrFail();

        $supplier = DB::table('supplier_markup')->where('supplier_id', $id)->get();

		if (count($supplier) == 0) {
			$query = pricesetting::get();
            foreach ($query as $row) {
				$insert['supplier_id'] = $id;
				$insert['shape'] = $row->shape;
				$insert['min_range'] = $row->min_range;
				$insert['max_range'] = $row->max_range;
				$insert['pricechange'] = '0';

                DB::table('supplier_markup')->insert($insert);
			}
		}

        $data['round'] = DB::table('supplier_markup')->where('supplier_id', $id)->where('shape', 'round')->get();
        $data['pear'] = DB::table('supplier_markup')->where('supplier_id', $id)->where('shape', 'pear')->get();

        return view('admin.supplier.supplier-markup')->with($data);
    }

    public function supplierMarkupPost(Request $request)
    {
        $id = $request->sup_id;
        $supplier = Supplier::where('sup_id', $id)->first();

		if (!empty($request)) {
            $post = $request->all();
            foreach ($post as $key => $value) {
                DB::table('supplier_markup')->where('price_id',$key)->update(['pricechange' => $value]);
			}
		}
        return redirect("supplier-markup/$id")->withSuccess('Price Updated Successfully!!');
    }

    public function supplierResendEmailVerify(Request $request)
    {
        $id = $request->id;
        $supplier = Supplier::with('users')->where('sup_id', $id)->firstOrFail();

        $token = mt_rand() . time();
        User::where('id', $id)->update(['email_verify_code' => $token]);
        // try {
            Mail::send('emails.supplier_reverifiacation', ['firstname'=> $supplier->users->firstname, 'lastname'=> $supplier->users->lastname, 'link' => $token], function($message) use($supplier){
                $message->to($supplier->users->email);
                // $message->cc(\Cons::EMAIL_SUPPLIER);
                $message->subject("Please verify your email | ". config('app.name'));
            });
        // } catch (\Throwable $th) {

        // }
        $data['success'] = true;

        echo json_encode($data);
    }

    public function adminuploadDiamond(Request $request)
    {
        $data['suppliers'] = Supplier::with('users')->whereHas('users',function($query){ $query->orderBy('companyname', 'asc'); })->where('stock_status','ACTIVE')->get()->sortBy('users.companyname');
        return view('admin.upload-diamond')->with($data);
    }

    public function adminuploadDiamondPost(Request $request)
    {
        $request->validate([
            'stock_file' => 'required|mimes:application/octet-stream,csv,xlsx,xlx,xls,text/csv|max:20240'
        ],
        [
            'stock_file.required' => 'You have to choose the file!',
            'stock_file.mimes' => 'You have to choose type of the file!',
            'stock_file.max' => 'You have to max size of the file!'
        ]);

        if($request->file()) {
            $id = $request->supplier;
            $supplier_detail = Supplier::with('users')->where('sup_id',$id)->first();

            $fileName = str_replace(array(' ', ',','+','&','-'), '', $id.'_'.time().$request->stock_file->getClientOriginalName());

            $request->file('stock_file')->storeAs('stocks_upload', $fileName, 'public');
            $file = $request->file('stock_file');
            if (!$file->isValid()) {
                $error = $file->getErrorMessage();
                return back()->with('success', $error)->with('stock_file', $fileName);
            }
            if($supplier_detail->upload_mode == 'FTP'){
                $filePath = $request->file('stock_file')->storeAs('/'.$supplier_detail->folder_name, $fileName, 'supplier_files');
            }

            // $filePath = $request->file('stock_file')->storeAs('stocks_upload/', $fileName, 'public');

            if (strtoupper($supplier_detail->diamond_type) == 'NATURAL') {
                Excel::import(new NaturalDiamondImport($supplier_detail, $fileName), $file);
                                } else {
                Excel::import(new LabgrownDiamondImport($supplier_detail, $fileName), $file);
                                }
            return back();//->with('success','File has been uploaded.')->with('stock_file', $fileName);
        }
        else
        {
            return back()->with('success', "Something went wrong");
        }
    }

    public function MoveSupplier(Request $request)
    {
        $id = $request->id;
        User::where('id',$id)->update(['user_type' => 2]);
        $data = Supplier::where('sup_id',$id)->first();
        $sup_table = array(
            'cus_id' => $id,
            // 'supplier_name' => $request->companyname,
            // 'diamond_type' => $request->diamond_type,
            'country' => $data->country,
            'state' => $data->state,
            'city' => $data->city,
        );
        Customer::insert($sup_table);
        Supplier::where('sup_id',$id)->delete();
        return  redirect('pending-suppliers-list')->with('success','Supplier move Successful');
    }

    public function SupplierRequestEntry($id){
        $customers = User::with('customer')->where('is_active', 1)->where('is_delete', 0)->whereHas('customer',function($query){ $query->where('api_enable', 1); })->get();

        foreach($customers as $user){
            DB::table('supplier_requests')->insert(array(
                    'supplier_id' => $id,
                    'user_id'     => $user->customer->cus_id,
                    'request_status'=> 1
                )
            );
        }
    }

    public function LastDeletedStones(Request $request){
	    $lastdays = date('Y-m-d H:i:s', strtotime('-1 days', strtotime(date('Y-m-d'))));

        $natural = DiamondNatural::with('suppliers','users')->selectRaw('count(diamond_natural.id) as count,updated_at,diamond_type,supplier_id')->where('diamond_natural.is_delete',1)->where('updated_at','>',$lastdays)->groupBy('supplier_id')->get();

        $labgrown = DiamondLabgrown::with('suppliers','users')->selectRaw('count(diamond_labgrown.id) as count,updated_at,diamond_type,supplier_id')->where('is_delete',1)->where('updated_at','>',$lastdays)->groupBy('supplier_id')->get();

        $natural_array = json_decode(json_encode($natural), true);
        $lab_array = json_decode(json_encode($labgrown), true);

        $data['stones'] = array_merge( $natural_array , $lab_array);

        return view('admin.supplier.last-deleted-stones')->with($data);
    }
}

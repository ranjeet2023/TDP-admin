<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Associates;
use DB;

class AssociateController extends Controller
{
    public function addAssociate()
    {
        return view('admin.associate.add-associate');
    }

    public function postAssociate(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'address'=>'required',
            'email'=>'required',
            'mobile'=>'required',
            'accountnumber'=>'required',
            'bankname'=>'required',
            'bankaddress'=>'required',
            'swiftcode'=>'required',
            'addresscode'=>'required',
            'routingbank'=>'required',
            'routingdirect'=>'required',
            'intermediarybank'=>'required',
            'hsncodenatural'=>'required',
            'onehsncode'=>'required',
            'port_loading'=>'required',
            'carrier_place'=>'required'
        ]);

        $associate = new Associates;
        $associate->name = $request->name;
        $associate->address = $request->address;
        $associate->email = $request->email;
        $associate->mobile = $request->mobile;
        $associate->gst_no = strtoupper($request->gst_no);
        $associate->pan_no = strtoupper($request->pan_no);
        $associate->account_number = $request->accountnumber;
        $associate->branch_name = $request->branchname;
        $associate->bank_name = $request->bankname;
        $associate->ifsc_code = $request->ifsccode;
        $associate->bank_address = $request->bankaddress;
        $associate->swift_code = $request->swiftcode;
        $associate->address_code = $request->addresscode;
        $associate->routing_bank_number = $request->routingbank;
        $associate->routig_number_directs_deposite = $request->routingdirect;
        $associate->intermediary_bank = $request->intermediarybank;
        $associate->intermediary_swift_code = $request->intermediaryswiftcode;
        $associate->bsb_code = $request->bsbcode;
        $associate->hsn_code_natural = $request->hsncodenatural;
        $associate->hsn_code_natural_one = $request->onehsncode;
        $associate->hsn_code_lab = $request->hsncodelab;
        $associate->port_loading = $request->port_loading;
        $associate->carrier_place = $request->carrier_place;
        $associate->save();

        return redirect('add-associate')->with('update','Associate Add Succesful');
    }

    public function ManageAssociate()
    {
        $data['associate'] = Associates::all();
        return view('admin.associate.associate-management')->with($data);
    }

    public function EditAssociate(Request $request)
    {
        $id = $request->id;
        $data['associate_info'] = Associates::where('id',$id)->get();
        return view('admin.associate.edit-associate')->with($data);
    }

    public function UpdateAssociateDetail(Request $request)
    {
        $id = $request->id;
        $data['associate_info'] = Associates::where('id',$id)->update([
            'name'=>$request->name,
            'address'=>$request->address,
            'email'=>$request->email,
            'mobile'=>$request->mobile,
            'country'=>$request->country,
            'account_number'=>$request->accountnumber,
            'gst_no'=>strtoupper($request->gst_no),
            'pan_no'=>strtoupper($request->pan_no),
            'bank_name'=>$request->bankname,
            'branch_name'=>$request->branchname,
            'bank_address'=>$request->bankaddress,
            'ifsc_code'=>$request->ifsccode,
            'swift_code'=>$request->swiftcode,
            'address_code'=>$request->addresscode,
            'routing_bank_number'=>$request->routingbank,
            'routig_number_directs_deposite'=>$request->routingdirect,
            'intermediary_bank'=>$request->intermediarybank,
            'intermediary_swift_code'=>$request->intermediaryswiftcode,
            'bsb_code'=>$request->bsbcode,
            'hsn_code_natural'=>$request->hsncodenatural,
            'hsn_code_natural_one'=>$request->onehsncode,
            'hsn_code_lab'=>$request->hsncodelab,

            'port_loading'=>$request->port_loading,
            'carrier_place'=>$request->carrier_place,
        ]);

        return redirect('manage-associate')->with('update','Associate Details Update');
    }
}


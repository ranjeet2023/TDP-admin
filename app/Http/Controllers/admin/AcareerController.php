<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class AcareerController extends Controller
{

    public function Career()
    {

        $data = DB::table('career')->where('is_delete',0)->orderBy('created_at', 'DESC')->get();

        return view('frontend.career')->with('jobdata',$data);
    }
    public function Addjob()
    {
       return view('admin.career.add-job');
    }

    public function PostAddJob(Request $request)
    {
        $data = [
        'job_title' => $request->jtitle,
        'job_descritpion' => $request->jdesc,
        'location' => $request->location,
        'number_of_postion' => $request->number_of_postion,
        'technology'=>$request->technology,
        'work_experience'=>$request->workexperience,
        'is_delete'=>0
        ];
        DB::table('career')->insert($data);

        return redirect()->back()->with('mes','Job Opening Successful');
    }
    public function ManageJob()
    {
        $data['data'] =  DB::table('career')->where('is_delete',0)->get();
        return view('admin.career.manage-job')->with($data);
    }

    public function JobEdit(Request $request)
    {
        $id = $request->id;
        $data = DB::table('career')->where('id',$id)->first();

        return view('admin.career.edit-job')->with('jobdata',$data);
    }

    public function JobEditPost(Request $request)
    {
        $id = $request->id;
        DB::table('career')->where('id',$id)->update([
            'job_title' => $request->jtitle,
            'job_descritpion' => $request->jdesc,
            'location' => $request->location,
            'number_of_postion' => $request->number_of_postion,
            'technology'=>$request->technology,
            'work_experience'=>$request->workexperience,
        ]);

        return redirect('manage-job')->with('mes','Update Succesful');
    }

    public function JobDelete(Request $request)
    {
        $id = $request->id;
        DB::table('career')->where('id',$id)->update([
            'is_delete'=>1
        ]);

        return redirect()->back()->with('mes','Delete Successful');
    }

    public function Applied(Request $request)
    {
        $job_id = $request->job_id;
        return view('admin.career.candidate-form');
    }
}

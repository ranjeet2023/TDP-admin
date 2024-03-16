<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

use App\Models\User;
use App\Models\DiamondNatural;
use App\Models\DiamondLabgrown;
use App\Models\StockUploadReport;

class DebugController extends Controller
{
    public function redAlert()
    {
        return view('admin.log.red-alert');
    }

    public function redAlertPost(Request $request){
        $table = $request->diamond_type;
        $tabs = $request->tabdata;
        $data['detail'] = DB::table($table)->select('shape','supplier_name','color','cut','clarity','polish','symmetry','fluorescence','lab','eyeclean','country','carat')->where('is_delete',0)->groupBy($tabs)->get();

        echo json_encode($data);
    }

    public function invalidDiscount()
    {
        return view('admin.log.invalid-discount');
    }

    public function invalidDiscountPost(Request $request){
        $table = $request->diamond_type;
        $detail = DB::table($table)->select('supplier_name','id','ref_no','shape','carat','color','cut','clarity','lab','certificate_no','discount','orignal_rate')->where('is_delete',0);
        $detail->where(function($query){
            $query->orwhere('discount','>','0');
            $query->orwhere('discount','<','-90');
        });
        $data['detail'] = $detail->groupBy('supplier_name')->orderBy('discount', 'asc')->get();

        echo json_encode($data);
    }

    public function ImageDownloadLabgrown(Request $request){
        $images = DiamondLabgrown::select('image','certificate_no')->where('image','!=','')->where('image_status','=','0')->get();
        if(count($images) > 0){
            foreach($images as $image){
                $filename = basename($image->image);
                $extension = (pathinfo($filename,PATHINFO_EXTENSION));
                try{
                    Image::make($image->image)->save(public_path('assets/diamond_labgrown/' . $image->certificate_no .".".$extension));
                    DiamondLabgrown::where('certificate_no','=',$image->certificate_no)->update(['image_status'=>'2']);
                }
                catch(\Exception $e){
                    DiamondLabgrown::where('certificate_no','=',$image->certificate_no)->update(['image_status'=>'3']);
                }
            }
            $data['success'] = true;
            $data['message'] = "SuccessFully Downloaded to public folder";
        }
        else{
            $data['success'] = false;
            $data['message'] = "Nothing to download";
        }
        return response()->json($data);
    }

    public function ImageDownloadNatural(){
        $images = DiamondNatural::select('image','certificate_no')->where('image','!=','')->where('image_status','=','0')->get();
        if(count($images) > 0){
            foreach($images as $image){
                $filename = basename($image->image);
                $extension = (pathinfo($filename,PATHINFO_EXTENSION));
                try{
                    Image::make($image->image)->save(public_path('assets/diamond_natural/' . $image->certificate_no .".".$extension));
                    DiamondNatural::where('certificate_no','=',$image->certificate_no)->update(['image_status'=>'2']);
                }
                catch(\Exception $e){
                    DiamondNatural::where('certificate_no','=',$image->certificate_no)->update(['image_status'=>'3']);
                }
            }
            $data['success'] = true;
            $data['message'] = "SuccessFully Downloaded to public folder";
        }
        else{
            $data['success'] = false;
            $data['message'] = "Nothing to download";
        }
        return response()->json($data);
    }

    public function ImageUploads3Labgrown()
    {
        $files=File::glob(public_path('assets/diamond_labgrown').'/*');
        if(count($files) > 0){
            $array = array();
            foreach(File::glob(public_path('assets/diamond_labgrown').'/*') as $path){
                $filesizeinkb = (File::size($path))/1024;
                if($filesizeinkb > 10){
                    $array[]= $path;
                    $filename = (str_replace(public_path('assets/diamond_labgrown/'), '', $path));
                    $path=public_path('assets/diamond_labgrown/').$filename;
                    $file = Storage::disk('s3')->put($filename, file_get_contents(public_path('assets/diamond_labgrown/'.$filename)), 's3');
                    $extension = pathinfo($filename, PATHINFO_EXTENSION);
                    $certificate = (basename($filename,'.'.$extension));
                    $s3url = Storage::disk('s3')->url($filename);
                    DiamondLabgrown::where('certificate_no','=',$certificate)->update(['cloud_image' => $s3url,'image_status'=>1]);
                }
            }
            $delete = File::delete($array);

            $data['success'] = true;
            $data['message'] = "SuccessFully uploaded to S3";
        }
        else{
            $data['success'] = false;
            $data['message'] = "Nothing to Upload";
        }
        return response()->json($data);
    }

    public function ImageUploads3Natural()
    {
        $files = File::glob(public_path('assets/diamond_natural').'/*');
        if(count($files) > 0){
            $array = array();
            foreach(File::glob(public_path('assets/diamond_natural').'/*') as $path){
                $filesizeinkb = (File::size($path))/1024;
                if($filesizeinkb > 10){
                    $array[]= $path;
                    $filename = (str_replace(public_path('assets/diamond_natural/'), '', $path));
                    $path=public_path('assets/diamond_natural/').$filename;
                    $file = Storage::disk('s3')->put($filename, file_get_contents(public_path('assets/diamond_natural/'.$filename)), 's3');
                    $extension = pathinfo($filename, PATHINFO_EXTENSION);
                    $certificate = (basename($filename,'.'.$extension));
                    $s3url = Storage::disk('s3')->url($filename);
                    DiamondNatural::where('certificate_no','=',$certificate)->update(['cloud_image' => $s3url,'image_status'=>1 ]);
                }
            }
            $delete = File::delete($array);

            $data['success'] = true;
            $data['message'] = "SuccessFully uploaded to S3";
        }
        else{
            $data['success'] = false;
            $data['message'] = "Nothing to Upload";
        }
        return response()->json($data);

    }

    public function deleteExpiredFile(){
        $suppliers = StockUploadReport::select('supplier_id')->groupBy('supplier_id')->get();
        foreach($suppliers as $supplier){
            $records = StockUploadReport::where('supplier_id','=',$supplier->supplier_id)->where('is_delete','=','0')->skip(30)->take(1000)->latest()->get();
            foreach($records as $record){
                if($record->upload_mode != 'API'){
                    if(file_exists(public_path('uploads/stocks_upload/'.$record->info))){
                        $unlink = unlink(public_path('uploads/stocks_upload/'.$record->info));
                        StockUploadReport::where('id','=', $record->id)->update(['is_delete' => 1]);
                    }
                }
            }
        }
    }


    public function Ucfirstuser(){
        $users = User::where('user_type', '3')->get();
        foreach ($users as $user) {
            $user_sin = array();
            echo $user_sin['companyname'] = ucfirst(strtolower($user->companyname));
            echo "<pre>";
            User::where('id',$user->id)->update($user_sin);
        }
    }
}

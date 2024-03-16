<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use App\Exports\RareExport;
use App\Exports\DiamondExport;
use App\Exports\NaturalExport;
use Maatwebsite\Excel\Facades\Excel;

use DB;

use App\Models\DiamondNatural;
use App\Models\DiamondLabgrown;

set_time_limit(0);
ini_set('memory_limit', -1);

class AExcelController extends Controller
{
    public function index(Request $request)
    {
        $customer_id = 111;
        $days_ago = date('Y-m-d H:i:s', strtotime('-1 days', strtotime(date('Y-m-d'))));

        $natural = DiamondNatural::select('id', 'diamond_type', 'ref_no', 'shape', 'carat', 'color', 'clarity', 'cut', 'polish', 'symmetry', 'fluorescence', 'lab', 'certificate_no', 'location',
            'eyeclean', 'City', 'country', 'image', 'video', 'gridle', 'gridle_per','rate', 'depth_per', 'table_per', 'crown_angle', 'crown_height', 'pavilion_angle', 'pavilion_depth', 'length', 'width', 'depth', 'net_dollar', 'orignal_rate',
            DB::raw('(SELECT pricechange FROM markup_setting WHERE (net_dollar BETWEEN `min_range` and max_range)) as aditional_discount'))
            ->where('location', 1)
            ->where('status', '0')
            ->where('is_delete', 0)
            ->whereIn('availability', array('available',''))
            ->where('color', '!=', 'fancy')
            // ->where('clarity', '!=', 'SI2')
            ->where('updated_at', '>', $days_ago)
            ->whereIn('supplier_id',  array(642,741,705));

        $diamond_data = DiamondLabgrown::select('id', 'diamond_type', 'ref_no', 'shape', 'carat', 'color', 'clarity', 'cut', 'polish', 'symmetry', 'fluorescence', 'lab', 'certificate_no', 'location',
            'eyeclean', 'City', 'country', 'image', 'video', 'gridle', 'gridle_per','rate', 'depth_per', 'table_per', 'crown_angle', 'crown_height', 'pavilion_angle', 'pavilion_depth', 'length', 'width', 'depth', 'net_dollar', 'orignal_rate',
            DB::raw('(SELECT pricechange FROM markup_setting WHERE (net_dollar BETWEEN `min_range` and max_range)) as aditional_discount'))
            ->where('location', 1)
            ->where('status', '0')
            ->where('is_delete', 0)
            ->whereIn('availability', array('available',''))
            ->where('color', '!=', 'fancy')
            ->where('clarity', '!=', 'SI2')
            ->where('updated_at', '>', $days_ago)
            ->whereIn('supplier_id', function ($query) {
                    $query->select('sup_id')->from('suppliers')
                    ->Where('return_allow',1);
                })
            ->union($natural)
            ->orderBy('carat', 'desc')
            ->get();

        $filename = 'RLabgrown.csv';
        $result = Excel::store(new RareExport($diamond_data, $customer_id), $filename);

        // $data = new RareExport;
        // $result = Excel::store($data, $filename);

        $ftp = Storage::createFtpDriver([
            'driver'   => 'ftp',
            'host'     => 'ftp.rarecarat.com',
            'username' => 'diamondport',
            'password' => 'RLzK6;W:#5<_$aD4',
            'port'     => 21,
            'passive'  => true,
            'ignorePassiveAddress' => true,
        ]);
        $file = Storage::disk('public')->get($filename);
        $ftp->put($filename, $file);

        // $ftp_server = "ftp.rarecarat.com";
        // $ftp_conn = ftp_connect($ftp_server) or die("Could not connect to $ftp_server");
        // $login = ftp_login($ftp_conn, 'diamondport', 'RLzK6;W:#5<_$aD4');
        // ftp_pasv($ftp_conn, true) or die("Unable switch to passive mode");
        // if (ftp_put($ftp_conn, $new_file_name, "" . $new_file_name, FTP_sBINARY))
        // {
            echo "Successfully uploaded.";
        // }
        // else
        // {
        //     echo "Error uploading.";
        // }
        // ftp_close($ftp_conn);
    }

    public function ritani(Request $request)
    {

        $customer_id = 483;

        $days_ago = date('Y-m-d H:i:s', strtotime('-2 days', strtotime(date('Y-m-d'))));

        // DB::enableQueryLog();
        $diamond_data = DiamondLabgrown::select('id', 'ref_no', 'shape', 'carat', 'color', 'clarity', 'cut', 'polish', 'symmetry', 'fluorescence', 'lab', 'certificate_no', 'location',
            'eyeclean', 'City', 'country', 'image', 'video', 'gridle', 'gridle_per','rate', 'depth_per', 'table_per', 'crown_angle', 'crown_height', 'pavilion_angle', 'pavilion_depth', 'length', 'width', 'depth', 'net_dollar', 'orignal_rate',
            DB::raw('(SELECT pricechange FROM markup_setting WHERE (net_dollar BETWEEN `min_range` and max_range)) as aditional_discount'))
            ->where('location', 1)
            ->where('status', '0')
            ->where('is_delete', 0)
            ->where('color', '!=', 'fancy')
            ->where('updated_at', '>', $days_ago)
            ->whereIn('supplier_id', function ($query) {
                    $query->select('sup_id')->from('suppliers')
                    ->Where('return_allow',1);
                })
            ->whereIn('supplier_id', array(196,197,199,200,202,205,214,215,216,220,229,245,247,249,255,267,269,270,280,282,286,297,303,312,319,323,326,330,331,338,344,350,357,369,372,378,399,412,462))
            ->orderBy('carat', 'desc')
            ->get();

        $filename = 'RiLabgrown.csv';
        $result = Excel::store(new RareExport($diamond_data, $customer_id), $filename);

        // $ftp = Storage::createFtpDriver([
        //     'driver'   => 'ftp',
        //     'host'     => 'ftp.rarecarat.com',
        //     'username' => 'diamondport',
        //     'password' => 'RLzK6;W:#5<_$aD4',
        //     'port'     => 21,
        //     'passive'  => true,
        //     'ignorePassiveAddress' => true,
        // ]);
        // $ftp->put($new_file_name, Storage::disk('local')->get($new_file_name));

        // $ftp_server = "ftp.rarecarat.com";
        // $ftp_conn = ftp_connect($ftp_server) or die("Could not connect to $ftp_server");
        // $login = ftp_login($ftp_conn, 'diamondport', 'RLzK6;W:#5<_$aD4');
        // ftp_pasv($ftp_conn, true) or die("Unable switch to passive mode");
        // if (ftp_put($ftp_conn, $new_file_name, "" . $new_file_name, FTP_sBINARY))
        // {
            echo "Successfully uploaded.";
        // }
        // else
        // {
        //     echo "Error uploading.";
        // }
        // ftp_close($ftp_conn);
    }

    public function mejewel(Request $request)
    {
        $customer_id = 44;
        $days_ago = date('Y-m-d H:i:s', strtotime('-1 days', strtotime(date('Y-m-d'))));

        $diamond_data = DiamondLabgrown::select('id', 'diamond_type','ref_no', 'shape', 'carat', 'color', 'clarity', 'cut', 'polish', 'symmetry', 'fluorescence', 'lab', 'certificate_no', 'location',
            'eyeclean', 'City', 'country', 'image', 'video', 'gridle', 'gridle_per','rate', 'depth_per', 'table_per', 'crown_angle', 'crown_height', 'pavilion_angle', 'pavilion_depth', 'length', 'width', 'depth', 'net_dollar', 'orignal_rate',
            DB::raw('(SELECT pricechange FROM markup_setting WHERE (net_dollar BETWEEN `min_range` and max_range)) as aditional_discount'))
            ->where('location', 1)
            ->where('status', '0')
            ->where('is_delete', 0)
            ->whereIn('availability', array('available',''))
            ->where('color', '!=', 'fancy')
            ->where('clarity', '!=', 'SI2')
            ->where('updated_at', '>', $days_ago)
            // ->whereIn('supplier_id', function ($query) {
            //         $query->select('sup_id')->from('suppliers')
            //         ->Where('return_allow', 1);
            //     })
            ->orderBy('carat', 'desc')
            ->get();

        $filename = 'meLabgrown.csv';
        $result = Excel::store(new DiamondExport($diamond_data, $customer_id), $filename);

        // $data = new RareExport;
        // $result = Excel::store($data, $filename);

        $ftp = Storage::createFtpDriver([
            'driver'   => 'ftp',
            'host'     => '159.223.41.45',
            'username' => 'diamondport',
            'password' => 'aQ#GfGa12L@tqWXrPd',
            'port'     => 21,
            'passive'  => true,
            'ignorePassiveAddress' => true,
        ]);
        $file = Storage::disk('public')->get($filename);
        $ftp->put($filename, $file);

        // $ftp_server = "ftp.rarecarat.com";
        // $ftp_conn = ftp_connect($ftp_server) or die("Could not connect to $ftp_server");
        // $login = ftp_login($ftp_conn, 'diamondport', 'RLzK6;W:#5<_$aD4');
        // ftp_pasv($ftp_conn, true) or die("Unable switch to passive mode");
        // if (ftp_put($ftp_conn, $new_file_name, "" . $new_file_name, FTP_sBINARY))
        // {
            echo "Successfully uploaded.";
        // }
        // else
        // {
        //     echo "Error uploading.";
        // }
        // ftp_close($ftp_conn);
    }

    public function mejewelNatural(Request $request)
    {
        $customer_id = 44;
        $days_ago = date('Y-m-d H:i:s', strtotime('-1 days', strtotime(date('Y-m-d'))));

        $diamond_data = DiamondNatural::select('id', 'ref_no', 'shape', 'carat', 'color', 'clarity', 'cut', 'polish', 'symmetry', 'fluorescence', 'lab', 'certificate_no', 'location',
            'eyeclean', 'City', 'country', 'image', 'video', 'gridle', 'gridle_per','rate', 'depth_per', 'table_per', 'crown_angle', 'crown_height', 'pavilion_angle', 'pavilion_depth', 'length', 'width', 'depth', 'net_dollar', 'orignal_rate',
            DB::raw('(SELECT pricechange FROM markup_setting WHERE (net_dollar BETWEEN `min_range` and max_range)) as aditional_discount'))
            ->where('location', 1)
            ->where('status', '0')
            ->where('is_delete', 0)
            ->whereIn('availability', array('available',''))
            ->where('color', '!=', 'fancy')
            ->where('clarity', '!=', 'SI2')
            ->where('updated_at', '>', $days_ago)
            // ->whereIn('supplier_id', function ($query) {
            //         $query->select('sup_id')->from('suppliers')
            //         ->Where('return_allow', 1);
            //     })
            ->orderBy('carat', 'desc')
            ->get();

        $filename = 'meNatural.csv';
        $result = Excel::store(new NaturalExport($diamond_data, $customer_id), $filename);

        // $data = new RareExport;
        // $result = Excel::store($data, $filename);

        $ftp = Storage::createFtpDriver([
            'driver'   => 'ftp',
            'host'     => '159.223.41.45',
            'username' => 'diamondport',
            'password' => 'aQ#GfGa12L@tqWXrPd',
            'port'     => 21,
            'passive'  => true,
            'ignorePassiveAddress' => true,
        ]);
        $file = Storage::disk('public')->get($filename);
        $ftp->put($filename, $file);

        // $ftp_server = "ftp.rarecarat.com";
        // $ftp_conn = ftp_connect($ftp_server) or die("Could not connect to $ftp_server");
        // $login = ftp_login($ftp_conn, 'diamondport', 'RLzK6;W:#5<_$aD4');
        // ftp_pasv($ftp_conn, true) or die("Unable switch to passive mode");
        // if (ftp_put($ftp_conn, $new_file_name, "" . $new_file_name, FTP_sBINARY))
        // {
            echo "Successfully uploaded.";
        // }
        // else
        // {
        //     echo "Error uploading.";
        // }
        // ftp_close($ftp_conn);
    }
}

<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Log;
use App\Helpers\AppHelper;

use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\Supplier;
use App\Models\DiamondConflict;
use App\Models\DiamondInvalid;
use App\Models\DiamondNatural;
use App\Models\DiamondLabgrown;
use App\Models\DiamondUnapprove;
use App\Models\StockUploadReport;

set_time_limit(0);
ini_set('memory_limit', -1);

class TestController extends Controller
{
    public function index(Request $request)
    {
        $todate = date('Y-m-d');
        $listen = Log::listen(function($level, $message, $context)
        {
            dd('sajd');
        });


        dd($listen);
        $data_response = array();

        $company_detail = Supplier::select('sup_id', 'companyname', 'supplier_name', 'markup', 'upload_mode', 'diamond_type', 'folder_name', 'stock_status', 'is_active',
        DB::raw('(SELECT `created_at` FROM `stock_upload_report` WHERE `supplier_id` = suppliers.sup_id ORDER BY `created_at` DESC LIMIT 1) as stock_created_date'))
        ->join('users', 'users.id', '=', 'suppliers.sup_id')
        ->where('ftp_host', '!=', '')
        ->where('ftp_username', '!=', '')
        ->where('ftp_password', '!=', '')
        ->where('ftp_password', '!=', '')
        ->where('upload_mode', 'FTP')
        ->where('stock_status', 'ACTIVE')
        // ->where('supplier_name', '=', 'Ramee Gems')
        // ->whereNotIn('supplier_name', array('Ramee Gems','Dharmanandan Diamonds Pvt. Ltd.'))//
        ->where('is_active', 1)->get();

        $ext_array = array('xls','xlsx','csv');

        if (count($company_detail) > 0) {

            foreach ($company_detail as $supplier) {

                Log::build([
                    'driver' =>'single',
                    'path' =>storage_path('logs/stock-'.$todate.'log'),
                ])->info('INFO-'.$supplier->companyname.'-start');


                if(!empty($supplier->folder_name))
                {
                    $Folder_Path = '../supplier_files/' . $supplier->folder_name . '/';

                    $filesInFolder = \File::allFiles($Folder_Path);
                    $fileNameArray = [];
                    foreach ($filesInFolder as $file){
                            $fileNameArray[] = $file->getFilename();
                    }

                    $File_Name = !empty($fileNameArray)? $fileNameArray[0]:'';
                    $ext = \File::extension($File_Name);

                    $file_path = !in_array(strtolower($ext), $ext_array) ? NULL : $Folder_Path.$File_Name;
                }

                if(file_exists($file_path)) {
                    $file_update_date = filemtime($file_path);

                    $data_response['name'] = $supplier->companyname;
                    $data_response['time'] = time() - $file_update_date;
                    $data_response['file_path'] = $file_path;
                    $data_response['file_data'] = date('Y-m-d H:i:s', $file_update_date);
                    // File uploaded date before 72 hours
                    if (time() - $file_update_date < (72 * 3600)) {
                        $data_response['last_refresh'] = !empty($supplier->stock_created_date) ? $supplier->stock_created_date : date('Y-m-d', strtotime('-7 days'));
                        $data_response['diff'] = time() - strtotime($data_response['last_refresh']) . ' > ' . (3 * 3600);
                        if (time() - strtotime($data_response['last_refresh']) > (3 * 3600)) {
                            $data_response['filest'] = 'Execute';

                            if($ext == "xlsx")
                            {
                                $sheet = Excel::toArray(new UsersImport(), $file_path, null,\Maatwebsite\Excel\Excel::XLSX);
                            }
                            else
                            {
                                $sheet = Excel::toArray(new UsersImport(), $file_path);
                            }
                            $sheet_data = $sheet[0];

                            if ($supplier->diamond_type == 'Natural') {
                                $sheet_data[0] = AppHelper::ChangeExcelTitleNatural($sheet_data[0]);
                            }
                            else
                            {
                                $sheet_data[0] = AppHelper::ChangeExcelTitleLabGrown($sheet_data[0]);
                            }

                            if(in_array('stock #',$sheet_data[0]) && in_array('certificate #',$sheet_data[0]))
                            {
                                $stock_array = array_column($sheet_data, array_search('stock #', $sheet_data[0]));
                                array_shift($stock_array);
                                $st = array_diff_assoc($stock_array, array_unique($stock_array));

                                $shiping_price_array = array();
                                $shiping_price_array = AppHelper::shipingPriceArray();
                                $s_price_array = AppHelper::sPriceArray($supplier);

                                DiamondConflict::where('supplier_id', $supplier->sup_id)->update(['is_delete' => 1]);

                                if ($supplier->diamond_type == 'Natural') {
                                    if(empty($SheetError))
                                    {
                                        $certificate_array = array_column($sheet_data, array_search('certificate #', $sheet_data[0]));
                                        array_shift($certificate_array);
                                        $d = array_diff_assoc($certificate_array, array_unique($certificate_array));
                                    }
                                    // else
                                    // {
                                    //     print_r($SheetError);
                                    // }

                                    $updatedelete = 1;
                                    DiamondInvalid::where('supplier_id', $supplier->sup_id)->delete();
                                    DiamondNatural::where('supplier_id', $supplier->sup_id)->update(['is_delete' => 1]);
                                    DiamondUnapprove::where('supplier_id', $supplier->sup_id)->update(['is_delete' => 1]);

                                    if ($supplier->stock_status == 'ACTIVE') {
                                    } else {
                                        DiamondUnapprove::where('supplier_id', $supplier->id)->delete();
                                    }

                                    $flag = false;
                                    $i = 1;
                                    $index = 0;
                                    $invalid = 0;
                                    $j = 0;
                                    $file_update_date = date("Y-m-d H:i:s", filemtime($file_path));

                                    if ($updatedelete) {
                                        $updatestring = '';
                                        foreach ($sheet_data as $value) {
                                            $keyvaluepair = array_combine($sheet_data[0], $value);
                                            if ($i == 1 && str_replace(' ', '', @$keyvaluepair['stock #']) == 'stock#' && @$keyvaluepair['shape'] == 'shape' && @$keyvaluepair['weight'] == 'weight' && @$keyvaluepair['color'] == 'color' && @$keyvaluepair['clarity'] == 'clarity' && str_replace(' ', '', @$keyvaluepair['certificate #']) == 'certificate#' && @$keyvaluepair['$/ct'] == '$/ct') {
                                                $flag = true;
                                            }

                                            if ($flag == true && !empty(@$keyvaluepair['weight']) && @$keyvaluepair['stock #'] != "stock #") {

                                                if (@$keyvaluepair['measurements'] != "") {
                                                    $mesurment = str_replace(array('*', '-', 'X', 'x'), "x", strtolower(@$keyvaluepair['measurements']));
                                                    $main = explode("x", $mesurment);
                                                    $C_Length = (!empty($main[0])) ? $main[0] : '';
                                                    $C_Width = (!empty($main[1])) ? $main[1] : '';
                                                    $C_Depth = (!empty($main[2])) ? $main[2] : '';
                                                }

                                                if (empty(trim(@$keyvaluepair['certificate #']))) {
                                                    $respo['success'] = false;
                                                    $respo['reason'] = 'Certificate Blank';
                                                } elseif (in_array(@$keyvaluepair['certificate #'], $d)) {
                                                    $respo['success'] = false;
                                                    $respo['reason'] = 'Certificate Duplicate';
                                                } elseif (empty(trim(@$keyvaluepair['stock #']))) {
                                                    $respo['success'] = false;
                                                    $respo['reason'] = 'Stock ID Blank';
                                                } elseif (in_array(@$keyvaluepair['stock #'], $st)) {
                                                    $respo['success'] = false;
                                                    $respo['reason'] = 'Stock ID Duplicate';
                                                } else {
                                                    $shape = AppHelper::ShapeValidation(@$keyvaluepair['shape']);
                                                    if ($shape == '') {
                                                        $respo['success'] = false;
                                                        $respo['reason'] = 'Shape';
                                                    } else {
                                                        $respo = AppHelper::NaturalCondition($keyvaluepair, $shape);
                                                    }
                                                }

                                                $file_update_date = date("Y-m-d H:i:s", filemtime($file_path));
                                                if ($respo['success']) {
                                                    $cut = '';
                                                    $Crn_Ht =  $Crn_Ag = $Pav_Ag = $Pav_Dp = 0;
                                                    if ($shape == "ROUND") {
                                                        $C_shape = "round";
                                                        $cut = AppHelper::CutValidation(@$keyvaluepair['cut grade']);
                                                        $Crn_Ht = AppHelper::Diamond_CH_CA_PD_PA(@$keyvaluepair['crown height']);
                                                        $Crn_Ag = AppHelper::Diamond_CH_CA_PD_PA(@$keyvaluepair['crown angle']);
                                                        $Pav_Ag = AppHelper::Diamond_CH_CA_PD_PA(@$keyvaluepair['pavilion angle']);
                                                        $Pav_Dp = AppHelper::Diamond_CH_CA_PD_PA(@$keyvaluepair['pavilion depth']);
                                                    } else {
                                                        $C_shape = "pear";
                                                    }

                                                    if (strtolower(@$keyvaluepair['color']) == 'fancy' || @$keyvaluepair['color'] == '*' || @$keyvaluepair['color'] == '') {
                                                        $color = 'fancy';
                                                        $f_color = AppHelper::fancycolorValidation(@$keyvaluepair['fancy color']);
                                                        $f_intensity = AppHelper::intensityValidation(@$keyvaluepair['fancy color intensity']);
                                                        $f_overtone = AppHelper::overtoneValidation(@$keyvaluepair['fancy color overtone']);
                                                        $cut = '';
                                                        $Crn_Ht =  $Crn_Ag = $Pav_Ag = $Pav_Dp = 0;
                                                    } else {
                                                        $f_color = $f_intensity = $f_overtone = '';
                                                        $color = AppHelper::ColorValidation(@$keyvaluepair['color']);
                                                    }

                                                    $clarity = AppHelper::ClarityValidation(@str_replace(" ", "", @$keyvaluepair['clarity']));

                                                    $polish = AppHelper::CutValidation(@$keyvaluepair['polish']);
                                                    $symmetry = AppHelper::CutValidation(@$keyvaluepair['symmetry']);
                                                    $fluorescence = AppHelper::FluorescenceValidation(@$keyvaluepair['fluorescence intensity']);

                                                    $certi = trim(@$keyvaluepair['certificate #']);
                                                    $lab = AppHelper::LabValidation(@$keyvaluepair['lab']);
                                                    $country = AppHelper::CountryValidation(trim(@$keyvaluepair['country']));
                                                    $eyeclean = AppHelper::EyecleanValidation(@$keyvaluepair['eye clean']);

                                                    if(empty($eyeclean))
                                                    {
                                                        $clarityToeyeclean = trim(str_replace(" ", "", strtolower(@$keyvaluepair['clarity'])));
                                                        switch($clarityToeyeclean)
                                                        {
                                                            case 'fl':
                                                            case 'if':
                                                            case 'vvs1':
                                                            case 'vvs2':
                                                            case 'vs1':
                                                                $eyeclean = "Yes";
                                                                break;
                                                        }
                                                    }

                                                    $image = AppHelper::ImageValidation(@$keyvaluepair['image link']);
                                                    $heart = !empty(@$keyvaluepair['heart image']) ? AppHelper::ImageValidation(trim(@$keyvaluepair['heart image'])) : '';
                                                    $arrow = !empty(@$keyvaluepair['arrow image']) ? AppHelper::ImageValidation(trim(@$keyvaluepair['arrow image'])) : '';
                                                    $asset = !empty(@$keyvaluepair['aset image']) ? AppHelper::ImageValidation(trim(@$keyvaluepair['aset image'])) : '';
                                                    $video = !empty(@$keyvaluepair['video link']) ? AppHelper::VideoValidation(trim(@$keyvaluepair['video link'])) : '';

                                                    $milky = '';
                                                    $milky = !empty(@$keyvaluepair['milky']) ? AppHelper::MilkyValidation(@$keyvaluepair['milky']) : '';

                                                    if(empty($milky))
                                                    {
                                                        if(isset($keyvaluepair['luster']))
                                                        {
                                                            $lusterToMilky = trim(str_replace(" ", "", strtolower(@$keyvaluepair['luster'])));
                                                            switch($lusterToMilky)
                                                            {
                                                                case 'excellent':
                                                                case 'ex':
                                                                case 'verygood':
                                                                case 'vg+':
                                                                    $milky = 'NO MILKY';
                                                                    break;
                                                                case 'lightmilky':
                                                                    $milky = 'LIGHT MILKY ';
                                                                    break;
                                                                case 'milky':
                                                                    $milky = 'MILKY';
                                                                    break;
                                                            }
                                                        }
                                                    }

                                                    $carat = bcdiv(@$keyvaluepair['weight'], 1, 2);
                                                    $add_dic = 0;
                                                    if (!empty($s_price_array)) {
                                                        $add_dic = $this->findAdditionalValue($s_price_array[$C_shape], $carat);
                                                    }

                                                    $dollerpercarat = str_replace(array('$', ','), "", @$keyvaluepair['$/ct']);
                                                    $newdollerpercarat = round($dollerpercarat + ($dollerpercarat * ($supplier->markup + $add_dic)) / 100, 2);

                                                    $shippingprice = $this->shippingPrice($shiping_price_array[$country], $newdollerpercarat);
                                                    $shippingprice = ($shippingprice > 0) ? ($shippingprice / $carat) : 0;
                                                    $newdollerpercarat = $newdollerpercarat + $shippingprice;

                                                    $net_price = round($carat * $newdollerpercarat, 2);

                                                    $C_Length = $C_Width = $C_Depth = '0';
                                                    if (@$keyvaluepair['measurements'] != '') {
                                                        $mesurment = str_replace(array('*', '-', 'X', 'x'), 'x', strtolower(@$keyvaluepair['measurements']));
                                                        $main = explode('x', $mesurment);
                                                        $C_Length = (!empty($main[0])) ? $main[0] : '';
                                                        $C_Width = (!empty($main[1])) ? $main[1] : '';
                                                        $C_Depth = (!empty($main[2])) ? $main[2] : '';
                                                    }
                                                    else
                                                    {
                                                        $C_Length = !empty($keyvaluepair['length'])?$keyvaluepair['length']:'0';
                                                        $C_Width = !empty($keyvaluepair['width'])?$keyvaluepair['width']:'0';
                                                        $C_Depth = !empty($keyvaluepair['height'])?$keyvaluepair['height']:'0';
                                                    }

                                                    @$keyvaluepair['table percent'] = (@$keyvaluepair['table percent'] > 0.01 && @$keyvaluepair['table percent'] < 0.99) ? @$keyvaluepair['table percent'] * 100 : @$keyvaluepair['table percent'];
                                                    @$keyvaluepair['depth percent'] = (@$keyvaluepair['depth percent'] > 0.01 && @$keyvaluepair['depth percent'] < 0.99) ? @$keyvaluepair['depth percent'] * 100 : @$keyvaluepair['depth percent'];

                                                    $result = array(
                                                        'supplier_name' => $supplier->companyname,
                                                        'supplier_id' => $supplier->sup_id,
                                                        'ref_no' => @$keyvaluepair['stock #'],
                                                        'shape' => $shape,
                                                        'carat' => $carat,
                                                        'color' => $color,
                                                        'clarity' => strtoupper($clarity),
                                                        'cut' => $cut,
                                                        'polish' => $polish,
                                                        'symmetry' => $symmetry,
                                                        'fluorescence' => $fluorescence,
                                                        'orignal_rate' => $dollerpercarat,
                                                        'rate' => $newdollerpercarat,
                                                        'net_dollar' => $net_price,
                                                        'table_per' => @$keyvaluepair['table percent'],
                                                        'depth_per' => @$keyvaluepair['depth percent'],
                                                        'lab' => $lab,
                                                        'fancy_color' => $f_color,
                                                        'fancy_intensity' => $f_intensity,
                                                        'fancy_overtone' => $f_overtone,
                                                        'girdle_thin' => AppHelper::CheckgridleThin(@$keyvaluepair['girdle thin']),
                                                        'girdle_thick' => AppHelper::CheckgridleThink(@$keyvaluepair['girdle thick']),
                                                        'gridle' => AppHelper::GridleConValidation(@$keyvaluepair['girdle condition']),
                                                        'cutlet' => AppHelper::CuletSizeValidation(@$keyvaluepair['culet']),
                                                        'gridle_per' => AppHelper::GridlePerValidation(@$keyvaluepair['girdle percent']),
                                                        'crown_angle' => $Crn_Ag,
                                                        'crown_height' => $Crn_Ht,
                                                        'pavilion_angle' => $Pav_Ag,
                                                        'pavilion_depth' => $Pav_Dp,
                                                        'certificate_no' => $certi,
                                                        'key_symbols' => @$keyvaluepair['key to symbol'],
                                                        'country' => $country,
                                                        'city' => @$keyvaluepair['city'],
                                                        'length' => $C_Length,
                                                        'width' => $C_Width,
                                                        'depth' => $C_Depth,
                                                        'milky' => $milky,
                                                        'eyeclean' => $eyeclean,
                                                        'image' => $image,
                                                        'video' => $video,
                                                        'heart' => $heart,
                                                        'arrow' => $arrow,
                                                        'asset' => $asset,
                                                        'diamond_type' => 'W',
                                                        'c_type' => AppHelper::TreatmentValidation(@$keyvaluepair['treatment']),
                                                        'availability' => AppHelper::AvailabilityValidation(@$keyvaluepair['availability']),
                                                        'shade' => AppHelper::ShadeValidation(@$keyvaluepair['shade']),
                                                        'supplier_comments' => @$keyvaluepair['supplier comments'],
                                                        'luster' => AppHelper::LusterValidation(@$keyvaluepair['luster']),
                                                        'culet_condition' => AppHelper::CuletConValidation(@$keyvaluepair['culet condition']),
                                                        'is_delete' => '0',
                                                    );

                                                    $this->insert_update_diamond($result, $supplier, $certi, $dollerpercarat);
                                                    $index++;
                                                } else {
                                                    if (@$keyvaluepair['girdle %'] != '') {
                                                        $gridle_per = @$keyvaluepair['girdle %'];
                                                    } else {
                                                        $gridle_per = '0';
                                                    }

                                                    if (@$keyvaluepair['measurements'] != "") {
                                                        $mesurment = str_replace(array('*', '-', 'X', 'x'), "x", strtolower(@$keyvaluepair['measurements']));
                                                        $main = explode("x", $mesurment);
                                                        $C_Length = (!empty($main[0])) ? $main[0] : '';
                                                        $C_Width = (!empty($main[1])) ? $main[1] : '';
                                                        $C_Depth = (!empty($main[2])) ? $main[2] : '';
                                                    }

                                                    $result = array(
                                                        'supplier_name' => $supplier->companyname,
                                                        'supplier_id' => $supplier->sup_id,
                                                        'ref_no' => @$keyvaluepair['stock #'],
                                                        'shape' => @$keyvaluepair['shape'],
                                                        'carat' => @$keyvaluepair['weight'],
                                                        'color' => @$keyvaluepair['color'],
                                                        'clarity' => @$keyvaluepair['clarity'],
                                                        'cut' => @$keyvaluepair['cut grade'],
                                                        'polish' => @$keyvaluepair['polish'],
                                                        'symmetry' => @$keyvaluepair['symmetry'],
                                                        'fluorescence' => @$keyvaluepair['fluorescence intensity'],
                                                        'length' => $C_Length,
                                                        'width' => $C_Width,
                                                        'depth' => $C_Depth,
                                                        'lab' => @$keyvaluepair['lab'],
                                                        'certificate_no' => @$keyvaluepair['certificate #'],
                                                        'cert_comment' => @$keyvaluepair['report comments'],
                                                        'orignal_rate' => !empty(@$keyvaluepair['$/ct']) ? @$keyvaluepair['$/ct'] : 0,
                                                        'fancy_color' => @$keyvaluepair['fancy color'],
                                                        'fancy_intensity' => @$keyvaluepair['fancy color intensity'],
                                                        'fancy_overtone' => @$keyvaluepair['fancy color overtone'],
                                                        'depth_per' => @$keyvaluepair['depth percent'],
                                                        'table_per' => @$keyvaluepair['table percent'],
                                                        'girdle_thin' => @$keyvaluepair['girdle thin'],
                                                        'girdle_thick' => @$keyvaluepair['girdle thick'],
                                                        'gridle' => @$keyvaluepair['girdle condition'],
                                                        'cutlet' => @$keyvaluepair['culet'],
                                                        'gridle_per' => @$keyvaluepair['girdle percent'],
                                                        'crown_height' => @$keyvaluepair['crown height'],
                                                        'crown_angle' => @$keyvaluepair['crown angle'],
                                                        'pavilion_angle' => @$keyvaluepair['pavilion angle'],
                                                        'pavilion_depth' => @$keyvaluepair['pavilion depth'],
                                                        'country' => @$keyvaluepair['country'],
                                                        'city' => @$keyvaluepair['city'],
                                                        'image' => @$keyvaluepair['image link'],
                                                        'video' => trim(@$keyvaluepair['video link']),
                                                        'heart' => @$keyvaluepair['heart image'],
                                                        'arrow' => @$keyvaluepair['arrow image'],
                                                        'asset' => @$keyvaluepair['aset image'],
                                                        'key_symbols' => @$keyvaluepair['key to symbol'],
                                                        'milky' => @$keyvaluepair['milky'],
                                                        'eyeclean' => @$keyvaluepair['eye clean'],
                                                        'availability' => @$keyvaluepair['availability'],
                                                        'shade' => @$keyvaluepair['shade'],
                                                        'supplier_comments' => @$keyvaluepair['supplier comments'],
                                                        'luster' => @$keyvaluepair['luster'],
                                                        'culet_condition' => @$keyvaluepair['culet condition'],
                                                    );
                                                    $result['reason'] = $respo['reason'];
                                                    DiamondInvalid::insert($result);
                                                    $invalid++;
                                                }
                                                $j++;
                                            }
                                            $i++;
                                        }
                                    }

                                    if ($flag) {
                                        $stock_upload_report = array(
                                            'supplier_id' => $supplier->sup_id,
                                            'no_of_stone' => $j,
                                            'valid_diamond' => $index,
                                            'invalid_diamond' => $invalid,
                                            'upload_mode' => 'FTP',
                                            'info' => $File_Name,
                                            'created_at' => date('Y-m-d H:i:s'),
                                            'file_updated_at' => $file_update_date,
                                        );
                                        $load_id = StockUploadReport::insertGetId($stock_upload_report);

                                        // $this->db->where('load_id', 0);
                                        // $this->db->where('supp_id', $supplier->id);
                                        // $this->db->update('diamond_master_stg', array('load_id' => $load_id));

                                        $data_response['message'] = $index . ' diamond uploaded';
                                        $data_response['files'][0]['name'] = $index . ' diamond uploaded  ' . $invalid . ' Invalid Diamond';
                                        $data_response['isSuccess'] = true;
                                        $data_response['flag'] = $flag;
                                    } else {
                                        $data_response['flag'] = $flag;
                                        $data_response['warnings'][] = 'Format not supported';
                                        $data_response['hasWarnings'] = true;
                                    }
                                } else {
                                    // Lab Grown Diamond  //---------------------------------------------------------------------------------------------------------------------------------/
                                    // Lab Grown Diamond  //---------------------------------------------------------------------------------------------------------------------------------/
                                    // Lab Grown Diamond  //---------------------------------------------------------------------------------------------------------------------------------/

                                    if(empty($SheetError))
                                    {
                                        $certificate_array = array_column($sheet_data, array_search("certificate #", $sheet_data[0]));
                                        array_shift($certificate_array);
                                        $d = array_diff_assoc($certificate_array, array_unique($certificate_array));
                                    }

                                    $updatedelete = 1;
                                    DiamondInvalid::where('supplier_id', $supplier->sup_id)->delete();
                                    DiamondLabgrown::where('supplier_id', $supplier->sup_id)->update(['is_delete' => 1]);
                                    DiamondUnapprove::where('supplier_id', $supplier->sup_id)->update(['is_delete' => 1]);

                                    if ($supplier->stock_status == 'ACTIVE') {
                                    } else {
                                        DiamondUnapprove::where('supplier_id', $supplier->id)->delete();
                                    }

                                    $flag = false;
                                    $i = 1;
                                    $index = 0;
                                    $invalid = 0;
                                    $j = 0;
                                    $file_update_date = date("Y-m-d H:i:s", filemtime($file_path));

                                    if ($updatedelete) {
                                        $updatestring = '';
                                        foreach ($sheet_data as $value) {
                                            $keyvaluepair = array_combine($sheet_data[0], $value);
                                            if ($i == 1 && str_replace(" ", "", @$keyvaluepair['stock #']) == "stock#" && trim(@$keyvaluepair['shape']) == 'shape' && trim(@$keyvaluepair['weight']) == 'weight' && trim(@$keyvaluepair['clarity']) == 'clarity' && trim(@$keyvaluepair['color']) == 'color' && str_replace(" ", "", @$keyvaluepair['certificate #']) == "certificate#" && strtoupper(trim(@$keyvaluepair['$/ct'])) == '$/CT') {
                                                $flag = true;
                                            }

                                            if ($flag == true && !empty(@$keyvaluepair['weight']) && @$keyvaluepair['stock #'] != "stock #") {

                                                if (@$keyvaluepair['measurements'] != "") {
                                                    $mesurment = str_replace(array('*', '-', 'X', 'x'), "x", strtolower(@$keyvaluepair['measurements']));
                                                    $main = explode("x", $mesurment);
                                                    $C_Length = (!empty($main[0])) ? $main[0] : '';
                                                    $C_Width = (!empty($main[1])) ? $main[1] : '';
                                                    $C_Depth = (!empty($main[2])) ? $main[2] : '';
                                                }

                                                if (empty(trim(@$keyvaluepair['certificate #']))) {
                                                    $respo['success'] = false;
                                                    $respo['reason'] = "Certificate Blank";
                                                } elseif (in_array(@$keyvaluepair['certificate #'], $d)) {
                                                    $respo['success'] = false;
                                                    $respo['reason'] = "Certificate Duplicate";
                                                } elseif (empty(trim(@$keyvaluepair['stock #']))) {
                                                    $respo['success'] = false;
                                                    $respo['reason'] = "Stock ID Blank";
                                                } elseif (in_array(@$keyvaluepair['stock #'], $st)) {
                                                    $respo['success'] = false;
                                                    $respo['reason'] = "Stock ID Duplicate";
                                                } else {
                                                    $shape = AppHelper::ShapeValidation(@$keyvaluepair['shape']);
                                                    if ($shape == "") {
                                                        $respo['success'] = false;
                                                        $respo['reason'] = "Shape";
                                                    } else {
                                                        $respo = AppHelper::LabGrownCondition($keyvaluepair, $shape);
                                                    }
                                                }

                                                if ($respo['success']) {
                                                    $cut = '';
                                                    $Crn_Ht =  $Crn_Ag = $Pav_Ag = $Pav_Dp = 0;
                                                    if ($shape == 'ROUND') {
                                                        $C_shape = 'round';
                                                        $cut = AppHelper::CutValidation(@$keyvaluepair['cut grade']);
                                                        $Crn_Ht = AppHelper::Diamond_CH_CA_PD_PA(@$keyvaluepair['crown height']);
                                                        $Crn_Ag = AppHelper::Diamond_CH_CA_PD_PA(@$keyvaluepair['crown angle']);
                                                        $Pav_Ag = AppHelper::Diamond_CH_CA_PD_PA(@$keyvaluepair['pavilion angle']);
                                                        $Pav_Dp = AppHelper::Diamond_CH_CA_PD_PA(@$keyvaluepair['pavilion depth']);
                                                    } else {
                                                        $C_shape = 'pear';
                                                    }

                                                    if (strtolower(@$keyvaluepair['color']) == 'fancy' || @$keyvaluepair['color'] == '*' || @$keyvaluepair['color'] == '') {
                                                        $color = 'fancy';
                                                        $f_color = AppHelper::fancycolorValidation(@$keyvaluepair['fancy color']);
                                                        $f_intensity = AppHelper::intensityValidation(@$keyvaluepair['fancy color intensity']);
                                                        $f_overtone = AppHelper::overtoneValidation(@$keyvaluepair['fancy color overtone']);
                                                        $cut = '';
                                                        $Crn_Ht =  $Crn_Ag = $Pav_Ag = $Pav_Dp = 0;
                                                    } else {
                                                        $f_color = $f_intensity = $f_overtone = '';
                                                        $color = AppHelper::ColorValidation(@$keyvaluepair['color']);
                                                    }
                                                    $clarity = AppHelper::ClarityValidation(@str_replace(" ", "", @$keyvaluepair['clarity']));

                                                    $polish = AppHelper::CutValidation(@$keyvaluepair['polish']);
                                                    $symmetry = AppHelper::CutValidation(@$keyvaluepair['symmetry']);
                                                    $fluorescence = AppHelper::FluorescenceValidation(@$keyvaluepair['fluorescence intensity']);

                                                    $certi = trim(@$keyvaluepair['certificate #']);
                                                    $lab = AppHelper::LabValidation(@$keyvaluepair['lab']);
                                                    $country = AppHelper::CountryValidation(trim(@$keyvaluepair['country']));
                                                    $eyeclean = AppHelper::EyecleanValidation(@$keyvaluepair['eye clean']);

                                                    if(empty($eyeclean))
                                                    {
                                                        $clarityToeyeclean = trim(str_replace(" ", "", strtolower(@$keyvaluepair['clarity'])));
                                                        switch($clarityToeyeclean)
                                                        {
                                                            case 'fl':
                                                            case 'if':
                                                            case 'vvs1':
                                                            case 'vvs2':
                                                            case 'vs1':
                                                                $eyeclean = "Yes";
                                                                break;
                                                        }
                                                    }

                                                    $image = AppHelper::ImageValidation(@$keyvaluepair['image link']);
                                                    $heart = !empty(@$keyvaluepair['heart image']) ? AppHelper::ImageValidation(trim(@$keyvaluepair['heart image'])) : '';
                                                    $arrow = !empty(@$keyvaluepair['arrow image']) ? AppHelper::ImageValidation(trim(@$keyvaluepair['arrow image'])) : '';
                                                    $asset = !empty(@$keyvaluepair['aset image']) ? AppHelper::ImageValidation(trim(@$keyvaluepair['aset image'])) : '';
                                                    $video = !empty(@$keyvaluepair['video link']) ? AppHelper::VideoValidation(trim(@$keyvaluepair['video link'])) : '';

                                                    $milky = '';
                                                    $milky = !empty(@$keyvaluepair['milky']) ? AppHelper::MilkyValidation(@$keyvaluepair['milky']) : '';
                                                    if(empty($milky))
                                                    {
                                                        if(isset($keyvaluepair['luster']))
                                                        {
                                                            $lusterToMilky = trim(str_replace(" ", "", strtolower(@$keyvaluepair['luster'])));
                                                            switch($lusterToMilky)
                                                            {
                                                                case 'excellent':
                                                                case 'ex':
                                                                case 'verygood':
                                                                case 'vg+':
                                                                    $milky = 'NO MILKY';
                                                                    break;
                                                                case 'lightmilky':
                                                                    $milky = 'LIGHT MILKY ';
                                                                    break;
                                                                case 'milky':
                                                                    $milky = 'MILKY';
                                                                    break;
                                                            }
                                                        }
                                                    }

                                                    $carat = sprintf('%.2f', @$keyvaluepair['weight']); //empty(bcdiv(@$keyvaluepair['Weight'], 1, 2)) ? @$keyvaluepair['Weight'] :
                                                    $add_dic = 0;
                                                    if (!empty($s_price_array)) {
                                                        $add_dic = $this->findAdditionalValue($s_price_array[$C_shape], $carat);
                                                    }

                                                    $dollerpercarat = str_replace(array('$', ','), '', @$keyvaluepair['$/ct']);
                                                    $newdollerpercarat = round($dollerpercarat + ($dollerpercarat * ($supplier->markup + $add_dic)) / 100, 2);

                                                    $shippingprice = $this->shippingPrice($shiping_price_array[$country], $newdollerpercarat);
                                                    $shippingprice = ($shippingprice > 0) ? ($shippingprice / $carat) : 0;
                                                    $newdollerpercarat = $newdollerpercarat + $shippingprice;

                                                    $net_price = round($carat * $newdollerpercarat, 2);

                                                    $C_Length = $C_Width = $C_Depth = '0';
                                                    if (@$keyvaluepair['measurements'] != '') {
                                                        $mesurment = str_replace(array('*', '-', 'X', 'x'), 'x', strtolower(@$keyvaluepair['measurements']));
                                                        $main = explode('x', $mesurment);
                                                        $C_Length = (!empty($main[0])) ? $main[0] : '';
                                                        $C_Width = (!empty($main[1])) ? $main[1] : '';
                                                        $C_Depth = (!empty($main[2])) ? $main[2] : '';
                                                    }
                                                    else
                                                    {
                                                        $C_Length = !empty($keyvaluepair['length'])?$keyvaluepair['length']:'0';
                                                        $C_Width = !empty($keyvaluepair['width'])?$keyvaluepair['width']:'0';
                                                        $C_Depth = !empty($keyvaluepair['height'])?$keyvaluepair['height']:'0';
                                                    }

                                                    @$keyvaluepair['table percent'] = (@$keyvaluepair['table percent'] > 0.01 && @$keyvaluepair['table percent'] < 0.99) ? @$keyvaluepair['table percent'] * 100 : @$keyvaluepair['table percent'];
                                                    @$keyvaluepair['depth percent'] = (@$keyvaluepair['depth percent'] > 0.01 && @$keyvaluepair['depth percent'] < 0.99) ? @$keyvaluepair['depth percent'] * 100 : @$keyvaluepair['depth percent'];

                                                    $result = array(
                                                        'supplier_name' => $supplier->companyname,
                                                        'supplier_id' => $supplier->sup_id,
                                                        'ref_no' => @$keyvaluepair['stock #'],
                                                        'shape' => $shape,
                                                        'carat' => $carat,
                                                        'color' => $color,
                                                        'clarity' => strtoupper($clarity),
                                                        'cut' => $cut,
                                                        'polish' => $polish,
                                                        'symmetry' => $symmetry,
                                                        'fluorescence' => $fluorescence,
                                                        'orignal_rate' => $dollerpercarat,
                                                        'rate' => $newdollerpercarat,
                                                        'net_dollar' => $net_price,
                                                        'table_per' => @$keyvaluepair['table percent'],
                                                        'depth_per' => @$keyvaluepair['depth percent'],
                                                        'lab' => $lab,
                                                        'fancy_color' => $f_color,
                                                        'fancy_intensity' => $f_intensity,
                                                        'fancy_overtone' => $f_overtone,
                                                        'girdle_thin' => AppHelper::CheckgridleThin(@$keyvaluepair['girdle thin']),
                                                        'girdle_thick' => AppHelper::CheckgridleThink(@$keyvaluepair['girdle thick']),
                                                        'gridle' => AppHelper::GridleConValidation(@$keyvaluepair['girdle condition']),
                                                        'cutlet' => AppHelper::CuletSizeValidation(@$keyvaluepair['culet']),
                                                        'gridle_per' => AppHelper::GridlePerValidation(@$keyvaluepair['girdle percent']),
                                                        'crown_angle' => $Crn_Ag,
                                                        'crown_height' => $Crn_Ht,
                                                        'pavilion_angle' => $Pav_Ag,
                                                        'pavilion_depth' => $Pav_Dp,
                                                        'certificate_no' => $certi,
                                                        'key_symbols' => @$keyvaluepair['key to symbol'],
                                                        'country' => $country,
                                                        'city' => @$keyvaluepair['city'],
                                                        'length' => $C_Length,
                                                        'width' => $C_Width,
                                                        'depth' => $C_Depth,
                                                        'milky' => $milky,
                                                        'eyeclean' => $eyeclean,
                                                        'image' => $image,
                                                        'video' => $video,
                                                        'heart' => $heart,
                                                        'arrow' => $arrow,
                                                        'asset' => $asset,
                                                        'diamond_type' => 'L',
                                                        'c_type' => AppHelper::TreatmentValidation(@$keyvaluepair['treatment']),
                                                        'availability' => AppHelper::AvailabilityValidation(@$keyvaluepair['availability']),
                                                        'shade' => AppHelper::ShadeValidation(@$keyvaluepair['shade']),
                                                        'supplier_comments' => @$keyvaluepair['supplier comments'],
                                                        'luster' => AppHelper::LusterValidation(@$keyvaluepair['luster']),
                                                        'culet_condition' => AppHelper::CuletConValidation(@$keyvaluepair['culet condition']),
                                                        'is_delete' => '0',
                                                    );

                                                    $this->insert_update_lab_diamond($result, $supplier, $certi, $dollerpercarat);
                                                    $index++;
                                                } else {
                                                    $C_Length = $C_Width = $C_Depth = '0';
                                                    if (@$keyvaluepair['measurements'] != "") {
                                                        $mesurment = str_replace(array('*', '-', 'X', 'x'), "x", strtolower(@$keyvaluepair['measurements']));
                                                        $main = explode("x", $mesurment);
                                                        $C_Length = (!empty($main[0])) ? $main[0] : '0';
                                                        $C_Width = (!empty($main[1])) ? $main[1] : '0';
                                                        $C_Depth = (!empty($main[2])) ? $main[2] : '0';
                                                    }
                                                    else
                                                    {
                                                        $C_Length = !empty($keyvaluepair['length'])?$keyvaluepair['length']:'0';
                                                        $C_Width = !empty($keyvaluepair['width'])?$keyvaluepair['width']:'0';
                                                        $C_Depth = !empty($keyvaluepair['height'])?$keyvaluepair['height']:'0';
                                                    }
                                                    $result = array(
                                                        'supplier_name' => $supplier->companyname,
                                                        'supplier_id' => $supplier->sup_id,
                                                        'ref_no' => @$keyvaluepair['stock #'],
                                                        'shape' => @$keyvaluepair['shape'],
                                                        'carat' => @$keyvaluepair['weight'],
                                                        'color' => @$keyvaluepair['color'],
                                                        'clarity' => @$keyvaluepair['clarity'],
                                                        'cut' => @$keyvaluepair['cut grade'],
                                                        'polish' => @$keyvaluepair['polish'],
                                                        'symmetry' => @$keyvaluepair['symmetry'],
                                                        'fluorescence' => @$keyvaluepair['fluorescence intensity'],
                                                        'length' => $C_Length,
                                                        'width' => $C_Width,
                                                        'depth' => $C_Depth,
                                                        'lab' => @$keyvaluepair['lab'],
                                                        'certificate_no' => @$keyvaluepair['certificate #'],
                                                        'cert_comment' => @$keyvaluepair['report comments'],
                                                        'orignal_rate' => !empty(@$keyvaluepair['$/ct']) ? @$keyvaluepair['$/ct'] : 0,
                                                        'fancy_color' => @$keyvaluepair['fancy color'],
                                                        'fancy_intensity' => @$keyvaluepair['fancy color intensity'],
                                                        'fancy_overtone' => @$keyvaluepair['fancy color overtone'],
                                                        'depth_per' => @$keyvaluepair['depth percent'],
                                                        'table_per' => @$keyvaluepair['table percent'],
                                                        'girdle_thin' => @$keyvaluepair['girdle thin'],
                                                        'girdle_thick' => @$keyvaluepair['girdle thick'],
                                                        'gridle' => @$keyvaluepair['girdle condition'],
                                                        'cutlet' => @$keyvaluepair['culet'],
                                                        'gridle_per' => @$keyvaluepair['girdle percent'],
                                                        'crown_height' => @$keyvaluepair['crown height'],
                                                        'crown_angle' => @$keyvaluepair['crown angle'],
                                                        'pavilion_angle' => @$keyvaluepair['pavilion angle'],
                                                        'pavilion_depth' => @$keyvaluepair['pavilion depth'],
                                                        'country' => @$keyvaluepair['country'],
                                                        'city' => @$keyvaluepair['city'],
                                                        'image' => @$keyvaluepair['image link'],
                                                        'video' => trim(@$keyvaluepair['video link']),
                                                        'heart' => @$keyvaluepair['heart image'],
                                                        'arrow' => @$keyvaluepair['arrow image'],
                                                        'asset' => @$keyvaluepair['aset image'],
                                                        'key_symbols' => @$keyvaluepair['key to symbol'],
                                                        'milky' => @$keyvaluepair['milky'],
                                                        'eyeclean' => @$keyvaluepair['eye clean'],
                                                        'availability' => @$keyvaluepair['availability'],
                                                        'shade' => @$keyvaluepair['shade'],
                                                        'supplier_comments' => @$keyvaluepair['supplier comments'],
                                                        'luster' => @$keyvaluepair['luster'],
                                                        'culet_condition' => @$keyvaluepair['culet condition'],
                                                    );
                                                    $result['reason'] = $respo['reason'];
                                                    DiamondInvalid::insert($result);
                                                    $invalid++;
                                                }
                                                $j++;
                                            }
                                            $i++;
                                        }
                                    }
                                    if ($flag) {
                                        $stock_upload_report = array(
                                            'supplier_id' => $supplier->sup_id,
                                            'no_of_stone' => $j,
                                            'valid_diamond' => $index,
                                            'invalid_diamond' => $invalid,
                                            'upload_mode' => 'FTP',
                                            'info' => $File_Name,
                                            'created_at' => date('Y-m-d H:i:s'),
                                            'file_updated_at' => $file_update_date,
                                        );
                                        $load_id = StockUploadReport::insertGetId($stock_upload_report);

                                        $data_response['message'] = $index . " Lab diamond uploaded";
                                        $data_response['files'][0]['name'] = $index . "  Lab diamond uploaded  " . $invalid . " Invalid Diamond";
                                        $data_response['isSuccess'] = true;
                                        $data_response['flag'] = $flag;
                                    } else {
                                        $data_response['flag'] = $flag;
                                        $data_response['warnings'][] = "Format not supported";
                                        $data_response['hasWarnings'] = true;
                                    }
                                }
                            }
                            else
                            {
                                $data_response['Missing'] = 'stock # or certificate # missing.';
                            }
                        }
                    }
                    else
                    {
                        $data_response['expire'] = "true";
                    }
                }
                else
                {
                    $data_response['File_Exception'] = 'File not found or file extension not allowed for '.$supplier->companyname.'.';
                }

                Log::build([
                    'driver' =>'single',
                    'path' =>storage_path('logs/stock-'.$todate.'log'),
                ])->info('INFO-'.$supplier->companyname.'-end');
            }
        }

    }

    public function insert_update_diamond($result, $supplier, $certi, $dollerpercarat)
    {
        $created_date = date('Y-m-d H:i:s');
        if ($supplier->stock_status == 'ACTIVE') {
            $checkvalue = DiamondNatural::where('certificate_no', $certi)->get();
            if ($checkvalue->count() > 0) {
                $diamond_data = $checkvalue->first();
                if ($diamond_data->location == 1 && $diamond_data->status == 0) {
                    if ($diamond_data->supplier_id == $supplier->sup_id) {
                        DiamondNatural::where('supplier_id', $supplier->sup_id)->where('certificate_no', $certi)->update($result);
                    } else {

                        if($diamond_data->is_delete == 1)
                        {
                            DiamondNatural::where('certificate_no', $certi)->delete();
                            DiamondNatural::insert($result);
                        }
                        else
                        {
                            if($diamond_data->orignal_rate > $dollerpercarat)
                            {
                                $another_table_add = DB::insert("INSERT IGNORE INTO `diamond_conflict` (`supplier_id`, `supplier_name`, `new_supplier_name`, `ref_no`, `availability`, `diamond_type`, `shape`, `carat`, `color`, `clarity`, `cut`, `polish`, `symmetry`, `fluorescence`, `lab`, `certificate_no`, `certificate_link`, `certificate_download`, `length`, `width`, `depth`, `location`, `city`, `country`, `milky`, `eyeclean`, `hna`, `depth_per`, `table_per`, `crown_angle`, `crown_height`, `pavilion_angle`, `pavilion_depth`, `discount`, `rap`, `orignal_rate`, `rate`, `net_dollar`, `key_symbols`, `fancy_color`, `fancy_intensity`, `fancy_overtone`, `image_status`, `cloud_image`, `image`, `video`, `heart`, `cloud_heart`, `arrow`, `cloud_arrow`, `asset`, `cloud_asset`, `canada_mark`, `cutlet`, `luster`, `gridle`, `gridle_per`, `girdle_thin`, `girdle_thick`, `shade`, `c_type`, `status`, `supplier_comments`, `culet_condition`, `hold_for`, `updated_at`, `created_at`, `conflict_at`, `is_delete`)
                                                                SELECT `supplier_id`, `supplier_name`, '".$supplier->companyname."',`ref_no`, `availability`, `diamond_type`, `shape`, `carat`, `color`, `clarity`, `cut`, `polish`, `symmetry`, `fluorescence`, `lab`, `certificate_no`, `certificate_link`, `certificate_download`, `length`, `width`, `depth`, `location`, `city`, `country`, `milky`, `eyeclean`, `hna`, `depth_per`, `table_per`, `crown_angle`, `crown_height`, `pavilion_angle`, `pavilion_depth`, `discount`, `rap`, `orignal_rate`, `rate`, `net_dollar`, `key_symbols`, `fancy_color`, `fancy_intensity`, `fancy_overtone`, `image_status`, `cloud_image`, `image`, `video`, `heart`, `cloud_heart`, `arrow`, `cloud_arrow`, `asset`, `cloud_asset`, `canada_mark`, `cutlet`, `luster`, `gridle`, `gridle_per`, `girdle_thin`, `girdle_thick`, `shade`, `c_type`, `status`, `supplier_comments`, `culet_condition`, `hold_for`, `updated_at`, `created_at`, '".$created_date."',`is_delete`
                                    FROM diamond_natural WHERE certificate_no = '".$certi."' limit 1");

                                if ($another_table_add) {
                                    DiamondNatural::where('certificate_no', $certi)->delete();
                                    DiamondNatural::insert($result);
                                }
                            }
                            else
                            {
                                // $result['id'] = $diamond_data->id;
                                $result['conflict_at'] = $created_date;
                                $result['diamond_type'] = 'W';
                                DiamondConflict::insert($result);
                            }
                        }
                    }
                }
                else
                {
                    if ($diamond_data->supplier_name != $supplier->companyname) {
                        $result['conflict_at'] = $created_date;
                        $result['diamond_type'] = 'W';
                        DiamondConflict::insert($result);
                    }
                    else
                    {
                        DiamondNatural::where('supplier_id', $supplier->sup_id)->where('certificate_no', $certi)->update(['updated_at' => date('Y-m-d H:i:s')]);
                    }
                }
            } else {
                DiamondNatural::insert($result);
            }
        } else {
            DiamondUnapprove::insert($result);
        }

    }

    function findAdditionalValue($s_price_array, $CARAT)
    {
        foreach ($s_price_array as $key => $value) {
            if ($key <= $CARAT && key($value) >= $CARAT) {
                return reset($value);
            }
        }
    }

    public function insert_update_lab_diamond($result, $supplier, $certi, $dollerpercarat)
    {
        $created_date = date('Y-m-d H:i:s');
        if ($supplier->stock_status == 'ACTIVE') {
            $checkvalue = DiamondLabgrown::where('certificate_no', $certi)->get();
            if ($checkvalue->count() > 0) {
                $diamond_data = $checkvalue->first();
                if ($diamond_data->location == 1 && $diamond_data->status == 0) {
                    if ($diamond_data->supplier_id == $supplier->sup_id) {
                        DiamondLabgrown::where('supplier_id', $supplier->sup_id)->where('certificate_no', $certi)->update($result);
                    } else {

                        if($diamond_data->is_delete == 1)
                        {
                            DiamondLabgrown::where('certificate_no', $certi)->delete();
                            DiamondLabgrown::insert($result);
                        }
                        else
                        {
                            if($diamond_data->orignal_rate > $dollerpercarat)
                            {
                                $another_table_add = DB::insert("INSERT IGNORE INTO `diamond_conflict` (`supplier_id`, `supplier_name`, `new_supplier_name`, `ref_no`, `availability`, `diamond_type`, `shape`, `carat`, `color`, `clarity`, `cut`, `polish`, `symmetry`, `fluorescence`, `lab`, `certificate_no`, `certificate_link`, `certificate_download`, `length`, `width`, `depth`, `location`, `city`, `country`, `milky`, `eyeclean`, `hna`, `depth_per`, `table_per`, `crown_angle`, `crown_height`, `pavilion_angle`, `pavilion_depth`, `discount`, `rap`, `orignal_rate`, `rate`, `net_dollar`, `key_symbols`, `fancy_color`, `fancy_intensity`, `fancy_overtone`, `image_status`, `cloud_image`, `image`, `video`, `heart`, `cloud_heart`, `arrow`, `cloud_arrow`, `asset`, `cloud_asset`, `canada_mark`, `cutlet`, `luster`, `gridle`, `gridle_per`, `girdle_thin`, `girdle_thick`, `shade`, `c_type`, `status`, `supplier_comments`, `culet_condition`, `hold_for`, `updated_at`, `created_at`, `conflict_at`, `is_delete`)
                                                                SELECT `supplier_id`, `supplier_name`, '".$supplier->companyname."',`ref_no`, `availability`, `diamond_type`, `shape`, `carat`, `color`, `clarity`, `cut`, `polish`, `symmetry`, `fluorescence`, `lab`, `certificate_no`, `certificate_link`, `certificate_download`, `length`, `width`, `depth`, `location`, `city`, `country`, `milky`, `eyeclean`, `hna`, `depth_per`, `table_per`, `crown_angle`, `crown_height`, `pavilion_angle`, `pavilion_depth`, `discount`, `rap`, `orignal_rate`, `rate`, `net_dollar`, `key_symbols`, `fancy_color`, `fancy_intensity`, `fancy_overtone`, `image_status`, `cloud_image`, `image`, `video`, `heart`, `cloud_heart`, `arrow`, `cloud_arrow`, `asset`, `cloud_asset`, `canada_mark`, `cutlet`, `luster`, `gridle`, `gridle_per`, `girdle_thin`, `girdle_thick`, `shade`, `c_type`, `status`, `supplier_comments`, `culet_condition`, `hold_for`, `updated_at`, `created_at`, '".$created_date."',`is_delete`
                                    FROM diamond_labgrown WHERE certificate_no = '".$certi."' limit 1");

                                if ($another_table_add) {
                                    DiamondLabgrown::where('certificate_no', $certi)->delete();
                                    DiamondLabgrown::insert($result);
                                }
                            }
                            else
                            {
                                // $result['id'] = $diamond_data->id;
                                $result['conflict_at'] = $created_date;
                                $result['diamond_type'] = 'L';
                                DiamondConflict::insert($result);
                            }
                        }
                    }
                }
                else
                {
                    if ($diamond_data->supplier_name != $supplier->companyname) {
                        $result['conflict_at'] = $created_date;
                        $result['diamond_type'] = 'L';
                        DiamondConflict::insert($result);
                    }
                    else
                    {
                        DiamondLabgrown::where('supplier_id', $supplier->sup_id)->where('certificate_no', $certi)->update(['updated_at' => date('Y-m-d H:i:s')]);
                    }
                }
            } else {
                DiamondLabgrown::insert($result);
            }
        } else {
            DiamondUnapprove::insert($result);
        }
    }

    function shippingPrice($shiping_price_array, $newdollerpercarat) {
		foreach ($shiping_price_array as $key => $value) {
			if ($key <= $newdollerpercarat && key($value) >= $newdollerpercarat) {
				return reset($value);
			}
		}
	}

}

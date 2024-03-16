<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\AppHelper;
use Illuminate\Support\Facades\Auth;
use DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\NaturalExport;
use App\Exports\PecentcheckExport;
use App\Exports\DiamondExport;
use App\Exports\DiamondExportSupplier;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\FromCollection;

use App\Models\User;
use App\Models\Supplier;
use App\Models\ImgVidRequest;
use App\Models\DiamondNatural;
use App\Models\DiamondLabgrown;
use App\Models\Invoice;
use App\Models\Pickups;
use App\Models\Order;
use App\Models\TimelineCycle;

class ADiamondController extends Controller
{
    public function diamondNatural(Request $request)
    {
        $data['suppliers'] = Supplier::with('users')->whereHas('users',function($query){$query->orderBy('companyname','asc');})->where('diamond_type', 'Natural')->where('stock_status','ACTIVE')->get();
        return view('admin.diamond-natural')->with($data);
    }

    public function diamondcountnatural(Request $request)
    {
        // DB::enableQueryLog();
        // dd($request->all());
        $result_query = DiamondNatural::select('id')
		->where('carat', '>', 0.08)
		->where('orignal_rate','>',50);

		if(!empty($request->stoneid) && $request->stoneid != 'undefined')
		{
			$postdata = strtoupper($request->stoneid);
			$result_query->where(function($query) use ($postdata) {
                $stoneid = str_replace('LG', '', $postdata);
                $stoneid = str_replace(' ', ',', $stoneid);

				$stoneids = explode(",", $stoneid);
				$query->orWhereIn('id', $stoneids);
				$query->orWhereIn('certificate_no', $stoneids);
                $query->orWhereIn('certificate_no', $stoneids);

                $certino = explode(",", $postdata);
                $query->orWhereIn('certificate_no', $certino);
			});
		}
		else if(!empty($request->certificateid) && $request->certificateid != 'undefined')
		{
			$postdata = $request->certificateid;
			$certificate_no = explode(",", $postdata);
			$result_query->whereIn('certificate_no', $certificate_no);
		}
		else
		{
			if(!empty($request->min_carat) && !empty($request->max_carat))
			{
				$min_carat = $request->min_carat;
				$max_carat = $request->max_carat;
				if(!empty($request->min_carat) && !empty($request->max_carat))
				{
					$result_query->where('carat', '>=', $min_carat);
				}

				if(!empty($request->min_carat) && !empty($request->max_carat))
				{
					$result_query->where('carat', '<=', $max_carat);
				}
			}
			else
			{
				$result_query->where('carat', '>', 0.08);
				$result_query->where('carat', '<', 99.99);
			}

            if(!empty($request->fancyorwhite))
			{
                $result_query->where('color', 'fancy');
				if(!empty($request->fcolor))
				{
					$data2 = "";
					$fcolor = $request->fcolor;
					$fcolor = trim($fcolor, ",");
					$tmp = explode(",", $fcolor);

                    $result_query->where(function($query) use ($tmp) {
                        foreach ($tmp as $t) {
                            $query->orWhere('fancy_color','like', '%' . $t . '%');
                        }
                    });

					// foreach($tmp as $t)
					// {
					// 	$data2.=" f_color LIKE '%".$t."%' OR ";
					// }
					// $data2 = trim($data2,"OR ");
					// $main_query_string = "( $data2 ) AND color = 'fancy' ";
					// $result_query->where($main_query_string);
				}

				if(!empty($request->intesites))
				{
					$data2 = "";
					$intesites = $request->intesites;
					$intesites = trim($intesites, ",");
					$tmp = explode(",", $intesites);
                    $result_query->where(function($query) use ($tmp) {
                        foreach ($tmp as $t) {
                            $query->orWhere('fancy_intensity','like', '%' . $t . '%');
                        }
                    });
					// foreach($tmp as $t)
					// {
					// 	$data2.=" f_intensity LIKE '%".$t."%' OR ";
					// }
					// $data2 = trim($data2,"OR ");
					// $main_query_string = "( $data2 ) AND color = 'fancy' ";
					// $result_query->where($main_query_string);
				}

				if(!empty($request->overtones))
				{
					$data2 = "";
					$overtones = $request->overtones;
					$overtones = trim($overtones, ",");
					$tmp = explode(",", $overtones);
                    $result_query->where(function($query) use ($tmp) {
                        foreach ($tmp as $t) {
                            $query->orWhere('fancy_overtone','like', '%' . $t . '%');
                        }
                    });
					// foreach($tmp as $t)
					// {
					// 	if($t == "None")
					// 	{
					// 		$data2.=" f_overtone = '' OR ";
					// 	}
					// 	else {
					// 		$data2.=" f_overtone LIKE '%".$t."%' OR ";
					// 	}
					// 	$data2.=" f_overtone LIKE '%".$t."%' OR ";
					// }
					// $data2 = trim($data2,"OR ");
					// $main_query_string = "( $data2 ) AND color = 'fancy' ";
					// $result_query->where($main_query_string);
				}
			}
			else
			{
				$result_query->where('color', '!=', 'fancy');
				if(!empty($request->color))
				{
					$result_query->whereIn('color', explode(",", $request->color));
				}
				else
				{
					$result_query->whereIn('color', array('D','E','F','G','H','I','J','K','L','M','N','O','OP','QR','ST','UV','WX','YZ'));
				}
			}

			if(!empty($request->shape))
			{
				$result_query->whereIn('shape',explode(",",$request->shape));
			}
			if(!empty($request->clarity))
			{
				$result_query->whereIn('clarity',explode(",", $request->clarity));
			}
			if(!empty($request->cut))
			{
				$cut_arrya = explode(",", $request->cut);
				$cut_arrya[] = '';
				$result_query->whereIn('cut',$cut_arrya);
			}
			if(!empty($request->polish))
			{
				$result_query->whereIn('polish',explode(",", $request->polish));
			}
			if(!empty($request->symmetry))
			{
				$result_query->whereIn('symmetry',explode(",",$request->symmetry));
			}
			if(!empty($request->flourescence))
			{
				$result_query->whereIn('fluorescence',explode(",",$request->flourescence));
			}
			if(!empty($request->lab))
			{
				$result_query->whereIn('lab',explode(",",$request->lab));
			}

            if(!empty($request->c_type))
			{
				$result_query->whereIn('c_type', explode(",",$request->c_type));
			}

			if(!empty($request->location))
			{
				$location = $request->location;
				$data2="";
				$tmp=explode(",",$location);
				foreach($tmp as $t)
				{
					$data2.= "$t,";
				}
				$data2s = trim($data2,",");
				$result_query->whereIn('country',explode(",", $data2s));
			}
            if(!empty($request->company))
			{
				$result_query->whereIn('supplier_id',explode(",",$request->company));
			}

			$table_per_from = strip_tags(substr($request->table_per_from,0,100));
			$table_per_to = strip_tags(substr($request->table_per_to,0,100));
			$depth_per_from = strip_tags(substr($request->depth_per_from,0,100));
			$depth_per_to = strip_tags(substr($request->depth_per_to,0,100));

			$result_query->when((isset($depth_per_from) && $depth_per_from > 0), function ($q) use ($depth_per_from) {
				return $q->where('depth_per', '>=', $depth_per_from);
			});
			$result_query->when((isset($depth_per_to) && $depth_per_to > 0), function ($q) use ($depth_per_to) {
				return $q->where('depth_per', '<=', $depth_per_to);
			});

			$result_query->when((isset($table_per_from) && $table_per_from > 0), function ($q) use ($table_per_from) {
				return $q->where('table_per', '>=', $table_per_from);
			});
			$result_query->when((isset($table_per_to) && $table_per_to > 0), function ($q) use ($table_per_to) {
				return $q->where('table_per', '<=', $table_per_to);
			});

            $min_length = strip_tags(substr($request->min_length,0,100));
			$max_length = strip_tags(substr($request->max_length,0,100));
			$width_min = strip_tags(substr($request->width_min,0,100));
			$width_max = strip_tags(substr($request->width_max,0,100));
            $depth_min = strip_tags(substr($request->depth_min,0,100));
			$depth_max = strip_tags(substr($request->depth_max,0,100));

			$result_query->when((isset($min_length) && $min_length > 0), function ($q) use ($min_length) {
				return $q->where('length', '>=', $min_length);
			});
			$result_query->when((isset($max_length) && $max_length > 0), function ($q) use ($max_length) {
				return $q->where('length', '<=', $max_length);
			});

			$result_query->when((isset($width_min) && $width_min > 0), function ($q) use ($width_min) {
				return $q->where('width', '>=', $width_min);
			});
			$result_query->when((isset($width_max) && $width_max > 0), function ($q) use ($width_max) {
				return $q->where('width', '<=', $width_max);
			});

            $result_query->when((isset($depth_min) && $depth_min > 0), function ($q) use ($depth_min) {
				return $q->where('depth', '>=', $depth_min);
			});
			$result_query->when((isset($depth_max) && $depth_max > 0), function ($q) use ($depth_max) {
				return $q->where('depth', '<=', $depth_max);
			});

            $cr_from = strip_tags(substr($request->cr_from,0,100));
			$cr_to = strip_tags(substr($request->cr_to,0,100));
			$crag_from = strip_tags(substr($request->crag_from,0,100));
			$crag_to = strip_tags(substr($request->crag_to,0,100));
            $pv_from = strip_tags(substr($request->pv_from,0,100));
			$pv_to = strip_tags(substr($request->pv_to,0,100));

            $pvag_from = strip_tags(substr($request->pvag_from,0,100));
			$pvag_to = strip_tags(substr($request->pvag_to,0,100));

			$result_query->when((isset($cr_from) && $cr_from > 0), function ($q) use ($cr_from) {
				return $q->where('crown_height', '>=', $cr_from);
			});
			$result_query->when((isset($cr_to) && $cr_to > 0), function ($q) use ($cr_to) {
				return $q->where('crown_height', '<=', $cr_to);
			});

			$result_query->when((isset($crag_from) && $crag_from > 0), function ($q) use ($crag_from) {
				return $q->where('crown_angle', '>=', $crag_from);
			});
			$result_query->when((isset($crag_to) && $crag_to > 0), function ($q) use ($crag_to) {
				return $q->where('crown_angle', '<=', $crag_to);
			});

            $result_query->when((isset($pv_from) && $pv_from > 0), function ($q) use ($pv_from) {
				return $q->where('pavilion_depth', '>=', $pv_from);
			});
			$result_query->when((isset($pv_to) && $pv_to > 0), function ($q) use ($pv_to) {
				return $q->where('pavilion_depth', '<=', $pv_to);
			});

            $result_query->when((isset($pvag_from) && $pvag_from > 0), function ($q) use ($pvag_from) {
				return $q->where('pavilion_angle', '>=', $pvag_from);
			});
			$result_query->when((isset($pvag_to) && $pvag_to > 0), function ($q) use ($pvag_to) {
				return $q->where('pavilion_angle', '<=', $pvag_to);
			});

            if(!empty($request->image))
			{
				$result_query->where('image', '!=', '');
			}

            if(!empty($request->video))
			{
				$result_query->where('video', '!=', '');
			}

            $days_ago = date('Y-m-d H:i:s', strtotime('-5 days', strtotime(date('Y-m-d'))));
            $result_query->where('updated_at', '>', $days_ago);

			// if($this->input->post('min_price') >= '0' && $this->input->post('max_price') < '1000000.0')
			// {	$price ="net_dollar BETWEEN least(".$this->input->post('min_price')." , ".$this->input->post('max_price').") AND greatest(".$this->input->post('min_price').", ".$this->input->post('max_price').")";
			// 	$this->db->where($price);
			// }

			// if(!empty($this->input->post('location')))
			// {
			// 	$location =$this->input->post('location');
			// 	$data2="";
			// 	$tmp=explode(",",$location);
			// 	foreach($tmp as $t)
			// 	{
			// 		$data2.= "$t,";
			// 	}
			// 	$data2s = trim($data2,",");
			// 	$this->db->where_in('country',explode(",",$data2s));
			// }

			// if(!empty($this->input->post('eye_clean')))
			// {
			// 	$main_query_eye="";
			// 	$chk = explode(",",$this->input->post('eye_clean'));
			// 	if(in_array("YES",$chk))
			// 	{
			// 		$main_query_eye = "(EyeC = 'YES') ";
			// 		$this->db->where($main_query_eye);
			// 	}

			// 	if(in_array("NO",$chk))
			// 	{
			// 		$main_query_eye = "(EyeC != 'YES') ";
			// 		$this->db->where($main_query_eye);
			// 	}
			// }

			// if(!empty($this->input->post('brown')))
			// {
			// 	$this->db->where_in('brown',explode(",",$this->input->post('brown')));
			// }
			// if(!empty($this->input->post('green')))
			// {
			// 	$this->db->where_in('green',explode(",",$this->input->post('green')));
			// }
			// if(!empty($this->input->post('milky')))
			// {
			// 	$this->db->where_in('Milky',explode(",",$this->input->post('milky')));
			// }

			// if(!empty($this->input->post('imagedetails')))
			// {
			// 	$imagedetails =$this->input->post('imagedetails');
			// 	$tmp=explode(",",$imagedetails);
			// 	foreach($tmp as $t)
			// 	{
			// 		if($t == "ALL"){
			// 			$this->db->where('aws_image !=','');
			// 		}
			// 		if($t == "IMAGE"){
			// 			$this->db->where('aws_image !=','');
			// 		}
			// 		if($t == "VIDEO"){
			// 			$this->db->where('video !=','');
			// 		}
			// 		if($t == "HA"){
			// 			$this->db->where('aws_heart !=','');
			// 		}
			// 		if($t == "ASSET"){
			// 			$this->db->where('aws_asset !=','');
			// 		}
			// 	}
			// }
		}

		$result_query->where('location', 1);
		$result_query->where('status', '0');
		$result_query->where('is_delete', 0);

		if(empty($request->selectcolumn) || $request->selectcolumn == "undefined")
		{
			$result_query->orderBy('carat', 'asc');
			// $order = "FIELD(C_Shape, 'ROUND','HEART','MARQUISE','PEAR','OVAL','EMERALD','CUSHION','PRINCESS','RADIANT','KT')";
			$result_query->orderBy('color', 'asc');
			// $order = "FIELD(C_Clarity, 'FL','IF','VVS1','VVS2','VS1','VS2','SI1','SI2','SI3','I1','I2')";
			// $order = "FIELD(C_Cut, 'ID','EX','VG','GD','FR','PR', '')";
			$result_query->orderBy('orignal_rate', 'asc');
		}
		// $result_query->limit(50)->offset($start_from);
		$result = $result_query->count();
		// dd(DB::getQueryLog());

        $data['count'] = $result;
		return json_encode($data);
    }

    public function diamondNaturalList(Request $request)
    {
        $data['suppliers'] = Supplier::with('users')->whereHas('users',function($query){$query->orderBy('companyname','asc');})->where('diamond_type', 'Natural')->where('stock_status','ACTIVE')->get();
        return view('admin.diamond-natural-list')->with($data);
    }

    public function NaturalDiamondDownload(Request $request)
    {
        $sku = explode(',', $request->sku);
		$customer_id = ''; //Auth::user()->id;

        if(!empty($request->selected_stone))
        {
            $sku = explode(',', $request->selected_stone);

            $diamond_data =  DiamondNatural::select('id','ref_no','diamond_type','shape','carat','color', 'fancy_color', 'fancy_intensity', 'fancy_overtone', 'clarity','cut','polish','symmetry','fluorescence','lab','certificate_no','rate', 'depth_per', 'table_per', 'length', 'width', 'depth', 'crown_angle', 'crown_height', 'pavilion_angle', 'pavilion_depth', 'milky', 'eyeclean', 'shade', 'key_symbols', 'hna', 'image', 'video', 'supplier_name', 'c_type', 'country', DB::raw('(SELECT pricechange FROM markup_setting WHERE (net_dollar BETWEEN `min_range` and max_range)) as aditional_discount'),
            DB::raw("(SELECT caratprice FROM rap_list WHERE IF(`shape_name` = 'ROUND', shape = 'ROUND', shape != 'ROUND') AND color_name = `color` AND clarity_name = IF(`clarity` = 'FL','IF', `clarity`) AND low_size <= carat AND high_size >= carat) as raprate"))
            ->whereIn('id', $sku)
            ->where('is_delete', 0)->get();
        }
        else
        {
            $result_query =  DiamondNatural::select('id','ref_no','diamond_type','shape','carat','color', 'fancy_color', 'fancy_intensity', 'fancy_overtone', 'clarity','cut','polish','symmetry','fluorescence','lab','certificate_no','rate', 'depth_per', 'table_per', 'length', 'width', 'depth', 'crown_angle', 'crown_height', 'pavilion_angle', 'pavilion_depth', 'milky', 'eyeclean', 'shade', 'key_symbols', 'hna', 'image', 'video', 'supplier_name', 'c_type', 'country', DB::raw('(SELECT pricechange FROM markup_setting WHERE (net_dollar BETWEEN `min_range` and max_range)) as aditional_discount'),
            DB::raw("(SELECT caratprice FROM rap_list WHERE IF(`shape_name` = 'ROUND', shape = 'ROUND', shape != 'ROUND') AND color_name = `color` AND clarity_name = IF(`clarity` = 'FL','IF', `clarity`) AND low_size <= carat AND high_size >= carat) as raprate"));

            if(!empty($request->stoneid) && $request->stoneid != 'undefined')
            {
                $postdata = strtoupper($request->stoneid);
                $result_query->where(function($query) use ($postdata) {
                    $stoneid = str_replace('LG', '', $postdata);
                    $stoneid = str_replace(' ', ',', $stoneid);

                    $stoneids = explode(",", $stoneid);
                    $query->orWhereIn('id', $stoneids);
                    $query->orWhereIn('certificate_no', $stoneids);
                    $query->orWhereIn('certificate_no', $stoneids);

                    $certino = explode(",", $postdata);
                    $query->orWhereIn('certificate_no', $certino);
                });
            }
            else if(!empty($request->certificateid) && $request->certificateid != 'undefined')
            {
                $postdata = $request->certificateid;
                $certificate_no = explode(",", $postdata);
                $result_query->whereIn('certificate_no', $certificate_no);
            }
            else
            {
                if(!empty($request->min_carat) && !empty($request->max_carat))
                {
                    $min_carat = (float)$request->min_carat;
                    $max_carat = (float)$request->max_carat;
                    if(!empty($request->min_carat) && !empty($request->max_carat))
                    {
                        $result_query->where('carat', '>=', $min_carat);
                    }

                    if(!empty($request->min_carat) && !empty($request->max_carat))
                    {
                        $result_query->where('carat', '<=', $max_carat);
                    }
                }
                else
                {
                    $result_query->where('carat', '>', 0.08);
                    $result_query->where('carat', '<', 99.99);
                }

                if(!empty($request->fancyorwhite))
                {
                    $result_query->where('color', 'fancy');
                    if(!empty($request->fcolor))
                    {
                        $data2 = "";
                        $fcolor = $request->fcolor;
                        $fcolor = trim($fcolor, ",");
                        $tmp = explode(",", $fcolor);

                        $result_query->where(function($query) use ($tmp) {
                            foreach ($tmp as $t) {
                                $query->orWhere('fancy_color','like', '%' . $t . '%');
                            }
                        });
                    }

                    if(!empty($request->intesites))
                    {
                        $data2 = "";
                        $intesites = $request->intesites;
                        $intesites = trim($intesites, ",");
                        $tmp = explode(",", $intesites);
                        $result_query->where(function($query) use ($tmp) {
                            foreach ($tmp as $t) {
                                $query->orWhere('fancy_intensity','like', '%' . $t . '%');
                            }
                        });
                    }

                    if(!empty($request->overtones))
                    {
                        $data2 = "";
                        $overtones = $request->overtones;
                        $overtones = trim($overtones, ",");
                        $tmp = explode(",", $overtones);
                        $result_query->where(function($query) use ($tmp) {
                            foreach ($tmp as $t) {
                                $query->orWhere('fancy_overtone','like', '%' . $t . '%');
                            }
                        });
                    }
                }
                else
                {
                    $result_query->where('color', '!=', 'fancy');
                    if(!empty($request->color))
                    {
                        $result_query->whereIn('color', explode(",", $request->color));
                    }
                    else
                    {
                        $result_query->whereIn('color', array('D','E','F','G','H','I','J','K','L','M','N','O','OP','QR','ST','UV','WX','YZ'));
                    }
                }

                if(!empty($request->shape))
                {
                    $result_query->whereIn('shape',explode(",",$request->shape));
                }
                if(!empty($request->clarity))
                {
                    $result_query->whereIn('clarity',explode(",", $request->clarity));
                }
                if(!empty($request->cut))
                {
                    $cut_arrya = explode(",", $request->cut);
                    $cut_arrya[] = '';
                    $result_query->whereIn('cut',$cut_arrya);
                }
                if(!empty($request->polish))
                {
                    $result_query->whereIn('polish',explode(",", $request->polish));
                }
                if(!empty($request->symmetry))
                {
                    $result_query->whereIn('symmetry',explode(",",$request->symmetry));
                }
                if(!empty($request->flourescence))
                {
                    $result_query->whereIn('fluorescence',explode(",",$request->flourescence));
                }
                if(!empty($request->lab))
                {
                    $result_query->whereIn('lab',explode(",",$request->lab));
                }

                if(!empty($request->company))
                {
                    $result_query->whereIn('supplier_id',explode(",",$request->company));
                }

                if(!empty($request->location))
                {
                    $location = $request->location;
                    $data2="";
                    $tmp=explode(",",$location);
                    foreach($tmp as $t)
                    {
                        $data2.= "$t,";
                    }
                    $data2s = trim($data2,",");
                    $result_query->whereIn('country',explode(",", $data2s));
                }

                $table_per_from = strip_tags(substr($request->table_per_from,0,100));
                $table_per_to = strip_tags(substr($request->table_per_to,0,100));
                $depth_per_from = strip_tags(substr($request->depth_per_from,0,100));
                $depth_per_to = strip_tags(substr($request->depth_per_to,0,100));

                $result_query->when((isset($depth_per_from) && $depth_per_from > 0), function ($q) use ($depth_per_from) {
                    return $q->where('depth_per', '>=', $depth_per_from);
                });
                $result_query->when((isset($depth_per_to) && $depth_per_to > 0), function ($q) use ($depth_per_to) {
                    return $q->where('depth_per', '<=', $depth_per_to);
                });

                $result_query->when((isset($table_per_from) && $table_per_from > 0), function ($q) use ($table_per_from) {
                    return $q->where('table_per', '>=', $table_per_from);
                });
                $result_query->when((isset($table_per_to) && $table_per_to > 0), function ($q) use ($table_per_to) {
                    return $q->where('table_per', '<=', $table_per_to);
                });

                $min_length = strip_tags(substr($request->min_length,0,100));
                $max_length = strip_tags(substr($request->max_length,0,100));
                $width_min = strip_tags(substr($request->width_min,0,100));
                $width_max = strip_tags(substr($request->width_max,0,100));
                $depth_min = strip_tags(substr($request->depth_min,0,100));
                $depth_max = strip_tags(substr($request->depth_max,0,100));

                $result_query->when((isset($min_length) && $min_length > 0), function ($q) use ($min_length) {
                    return $q->where('length', '>=', $min_length);
                });
                $result_query->when((isset($max_length) && $max_length > 0), function ($q) use ($max_length) {
                    return $q->where('length', '<=', $max_length);
                });

                $result_query->when((isset($width_min) && $width_min > 0), function ($q) use ($width_min) {
                    return $q->where('width', '>=', $width_min);
                });
                $result_query->when((isset($width_max) && $width_max > 0), function ($q) use ($width_max) {
                    return $q->where('width', '<=', $width_max);
                });

                $result_query->when((isset($depth_min) && $depth_min > 0), function ($q) use ($depth_min) {
                    return $q->where('depth', '>=', $depth_min);
                });
                $result_query->when((isset($depth_max) && $depth_max > 0), function ($q) use ($depth_max) {
                    return $q->where('depth', '<=', $depth_max);
                });

                $cr_from = strip_tags(substr($request->cr_from,0,100));
                $cr_to = strip_tags(substr($request->cr_to,0,100));
                $crag_from = strip_tags(substr($request->crag_from,0,100));
                $crag_to = strip_tags(substr($request->crag_to,0,100));
                $pv_from = strip_tags(substr($request->pv_from,0,100));
                $pv_to = strip_tags(substr($request->pv_to,0,100));

                $pvag_from = strip_tags(substr($request->pvag_from,0,100));
                $pvag_to = strip_tags(substr($request->pvag_to,0,100));

                $result_query->when((isset($cr_from) && $cr_from > 0), function ($q) use ($cr_from) {
                    return $q->where('crown_height', '>=', $cr_from);
                });
                $result_query->when((isset($cr_to) && $cr_to > 0), function ($q) use ($cr_to) {
                    return $q->where('crown_height', '<=', $cr_to);
                });

                $result_query->when((isset($crag_from) && $crag_from > 0), function ($q) use ($crag_from) {
                    return $q->where('crown_angle', '>=', $crag_from);
                });
                $result_query->when((isset($crag_to) && $crag_to > 0), function ($q) use ($crag_to) {
                    return $q->where('crown_angle', '<=', $crag_to);
                });

                $result_query->when((isset($pv_from) && $pv_from > 0), function ($q) use ($pv_from) {
                    return $q->where('pavilion_depth', '>=', $pv_from);
                });
                $result_query->when((isset($pv_to) && $pv_to > 0), function ($q) use ($pv_to) {
                    return $q->where('pavilion_depth', '<=', $pv_to);
                });

                $result_query->when((isset($pvag_from) && $pvag_from > 0), function ($q) use ($pvag_from) {
                    return $q->where('pavilion_angle', '>=', $pvag_from);
                });
                $result_query->when((isset($pvag_to) && $pvag_to > 0), function ($q) use ($pvag_to) {
                    return $q->where('pavilion_angle', '<=', $pvag_to);
                });


                // if($this->input->post('min_price') >= '0' && $this->input->post('max_price') < '1000000.0')
                // {	$price ="net_dollar BETWEEN least(".$this->input->post('min_price')." , ".$this->input->post('max_price').") AND greatest(".$this->input->post('min_price').", ".$this->input->post('max_price').")";
                // 	$this->db->where($price);
                // }s

                // if(!empty($this->input->post('location')))
                // {
                // 	$location =$this->input->post('location');
                // 	$data2="";
                // 	$tmp=explode(",",$location);
                // 	foreach($tmp as $t)
                // 	{
                // 		$data2.= "$t,";
                // 	}
                // 	$data2s = trim($data2,",");
                // 	$this->db->where_in('country',explode(",",$data2s));
                // }

                // if(!empty($this->input->post('eye_clean')))
                // {
                // 	$main_query_eye="";
                // 	$chk = explode(",",$this->input->post('eye_clean'));
                // 	if(in_array("YES",$chk))
                // 	{
                // 		$main_query_eye = "(EyeC = 'YES') ";
                // 		$this->db->where($main_query_eye);
                // 	}

                // 	if(in_array("NO",$chk))
                // 	{
                // 		$main_query_eye = "(EyeC != 'YES') ";
                // 		$this->db->where($main_query_eye);
                // 	}
                // }

                // if(!empty($this->input->post('brown')))
                // {
                // 	$this->db->where_in('brown',explode(",",$this->input->post('brown')));
                // }
                // if(!empty($this->input->post('green')))
                // {
                // 	$this->db->where_in('green',explode(",",$this->input->post('green')));
                // }
                // if(!empty($this->input->post('milky')))
                // {
                // 	$this->db->where_in('Milky',explode(",",$this->input->post('milky')));
                // }

                // if(!empty($this->input->post('imagedetails')))
                // {
                // 	$imagedetails =$this->input->post('imagedetails');
                // 	$tmp=explode(",",$imagedetails);
                // 	foreach($tmp as $t)
                // 	{
                // 		if($t == "ALL"){
                // 			$this->db->where('aws_image !=','');
                // 		}
                // 		if($t == "IMAGE"){
                // 			$this->db->where('aws_image !=','');
                // 		}
                // 		if($t == "VIDEO"){
                // 			$this->db->where('video !=','');
                // 		}
                // 		if($t == "HA"){
                // 			$this->db->where('aws_heart !=','');
                // 		}
                // 		if($t == "ASSET"){
                // 			$this->db->where('aws_asset !=','');
                // 		}
                // 	}
                // }
            }

            $result_query->where('location', 1);
            $result_query->where('status', '0');
            $diamond_data = $result_query->where('is_delete', 0)->get();
        }

        $filename = date('Y-m-d-His').'-Natural.csv';
        if($request->supplier_name == "true")
        {
            $result = Excel::store(new DiamondExportSupplier($diamond_data, $customer_id), $filename);
        }
        else
        {
            $result = Excel::store(new DiamondExport($diamond_data, $customer_id), $filename);
        }

		$json["file_name"] = $filename;
		echo json_encode($json);
    }


    public function diamondNaturalSearch(Request $request)
    {
        // DB::enableQueryLog();
        // dd($request->all());
        $result_query = DiamondNatural::select('*', DB::raw('(SELECT pricechange FROM markup_setting WHERE (net_dollar BETWEEN `min_range` and max_range)) as aditional_discount'),
			DB::raw("(SELECT caratprice FROM rap_list WHERE IF(`shape_name` = 'ROUND', shape = 'ROUND', shape != 'ROUND') AND color_name = `color` AND clarity_name = IF(`clarity` = 'FL','IF', `clarity`) AND low_size <= carat AND high_size >= carat) as raprate"))
		->where('carat', '>', 0.08);
		// ->where('orignal_rate','>',50);

		if(!empty($request->stoneid) && $request->stoneid != 'undefined')
		{
			$postdata = strtoupper($request->stoneid);
			$result_query->where(function($query) use ($postdata) {
                $stoneid = str_replace('LG', '', $postdata);
                $stoneid = str_replace(' ', ',', $stoneid);

				$stoneids = explode(",", $stoneid);
				$query->orWhereIn('id', $stoneids);
				$query->orWhereIn('certificate_no', $stoneids);
                $query->orWhereIn('certificate_no', $stoneids);

                $certino = explode(",", $postdata);
                $query->orWhereIn('certificate_no', $certino);
			});
		}
		else if(!empty($request->certificateid) && $request->certificateid != 'undefined')
		{
			$postdata = $request->certificateid;
			$certificate_no = explode(",", $postdata);
			$result_query->whereIn('certificate_no', $certificate_no);
		}
		else
		{
			if(!empty($request->min_carat) && !empty($request->max_carat))
			{
				$min_carat = $request->min_carat;
				$max_carat = $request->max_carat;
				if(!empty($request->min_carat) && !empty($request->max_carat))
				{
					$result_query->where('carat', '>=', $min_carat);
				}

				if(!empty($request->min_carat) && !empty($request->max_carat))
				{
					$result_query->where('carat', '<=', $max_carat);
				}
			}
			else
			{
				$result_query->where('carat', '>', 0.08);
				$result_query->where('carat', '<', 99.99);
			}

            if(!empty($request->fancyorwhite))
			{
				$result_query->where('color', 'fancy');
				if(!empty($request->fcolor))
				{
					$data2 = "";
					$fcolor = $request->fcolor;
					$fcolor = trim($fcolor, ",");
					$tmp = explode(",", $fcolor);

                    $result_query->where(function($query) use ($tmp) {
                        foreach ($tmp as $t) {
                            $query->orWhere('fancy_color','like', '%' . $t . '%');
                        }
                    });
                }

				if(!empty($request->intesites))
				{
					$data2 = "";
					$intesites = $request->intesites;
					$intesites = trim($intesites, ",");
					$tmp = explode(",", $intesites);
                    $result_query->where(function($query) use ($tmp) {
                        foreach ($tmp as $t) {
                            $query->orWhere('fancy_intensity','like', '%' . $t . '%');
                        }
                    });
                }

				if(!empty($request->overtones))
				{
					$data2 = "";
					$overtones = $request->overtones;
					$overtones = trim($overtones, ",");
					$tmp = explode(",", $overtones);
                    $result_query->where(function($query) use ($tmp) {
                        foreach ($tmp as $t) {
                            $query->orWhere('fancy_overtone','like', '%' . $t . '%');
                        }
                    });
                }
			}
			else
			{
				$result_query->where('color', '!=', 'fancy');
				if(!empty($request->color))
				{
					$result_query->whereIn('color', explode(",", $request->color));
				}
				else
				{
					$result_query->whereIn('color', array('D','E','F','G','H','I','J','K','L','M','N','O','OP','QR','ST','UV','WX','YZ'));
				}
			}

			if(!empty($request->shape))
			{
				$result_query->whereIn('shape',explode(",",$request->shape));
			}
			if(!empty($request->clarity))
			{
				$result_query->whereIn('clarity',explode(",", $request->clarity));
			}
			if(!empty($request->cut))
			{
				$cut_arrya = explode(",", $request->cut);
				$cut_arrya[] = '';
				$result_query->whereIn('cut',$cut_arrya);
			}
			if(!empty($request->polish))
			{
				$result_query->whereIn('polish',explode(",", $request->polish));
			}
			if(!empty($request->symmetry))
			{
				$result_query->whereIn('symmetry',explode(",",$request->symmetry));
			}
			if(!empty($request->flourescence))
			{
				$result_query->whereIn('fluorescence',explode(",",$request->flourescence));
			}
			if(!empty($request->lab))
			{
				$result_query->whereIn('lab',explode(",",$request->lab));
			}

			if(!empty($request->location))
			{
				$location = $request->location;
				$data2="";
				$tmp=explode(",",$location);
				foreach($tmp as $t)
				{
					$data2.= "$t,";
				}
				$data2s = trim($data2,",");
				$result_query->whereIn('country',explode(",", $data2s));
			}

            if(!empty($request->company))
			{
				$result_query->whereIn('supplier_id',explode(",",$request->company));
			}

            if(!empty($request->c_type))
			{
				$result_query->whereIn('c_type', explode(",",$request->c_type));
			}

			$table_per_from = strip_tags(substr($request->table_per_from,0,100));
			$table_per_to = strip_tags(substr($request->table_per_to,0,100));
			$depth_per_from = strip_tags(substr($request->depth_per_from,0,100));
			$depth_per_to = strip_tags(substr($request->depth_per_to,0,100));

			$result_query->when((isset($depth_per_from) && $depth_per_from > 0), function ($q) use ($depth_per_from) {
				return $q->where('depth_per', '>=', $depth_per_from);
			});
			$result_query->when((isset($depth_per_to) && $depth_per_to > 0), function ($q) use ($depth_per_to) {
				return $q->where('depth_per', '<=', $depth_per_to);
			});

			$result_query->when((isset($table_per_from) && $table_per_from > 0), function ($q) use ($table_per_from) {
				return $q->where('table_per', '>=', $table_per_from);
			});
			$result_query->when((isset($table_per_to) && $table_per_to > 0), function ($q) use ($table_per_to) {
				return $q->where('table_per', '<=', $table_per_to);
			});

            $min_length = strip_tags(substr($request->min_length,0,100));
			$max_length = strip_tags(substr($request->max_length,0,100));
			$width_min = strip_tags(substr($request->width_min,0,100));
			$width_max = strip_tags(substr($request->width_max,0,100));
            $depth_min = strip_tags(substr($request->depth_min,0,100));
			$depth_max = strip_tags(substr($request->depth_max,0,100));

			$result_query->when((isset($min_length) && $min_length > 0), function ($q) use ($min_length) {
				return $q->where('length', '>=', $min_length);
			});
			$result_query->when((isset($max_length) && $max_length > 0), function ($q) use ($max_length) {
				return $q->where('length', '<=', $max_length);
			});

			$result_query->when((isset($width_min) && $width_min > 0), function ($q) use ($width_min) {
				return $q->where('width', '>=', $width_min);
			});
			$result_query->when((isset($width_max) && $width_max > 0), function ($q) use ($width_max) {
				return $q->where('width', '<=', $width_max);
			});

            $result_query->when((isset($depth_min) && $depth_min > 0), function ($q) use ($depth_min) {
				return $q->where('depth', '>=', $depth_min);
			});
			$result_query->when((isset($depth_max) && $depth_max > 0), function ($q) use ($depth_max) {
				return $q->where('depth', '<=', $depth_max);
			});

            $cr_from = strip_tags(substr($request->cr_from,0,100));
			$cr_to = strip_tags(substr($request->cr_to,0,100));
			$crag_from = strip_tags(substr($request->crag_from,0,100));
			$crag_to = strip_tags(substr($request->crag_to,0,100));
            $pv_from = strip_tags(substr($request->pv_from,0,100));
			$pv_to = strip_tags(substr($request->pv_to,0,100));

            $pvag_from = strip_tags(substr($request->pvag_from,0,100));
			$pvag_to = strip_tags(substr($request->pvag_to,0,100));

			$result_query->when((isset($cr_from) && $cr_from > 0), function ($q) use ($cr_from) {
				return $q->where('crown_height', '>=', $cr_from);
			});
			$result_query->when((isset($cr_to) && $cr_to > 0), function ($q) use ($cr_to) {
				return $q->where('crown_height', '<=', $cr_to);
			});

			$result_query->when((isset($crag_from) && $crag_from > 0), function ($q) use ($crag_from) {
				return $q->where('crown_angle', '>=', $crag_from);
			});
			$result_query->when((isset($crag_to) && $crag_to > 0), function ($q) use ($crag_to) {
				return $q->where('crown_angle', '<=', $crag_to);
			});

            $result_query->when((isset($pv_from) && $pv_from > 0), function ($q) use ($pv_from) {
				return $q->where('pavilion_depth', '>=', $pv_from);
			});
			$result_query->when((isset($pv_to) && $pv_to > 0), function ($q) use ($pv_to) {
				return $q->where('pavilion_depth', '<=', $pv_to);
			});

            $result_query->when((isset($pvag_from) && $pvag_from > 0), function ($q) use ($pvag_from) {
				return $q->where('pavilion_angle', '>=', $pvag_from);
			});
			$result_query->when((isset($pvag_to) && $pvag_to > 0), function ($q) use ($pvag_to) {
				return $q->where('pavilion_angle', '<=', $pvag_to);
			});

            if(!empty($request->image))
			{
				$result_query->where('image', '!=', '');
			}

            if(!empty($request->video))
			{
				$result_query->where('video', '!=', '');
			}
			// if($this->input->post('min_price') >= '0' && $this->input->post('max_price') < '1000000.0')
			// {	$price ="net_dollar BETWEEN least(".$this->input->post('min_price')." , ".$this->input->post('max_price').") AND greatest(".$this->input->post('min_price').", ".$this->input->post('max_price').")";
			// 	$this->db->where($price);
			// }

			// if(!empty($this->input->post('location')))
			// {
			// 	$location =$this->input->post('location');
			// 	$data2="";
			// 	$tmp=explode(",",$location);
			// 	foreach($tmp as $t)
			// 	{
			// 		$data2.= "$t,";
			// 	}
			// 	$data2s = trim($data2,",");
			// 	$this->db->where_in('country',explode(",",$data2s));
			// }

			// if(!empty($this->input->post('eye_clean')))
			// {
			// 	$main_query_eye="";
			// 	$chk = explode(",",$this->input->post('eye_clean'));
			// 	if(in_array("YES",$chk))
			// 	{
			// 		$main_query_eye = "(EyeC = 'YES') ";
			// 		$this->db->where($main_query_eye);
			// 	}

			// 	if(in_array("NO",$chk))
			// 	{
			// 		$main_query_eye = "(EyeC != 'YES') ";
			// 		$this->db->where($main_query_eye);
			// 	}
			// }

			// if(!empty($this->input->post('brown')))
			// {
			// 	$this->db->where_in('brown',explode(",",$this->input->post('brown')));
			// }
			// if(!empty($this->input->post('green')))
			// {
			// 	$this->db->where_in('green',explode(",",$this->input->post('green')));
			// }
			// if(!empty($this->input->post('milky')))
			// {
			// 	$this->db->where_in('Milky',explode(",",$this->input->post('milky')));
			// }

			// if(!empty($this->input->post('imagedetails')))
			// {
			// 	$imagedetails =$this->input->post('imagedetails');
			// 	$tmp=explode(",",$imagedetails);
			// 	foreach($tmp as $t)
			// 	{
			// 		if($t == "ALL"){
			// 			$this->db->where('aws_image !=','');
			// 		}
			// 		if($t == "IMAGE"){
			// 			$this->db->where('aws_image !=','');
			// 		}
			// 		if($t == "VIDEO"){
			// 			$this->db->where('video !=','');
			// 		}
			// 		if($t == "HA"){
			// 			$this->db->where('aws_heart !=','');
			// 		}
			// 		if($t == "ASSET"){
			// 			$this->db->where('aws_asset !=','');
			// 		}
			// 	}
			// }
		}

		$result_query->where('location', 1);
		$result_query->where('status', '0');
		$result_query->where('is_delete', 0);

		if(empty($request->selectcolumn) || $request->selectcolumn == "undefined")
		{
			$result_query->orderBy('carat', 'asc');
			// $order = "FIELD(C_Shape, 'ROUND','HEART','MARQUISE','PEAR','OVAL','EMERALD','CUSHION','PRINCESS','RADIANT','KT')";
			$result_query->orderBy('color', 'asc');
			// $order = "FIELD(C_Clarity, 'FL','IF','VVS1','VVS2','VS1','VS2','SI1','SI2','SI3','I1','I2')";
			// $order = "FIELD(C_Cut, 'ID','EX','VG','GD','FR','PR', '')";
			$result_query->orderBy('orignal_rate', 'asc');
		}


        $start_from = $request->page * 100;
		$result_query->limit(100)->offset($start_from);
		$result = $result_query->get();
		// dd(DB::getQueryLog());

        $data_count = $this->diamondcountnatural($request);
        $data_array = json_decode($data_count);
        $data['count'] = $data_array->count;
        $data['number'] = ceil($data_array->count / 100) + 1;

        $render_string = '';
        if (!empty($result)) {
            foreach ($result as $value) {
                //Diamond Price calculation
                $cus_discount = 0;

                $orignal_rate = $value->rate + (($value->rate * ($cus_discount)) / 100);
				$supplier_price = ($orignal_rate * $value->carat);

                $procurment_price = AppHelper::procurmentPrice($supplier_price);
                $carat_price = $procurment_price / $value->carat;

                $net_price = $value->net_dollar;
                $orignal_price = round($value->orignal_rate * $value->carat, 2);

                $color_code = '';
                // if($value->confirm_status == 1 || $value->confirm_status == 3)
                // {
                // 	$color_code = 'style="color:#D0C301"';
                // }
                // else if($value->confirm_status == 2){
                // 	$color_code = 'style="color:red"';
                // }

                $render_string .= '<tr ' . $color_code . '>'
                    . '<td><i class="fa fa-eye diamond_detail" id="' . $value->certificate_no . '"></i></td>';
                if ($value->color != "fancy") {
                    $discount_main = !empty($value->raprate) ? round(($carat_price - $value->raprate) / $value->raprate * 100, 2) : 0;

                    $render_string .= '<td><input id=' . $value->ref_no . ' name="checkbox" data-id="' . $value->id . '" data-fcolor="0" value=' . $value->certificate_no . ' data-carat=' . $value->carat . ' data-stone="1" data-cprice=' . $carat_price . ' data-price=' . $procurment_price . ' type="checkbox" class="checkbox"></td>';
                    $fancy_string = $value->color;
                    $procurment_discount = number_format($discount_main,2); //<td class="fancy">' . $value->raprate . '</td>
                } else {
                    $render_string .= '<td><input id=' . $value->ref_no . ' name="checkbox" data-id="' . $value->id . '" data-fcolor="1" value=' . $value->certificate_no . ' data-carat=' . $value->carat . ' data-stone="1" data-cprice=' . $carat_price . ' data-price=' . $procurment_price . ' type="checkbox" class="checkbox"></td>';
                    $fancy_string = $value->fancy_intensity . ' ' . $value->fancy_overtone . ' ' . $value->fancy_color;
                    $procurment_discount = ''; //<td></td>
                }

                $render_string .= '<td>' . $value->supplier_name . '</td>';
                $render_string .= '<td nowrap="nowrap">';
                if (!empty($value->image)) {
                    $render_string .= '<a href="' . $value->image . '" target="_blank" class="ms-1"><img height="22" src="' . asset("assets/images/imagesicon.png") .'" style="cursor:pointer;" title="Image"></a>';
                } else {
                    $render_string .= '<span style="width:22px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>';
                }
                if (!empty($value->video)) {
                    $render_string .= '<a href="' . $value->video . '" target="_blank" class="ms-1"><img height="20" src="' . asset("assets/images/videoicon.png") . '" style="cursor:pointer;" title="Video"></a>';
                } else {
                    $render_string .= '<span style="width:22px;">&nbsp;&nbsp;&nbsp;&nbsp;</span>';
                }

                $render_string .= '<img class="ms-1" height="20" src="'. asset("assets/images/" . strtolower($value->country) .".png") . '">';
                $render_string .= '</td>';

                $render_string .= '<td>' . $value->id . '</td>'
                    . '<td>' . $value->ref_no . '</td>'
                    . '<td>' . $value->availability . '</td>'
                    . '<td>' . $value->shape . '</td>'
                    . '<td>' . number_format($value->carat, 2) . '</td>'
                    . '<td>' . $fancy_string . '</td>'
                    . '<td>' . $value->clarity . '</td>'
                    . '<td>' . $value->cut . '</td>'
                    . '<td>' . $value->polish . '</td>'
                    . '<td>' . $value->symmetry . '</td>'
                    . '<td>' . $value->fluorescence . '</td>'
                    . '<td>' . $value->lab . '</td>';
                // . '<td><a href="' . $value->Certificate_link . '" target="_blank" style="text-decoration: underline;">' . $value->certificate_no . '</a></td>';

                if (!empty($value->Certificate_link)) {
                    $render_string .= '<td><a href="' . $value->Certificate_link . '" target="_blank" style="text-decoration: underline;">' . $value->certificate_no . '</a></td>';
                } else {
                    if ($value->lab == 'IGI') {
                        $render_string .= '<td><a href="https://www.igi.org/viewpdf.php?r=' . $value->certificate_no . '" target="_blank">' . $value->certificate_no . '</a></td>';
                    } elseif ($value->lab == 'GIA') {
                        $render_string .= '<td><a href="http://www.gia.edu/report-check?reportno=' . $value->certificate_no . '" target="_blank">' . $value->certificate_no . '</a></td>';
                    } elseif ($value->lab == 'HRD') {
                        $render_string .= '<td><a href="https://my.hrdantwerp.com/?id=34&record_number=' . $value->certificate_no . '&weight=" target="_blank">' . $value->certificate_no . '</a></td>';
                    } elseif ($value->lab == 'GCAL') {
                        $render_string .= '<td><a href="https://www.gcalusa.com/certificate-search.html?certificate_id=' . $value->certificate_no . '&weight=" target="_blank">' . $value->certificate_no . '</a></td>';
                    } else {
                        $render_string .= '<td>' . $value->certificate_no . '</td>';
                    }
                }
                $render_string .= '<td>' . $procurment_discount . '</td>';
                $render_string .= '<td>' . number_format($procurment_price, 2). '</td>';
				$render_string .= '<td>' . $net_price . '</td>';
				$render_string .= '<td>' . number_format($orignal_price, 2) . '</td>';
                $render_string .= '<td>' . number_format($value->table_per, 2) . '</td>';
				$render_string .= '<td>' . number_format($value->depth_per, 2) . '</td>';
				$render_string .= '</tr>';
            }
        } else {
            $render_string .= '<tr><td colspan="100%">No Record Found!!</td></tr>';
        }
        $data['result'] = $render_string;

		echo json_encode($data);
    }




    public function diamondLabgrown(Request $request)
    {
        $data['suppliers'] = Supplier::with('users')->whereHas('users',function($query){$query->orderBy('companyname','asc');})->where('diamond_type', 'Lab Grown')->where('stock_status','ACTIVE')->get();
        return view('admin.diamond-labgrown')->with($data);
    }

    public function diamondcountlabgrown(Request $request)
    {
        // DB::enableQueryLog();
        $result_query = DiamondLabgrown::select('id')
		->where('carat', '>', 0.08)
		->where('orignal_rate','>',50);

		if(!empty($request->stoneid) && $request->stoneid != 'undefined')
		{
			$postdata = strtoupper($request->stoneid);
			$result_query->where(function($query) use ($postdata) {
                $stoneid = str_replace('LG', '', $postdata);
                $stoneid = str_replace(' ', ',', $stoneid);

				$stoneids = explode(",", $stoneid);
				$query->orWhereIn('id', $stoneids);
				$query->orWhereIn('certificate_no', $stoneids);
                $query->orWhereIn('certificate_no', $stoneids);

                $certino = explode(",", $postdata);
                $query->orWhereIn('certificate_no', $certino);
			});
		}
		else if(!empty($request->certificateid) && $request->certificateid != 'undefined')
		{
			$postdata = $request->certificateid;
			$certificate_no = explode(",", $postdata);
			$result_query->whereIn('certificate_no', $certificate_no);
		}
		else
		{
			if(!empty($request->min_carat) && !empty($request->max_carat))
			{
				$min_carat = (float)$request->min_carat;
				$max_carat = (float)$request->max_carat;
				if(!empty($request->min_carat) && !empty($request->max_carat))
				{
					$result_query->where('carat', '>=', $min_carat);
				}

				if(!empty($request->min_carat) && !empty($request->max_carat))
				{
					$result_query->where('carat', '<=', $max_carat);
				}
			}
			else
			{
				$result_query->where('carat', '>', 0.08);
				$result_query->where('carat', '<', 99.99);
			}

            if(!empty($request->fancyorwhite))
			{
				$result_query->where('color', 'fancy');
				if(!empty($request->fcolor))
				{
					$data2 = "";
					$fcolor = $request->fcolor;
					$fcolor = trim($fcolor, ",");
					$tmp = explode(",", $fcolor);

                    $result_query->where(function($query) use ($tmp) {
                        foreach ($tmp as $t) {
                            $query->orWhere('fancy_color','like', '%' . $t . '%');
                        }
                    });
                }

				if(!empty($request->intesites))
				{
					$data2 = "";
					$intesites = $request->intesites;
					$intesites = trim($intesites, ",");
					$tmp = explode(",", $intesites);
                    $result_query->where(function($query) use ($tmp) {
                        foreach ($tmp as $t) {
                            $query->orWhere('fancy_intensity','like', '%' . $t . '%');
                        }
                    });
                }

				if(!empty($request->overtones))
				{
					$data2 = "";
					$overtones = $request->overtones;
					$overtones = trim($overtones, ",");
					$tmp = explode(",", $overtones);
                    $result_query->where(function($query) use ($tmp) {
                        foreach ($tmp as $t) {
                            $query->orWhere('fancy_overtone','like', '%' . $t . '%');
                        }
                    });
                }
			}
			else
			{
				$result_query->where('color', '!=', 'fancy');
				if(!empty($request->color))
				{
					$result_query->whereIn('color', explode(",", $request->color));
				}
				else
				{
					$result_query->whereIn('color', array('D','E','F','G','H','I','J','K','L','M','N','O','OP','QR','ST','UV','WX','YZ'));
				}
			}

			if(!empty($request->shape))
			{
				$result_query->whereIn('shape',explode(",",$request->shape));
			}
			if(!empty($request->clarity))
			{
				$result_query->whereIn('clarity',explode(",", $request->clarity));
			}
			if(!empty($request->cut))
			{
				$cut_arrya = explode(",", $request->cut);
				$cut_arrya[] = '';
				$result_query->whereIn('cut',$cut_arrya);
			}
			if(!empty($request->polish))
			{
				$result_query->whereIn('polish',explode(",", $request->polish));
			}
			if(!empty($request->symmetry))
			{
				$result_query->whereIn('symmetry',explode(",",$request->symmetry));
			}
			if(!empty($request->flourescence))
			{
				$result_query->whereIn('fluorescence',explode(",",$request->flourescence));
			}
			if(!empty($request->lab))
			{
				$result_query->whereIn('lab',explode(",",$request->lab));
			}

            if(!empty($request->c_type))
			{
				$result_query->whereIn('c_type', explode(",",$request->c_type));
			}

			if(!empty($request->location))
			{
				$location = $request->location;
				$data2="";
				$tmp=explode(",",$location);
				foreach($tmp as $t)
				{
					$data2.= "$t,";
				}
				$data2s = trim($data2,",");
				$result_query->whereIn('country',explode(",", $data2s));
			}

            if(!empty($request->company))
			{
				$result_query->whereIn('supplier_id',explode(",",$request->company));
			}

			$table_per_from = strip_tags(substr($request->table_per_from,0,100));
			$table_per_to = strip_tags(substr($request->table_per_to,0,100));
			$depth_per_from = strip_tags(substr($request->depth_per_from,0,100));
			$depth_per_to = strip_tags(substr($request->depth_per_to,0,100));

			$result_query->when((isset($depth_per_from) && $depth_per_from > 0), function ($q) use ($depth_per_from) {
				return $q->where('depth_per', '>=', $depth_per_from);
			});
			$result_query->when((isset($depth_per_to) && $depth_per_to > 0), function ($q) use ($depth_per_to) {
				return $q->where('depth_per', '<=', $depth_per_to);
			});

			$result_query->when((isset($table_per_from) && $table_per_from > 0), function ($q) use ($table_per_from) {
				return $q->where('table_per', '>=', $table_per_from);
			});
			$result_query->when((isset($table_per_to) && $table_per_to > 0), function ($q) use ($table_per_to) {
				return $q->where('table_per', '<=', $table_per_to);
			});

            $min_length = strip_tags(substr($request->min_length,0,100));
			$max_length = strip_tags(substr($request->max_length,0,100));
			$width_min = strip_tags(substr($request->width_min,0,100));
			$width_max = strip_tags(substr($request->width_max,0,100));
            $depth_min = strip_tags(substr($request->depth_min,0,100));
			$depth_max = strip_tags(substr($request->depth_max,0,100));

			$result_query->when((isset($min_length) && $min_length > 0), function ($q) use ($min_length) {
				return $q->where('length', '>=', $min_length);
			});
			$result_query->when((isset($max_length) && $max_length > 0), function ($q) use ($max_length) {
				return $q->where('length', '<=', $max_length);
			});

			$result_query->when((isset($width_min) && $width_min > 0), function ($q) use ($width_min) {
				return $q->where('width', '>=', $width_min);
			});
			$result_query->when((isset($width_max) && $width_max > 0), function ($q) use ($width_max) {
				return $q->where('width', '<=', $width_max);
			});

            $result_query->when((isset($depth_min) && $depth_min > 0), function ($q) use ($depth_min) {
				return $q->where('depth', '>=', $depth_min);
			});
			$result_query->when((isset($depth_max) && $depth_max > 0), function ($q) use ($depth_max) {
				return $q->where('depth', '<=', $depth_max);
			});

            $cr_from = strip_tags(substr($request->cr_from,0,100));
			$cr_to = strip_tags(substr($request->cr_to,0,100));
			$crag_from = strip_tags(substr($request->crag_from,0,100));
			$crag_to = strip_tags(substr($request->crag_to,0,100));
            $pv_from = strip_tags(substr($request->pv_from,0,100));
			$pv_to = strip_tags(substr($request->pv_to,0,100));

            $pvag_from = strip_tags(substr($request->pvag_from,0,100));
			$pvag_to = strip_tags(substr($request->pvag_to,0,100));

			$result_query->when((isset($cr_from) && $cr_from > 0), function ($q) use ($cr_from) {
				return $q->where('crown_height', '>=', $cr_from);
			});
			$result_query->when((isset($cr_to) && $cr_to > 0), function ($q) use ($cr_to) {
				return $q->where('crown_height', '<=', $cr_to);
			});

			$result_query->when((isset($crag_from) && $crag_from > 0), function ($q) use ($crag_from) {
				return $q->where('crown_angle', '>=', $crag_from);
			});
			$result_query->when((isset($crag_to) && $crag_to > 0), function ($q) use ($crag_to) {
				return $q->where('crown_angle', '<=', $crag_to);
			});

            $result_query->when((isset($pv_from) && $pv_from > 0), function ($q) use ($pv_from) {
				return $q->where('pavilion_depth', '>=', $pv_from);
			});
			$result_query->when((isset($pv_to) && $pv_to > 0), function ($q) use ($pv_to) {
				return $q->where('pavilion_depth', '<=', $pv_to);
			});

            $result_query->when((isset($pvag_from) && $pvag_from > 0), function ($q) use ($pvag_from) {
				return $q->where('pavilion_angle', '>=', $pvag_from);
			});
			$result_query->when((isset($pvag_to) && $pvag_to > 0), function ($q) use ($pvag_to) {
				return $q->where('pavilion_angle', '<=', $pvag_to);
			});

			// if($this->input->post('min_price') >= '0' && $this->input->post('max_price') < '1000000.0')
			// {	$price ="net_dollar BETWEEN least(".$this->input->post('min_price')." , ".$this->input->post('max_price').") AND greatest(".$this->input->post('min_price').", ".$this->input->post('max_price').")";
			// 	$result_query->where($price);
			// }

			// if(!empty($request->eye_clean))
			// {
			// 	$main_query_eye="";
			// 	$chk = explode(",",$this->input->post('eye_clean'));
			// 	if(in_array("YES",$chk))
			// 	{
			// 		$main_query_eye = "(EyeC = 'YES') ";
			// 		$result_query->where($main_query_eye);
			// 	}

			// 	if(in_array("NO",$chk))
			// 	{
			// 		$main_query_eye = "(EyeC != 'YES') ";
			// 		$result_query->where($main_query_eye);
			// 	}
			// }

			// if(!empty($this->input->post('brown')))
			// {
			// 	$result_query->where_in('brown',explode(",",$this->input->post('brown')));
			// }
			// if(!empty($this->input->post('green')))
			// {
			// 	$result_query->where_in('green',explode(",",$this->input->post('green')));
			// }
			// if(!empty($this->input->post('milky')))
			// {
			// 	$result_query->where_in('Milky',explode(",",$this->input->post('milky')));
			// }

			// if(!empty($this->input->post('imagedetails')))
			// {
			// 	$imagedetails =$this->input->post('imagedetails');
			// 	$tmp=explode(",",$imagedetails);
			// 	foreach($tmp as $t)
			// 	{
			// 		if($t == "ALL"){
			// 			$result_query->where('aws_image !=','');
			// 		}
			// 		if($t == "IMAGE"){
			// 			$result_query->where('aws_image !=','');
			// 		}
			// 		if($t == "VIDEO"){
			// 			$result_query->where('video !=','');
			// 		}
			// 		if($t == "HA"){
			// 			$result_query->where('aws_heart !=','');
			// 		}
			// 		if($t == "ASSET"){
			// 			$result_query->where('aws_asset !=','');
			// 		}
			// 	}
			// }
		}

		$result_query->where('location', 1);
		$result_query->where('status', '0');
		$result_query->where('is_delete', 0);

		if(empty($request->selectcolumn) || $request->selectcolumn == "undefined")
		{
			$result_query->orderBy('carat', 'asc');
			// $order = "FIELD(C_Shape, 'ROUND','HEART','MARQUISE','PEAR','OVAL','EMERALD','CUSHION','PRINCESS','RADIANT','KT')";
			$result_query->orderBy('color', 'asc');
			// $order = "FIELD(C_Clarity, 'FL','IF','VVS1','VVS2','VS1','VS2','SI1','SI2','SI3','I1','I2')";
			// $order = "FIELD(C_Cut, 'ID','EX','VG','GD','FR','PR', '')";
			$result_query->orderBy('orignal_rate', 'asc');
		}
		// $result_query->limit(50)->offset($start_from);
		$result = $result_query->count();
		// dd(DB::getQueryLog());
		$data['count'] = $result;
		return json_encode($data);
    }

    public function diamondLabgrownList(Request $request)
    {
        $data['suppliers'] = Supplier::with('users')->whereHas('users',function($query){$query->orderBy('companyname','asc');})->where('diamond_type', 'Lab Grown')->where('stock_status','ACTIVE')->get();
        return view('admin.diamond-labgrown-list')->with($data);
    }

    public function diamondLabgrownSearch(Request $request)
    {
        // DB::enableQueryLog();
        $result_query = DiamondLabgrown::select('*', DB::raw('(SELECT pricechange FROM markup_setting WHERE (net_dollar BETWEEN `min_range` and max_range)) as aditional_discount'),
			DB::raw("(SELECT caratprice FROM rap_list WHERE IF(`shape_name` = 'ROUND', shape = 'ROUND', shape != 'ROUND') AND color_name = `color` AND clarity_name = IF(`clarity` = 'FL','IF', `clarity`) AND low_size <= carat AND high_size >= carat) as raprate"))
		->where('carat', '>', 0.08);
		// ->where('orignal_rate','>',50);

		if(!empty($request->sorting) && $request->sorting != 'undefined'){
			$sorting = $request->sorting;
			$order = $request->order;
			$result_query->orderBy($sorting,$order);
		}
		if(!empty($request->stoneid) && $request->stoneid != 'undefined')
		{
			$postdata = strtoupper($request->stoneid);
			$result_query->where(function($query) use ($postdata) {
                $stoneid = str_replace('LG', '', $postdata);
                $stoneid = str_replace(' ', ',', $stoneid);

				$stoneids = explode(",", $stoneid);
				$query->orWhereIn('id', $stoneids);
				$query->orWhereIn('certificate_no', $stoneids);
                $query->orWhereIn('certificate_no', $stoneids);

                $certino = explode(",", $postdata);
                $query->orWhereIn('certificate_no', $certino);
			});
		}
		else if(!empty($request->certificateid) && $request->certificateid != 'undefined')
		{
			$postdata = $request->certificateid;
			$certificate_no = explode(",", $postdata);
			$result_query->whereIn('certificate_no', $certificate_no);
		}
		else
		{
			if(!empty($request->min_carat) && !empty($request->max_carat))
			{
				$min_carat = (float)$request->min_carat;
				$max_carat = (float)$request->max_carat;
				if(!empty($request->min_carat) && !empty($request->max_carat))
				{
					$result_query->where('carat', '>=', $min_carat);
				}

				if(!empty($request->min_carat) && !empty($request->max_carat))
				{
					$result_query->where('carat', '<=', $max_carat);
				}
			}
			else
			{
				$result_query->where('carat', '>', 0.08);
				$result_query->where('carat', '<', 99.99);
			}

            if(!empty($request->fancyorwhite))
			{
				$result_query->where('color', 'fancy');
				if(!empty($request->fcolor))
				{
					$data2 = "";
					$fcolor = $request->fcolor;
					$fcolor = trim($fcolor, ",");
					$tmp = explode(",", $fcolor);

                    $result_query->where(function($query) use ($tmp) {
                        foreach ($tmp as $t) {
                            $query->orWhere('fancy_color','like', '%' . $t . '%');
                        }
                    });
                }

				if(!empty($request->intesites))
				{
					$data2 = "";
					$intesites = $request->intesites;
					$intesites = trim($intesites, ",");
					$tmp = explode(",", $intesites);
                    $result_query->where(function($query) use ($tmp) {
                        foreach ($tmp as $t) {
                            $query->orWhere('fancy_intensity','like', '%' . $t . '%');
                        }
                    });
                }

				if(!empty($request->overtones))
				{
					$data2 = "";
					$overtones = $request->overtones;
					$overtones = trim($overtones, ",");
					$tmp = explode(",", $overtones);
                    $result_query->where(function($query) use ($tmp) {
                        foreach ($tmp as $t) {
                            $query->orWhere('fancy_overtone','like', '%' . $t . '%');
                        }
                    });
                }
			}
			else
			{
				$result_query->where('color', '!=', 'fancy');
				if(!empty($request->color))
				{
					$result_query->whereIn('color', explode(",", $request->color));
				}
				else
				{
					$result_query->whereIn('color', array('D','E','F','G','H','I','J','K','L','M','N','O','OP','QR','ST','UV','WX','YZ'));
				}
			}

			if(!empty($request->shape))
			{
				$result_query->whereIn('shape',explode(",",$request->shape));
			}
			if(!empty($request->clarity))
			{
				$result_query->whereIn('clarity',explode(",", $request->clarity));
			}
			if(!empty($request->cut))
			{
				$cut_arrya = explode(",", $request->cut);
				$cut_arrya[] = '';
				$result_query->whereIn('cut',$cut_arrya);
			}
			if(!empty($request->polish))
			{
				$result_query->whereIn('polish',explode(",", $request->polish));
			}
			if(!empty($request->symmetry))
			{
				$result_query->whereIn('symmetry',explode(",",$request->symmetry));
			}
			if(!empty($request->flourescence))
			{
				$result_query->whereIn('fluorescence',explode(",",$request->flourescence));
			}
			if(!empty($request->lab))
			{
				$result_query->whereIn('lab',explode(",",$request->lab));
			}

            if(!empty($request->company))
			{
                $result_query->whereIn('supplier_id',explode(",",$request->company));
			}

			if(!empty($request->location))
			{
				$location = $request->location;
				$data2="";
				$tmp=explode(",",$location);
				foreach($tmp as $t)
				{
					$data2.= "$t,";
				}
				$data2s = trim($data2,",");
				$result_query->whereIn('country',explode(",", $data2s));
			}

            if(!empty($request->c_type))
			{
				$result_query->whereIn('c_type', explode(",",$request->c_type));
			}

			$table_per_from = strip_tags(substr($request->table_per_from,0,100));
			$table_per_to = strip_tags(substr($request->table_per_to,0,100));
			$depth_per_from = strip_tags(substr($request->depth_per_from,0,100));
			$depth_per_to = strip_tags(substr($request->depth_per_to,0,100));

			$result_query->when((isset($depth_per_from) && $depth_per_from > 0), function ($q) use ($depth_per_from) {
				return $q->where('depth_per', '>=', $depth_per_from);
			});
			$result_query->when((isset($depth_per_to) && $depth_per_to > 0), function ($q) use ($depth_per_to) {
				return $q->where('depth_per', '<=', $depth_per_to);
			});

			$result_query->when((isset($table_per_from) && $table_per_from > 0), function ($q) use ($table_per_from) {
				return $q->where('table_per', '>=', $table_per_from);
			});
			$result_query->when((isset($table_per_to) && $table_per_to > 0), function ($q) use ($table_per_to) {
				return $q->where('table_per', '<=', $table_per_to);
			});

            $min_length = strip_tags(substr($request->min_length,0,100));
			$max_length = strip_tags(substr($request->max_length,0,100));
			$width_min = strip_tags(substr($request->width_min,0,100));
			$width_max = strip_tags(substr($request->width_max,0,100));
            $depth_min = strip_tags(substr($request->depth_min,0,100));
			$depth_max = strip_tags(substr($request->depth_max,0,100));

			$result_query->when((isset($min_length) && $min_length > 0), function ($q) use ($min_length) {
				return $q->where('length', '>=', $min_length);
			});
			$result_query->when((isset($max_length) && $max_length > 0), function ($q) use ($max_length) {
				return $q->where('length', '<=', $max_length);
			});

			$result_query->when((isset($width_min) && $width_min > 0), function ($q) use ($width_min) {
				return $q->where('width', '>=', $width_min);
			});
			$result_query->when((isset($width_max) && $width_max > 0), function ($q) use ($width_max) {
				return $q->where('width', '<=', $width_max);
			});

            $result_query->when((isset($depth_min) && $depth_min > 0), function ($q) use ($depth_min) {
				return $q->where('depth', '>=', $depth_min);
			});
			$result_query->when((isset($depth_max) && $depth_max > 0), function ($q) use ($depth_max) {
				return $q->where('depth', '<=', $depth_max);
			});

            $cr_from = strip_tags(substr($request->cr_from,0,100));
			$cr_to = strip_tags(substr($request->cr_to,0,100));
			$crag_from = strip_tags(substr($request->crag_from,0,100));
			$crag_to = strip_tags(substr($request->crag_to,0,100));
            $pv_from = strip_tags(substr($request->pv_from,0,100));
			$pv_to = strip_tags(substr($request->pv_to,0,100));

            $pvag_from = strip_tags(substr($request->pvag_from,0,100));
			$pvag_to = strip_tags(substr($request->pvag_to,0,100));

			$result_query->when((isset($cr_from) && $cr_from > 0), function ($q) use ($cr_from) {
				return $q->where('crown_height', '>=', $cr_from);
			});
			$result_query->when((isset($cr_to) && $cr_to > 0), function ($q) use ($cr_to) {
				return $q->where('crown_height', '<=', $cr_to);
			});

			$result_query->when((isset($crag_from) && $crag_from > 0), function ($q) use ($crag_from) {
				return $q->where('crown_angle', '>=', $crag_from);
			});
			$result_query->when((isset($crag_to) && $crag_to > 0), function ($q) use ($crag_to) {
				return $q->where('crown_angle', '<=', $crag_to);
			});

            $result_query->when((isset($pv_from) && $pv_from > 0), function ($q) use ($pv_from) {
				return $q->where('pavilion_depth', '>=', $pv_from);
			});
			$result_query->when((isset($pv_to) && $pv_to > 0), function ($q) use ($pv_to) {
				return $q->where('pavilion_depth', '<=', $pv_to);
			});

            $result_query->when((isset($pvag_from) && $pvag_from > 0), function ($q) use ($pvag_from) {
				return $q->where('pavilion_angle', '>=', $pvag_from);
			});
			$result_query->when((isset($pvag_to) && $pvag_to > 0), function ($q) use ($pvag_to) {
				return $q->where('pavilion_angle', '<=', $pvag_to);
			});

			// if($this->input->post('min_price') >= '0' && $this->input->post('max_price') < '1000000.0')
			// {	$price ="net_dollar BETWEEN least(".$this->input->post('min_price')." , ".$this->input->post('max_price').") AND greatest(".$this->input->post('min_price').", ".$this->input->post('max_price').")";
			// 	$result_query->where($price);
			// }s

			// if(!empty($this->input->post('location')))
			// {
			// 	$location =$this->input->post('location');
			// 	$data2="";
			// 	$tmp=explode(",",$location);
			// 	foreach($tmp as $t)
			// 	{
			// 		$data2.= "$t,";
			// 	}
			// 	$data2s = trim($data2,",");
			// 	$result_query->where_in('country',explode(",",$data2s));
			// }

			// if(!empty($this->input->post('eye_clean')))
			// {
			// 	$main_query_eye="";
			// 	$chk = explode(",",$this->input->post('eye_clean'));
			// 	if(in_array("YES",$chk))
			// 	{
			// 		$main_query_eye = "(EyeC = 'YES') ";
			// 		$result_query->where($main_query_eye);
			// 	}

			// 	if(in_array("NO",$chk))
			// 	{
			// 		$main_query_eye = "(EyeC != 'YES') ";
			// 		$result_query->where($main_query_eye);
			// 	}
			// }

			// if(!empty($this->input->post('brown')))
			// {
			// 	$result_query->where_in('brown',explode(",",$this->input->post('brown')));
			// }
			// if(!empty($this->input->post('green')))
			// {
			// 	$result_query->where_in('green',explode(",",$this->input->post('green')));
			// }
			// if(!empty($this->input->post('milky')))
			// {
			// 	$result_query->where_in('Milky',explode(",",$this->input->post('milky')));
			// }

			// if(!empty($this->input->post('imagedetails')))
			// {
			// 	$imagedetails =$this->input->post('imagedetails');
			// 	$tmp=explode(",",$imagedetails);
			// 	foreach($tmp as $t)
			// 	{
			// 		if($t == "ALL"){
			// 			$result_query->where('aws_image !=','');
			// 		}
			// 		if($t == "IMAGE"){
			// 			$result_query->where('aws_image !=','');
			// 		}
			// 		if($t == "VIDEO"){
			// 			$result_query->where('video !=','');
			// 		}
			// 		if($t == "HA"){
			// 			$result_query->where('aws_heart !=','');
			// 		}
			// 		if($t == "ASSET"){
			// 			$result_query->where('aws_asset !=','');
			// 		}
			// 	}
			// }
		}

		$result_query->where('location', 1);
		$result_query->where('status', '0');
		$result_query->where('is_delete', 0);

		if(empty($request->selectcolumn) || $request->selectcolumn == "undefined")
		{
			$result_query->orderBy('carat', 'asc');
			// $order = "FIELD(C_Shape, 'ROUND','HEART','MARQUISE','PEAR','OVAL','EMERALD','CUSHION','PRINCESS','RADIANT','KT')";
			$result_query->orderBy('color', 'asc');
			// $order = "FIELD(C_Clarity, 'FL','IF','VVS1','VVS2','VS1','VS2','SI1','SI2','SI3','I1','I2')";
			// $order = "FIELD(C_Cut, 'ID','EX','VG','GD','FR','PR', '')";
			$result_query->orderBy('orignal_rate', 'asc');
		}

        $start_from = $request->page * 100;
		$result_query->limit(100)->offset($start_from);
		$result = $result_query->get();
		// dd(DB::getQueryLog());

        $data_count = $this->diamondcountlabgrown($request);
        $data_array = json_decode($data_count);
        $data['count'] = $data_array->count;
        $data['number'] = ceil($data_array->count / 100) + 1;

        $render_string = '';
        if (!empty($result)) {
            foreach ($result as $value) {
                //Diamond Price calculation
                $cus_discount = 0;

                $orignal_rate = $value->rate + (($value->rate * ($cus_discount)) / 100);
				$supplier_price = ($orignal_rate * $value->carat);

                $procurment_price = AppHelper::procurmentPrice($supplier_price);
                $carat_price = $procurment_price / $value->carat;

                $net_price = $value->net_dollar;
                $orignal_price = round($value->orignal_rate * $value->carat, 2);

                $color_code = '';
                // if($value->confirm_status == 1 || $value->confirm_status == 3)
                // {
                // 	$color_code = 'style="color:#D0C301"';
                // }
                // else if($value->confirm_status == 2){
                // 	$color_code = 'style="color:red"';
                // }

                $render_string .= '<tr ' . $color_code . '>'
                    . '<td><i class="fa fa-eye diamond_detail" id="' . $value->certificate_no . '"></i></td>';
                if ($value->color != "fancy") {
                    $discount_main = !empty($value->raprate) ? round(($carat_price - $value->raprate) / $value->raprate * 100, 2) : 0;

                    $render_string .= '<td><input id=' . $value->ref_no . ' name="checkbox" data-fcolor="0" data-id="' . $value->id . '" value=' . $value->certificate_no . ' data-carat=' . $value->carat . ' data-stone="1" data-cprice=' . $carat_price . ' data-price=' . $procurment_price . ' type="checkbox" class="checkbox"></td>';
                    $fancy_string = $value->color;
                    $procurment_discount = number_format($discount_main,2);
                } else {
                    $render_string .= '<td><input id=' . $value->ref_no . ' name="checkbox" data-fcolor="1" data-id=' . $value->id . ' value=' . $value->certificate_no . ' data-carat=' . $value->carat . ' data-stone="1" data-cprice=' . $carat_price . ' data-price=' . $procurment_price . ' type="checkbox" class="checkbox"></td>';
                    $fancy_string = $value->fancy_intensity . ' ' . $value->fancy_overtone . ' ' . $value->fancy_color;
                    $procurment_discount = '';
                }

                $render_string .= '<td>' . $value->supplier_name . '</td>';
                $render_string .= '<td nowrap="nowrap">';
                if (!empty($value->image)) {
                    $render_string .= '<a href="' . $value->image . '" target="_blank" class="ms-1"><img height="22" src="' . asset("assets/images/imagesicon.png") .'" style="cursor:pointer;" title="Image"></a>';
                } else {
                    $render_string .= '<span style="width:22px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>';
                }
                if (!empty($value->video)) {
                    $render_string .= '<a href="' . $value->video . '" target="_blank" class="ms-1"><img height="20" src="' . asset("assets/images/videoicon.png") . '" style="cursor:pointer;" title="Video"></a>';
                } else {
                    $render_string .= '<span style="width:22px;">&nbsp;&nbsp;&nbsp;&nbsp;</span>';
                }

                $render_string .= '<img class="ms-1" height="20" src="'. asset("assets/images/" . strtolower($value->country) .".png") . '">';
                $render_string .= '</td>';

                $render_string .= '<td>LG' . $value->id . '</td>'
                    . '<td>' . $value->ref_no . '</td>'
                    . '<td>' . $value->availability . '</td>'
                    . '<td>' . $value->shape . '</td>'
                    . '<td>' . number_format($value->carat, 2) . '</td>'
                    . '<td>' . $fancy_string . '</td>'
                    . '<td>' . $value->clarity . '</td>'
                    . '<td>' . $value->cut . '</td>'
                    . '<td>' . $value->polish . '</td>'
                    . '<td>' . $value->symmetry . '</td>'
                    . '<td>' . $value->fluorescence . '</td>'
                    . '<td>' . $value->lab . '</td>';
                // . '<td><a href="' . $value->Certificate_link . '" target="_blank" style="text-decoration: underline;">' . $value->certificate_no . '</a></td>';

                if (!empty($value->Certificate_link)) {
                    $render_string .= '<td><a href="' . $value->Certificate_link . '" target="_blank" style="text-decoration: underline;">' . $value->certificate_no . '</a></td>';
                } else {
                    if ($value->lab == 'IGI') {
                        $render_string .= '<td><a href="https://www.igi.org/viewpdf.php?r=' . $value->certificate_no . '" target="_blank">' . $value->certificate_no . '</a></td>';
                    } elseif ($value->lab == 'GIA') {
                        $render_string .= '<td><a href="http://www.gia.edu/report-check?reportno=' . $value->certificate_no . '" target="_blank">' . $value->certificate_no . '</a></td>';
                    } elseif ($value->lab == 'HRD') {
                        $render_string .= '<td><a href="https://my.hrdantwerp.com/?id=34&record_number=' . $value->certificate_no . '&weight=" target="_blank">' . $value->certificate_no . '</a></td>';
                    } elseif ($value->lab == 'GCAL') {
                        $render_string .= '<td><a href="https://www.gcalusa.com/certificate-search.html?certificate_id=' . $value->certificate_no . '&weight=" target="_blank">' . $value->certificate_no . '</a></td>';
                    } else {
                        $render_string .= '<td>' . $value->certificate_no . '</td>';
                    }
                }
                $render_string .= '<td>' . $procurment_discount . '</td>';
                $render_string .= '<td>' . number_format($procurment_price, 2). '</td>';
				$render_string .= '<td>' . $net_price . '</td>';
				$render_string .= '<td>' . number_format($orignal_price, 2) . '</td>';
                $render_string .= '<td>' . number_format($value->table_per, 2) . '</td>';
				$render_string .= '<td>' . number_format($value->depth_per, 2) . '</td>';
				$render_string .= '</tr>';
            }
        } else {
            $render_string .= '<tr><td colspan="100%">No Record Found!!</td></tr>';
        }
        $data['result'] = $render_string;

		echo json_encode($data);
    }


	public function diamondViewDetail(Request $request)
	{
		$detail = '';
		$data = array();
		$certificate_no = $request->certificate_no;
		$diamond_type = $request->diamond_type;
		$table = ($diamond_type == "L") ? 'diamond_labgrown' : 'diamond_natural';

		// DB::enableQueryLog();
		$value = DB::table($table)->select('*', DB::raw('(SELECT pricechange FROM markup_setting WHERE (net_dollar BETWEEN `min_range` and max_range)) as aditional_discount'),
			DB::raw("(SELECT caratprice FROM rap_list WHERE IF(`shape_name` = 'ROUND', shape = 'ROUND', shape != 'ROUND') AND color_name = `color` AND clarity_name = IF(`clarity` = 'FL','IF', `clarity`) AND low_size <= carat AND high_size >= carat) as raprate"))
			->where('certificate_no', $certificate_no)->first();

		if(!empty($value)) {

			// $carat_price = $value->rate + (($value->rate * ($value->aditional_discount)) / 100 );
			// $net_price = round($carat_price * $carat, 2);
			// $discount_main = !empty($value->raprate) ? round(($carat_price - $value->raprate) / $value->raprate * 100, 2) : 0;

			$carat = $value->carat;

			$base_price = $value->rate + (($value->rate * ($value->aditional_discount)) / 100);
			$carat_price = round($base_price, 3);
			$t_net_price = round($carat_price * $carat, 2);
			$net_price = $value->net_dollar; //round($carat_price * $carat, 2);
			$orignal_price = round($value->orignal_rate * $carat, 2);

			$discount_main = !empty($value->raprate) ? round(($carat_price - $value->raprate) / $value->raprate * 100, 2) : 0;
			$a_discount_main = !empty($value->raprate) ? round(($value->rate - $value->raprate) / $value->raprate * 100, 2) : 0;
			$buy_discount = !empty($value->raprate) ? round(($value->orignal_rate - $value->raprate) / $value->raprate * 100, 2) : 0;

			$color = $value->color;
			// $color_detail = '';
			// if ($color == 'fancy'){
			// 	$color_detail = 'intensity overtone color';
			// }else{
			// 	$color_detail = $value->C_Color;
			// }

			$detail .= '<div class="modal-dialog modal-dialog-centered mw-900px"><div class="modal-content">
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
								<div class="col-12">
									<div class="row mb-1">
										<div class="col-md-6"><span class="fw-bold text-dark">Last updated Date</span> : ' . $value->updated_at . '</div>
										<div class="col-md-6"><span class="fw-bold text-dark">Purchase Manager</span> : </div>
									</div>
								</div>
								<div class="col-12">
									<div class="row mb-1">
										<div class="col-md-4"><span class="fw-bold text-dark">SKU</span> : ' . $value->id . '</div>
										<div class="col-md-4"><span class="fw-bold text-dark">Certificate</span> : ' . $value->certificate_no . '</div>
										<div class="col-md-4"><span class="fw-bold text-dark">Shape</span> : ' . $value->shape . '</div>
									</div>
								</div>
								<div class="col-12">
									<div class="row mb-1">
										<div class="col-md-4"><span class="fw-bold text-dark">Color</span> : ' . $value->color . '</div>
										<div class="col-md-4"><span class="fw-bold text-dark">Clarity</span> : ' . $value->clarity . '</div>
										<div class="col-md-4"><span class="fw-bold text-dark">Carat</span> : ' . number_format($carat,2) . '</div>
									</div>
								</div>
								<div class="col-12">
									<div class="row">
										<div class="col-md-4"><span class="fw-bold text-dark">Cut</span> : ' . $value->cut . '</div>
										<div class="col-md-4"><span class="fw-bold text-dark">Polish</span> : ' . $value->polish . '</div>
										<div class="col-md-4"><span class="fw-bold text-dark">Symmetry</span> : ' . $value->symmetry . '</div>
									</div>
								</div>
								<div class="col-12">
									<div class="row mb-2">
										<div class="col-md-4"><span class="fw-bold text-dark">Fluorescence</span> : ' . $value->fluorescence . '</div>
									</div>
								</div>
								<div class="col-12">
									<h4 class="modal-title">Fancy Color:</h4>
									<hr class="my-1">
								</div>
								<div class="col-12">
									<div class="row mb-2">
										<div class="col-md-4"><span class="fw-bold text-dark">Fancy color</span> : ' . $value->fancy_color . '</div>
										<div class="col-md-4"><span class="fw-bold text-dark">Fancy intensity</span> : ' . $value->fancy_intensity . '</div>
										<div class="col-md-4"><span class="fw-bold text-dark">Fancy overtone</span> : ' . $value->fancy_overtone . '</div>
									</div>
								</div>

								<div class="col-12">
									<h4 class="modal-title">Extra detail:</h4>
									<hr class="my-1">
								</div>
								<div class="col-12">
									<div class="row">
										<div class="col-md-4"><span class="fw-bold text-dark">Measurement</span> : ' . number_format($value->length, 2) . '*' . number_format($value->width, 2) . '*' . number_format($value->depth, 2) . '</div>
										<div class="col-md-4"><span class="fw-bold text-dark">Table %</span> : ' . number_format($value->table_per, 2) . '</div>
										<div class="col-md-4"><span class="fw-bold text-dark">Depth %</span> : ' . number_format($value->depth_per, 2) . '</div>
									</div>
								</div>
								<div class="col-12">
									<div class="row">
										<div class="col-md-4"><span class="fw-bold text-dark">C.Height</span> : ' . number_format($value->crown_height, 2) . '</div>
										<div class="col-md-4"><span class="fw-bold text-dark">C.Angle</span> : ' . number_format($value->crown_angle, 2) . '</div>
										<div class="col-md-4"><span class="fw-bold text-dark">P.Height</span> : ' . number_format($value->pavilion_depth, 2) . '</div>
									</div>
								</div>
								<div class="col-12">
									<div class="row">
										<div class="col-md-4"><span class="fw-bold text-dark">P.Angle</span> : ' . number_format($value->pavilion_angle, 2) . '</div>
										<div class="col-md-4"><span class="fw-bold text-dark">Eye Clean</span> : ' . $value->eyeclean . '</div>
										<div class="col-md-4"><span class="fw-bold text-dark">Key to Symbol</span> : ' . $value->key_symbols . '</div>
									</div>
								</div>
								<div class="col-12">
									<div class="row">
										<div class="col-md-4"><span class="fw-bold text-dark">Country</span> : ' . $value->country . '</div>
										<div class="col-md-4"><span class="fw-bold text-dark">City</span> : ' . $value->city . '</div>
									</div>
								</div>
								<div class="col-12">
									<div class="row mb-2">
										<div class="col-md-4"><span class="fw-bold text-dark">Milky</span> : ' . $value->milky . '</div>
										<div class="col-md-4"><span class="fw-bold text-dark">Luster</span> : ' . $value->luster . '</div>
										<div class="col-md-4"><span class="fw-bold text-dark">Shade</span> : ' . $value->shade . '</div>
									</div>
								</div>
                                <div class="col-12">
									<div class="row mb-2">
										<div class="col-md-4"><span class="fw-bold text-dark">Treatment</span> : ' . $value->c_type . '</div>
                                        <div class="col-md-4"><span class="fw-bold text-dark">Ratio</span> : '.number_format( (!empty($value->length) && !empty($value->width)) ? $value->length/$value->width : 0, 2).'</div>
									</div>
								</div>
								<div class="col-12">
									<h4 class="modal-title">Price Detail:</h4>
									<hr class="my-1">
								</div>
								<div class="col-12">
									<div class="row mb-2">
										<div class="col-md-4"><span class="fw-bold text-dark">Rap</span> : ' . (($color != 'fancy') ?  $value->raprate : 0) . '</div>
									</div>
								</div>';

					if (Auth::user()->user_type == 1) {
						$detail .= '<div class="col-12">
										<h4 class="modal-title">Price:</h4>
										<hr class="my-1">
									</div>
									<div class="col-12">
										<div class="row mb-3">
											<div class="col-md-4"><span class="fw-bold text-dark">Per Carat Price</span> : ' . number_format($carat_price, 2) . '</div>
											<div class="col-md-4"><span class="fw-bold text-dark">Net Price</span> : ' . number_format($t_net_price, 2) . '</div>
											<div class="col-md-4"><span class="fw-bold text-dark">Discount (%)</span> : ' . (($color != 'fancy') ?  $discount_main : 0) . '</div>
										</div>
									</div>
									<div class="col-12">
										<h4 class="modal-title">Actual Price:</h4>
										<hr class="my-1">
									</div>
									<div class="col-12">
										<div class="row mb-3">
											<div class="col-md-4"><span class="fw-bold text-dark">Per Carat Price</span> : ' . number_format($value->rate, 2) . '</div>
											<div class="col-md-4"><span class="fw-bold text-dark">Net Price</span> : ' . number_format($net_price, 2) . '</div>
											<div class="col-md-4"><span class="fw-bold text-dark">Discount (%)</span> : ' . (($color != 'fancy') ?  number_format($a_discount_main, 2) : 0) . '</div>
										</div>
									</div>

									<div class="col-12">
										<h4 class="modal-title">Original Price:</h4>
										<hr class="my-1">
									</div>
									<div class="col-12">
										<div class="row mb-3">
											<div class="col-md-4"><span class="fw-bold text-dark">Per Carat Price</span> : ' . number_format($value->orignal_rate, 2) . '</div>
											<div class="col-md-4"><span class="fw-bold text-dark">Net Price</span> : ' . number_format(($value->orignal_rate * $value->carat), 2) . '</div>
											<div class="col-md-4"><span class="fw-bold text-dark">Discount (%)</span> : ' . (($color != 'fancy') ?  number_format($buy_discount, 2) : 0) . '</div>
										</div>
									</div>';
					} else {
						$detail .= '<div class="col-12">
										<h4 class="modal-title">Price:</h4>
										<hr class="my-1">
									</div>
									<div class="col-12">
										<div class="row mb-3">
											<div class="col-md-4"><span class="fw-bold text-dark">Per Carat Price</span> : ' . number_format($carat_price, 2) . '</div>
											<div class="col-md-4"><span class="fw-bold text-dark">Net Price</span> : ' . number_format($t_net_price, 2) . '</div>
											<div class="col-md-4"><span class="fw-bold text-dark">Discount (%)</span> : ' . (($color != 'fancy') ?  number_format($discount_main, 2) : 0) . '</div>
										</div>
									</div>';
					}
			$detail .= '</div></div>
							<div class="modal-footer text-center">
								<button type="button" class="btn btn-success btn-embossed bnt-square" data-bs-dismiss="modal"><i class="fa fa-check"></i> Ok</button>
							</div>

                    </div>';
			$data['success'] = $detail;
		}
		echo json_encode($data);
	}

    public function UnloadedNaturalList(Request $request)
    {
        return view('admin.unloaded.natural-unloaded');
    }

    public function UnloadedNaturalListSearch(Request $request)
    {
        // DB::enableQueryLog();
        $result_query = DiamondNatural::select('*', DB::raw('(SELECT pricechange FROM markup_setting WHERE (net_dollar BETWEEN `min_range` and max_range)) as aditional_discount'),
			DB::raw("(SELECT caratprice FROM rap_list WHERE IF(`shape_name` = 'ROUND', shape = 'ROUND', shape != 'ROUND') AND color_name = `color` AND clarity_name = IF(`clarity` = 'FL','IF', `clarity`) AND low_size <= carat AND high_size >= carat) as raprate"))
		->where('carat', '>', 0.08)
		->where('orignal_rate','>',50);

		if(!empty($request->stoneid) && $request->stoneid != 'undefined')
		{
			$postdata = strtoupper($request->stoneid);
			$stoneid = str_replace('LG', '', $postdata);
            $stoneid = str_replace(' ', ',', $postdata);
			$result_query->where(function($query) use ($stoneid) {
				$stoneids = explode(",", $stoneid);
				$query->orWhereIn('id', $stoneids);
				$query->orWhereIn('certificate_no', $stoneids);
			});
		}
		else if(!empty($request->certificateid) && $request->certificateid != 'undefined')
		{
			$postdata = $request->certificateid;
			$certificate_no = explode(",", $postdata);
			$result_query->whereIn('certificate_no', $certificate_no);
		}
		else
		{
			if(!empty($request->min_carat) && !empty($request->max_carat))
			{
				$min_carat = (float)$request->min_carat;
				$max_carat = (float)$request->max_carat;
				if(!empty($request->min_carat) && !empty($request->max_carat))
				{
					$result_query->where('carat', '>=', $min_carat);
				}

				if(!empty($request->min_carat) && !empty($request->max_carat))
				{
					$result_query->where('carat', '<=', $max_carat);
				}
			}
			else
			{
				$result_query->where('carat', '>', 0.08);
				$result_query->where('carat', '<', 99.99);
			}

            if(!empty($request->fancyorwhite))
			{
				$result_query->where('color', 'fancy');
				if(!empty($request->fcolor))
				{
					$data2 = "";
					$fcolor = $request->fcolor;
					$fcolor = trim($fcolor, ",");
					$tmp = explode(",", $fcolor);

                    $result_query->where(function($query) use ($tmp) {
                        foreach ($tmp as $t) {
                            $query->orWhere('fancy_color','like', '%' . $t . '%');
                        }
                    });
                }

				if(!empty($request->intesites))
				{
					$data2 = "";
					$intesites = $request->intesites;
					$intesites = trim($intesites, ",");
					$tmp = explode(",", $intesites);
                    $result_query->where(function($query) use ($tmp) {
                        foreach ($tmp as $t) {
                            $query->orWhere('fancy_intensity','like', '%' . $t . '%');
                        }
                    });
                }

				if(!empty($request->overtones))
				{
					$data2 = "";
					$overtones = $request->overtones;
					$overtones = trim($overtones, ",");
					$tmp = explode(",", $overtones);
                    $result_query->where(function($query) use ($tmp) {
                        foreach ($tmp as $t) {
                            $query->orWhere('fancy_overtone','like', '%' . $t . '%');
                        }
                    });
                }
			}
			else
			{
				$result_query->where('color', '!=', 'fancy');
				if(!empty($request->color))
				{
					$result_query->whereIn('color', explode(",", $request->color));
				}
				else
				{
					$result_query->whereIn('color', array('D','E','F','G','H','I','J','K','L','M','N','O','OP','QR','ST','UV','WX','YZ'));
				}
			}

			if(!empty($request->shape))
			{
				$result_query->whereIn('shape',explode(",",$request->shape));
			}
			if(!empty($request->clarity))
			{
				$result_query->whereIn('clarity',explode(",", $request->clarity));
			}
			if(!empty($request->cut))
			{
				$cut_arrya = explode(",", $request->cut);
				$cut_arrya[] = '';
				$result_query->whereIn('cut',$cut_arrya);
			}
			if(!empty($request->polish))
			{
				$result_query->whereIn('polish',explode(",", $request->polish));
			}
			if(!empty($request->symmetry))
			{
				$result_query->whereIn('symmetry',explode(",",$request->symmetry));
			}
			if(!empty($request->flourescence))
			{
				$result_query->whereIn('fluorescence',explode(",",$request->flourescence));
			}
			if(!empty($request->lab))
			{
				$result_query->whereIn('lab',explode(",",$request->lab));
			}

			if(!empty($request->location))
			{
				$location = $request->location;
				$data2="";
				$tmp=explode(",",$location);
				foreach($tmp as $t)
				{
					$data2.= "$t,";
				}
				$data2s = trim($data2,",");
				$result_query->whereIn('country',explode(",", $data2s));
			}

			$table_per_from = strip_tags(substr($request->table_per_from,0,100));
			$table_per_to = strip_tags(substr($request->table_per_to,0,100));
			$depth_per_from = strip_tags(substr($request->depth_per_from,0,100));
			$depth_per_to = strip_tags(substr($request->depth_per_to,0,100));

			$result_query->when((isset($depth_per_from) && $depth_per_from > 0), function ($q) use ($depth_per_from) {
				return $q->where('depth_per', '>=', $depth_per_from);
			});
			$result_query->when((isset($depth_per_to) && $depth_per_to > 0), function ($q) use ($depth_per_to) {
				return $q->where('depth_per', '<=', $depth_per_to);
			});

			$result_query->when((isset($table_per_from) && $table_per_from > 0), function ($q) use ($table_per_from) {
				return $q->where('table_per', '>=', $table_per_from);
			});
			$result_query->when((isset($table_per_to) && $table_per_to > 0), function ($q) use ($table_per_to) {
				return $q->where('table_per', '<=', $table_per_to);
			});

            $min_length = strip_tags(substr($request->min_length,0,100));
			$max_length = strip_tags(substr($request->max_length,0,100));
			$width_min = strip_tags(substr($request->width_min,0,100));
			$width_max = strip_tags(substr($request->width_max,0,100));
            $depth_min = strip_tags(substr($request->depth_min,0,100));
			$depth_max = strip_tags(substr($request->depth_max,0,100));

			$result_query->when((isset($min_length) && $min_length > 0), function ($q) use ($min_length) {
				return $q->where('length', '>=', $min_length);
			});
			$result_query->when((isset($max_length) && $max_length > 0), function ($q) use ($max_length) {
				return $q->where('length', '<=', $max_length);
			});

			$result_query->when((isset($width_min) && $width_min > 0), function ($q) use ($width_min) {
				return $q->where('width', '>=', $width_min);
			});
			$result_query->when((isset($width_max) && $width_max > 0), function ($q) use ($width_max) {
				return $q->where('width', '<=', $width_max);
			});

            $result_query->when((isset($depth_min) && $depth_min > 0), function ($q) use ($depth_min) {
				return $q->where('depth', '>=', $depth_min);
			});
			$result_query->when((isset($depth_max) && $depth_max > 0), function ($q) use ($depth_max) {
				return $q->where('depth', '<=', $depth_max);
			});

            $cr_from = strip_tags(substr($request->cr_from,0,100));
			$cr_to = strip_tags(substr($request->cr_to,0,100));
			$crag_from = strip_tags(substr($request->crag_from,0,100));
			$crag_to = strip_tags(substr($request->crag_to,0,100));
            $pv_from = strip_tags(substr($request->pv_from,0,100));
			$pv_to = strip_tags(substr($request->pv_to,0,100));

            $pvag_from = strip_tags(substr($request->pvag_from,0,100));
			$pvag_to = strip_tags(substr($request->pvag_to,0,100));

			$result_query->when((isset($cr_from) && $cr_from > 0), function ($q) use ($cr_from) {
				return $q->where('crown_height', '>=', $cr_from);
			});
			$result_query->when((isset($cr_to) && $cr_to > 0), function ($q) use ($cr_to) {
				return $q->where('crown_height', '<=', $cr_to);
			});

			$result_query->when((isset($crag_from) && $crag_from > 0), function ($q) use ($crag_from) {
				return $q->where('crown_angle', '>=', $crag_from);
			});
			$result_query->when((isset($crag_to) && $crag_to > 0), function ($q) use ($crag_to) {
				return $q->where('crown_angle', '<=', $crag_to);
			});

            $result_query->when((isset($pv_from) && $pv_from > 0), function ($q) use ($pv_from) {
				return $q->where('pavilion_depth', '>=', $pv_from);
			});
			$result_query->when((isset($pv_to) && $pv_to > 0), function ($q) use ($pv_to) {
				return $q->where('pavilion_depth', '<=', $pv_to);
			});

            $result_query->when((isset($pvag_from) && $pvag_from > 0), function ($q) use ($pvag_from) {
				return $q->where('pavilion_angle', '>=', $pvag_from);
			});
			$result_query->when((isset($pvag_to) && $pvag_to > 0), function ($q) use ($pvag_to) {
				return $q->where('pavilion_angle', '<=', $pvag_to);
			});


			// if($this->input->post('min_price') >= '0' && $this->input->post('max_price') < '1000000.0')
			// {	$price ="net_dollar BETWEEN least(".$this->input->post('min_price')." , ".$this->input->post('max_price').") AND greatest(".$this->input->post('min_price').", ".$this->input->post('max_price').")";
			// 	$result_query->where($price);
			// }

			// if(!empty($this->input->post('location')))
			// {
			// 	$location =$this->input->post('location');
			// 	$data2="";
			// 	$tmp=explode(",",$location);
			// 	foreach($tmp as $t)
			// 	{
			// 		$data2.= "$t,";
			// 	}
			// 	$data2s = trim($data2,",");
			// 	$result_query->where_in('country',explode(",",$data2s));
			// }

			// if(!empty($this->input->post('eye_clean')))
			// {
			// 	$main_query_eye="";
			// 	$chk = explode(",",$this->input->post('eye_clean'));
			// 	if(in_array("YES",$chk))
			// 	{
			// 		$main_query_eye = "(EyeC = 'YES') ";
			// 		$result_query->where($main_query_eye);
			// 	}

			// 	if(in_array("NO",$chk))
			// 	{
			// 		$main_query_eye = "(EyeC != 'YES') ";
			// 		$result_query->where($main_query_eye);
			// 	}
			// }

			// if(!empty($this->input->post('brown')))
			// {
			// 	$result_query->where_in('brown',explode(",",$this->input->post('brown')));
			// }
			// if(!empty($this->input->post('green')))
			// {
			// 	$result_query->where_in('green',explode(",",$this->input->post('green')));
			// }
			// if(!empty($this->input->post('milky')))
			// {
			// 	$result_query->where_in('Milky',explode(",",$this->input->post('milky')));
			// }

			// if(!empty($this->input->post('imagedetails')))
			// {
			// 	$imagedetails =$this->input->post('imagedetails');
			// 	$tmp=explode(",",$imagedetails);
			// 	foreach($tmp as $t)
			// 	{
			// 		if($t == "ALL"){
			// 			$result_query->where('aws_image !=','');
			// 		}
			// 		if($t == "IMAGE"){
			// 			$result_query->where('aws_image !=','');
			// 		}
			// 		if($t == "VIDEO"){
			// 			$result_query->where('video !=','');
			// 		}
			// 		if($t == "HA"){
			// 			$result_query->where('aws_heart !=','');
			// 		}
			// 		if($t == "ASSET"){
			// 			$result_query->where('aws_asset !=','');
			// 		}
			// 	}
			// }
		}

		$result_query->where('location', 1);
		$result_query->where('status', '0');
		$result_query->where('is_delete', 1);

		if(empty($request->selectcolumn) || $request->selectcolumn == "undefined")
		{
			$result_query->orderBy('carat', 'asc');
			// $order = "FIELD(C_Shape, 'ROUND','HEART','MARQUISE','PEAR','OVAL','EMERALD','CUSHION','PRINCESS','RADIANT','KT')";
			$result_query->orderBy('color', 'asc');
			// $order = "FIELD(C_Clarity, 'FL','IF','VVS1','VVS2','VS1','VS2','SI1','SI2','SI3','I1','I2')";
			// $order = "FIELD(C_Cut, 'ID','EX','VG','GD','FR','PR', '')";
			$result_query->orderBy('orignal_rate', 'asc');
		}
        $start_from = 0;//$request->page;
		$result_query->limit(100)->offset($start_from);
		$result = $result_query->get();
		// dd(DB::getQueryLog());

        $data['count'] = count($result);

        $render_string = '';
        if (!empty($result)) {
            foreach ($result as $value) {
                //Diamond Price calculation
                $carat = $value->carat;

                $base_price = $value->rate + (($value->rate * ($value->aditional_discount)) / 100);
                $carat_price = $base_price;
                $t_net_price = round($carat_price * $carat, 2);
                $net_price = $value->net_dollar; //round($carat_price * $C_Weight, 2);
                $orignal_price = round($value->orignal_rate * $carat, 2);

                $color_code = '';
                // if($value->confirm_status == 1 || $value->confirm_status == 3)
                // {
                // 	$color_code = 'style="color:#D0C301"';
                // }
                // else if($value->confirm_status == 2){
                // 	$color_code = 'style="color:red"';
                // }

                $render_string .= '<tr ' . $color_code . '>'
                    . '<td><i class="fa fa-eye diamond_detail" id="' . $value->certificate_no . '"></i></td>';
                if ($value->color != "fancy") {
                    $discount_main = !empty($value->raprate) ? round(($carat_price - $value->raprate) / $value->raprate * 100, 2) : 0;

                    $render_string .= '<td><input id=' . $value->ref_no . ' name="checkbox" data-fcolor="0" data-id=' . $value->id . ' value=' . $value->certificate_no . ' data-carat=' . $value->carat . ' data-stone="1" data-cprice=' . $carat_price . ' data-price=' . $t_net_price . ' type="checkbox" class="checkbox"></td>';
                    $fancy_string = $value->color;
                    $discount_string = '<td class="fancy">' . number_format($discount_main,2) . '</td>'; //<td class="fancy">' . $value->raprate . '</td>
                } else {
                    $render_string .= '<td><input id=' . $value->ref_no . ' name="checkbox" data-fcolor="1" data-id=' . $value->id . ' value=' . $value->certificate_no . ' data-carat=' . $value->carat . ' data-stone="1" data-cprice=' . $carat_price . ' data-price=' . $t_net_price . ' type="checkbox" class="checkbox"></td>';
                    $fancy_string = $value->fancy_intensity . ' ' . $value->fancy_overtone . ' ' . $value->fancy_color;
                    $discount_string = '<td></td>'; //<td></td>
                }

                $render_string .= '<td>' . $value->supplier_name . '</td>';
                $render_string .= '<td nowrap="nowrap">';
                if (!empty($value->image)) {
                    $render_string .= '<a href="' . $value->image . '" target="_blank" class="ms-1"><img height="22" src="' . asset("assets/images/imagesicon.png") .'" style="cursor:pointer;" title="Image"></a>';
                } else {
                    $render_string .= '<span style="width:22px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>';
                }
                if (!empty($value->video)) {
                    $render_string .= '<a href="' . $value->video . '" target="_blank" class="ms-1"><img height="20" src="' . asset("assets/images/videoicon.png") . '" style="cursor:pointer;" title="Video"></a>';
                } else {
                    $render_string .= '<span style="width:22px;">&nbsp;&nbsp;&nbsp;&nbsp;</span>';
                }

                $render_string .= '<img class="ms-1" height="20" src="'. asset("assets/images/" . strtolower($value->country) .".png") . '">';
                $render_string .= '</td>';

                $render_string .= '<td>' . $value->id . '</td>'
                    . '<td>' . $value->ref_no . '</td>'
                    . '<td>' . $value->availability . '</td>'
                    . '<td>' . $value->shape . '</td>'
                    . '<td>' . $carat . '</td>'
                    . '<td>' . $fancy_string . '</td>'
                    . '<td>' . $value->clarity . '</td>'
                    . '<td>' . $value->cut . '</td>'
                    . '<td>' . $value->polish . '</td>'
                    . '<td>' . $value->symmetry . '</td>'
                    . '<td>' . $value->fluorescence . '</td>'
                    . '<td>' . $value->lab . '</td>';
                // . '<td><a href="' . $value->Certificate_link . '" target="_blank" style="text-decoration: underline;">' . $value->certificate_no . '</a></td>';

                if (!empty($value->Certificate_link)) {
                    $render_string .= '<td><a href="' . $value->Certificate_link . '" target="_blank" style="text-decoration: underline;">' . $value->certificate_no . '</a></td>';
                } else {
                    if ($value->lab == 'IGI') {
                        $render_string .= '<td><a href="https://www.igi.org/viewpdf.php?r=' . $value->certificate_no . '" target="_blank">' . $value->certificate_no . '</a></td>';
                    } elseif ($value->lab == 'GIA') {
                        $render_string .= '<td><a href="http://www.gia.edu/report-check?reportno=' . $value->certificate_no . '" target="_blank">' . $value->certificate_no . '</a></td>';
                    } elseif ($value->lab == 'HRD') {
                        $render_string .= '<td><a href="https://my.hrdantwerp.com/?id=34&record_number=' . $value->certificate_no . '&weight=" target="_blank">' . $value->certificate_no . '</a></td>';
                    } elseif ($value->lab == 'GCAL') {
                        $render_string .= '<td><a href="https://www.gcalusa.com/certificate-search.html?certificate_id=' . $value->certificate_no . '&weight=" target="_blank">' . $value->certificate_no . '</a></td>';
                    } else {
                        $render_string .= '<td>' . $value->certificate_no . '</td>';
                    }
                }
                $render_string .= $discount_string;
                $render_string .= '<td>' . $t_net_price . '</td>';
				$render_string .= '<td>' . $net_price . '</td>';
				$render_string .= '<td>' . $orignal_price . '</td>';
                $render_string .= '<td>' . $value->table_per . '</td>';
				$render_string .= '<td>' . $value->depth_per . '</td>';
				$render_string .= '</tr>';
            }
        } else {
            $render_string .= '<tr><td colspan="100%">No Record Found!!</td></tr>';
        }
        $data['result'] = $render_string;

		echo json_encode($data);
    }

    public function unloadedDownloadNatural(Request $request)
    {
        $sku = explode(',', $request->sku);
		$customer_id = ''; //Auth::user()->id;

        if(!empty($request->selected_stone))
        {
            $sku = explode(',', $request->selected_stone);

            $diamond_data =  DiamondNatural::select('id','ref_no','diamond_type','shape','carat','color', 'fancy_color', 'fancy_intensity', 'fancy_overtone', 'clarity','cut','polish','symmetry','fluorescence','lab','certificate_no','rate', 'depth_per', 'table_per', 'length', 'width', 'depth', 'crown_angle', 'crown_height', 'pavilion_angle', 'pavilion_depth', 'milky', 'eyeclean', 'shade', 'key_symbols', 'hna', 'image', 'video', 'supplier_name', 'c_type', 'country', DB::raw('(SELECT pricechange FROM markup_setting WHERE (net_dollar BETWEEN `min_range` and max_range)) as aditional_discount'),
            DB::raw("(SELECT caratprice FROM rap_list WHERE IF(`shape_name` = 'ROUND', shape = 'ROUND', shape != 'ROUND') AND color_name = `color` AND clarity_name = IF(`clarity` = 'FL','IF', `clarity`) AND low_size <= carat AND high_size >= carat) as raprate"))
            ->whereIn('id', $sku)
            ->where('is_delete', 1)->get();
        }
        else
        {
            $result_query =  DiamondNatural::select('id','ref_no','diamond_type','shape','carat','color', 'fancy_color', 'fancy_intensity', 'fancy_overtone', 'clarity','cut','polish','symmetry','fluorescence','lab','certificate_no','rate', 'depth_per', 'table_per', 'length', 'width', 'depth', 'crown_angle', 'crown_height', 'pavilion_angle', 'pavilion_depth', 'milky', 'eyeclean', 'shade', 'key_symbols', 'hna', 'image', 'video', 'supplier_name', 'c_type', 'country', DB::raw('(SELECT pricechange FROM markup_setting WHERE (net_dollar BETWEEN `min_range` and max_range)) as aditional_discount'),
            DB::raw("(SELECT caratprice FROM rap_list WHERE IF(`shape_name` = 'ROUND', shape = 'ROUND', shape != 'ROUND') AND color_name = `color` AND clarity_name = IF(`clarity` = 'FL','IF', `clarity`) AND low_size <= carat AND high_size >= carat) as raprate"));


                if(!empty($request->stoneid) && $request->stoneid != 'undefined')
                {
                    $postdata = strtoupper($request->stoneid);
                    $result_query->where(function($query) use ($postdata) {
                        $stoneid = str_replace('LG', '', $postdata);
                        $stoneid = str_replace(' ', ',', $stoneid);

                        $stoneids = explode(",", $stoneid);
                        $query->orWhereIn('id', $stoneids);
                        $query->orWhereIn('certificate_no', $stoneids);
                        $query->orWhereIn('certificate_no', $stoneids);

                        $certino = explode(",", $postdata);
                        $query->orWhereIn('certificate_no', $certino);
                    });
                }

                if(!empty($request->min_carat) && !empty($request->max_carat))
                {
                    $min_carat = (float)$request->min_carat;
                    $max_carat = (float)$request->max_carat;
                    if(!empty($request->min_carat) && !empty($request->max_carat))
                    {
                        $result_query->where('carat', '>=', $min_carat);
                    }

                    if(!empty($request->min_carat) && !empty($request->max_carat))
                    {
                        $result_query->where('carat', '<=', $max_carat);
                    }
                }
                else
                {
                    $result_query->where('carat', '>', 0.08);
                    $result_query->where('carat', '<', 99.99);
                }

                if(!empty($request->fancyorwhite))
                {
                    $result_query->where('color', 'fancy');
                    if(!empty($request->fcolor))
                    {
                        $data2 = "";
                        $fcolor = $request->fcolor;
                        $fcolor = trim($fcolor, ",");
                        $tmp = explode(",", $fcolor);

                        $result_query->where(function($query) use ($tmp) {
                            foreach ($tmp as $t) {
                                $query->orWhere('fancy_color','like', '%' . $t . '%');
                            }
                        });
                    }

                    if(!empty($request->intesites))
                    {
                        $data2 = "";
                        $intesites = $request->intesites;
                        $intesites = trim($intesites, ",");
                        $tmp = explode(",", $intesites);
                        $result_query->where(function($query) use ($tmp) {
                            foreach ($tmp as $t) {
                                $query->orWhere('fancy_intensity','like', '%' . $t . '%');
                            }
                        });
                    }

                    if(!empty($request->overtones))
                    {
                        $data2 = "";
                        $overtones = $request->overtones;
                        $overtones = trim($overtones, ",");
                        $tmp = explode(",", $overtones);
                        $result_query->where(function($query) use ($tmp) {
                            foreach ($tmp as $t) {
                                $query->orWhere('fancy_overtone','like', '%' . $t . '%');
                            }
                        });
                    }
                }
                else
                {
                    $result_query->where('color', '!=', 'fancy');
                    if(!empty($request->color))
                    {
                        $result_query->whereIn('color', explode(",", $request->color));
                    }
                    else
                    {
                        $result_query->whereIn('color', array('D','E','F','G','H','I','J','K','L','M','N','O','OP','QR','ST','UV','WX','YZ'));
                    }
                }

                if(!empty($request->shape))
                {
                    $result_query->whereIn('shape',explode(",",$request->shape));
                }
                if(!empty($request->clarity))
                {
                    $result_query->whereIn('clarity',explode(",", $request->clarity));
                }
                if(!empty($request->cut))
                {
                    $cut_arrya = explode(",", $request->cut);
                    $cut_arrya[] = '';
                    $result_query->whereIn('cut',$cut_arrya);
                }
                if(!empty($request->polish))
                {
                    $result_query->whereIn('polish',explode(",", $request->polish));
                }
                if(!empty($request->symmetry))
                {
                    $result_query->whereIn('symmetry',explode(",",$request->symmetry));
                }
                if(!empty($request->flourescence))
                {
                    $result_query->whereIn('fluorescence',explode(",",$request->flourescence));
                }
                if(!empty($request->lab))
                {
                    $result_query->whereIn('lab',explode(",",$request->lab));
                }

                if(!empty($request->location))
                {
                    $location = $request->location;
                    $data2="";
                    $tmp=explode(",",$location);
                    foreach($tmp as $t)
                    {
                        $data2.= "$t,";
                    }
                    $data2s = trim($data2,",");
                    $result_query->whereIn('country',explode(",", $data2s));
                }

                $table_per_from = strip_tags(substr($request->table_per_from,0,100));
                $table_per_to = strip_tags(substr($request->table_per_to,0,100));
                $depth_per_from = strip_tags(substr($request->depth_per_from,0,100));
                $depth_per_to = strip_tags(substr($request->depth_per_to,0,100));

                $result_query->when((isset($depth_per_from) && $depth_per_from > 0), function ($q) use ($depth_per_from) {
                    return $q->where('depth_per', '>=', $depth_per_from);
                });
                $result_query->when((isset($depth_per_to) && $depth_per_to > 0), function ($q) use ($depth_per_to) {
                    return $q->where('depth_per', '<=', $depth_per_to);
                });

                $result_query->when((isset($table_per_from) && $table_per_from > 0), function ($q) use ($table_per_from) {
                    return $q->where('table_per', '>=', $table_per_from);
                });
                $result_query->when((isset($table_per_to) && $table_per_to > 0), function ($q) use ($table_per_to) {
                    return $q->where('table_per', '<=', $table_per_to);
                });

                $min_length = strip_tags(substr($request->min_length,0,100));
                $max_length = strip_tags(substr($request->max_length,0,100));
                $width_min = strip_tags(substr($request->width_min,0,100));
                $width_max = strip_tags(substr($request->width_max,0,100));
                $depth_min = strip_tags(substr($request->depth_min,0,100));
                $depth_max = strip_tags(substr($request->depth_max,0,100));

                $result_query->when((isset($min_length) && $min_length > 0), function ($q) use ($min_length) {
                    return $q->where('length', '>=', $min_length);
                });
                $result_query->when((isset($max_length) && $max_length > 0), function ($q) use ($max_length) {
                    return $q->where('length', '<=', $max_length);
                });

                $result_query->when((isset($width_min) && $width_min > 0), function ($q) use ($width_min) {
                    return $q->where('width', '>=', $width_min);
                });
                $result_query->when((isset($width_max) && $width_max > 0), function ($q) use ($width_max) {
                    return $q->where('width', '<=', $width_max);
                });

                $result_query->when((isset($depth_min) && $depth_min > 0), function ($q) use ($depth_min) {
                    return $q->where('depth', '>=', $depth_min);
                });
                $result_query->when((isset($depth_max) && $depth_max > 0), function ($q) use ($depth_max) {
                    return $q->where('depth', '<=', $depth_max);
                });

                $cr_from = strip_tags(substr($request->cr_from,0,100));
                $cr_to = strip_tags(substr($request->cr_to,0,100));
                $crag_from = strip_tags(substr($request->crag_from,0,100));
                $crag_to = strip_tags(substr($request->crag_to,0,100));
                $pv_from = strip_tags(substr($request->pv_from,0,100));
                $pv_to = strip_tags(substr($request->pv_to,0,100));

                $pvag_from = strip_tags(substr($request->pvag_from,0,100));
                $pvag_to = strip_tags(substr($request->pvag_to,0,100));

                $result_query->when((isset($cr_from) && $cr_from > 0), function ($q) use ($cr_from) {
                    return $q->where('crown_height', '>=', $cr_from);
                });
                $result_query->when((isset($cr_to) && $cr_to > 0), function ($q) use ($cr_to) {
                    return $q->where('crown_height', '<=', $cr_to);
                });

                $result_query->when((isset($crag_from) && $crag_from > 0), function ($q) use ($crag_from) {
                    return $q->where('crown_angle', '>=', $crag_from);
                });
                $result_query->when((isset($crag_to) && $crag_to > 0), function ($q) use ($crag_to) {
                    return $q->where('crown_angle', '<=', $crag_to);
                });

                $result_query->when((isset($pv_from) && $pv_from > 0), function ($q) use ($pv_from) {
                    return $q->where('pavilion_depth', '>=', $pv_from);
                });
                $result_query->when((isset($pv_to) && $pv_to > 0), function ($q) use ($pv_to) {
                    return $q->where('pavilion_depth', '<=', $pv_to);
                });

                $result_query->when((isset($pvag_from) && $pvag_from > 0), function ($q) use ($pvag_from) {
                    return $q->where('pavilion_angle', '>=', $pvag_from);
                });
                $result_query->when((isset($pvag_to) && $pvag_to > 0), function ($q) use ($pvag_to) {
                    return $q->where('pavilion_angle', '<=', $pvag_to);
                });

            // $result_query->where('location', 1);
            // $result_query->where('status', '0');
            $result_query->where('is_delete', 1);

            $diamond_data = $result_query->get();
        }

        $filename = 'Natural-'.date('Y-m-d-His').'-Natural.xlsx';
        if($request->supplier_name == "true")
        {
            $result = Excel::store(new DiamondExportSupplier($diamond_data, $customer_id), $filename);
        }
        else
        {
            $result = Excel::store(new DiamondExport($diamond_data, $customer_id), $filename);
        }

		$json["file_name"] = $filename;
		echo json_encode($json);
    }

    public function UnloadedLabList(Request $request)
    {
        return view('admin.unloaded.lab-unloaded');
    }

    public function UnloadedLabListSearch(Request $request)
    {
        // DB::enableQueryLog();
        $result_query = DiamondLabgrown::select('*', DB::raw('(SELECT pricechange FROM markup_setting WHERE (net_dollar BETWEEN `min_range` and max_range)) as aditional_discount'),
			DB::raw("(SELECT caratprice FROM rap_list WHERE IF(`shape_name` = 'ROUND', shape = 'ROUND', shape != 'ROUND') AND color_name = `color` AND clarity_name = IF(`clarity` = 'FL','IF', `clarity`) AND low_size <= carat AND high_size >= carat) as raprate"))
		->where('carat', '>', 0.08)
		->where('orignal_rate','>',50);

		if(!empty($request->stoneid) && $request->stoneid != 'undefined')
		{
			$postdata = strtoupper($request->stoneid);
			$stoneid = str_replace('LG', '', $postdata);
            $stoneid = str_replace(' ', ',', $postdata);
			$result_query->where(function($query) use ($stoneid) {
				$stoneids = explode(",", $stoneid);
				$query->orWhereIn('id', $stoneids);
				$query->orWhereIn('certificate_no', $stoneids);
			});
		}
		else if(!empty($request->certificateid) && $request->certificateid != 'undefined')
		{
			$postdata = $request->certificateid;
			$certificate_no = explode(",", $postdata);
			$result_query->whereIn('certificate_no', $certificate_no);
		}
		else
		{
			if(!empty($request->min_carat) && !empty($request->max_carat))
			{
				$min_carat = (float)$request->min_carat;
				$max_carat = (float)$request->max_carat;
				if(!empty($request->min_carat) && !empty($request->max_carat))
				{
					$result_query->where('carat', '>=', $min_carat);
				}

				if(!empty($request->min_carat) && !empty($request->max_carat))
				{
					$result_query->where('carat', '<=', $max_carat);
				}
			}
			else
			{
				$result_query->where('carat', '>', 0.08);
				$result_query->where('carat', '<', 99.99);
			}

            if(!empty($request->fancyorwhite))
			{
				$result_query->where('color', 'fancy');
				if(!empty($request->fcolor))
				{
					$data2 = "";
					$fcolor = $request->fcolor;
					$fcolor = trim($fcolor, ",");
					$tmp = explode(",", $fcolor);

                    $result_query->where(function($query) use ($tmp) {
                        foreach ($tmp as $t) {
                            $query->orWhere('fancy_color','like', '%' . $t . '%');
                        }
                    });
                }

				if(!empty($request->intesites))
				{
					$data2 = "";
					$intesites = $request->intesites;
					$intesites = trim($intesites, ",");
					$tmp = explode(",", $intesites);
                    $result_query->where(function($query) use ($tmp) {
                        foreach ($tmp as $t) {
                            $query->orWhere('fancy_intensity','like', '%' . $t . '%');
                        }
                    });
                }

				if(!empty($request->overtones))
				{
					$data2 = "";
					$overtones = $request->overtones;
					$overtones = trim($overtones, ",");
					$tmp = explode(",", $overtones);
                    $result_query->where(function($query) use ($tmp) {
                        foreach ($tmp as $t) {
                            $query->orWhere('fancy_overtone','like', '%' . $t . '%');
                        }
                    });
                }
			}
			else
			{
				$result_query->where('color', '!=', 'fancy');
				if(!empty($request->color))
				{
					$result_query->whereIn('color', explode(",", $request->color));
				}
				else
				{
					$result_query->whereIn('color', array('D','E','F','G','H','I','J','K','L','M','N','O','OP','QR','ST','UV','WX','YZ'));
				}
			}

			if(!empty($request->shape))
			{
				$result_query->whereIn('shape',explode(",",$request->shape));
			}
			if(!empty($request->clarity))
			{
				$result_query->whereIn('clarity',explode(",", $request->clarity));
			}
			if(!empty($request->cut))
			{
				$cut_arrya = explode(",", $request->cut);
				$cut_arrya[] = '';
				$result_query->whereIn('cut',$cut_arrya);
			}
			if(!empty($request->polish))
			{
				$result_query->whereIn('polish',explode(",", $request->polish));
			}
			if(!empty($request->symmetry))
			{
				$result_query->whereIn('symmetry',explode(",",$request->symmetry));
			}
			if(!empty($request->flourescence))
			{
				$result_query->whereIn('fluorescence',explode(",",$request->flourescence));
			}
			if(!empty($request->lab))
			{
				$result_query->whereIn('lab',explode(",",$request->lab));
			}

			if(!empty($request->location))
			{
				$location = $request->location;
				$data2="";
				$tmp=explode(",",$location);
				foreach($tmp as $t)
				{
					$data2.= "$t,";
				}
				$data2s = trim($data2,",");
				$result_query->whereIn('country',explode(",", $data2s));
			}

			$table_per_from = strip_tags(substr($request->table_per_from,0,100));
			$table_per_to = strip_tags(substr($request->table_per_to,0,100));
			$depth_per_from = strip_tags(substr($request->depth_per_from,0,100));
			$depth_per_to = strip_tags(substr($request->depth_per_to,0,100));

			$result_query->when((isset($depth_per_from) && $depth_per_from > 0), function ($q) use ($depth_per_from) {
				return $q->where('depth_per', '>=', $depth_per_from);
			});
			$result_query->when((isset($depth_per_to) && $depth_per_to > 0), function ($q) use ($depth_per_to) {
				return $q->where('depth_per', '<=', $depth_per_to);
			});

			$result_query->when((isset($table_per_from) && $table_per_from > 0), function ($q) use ($table_per_from) {
				return $q->where('table_per', '>=', $table_per_from);
			});
			$result_query->when((isset($table_per_to) && $table_per_to > 0), function ($q) use ($table_per_to) {
				return $q->where('table_per', '<=', $table_per_to);
			});

            $min_length = strip_tags(substr($request->min_length,0,100));
			$max_length = strip_tags(substr($request->max_length,0,100));
			$width_min = strip_tags(substr($request->width_min,0,100));
			$width_max = strip_tags(substr($request->width_max,0,100));
            $depth_min = strip_tags(substr($request->depth_min,0,100));
			$depth_max = strip_tags(substr($request->depth_max,0,100));

			$result_query->when((isset($min_length) && $min_length > 0), function ($q) use ($min_length) {
				return $q->where('length', '>=', $min_length);
			});
			$result_query->when((isset($max_length) && $max_length > 0), function ($q) use ($max_length) {
				return $q->where('length', '<=', $max_length);
			});

			$result_query->when((isset($width_min) && $width_min > 0), function ($q) use ($width_min) {
				return $q->where('width', '>=', $width_min);
			});
			$result_query->when((isset($width_max) && $width_max > 0), function ($q) use ($width_max) {
				return $q->where('width', '<=', $width_max);
			});

            $result_query->when((isset($depth_min) && $depth_min > 0), function ($q) use ($depth_min) {
				return $q->where('depth', '>=', $depth_min);
			});
			$result_query->when((isset($depth_max) && $depth_max > 0), function ($q) use ($depth_max) {
				return $q->where('depth', '<=', $depth_max);
			});

            $cr_from = strip_tags(substr($request->cr_from,0,100));
			$cr_to = strip_tags(substr($request->cr_to,0,100));
			$crag_from = strip_tags(substr($request->crag_from,0,100));
			$crag_to = strip_tags(substr($request->crag_to,0,100));
            $pv_from = strip_tags(substr($request->pv_from,0,100));
			$pv_to = strip_tags(substr($request->pv_to,0,100));

            $pvag_from = strip_tags(substr($request->pvag_from,0,100));
			$pvag_to = strip_tags(substr($request->pvag_to,0,100));

			$result_query->when((isset($cr_from) && $cr_from > 0), function ($q) use ($cr_from) {
				return $q->where('crown_height', '>=', $cr_from);
			});
			$result_query->when((isset($cr_to) && $cr_to > 0), function ($q) use ($cr_to) {
				return $q->where('crown_height', '<=', $cr_to);
			});

			$result_query->when((isset($crag_from) && $crag_from > 0), function ($q) use ($crag_from) {
				return $q->where('crown_angle', '>=', $crag_from);
			});
			$result_query->when((isset($crag_to) && $crag_to > 0), function ($q) use ($crag_to) {
				return $q->where('crown_angle', '<=', $crag_to);
			});

            $result_query->when((isset($pv_from) && $pv_from > 0), function ($q) use ($pv_from) {
				return $q->where('pavilion_depth', '>=', $pv_from);
			});
			$result_query->when((isset($pv_to) && $pv_to > 0), function ($q) use ($pv_to) {
				return $q->where('pavilion_depth', '<=', $pv_to);
			});

            $result_query->when((isset($pvag_from) && $pvag_from > 0), function ($q) use ($pvag_from) {
				return $q->where('pavilion_angle', '>=', $pvag_from);
			});
			$result_query->when((isset($pvag_to) && $pvag_to > 0), function ($q) use ($pvag_to) {
				return $q->where('pavilion_angle', '<=', $pvag_to);
			});


			// if($this->input->post('min_price') >= '0' && $this->input->post('max_price') < '1000000.0')
			// {	$price ="net_dollar BETWEEN least(".$this->input->post('min_price')." , ".$this->input->post('max_price').") AND greatest(".$this->input->post('min_price').", ".$this->input->post('max_price').")";
			// 	$this->db->where($price);
			// }

			// if(!empty($this->input->post('location')))
			// {
			// 	$location =$this->input->post('location');
			// 	$data2="";
			// 	$tmp=explode(",",$location);
			// 	foreach($tmp as $t)
			// 	{
			// 		$data2.= "$t,";
			// 	}
			// 	$data2s = trim($data2,",");
			// 	$this->db->where_in('country',explode(",",$data2s));
			// }

			// if(!empty($this->input->post('eye_clean')))
			// {
			// 	$main_query_eye="";
			// 	$chk = explode(",",$this->input->post('eye_clean'));
			// 	if(in_array("YES",$chk))
			// 	{
			// 		$main_query_eye = "(EyeC = 'YES') ";
			// 		$this->db->where($main_query_eye);
			// 	}

			// 	if(in_array("NO",$chk))
			// 	{
			// 		$main_query_eye = "(EyeC != 'YES') ";
			// 		$this->db->where($main_query_eye);
			// 	}
			// }

			// if(!empty($this->input->post('brown')))
			// {
			// 	$this->db->where_in('brown',explode(",",$this->input->post('brown')));
			// }
			// if(!empty($this->input->post('green')))
			// {
			// 	$this->db->where_in('green',explode(",",$this->input->post('green')));
			// }
			// if(!empty($this->input->post('milky')))
			// {
			// 	$this->db->where_in('Milky',explode(",",$this->input->post('milky')));
			// }

			// if(!empty($this->input->post('imagedetails')))
			// {
			// 	$imagedetails =$this->input->post('imagedetails');
			// 	$tmp=explode(",",$imagedetails);
			// 	foreach($tmp as $t)
			// 	{
			// 		if($t == "ALL"){
			// 			$this->db->where('aws_image !=','');
			// 		}
			// 		if($t == "IMAGE"){
			// 			$this->db->where('aws_image !=','');
			// 		}
			// 		if($t == "VIDEO"){
			// 			$this->db->where('video !=','');
			// 		}
			// 		if($t == "HA"){
			// 			$this->db->where('aws_heart !=','');
			// 		}
			// 		if($t == "ASSET"){
			// 			$this->db->where('aws_asset !=','');
			// 		}
			// 	}
			// }
		}

		$result_query->where('location', 1);
		$result_query->where('status', '0');
		$result_query->where('is_delete', 1);

		if(empty($request->selectcolumn) || $request->selectcolumn == "undefined")
		{
			$result_query->orderBy('carat', 'asc');
			// $order = "FIELD(C_Shape, 'ROUND','HEART','MARQUISE','PEAR','OVAL','EMERALD','CUSHION','PRINCESS','RADIANT','KT')";
			$result_query->orderBy('color', 'asc');
			// $order = "FIELD(C_Clarity, 'FL','IF','VVS1','VVS2','VS1','VS2','SI1','SI2','SI3','I1','I2')";
			// $order = "FIELD(C_Cut, 'ID','EX','VG','GD','FR','PR', '')";
			$result_query->orderBy('orignal_rate', 'asc');
		}

        $start_from = 0;//$request->page;
		$result_query->limit(100)->offset($start_from);
		$result = $result_query->get();
		// dd(DB::getQueryLog());

        $data['count'] = count($result);

        $render_string = '';
        if (!empty($result)) {
            foreach ($result as $value) {
                //Diamond Price calculation
                $carat = $value->carat;

                $base_price = $value->rate + (($value->rate * ($value->aditional_discount)) / 100);
                $carat_price = $base_price;
                $t_net_price = round($carat_price * $carat, 2);
                $net_price = $value->net_dollar; //round($carat_price * $C_Weight, 2);
                $orignal_price = round($value->orignal_rate * $carat, 2);

                $color_code = '';
                // if($value->confirm_status == 1 || $value->confirm_status == 3)
                // {
                // 	$color_code = 'style="color:#D0C301"';
                // }
                // else if($value->confirm_status == 2){
                // 	$color_code = 'style="color:red"';
                // }

                $render_string .= '<tr ' . $color_code . '>'
                    . '<td><i class="fa fa-eye diamond_detail" id="' . $value->certificate_no . '"></i></td>';
                if ($value->color != "fancy") {
                    $discount_main = !empty($value->raprate) ? round(($carat_price - $value->raprate) / $value->raprate * 100, 2) : 0;

                    $render_string .= '<td><input id=' . $value->ref_no . ' name="checkbox" data-fcolor="0" value=' . $value->certificate_no . ' data-id="'.$value->id.'" data-carat=' . $value->carat . ' data-stone="1" data-cprice=' . $carat_price . ' data-price=' . $t_net_price . ' type="checkbox" class="checkbox"></td>';
                    $fancy_string = $value->color;
                    $t_discount = number_format($discount_main,2); //<td class="fancy">' . $value->raprate . '</td>
                } else {
                    $render_string .= '<td><input id=' . $value->ref_no . ' name="checkbox" data-fcolor="1" value=' . $value->certificate_no . ' data-id="'.$value->id.'" data-carat=' . $value->carat . ' data-stone="1" data-cprice=' . $carat_price . ' data-price=' . $t_net_price . ' type="checkbox" class="checkbox"></td>';
                    $fancy_string = $value->fancy_intensity . ' ' . $value->fancy_overtone . ' ' . $value->fancy_color;
                    $t_discount = '';
                }

                $render_string .= '<td>' . $value->supplier_name . '</td>';
                $render_string .= '<td nowrap="nowrap">';
                if (!empty($value->image)) {
                    $render_string .= '<a href="' . $value->image . '" target="_blank" class="ms-1"><img height="22" src="' . asset("assets/images/imagesicon.png") .'" style="cursor:pointer;" title="Image"></a>';
                } else {
                    $render_string .= '<span style="width:22px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>';
                }
                if (!empty($value->video)) {
                    $render_string .= '<a href="' . $value->video . '" target="_blank" class="ms-1"><img height="20" src="' . asset("assets/images/videoicon.png") . '" style="cursor:pointer;" title="Video"></a>';
                } else {
                    $render_string .= '<span style="width:22px;">&nbsp;&nbsp;&nbsp;&nbsp;</span>';
                }

                $render_string .= '<img class="ms-1" height="20" src="'. asset("assets/images/" . strtolower($value->country) .".png") . '">';
                $render_string .= '</td>';

                $render_string .= '<td>' . $value->id . '</td>'
                    . '<td>' . $value->ref_no . '</td>'
                    . '<td>' . $value->availability . '</td>'
                    . '<td>' . $value->shape . '</td>'
                    . '<td>' . number_format($carat, 2) . '</td>'
                    . '<td>' . $fancy_string . '</td>'
                    . '<td>' . $value->clarity . '</td>'
                    . '<td>' . $value->cut . '</td>'
                    . '<td>' . $value->polish . '</td>'
                    . '<td>' . $value->symmetry . '</td>'
                    . '<td>' . $value->fluorescence . '</td>'
                    . '<td>' . $value->lab . '</td>';
                // . '<td><a href="' . $value->Certificate_link . '" target="_blank" style="text-decoration: underline;">' . $value->certificate_no . '</a></td>';

                if (!empty($value->Certificate_link)) {
                    $render_string .= '<td><a href="' . $value->Certificate_link . '" target="_blank" style="text-decoration: underline;">' . $value->certificate_no . '</a></td>';
                } else {
                    if ($value->lab == 'IGI') {
                        $render_string .= '<td><a href="https://www.igi.org/viewpdf.php?r=' . $value->certificate_no . '" target="_blank">' . $value->certificate_no . '</a></td>';
                    } elseif ($value->lab == 'GIA') {
                        $render_string .= '<td><a href="http://www.gia.edu/report-check?reportno=' . $value->certificate_no . '" target="_blank">' . $value->certificate_no . '</a></td>';
                    } elseif ($value->lab == 'HRD') {
                        $render_string .= '<td><a href="https://my.hrdantwerp.com/?id=34&record_number=' . $value->certificate_no . '&weight=" target="_blank">' . $value->certificate_no . '</a></td>';
                    } elseif ($value->lab == 'GCAL') {
                        $render_string .= '<td><a href="https://www.gcalusa.com/certificate-search.html?certificate_id=' . $value->certificate_no . '&weight=" target="_blank">' . $value->certificate_no . '</a></td>';
                    } else {
                        $render_string .= '<td>' . $value->certificate_no . '</td>';
                    }
                }
                $render_string .= '<td>' . $t_discount . '</td>';
                $render_string .= '<td>' . $t_net_price . '</td>';
				$render_string .= '<td>' . $net_price . '</td>';
				$render_string .= '<td>' . $orignal_price . '</td>';
                $render_string .= '<td>' . $value->table_per . '</td>';
				$render_string .= '<td>' . $value->depth_per . '</td>';
				$render_string .= '</tr>';
            }
        } else {
            $render_string .= '<tr><td colspan="100%">No Record Found!!</td></tr>';
        }
        $data['result'] = $render_string;

		echo json_encode($data);
    }

    public function unloadedDownloadLabgrown(Request $request) {

        $sku = explode(',', $request->sku);
		$customer_id = ''; //Auth::user()->id;

        if(!empty($request->selected_stone))
        {
            $sku = explode(',', $request->selected_stone);

            $diamond_data =  DiamondLabgrown::select('id','ref_no','diamond_type','shape','carat','color', 'fancy_color', 'fancy_intensity', 'fancy_overtone', 'clarity','cut','polish','symmetry','fluorescence','lab','certificate_no','rate', 'depth_per', 'table_per', 'length', 'width', 'depth', 'crown_angle', 'crown_height', 'pavilion_angle', 'pavilion_depth', 'milky', 'shade', 'eyeclean', 'key_symbols', 'hna', 'image', 'video', 'supplier_name', 'c_type', 'country', DB::raw('(SELECT pricechange FROM markup_setting WHERE (net_dollar BETWEEN `min_range` and max_range)) as aditional_discount'),
            DB::raw("(SELECT caratprice FROM rap_list WHERE IF(`shape_name` = 'ROUND', shape = 'ROUND', shape != 'ROUND') AND color_name = `color` AND clarity_name = IF(`clarity` = 'FL','IF', `clarity`) AND low_size <= carat AND high_size >= carat) as raprate"))
            ->whereIn('id', $sku)
            ->where('is_delete', 1)->get();
        }
        else
        {
            $result_query =  DiamondLabgrown::select('id','ref_no','diamond_type','shape','carat','color', 'fancy_color', 'fancy_intensity', 'fancy_overtone', 'clarity','cut','polish','symmetry','fluorescence','lab','certificate_no','rate', 'depth_per', 'table_per', 'length', 'width', 'depth', 'crown_angle', 'crown_height', 'pavilion_angle', 'pavilion_depth', 'milky', 'shade', 'eyeclean', 'key_symbols', 'hna', 'image', 'video', 'supplier_name', 'c_type', 'country', DB::raw('(SELECT pricechange FROM markup_setting WHERE (net_dollar BETWEEN `min_range` and max_range)) as aditional_discount'),
            DB::raw("(SELECT caratprice FROM rap_list WHERE IF(`shape_name` = 'ROUND', shape = 'ROUND', shape != 'ROUND') AND color_name = `color` AND clarity_name = IF(`clarity` = 'FL','IF', `clarity`) AND low_size <= carat AND high_size >= carat) as raprate"));

                if(!empty($request->stoneid) && $request->stoneid != 'undefined')
                {
                    $postdata = strtoupper($request->stoneid);
                    $result_query->where(function($query) use ($postdata) {
                        $stoneid = str_replace('LG', '', $postdata);
                        $stoneid = str_replace(' ', ',', $stoneid);

                        $stoneids = explode(",", $stoneid);
                        $query->orWhereIn('id', $stoneids);
                        $query->orWhereIn('certificate_no', $stoneids);
                        $query->orWhereIn('certificate_no', $stoneids);

                        $certino = explode(",", $postdata);
                        $query->orWhereIn('certificate_no', $certino);
                    });
                }

                if(!empty($request->min_carat) && !empty($request->max_carat))
                {
                    $min_carat = (float)$request->min_carat;
                    $max_carat = (float)$request->max_carat;
                    if(!empty($request->min_carat) && !empty($request->max_carat))
                    {
                        $result_query->where('carat', '>=', $min_carat);
                    }

                    if(!empty($request->min_carat) && !empty($request->max_carat))
                    {
                        $result_query->where('carat', '<=', $max_carat);
                    }
                }
                else
                {
                    $result_query->where('carat', '>', 0.08);
                    $result_query->where('carat', '<', 99.99);
                }

                if(!empty($request->fancyorwhite))
                {
                    $result_query->where('color', 'fancy');

                    if(!empty($request->fcolor))
                    {
                        $data2 = "";
                        $fcolor = $request->fcolor;
                        $fcolor = trim($fcolor, ",");
                        $tmp = explode(",", $fcolor);

                        $result_query->where(function($query) use ($tmp) {
                            foreach ($tmp as $t) {
                                $query->orWhere('fancy_color','like', '%' . $t . '%');
                            }
                        });
                    }

                    if(!empty($request->intesites))
                    {
                        $data2 = "";
                        $intesites = $request->intesites;
                        $intesites = trim($intesites, ",");
                        $tmp = explode(",", $intesites);
                        $result_query->where(function($query) use ($tmp) {
                            foreach ($tmp as $t) {
                                $query->orWhere('fancy_intensity','like', '%' . $t . '%');
                            }
                        });
                    }

                    if(!empty($request->overtones))
                    {
                        $data2 = "";
                        $overtones = $request->overtones;
                        $overtones = trim($overtones, ",");
                        $tmp = explode(",", $overtones);
                        $result_query->where(function($query) use ($tmp) {
                            foreach ($tmp as $t) {
                                $query->orWhere('fancy_overtone','like', '%' . $t . '%');
                            }
                        });
                    }
                }
                else
                {
                    $result_query->where('color', '!=', 'fancy');
                    if(!empty($request->color))
                    {
                        $result_query->whereIn('color', explode(",", $request->color));
                    }
                    else
                    {
                        $result_query->whereIn('color', array('D','E','F','G','H','I','J','K','L','M','N','O','OP','QR','ST','UV','WX','YZ'));
                    }
                }

                if(!empty($request->shape))
                {
                    $result_query->whereIn('shape',explode(",",$request->shape));
                }
                if(!empty($request->clarity))
                {
                    $result_query->whereIn('clarity',explode(",", $request->clarity));
                }
                if(!empty($request->cut))
                {
                    $cut_arrya = explode(",", $request->cut);
                    $cut_arrya[] = '';
                    $result_query->whereIn('cut',$cut_arrya);
                }
                if(!empty($request->polish))
                {
                    $result_query->whereIn('polish',explode(",", $request->polish));
                }
                if(!empty($request->symmetry))
                {
                    $result_query->whereIn('symmetry',explode(",",$request->symmetry));
                }
                if(!empty($request->flourescence))
                {
                    $result_query->whereIn('fluorescence',explode(",",$request->flourescence));
                }
                if(!empty($request->lab))
                {
                    $result_query->whereIn('lab',explode(",",$request->lab));
                }

                if(!empty($request->location))
                {
                    $location = $request->location;
                    $data2="";
                    $tmp=explode(",",$location);
                    foreach($tmp as $t)
                    {
                        $data2.= "$t,";
                    }
                    $data2s = trim($data2,",");
                    $result_query->whereIn('country',explode(",", $data2s));
                }

                $table_per_from = strip_tags(substr($request->table_per_from,0,100));
                $table_per_to = strip_tags(substr($request->table_per_to,0,100));
                $depth_per_from = strip_tags(substr($request->depth_per_from,0,100));
                $depth_per_to = strip_tags(substr($request->depth_per_to,0,100));

                $result_query->when((isset($depth_per_from) && $depth_per_from > 0), function ($q) use ($depth_per_from) {
                    return $q->where('depth_per', '>=', $depth_per_from);
                });
                $result_query->when((isset($depth_per_to) && $depth_per_to > 0), function ($q) use ($depth_per_to) {
                    return $q->where('depth_per', '<=', $depth_per_to);
                });

                $result_query->when((isset($table_per_from) && $table_per_from > 0), function ($q) use ($table_per_from) {
                    return $q->where('table_per', '>=', $table_per_from);
                });
                $result_query->when((isset($table_per_to) && $table_per_to > 0), function ($q) use ($table_per_to) {
                    return $q->where('table_per', '<=', $table_per_to);
                });

                $min_length = strip_tags(substr($request->min_length,0,100));
                $max_length = strip_tags(substr($request->max_length,0,100));
                $width_min = strip_tags(substr($request->width_min,0,100));
                $width_max = strip_tags(substr($request->width_max,0,100));
                $depth_min = strip_tags(substr($request->depth_min,0,100));
                $depth_max = strip_tags(substr($request->depth_max,0,100));

                $result_query->when((isset($min_length) && $min_length > 0), function ($q) use ($min_length) {
                    return $q->where('length', '>=', $min_length);
                });
                $result_query->when((isset($max_length) && $max_length > 0), function ($q) use ($max_length) {
                    return $q->where('length', '<=', $max_length);
                });

                $result_query->when((isset($width_min) && $width_min > 0), function ($q) use ($width_min) {
                    return $q->where('width', '>=', $width_min);
                });
                $result_query->when((isset($width_max) && $width_max > 0), function ($q) use ($width_max) {
                    return $q->where('width', '<=', $width_max);
                });

                $result_query->when((isset($depth_min) && $depth_min > 0), function ($q) use ($depth_min) {
                    return $q->where('depth', '>=', $depth_min);
                });
                $result_query->when((isset($depth_max) && $depth_max > 0), function ($q) use ($depth_max) {
                    return $q->where('depth', '<=', $depth_max);
                });

                $cr_from = strip_tags(substr($request->cr_from,0,100));
                $cr_to = strip_tags(substr($request->cr_to,0,100));
                $crag_from = strip_tags(substr($request->crag_from,0,100));
                $crag_to = strip_tags(substr($request->crag_to,0,100));
                $pv_from = strip_tags(substr($request->pv_from,0,100));
                $pv_to = strip_tags(substr($request->pv_to,0,100));

                $pvag_from = strip_tags(substr($request->pvag_from,0,100));
                $pvag_to = strip_tags(substr($request->pvag_to,0,100));

                $result_query->when((isset($cr_from) && $cr_from > 0), function ($q) use ($cr_from) {
                    return $q->where('crown_height', '>=', $cr_from);
                });
                $result_query->when((isset($cr_to) && $cr_to > 0), function ($q) use ($cr_to) {
                    return $q->where('crown_height', '<=', $cr_to);
                });

                $result_query->when((isset($crag_from) && $crag_from > 0), function ($q) use ($crag_from) {
                    return $q->where('crown_angle', '>=', $crag_from);
                });
                $result_query->when((isset($crag_to) && $crag_to > 0), function ($q) use ($crag_to) {
                    return $q->where('crown_angle', '<=', $crag_to);
                });

                $result_query->when((isset($pv_from) && $pv_from > 0), function ($q) use ($pv_from) {
                    return $q->where('pavilion_depth', '>=', $pv_from);
                });
                $result_query->when((isset($pv_to) && $pv_to > 0), function ($q) use ($pv_to) {
                    return $q->where('pavilion_depth', '<=', $pv_to);
                });

                $result_query->when((isset($pvag_from) && $pvag_from > 0), function ($q) use ($pvag_from) {
                    return $q->where('pavilion_angle', '>=', $pvag_from);
                });
                $result_query->when((isset($pvag_to) && $pvag_to > 0), function ($q) use ($pvag_to) {
                    return $q->where('pavilion_angle', '<=', $pvag_to);
                });

            $result_query->where('location', 1);
            $result_query->where('status', '0');
            $result_query->orderBy('carat', 'desc')->get();
            $diamond_data = $result_query->where('is_delete', 1)->get();
        }

        $filename = 'Lab-'.date('Y-m-d-His').'-Lab.xlsx';

        if($request->supplier_name == "true")
        {
            $result = Excel::store(new DiamondExportSupplier($diamond_data, $customer_id), $filename);
        }
        else
        {
            $result = Excel::store(new DiamondExport($diamond_data, $customer_id), $filename);
        }

		$json["file_name"] = $filename;
		echo json_encode($json);
	}

    public function movetoSearchNatural(Request $request)
    {
        if(!empty($request->selected_stone))
		{
            $selected_stone = explode(',', $request->selected_stone);
            DiamondNatural::whereIn('id', $selected_stone)->update(array('is_delete' => 0));
        }

        $data['success'] = true;
        echo json_encode($data);
    }

    public function movetoSearchLabgrown(Request $request)
    {
        if(!empty($request->selected_stone))
		{
            $selected_stone = explode(',', $request->selected_stone);
            DiamondLabgrown::whereIn('id', $selected_stone)->update(array('is_delete' => 0));
        }

        $data['success'] = true;
        echo json_encode($data);
    }

    public function allStockDownloadLabgrown(Request $request) {

        $sku = explode(',', $request->sku);
		$customer_id = ''; //Auth::user()->id;

        if(!empty($request->selected_stone))
        {
            $sku = explode(',', $request->selected_stone);

            $diamond_data =  DiamondLabgrown::select('id','diamond_type','shape','carat','color','clarity','cut','polish','symmetry','fluorescence','lab','certificate_no','rate', 'depth_per', 'table_per', 'length', 'width', 'depth', 'crown_angle', 'crown_height', 'pavilion_angle', 'pavilion_depth', 'milky', 'shade', 'eyeclean', 'key_symbols', 'hna', 'image', 'video', 'supplier_name', 'c_type',
            DB::raw("(SELECT caratprice FROM rap_list WHERE IF(`shape_name` = 'ROUND', shape = 'ROUND', shape != 'ROUND') AND color_name = `color` AND clarity_name = IF(`clarity` = 'FL','IF', `clarity`) AND low_size <= carat AND high_size >= carat) as raprate"))
            ->whereIn('id', $sku)
            ->where('is_delete', 0)->get();
        }
        else
        {
            $result_query =  DiamondLabgrown::select('id','diamond_type','shape','carat','color','clarity','cut','polish','symmetry','fluorescence','lab','certificate_no','rate', 'depth_per', 'table_per', 'length', 'width', 'depth', 'crown_angle', 'crown_height', 'pavilion_angle', 'pavilion_depth', 'milky', 'shade', 'eyeclean', 'key_symbols', 'hna', 'image', 'video', 'supplier_name', 'c_type',
            DB::raw("(SELECT caratprice FROM rap_list WHERE IF(`shape_name` = 'ROUND', shape = 'ROUND', shape != 'ROUND') AND color_name = `color` AND clarity_name = IF(`clarity` = 'FL','IF', `clarity`) AND low_size <= carat AND high_size >= carat) as raprate"));

            if(!empty($request->stoneid) && $request->stoneid != 'undefined')
            {
                $postdata = strtoupper($request->stoneid);
                $result_query->where(function($query) use ($postdata) {
                    $stoneid = str_replace('LG', '', $postdata);
                    $stoneid = str_replace(' ', ',', $stoneid);

                    $stoneids = explode(",", $stoneid);
                    $query->orWhereIn('id', $stoneids);
                    $query->orWhereIn('certificate_no', $stoneids);
                    $query->orWhereIn('certificate_no', $stoneids);

                    $certino = explode(",", $postdata);
                    $query->orWhereIn('certificate_no', $certino);
                });
            }
            else if(!empty($request->certificateid) && $request->certificateid != 'undefined')
            {
                $postdata = $request->certificateid;
                $certificate_no = explode(",", $postdata);
                $result_query->whereIn('certificate_no', $certificate_no);
            }
            else
            {
                if(!empty($request->min_carat) && !empty($request->max_carat))
                {
                    $min_carat = (float)$request->min_carat;
                    $max_carat = (float)$request->max_carat;
                    if(!empty($request->min_carat) && !empty($request->max_carat))
                    {
                        $result_query->where('carat', '>=', $min_carat);
                    }

                    if(!empty($request->min_carat) && !empty($request->max_carat))
                    {
                        $result_query->where('carat', '<=', $max_carat);
                    }
                }
                else
                {
                    $result_query->where('carat', '>', 0.08);
                    $result_query->where('carat', '<', 99.99);
                }

                if(!empty($request->fancyorwhite))
                {
                    $result_query->where('color', 'fancy');
                    if(!empty($request->fcolor))
                    {
                        $data2 = "";
                        $fcolor = $request->fcolor;
                        $fcolor = trim($fcolor, ",");
                        $tmp = explode(",", $fcolor);

                        $result_query->where(function($query) use ($tmp) {
                            foreach ($tmp as $t) {
                                $query->orWhere('fancy_color','like', '%' . $t . '%');
                            }
                        });
                    }

                    if(!empty($request->intesites))
                    {
                        $data2 = "";
                        $intesites = $request->intesites;
                        $intesites = trim($intesites, ",");
                        $tmp = explode(",", $intesites);
                        $result_query->where(function($query) use ($tmp) {
                            foreach ($tmp as $t) {
                                $query->orWhere('fancy_intensity','like', '%' . $t . '%');
                            }
                        });
                    }

                    if(!empty($request->overtones))
                    {
                        $data2 = "";
                        $overtones = $request->overtones;
                        $overtones = trim($overtones, ",");
                        $tmp = explode(",", $overtones);
                        $result_query->where(function($query) use ($tmp) {
                            foreach ($tmp as $t) {
                                $query->orWhere('fancy_overtone','like', '%' . $t . '%');
                            }
                        });
                    }
                }
                else
                {
                    $result_query->where('color', '!=', 'fancy');
                    if(!empty($request->color))
                    {
                        $result_query->whereIn('color', explode(",", $request->color));
                    }
                    else
                    {
                        $result_query->whereIn('color', array('D','E','F','G','H','I','J','K','L','M','N','O','OP','QR','ST','UV','WX','YZ'));
                    }
                }

                if(!empty($request->shape))
                {
                    $result_query->whereIn('shape',explode(",",$request->shape));
                }
                if(!empty($request->clarity))
                {
                    $result_query->whereIn('clarity',explode(",", $request->clarity));
                }
                if(!empty($request->cut))
                {
                    $cut_arrya = explode(",", $request->cut);
                    $cut_arrya[] = '';
                    $result_query->whereIn('cut',$cut_arrya);
                }
                if(!empty($request->polish))
                {
                    $result_query->whereIn('polish',explode(",", $request->polish));
                }
                if(!empty($request->symmetry))
                {
                    $result_query->whereIn('symmetry',explode(",",$request->symmetry));
                }
                if(!empty($request->flourescence))
                {
                    $result_query->whereIn('fluorescence',explode(",",$request->flourescence));
                }
                if(!empty($request->lab))
                {
                    $result_query->whereIn('lab',explode(",",$request->lab));
                }

                if(!empty($request->company))
                {
                    $result_query->whereIn('supplier_id',explode(",",$request->company));
                }

                if(!empty($request->location))
                {
                    $location = $request->location;
                    $data2="";
                    $tmp=explode(",",$location);
                    foreach($tmp as $t)
                    {
                        $data2.= "$t,";
                    }
                    $data2s = trim($data2,",");
                    $result_query->whereIn('country',explode(",", $data2s));
                }

                $table_per_from = strip_tags(substr($request->table_per_from,0,100));
                $table_per_to = strip_tags(substr($request->table_per_to,0,100));
                $depth_per_from = strip_tags(substr($request->depth_per_from,0,100));
                $depth_per_to = strip_tags(substr($request->depth_per_to,0,100));

                $result_query->when((isset($depth_per_from) && $depth_per_from > 0), function ($q) use ($depth_per_from) {
                    return $q->where('depth_per', '>=', $depth_per_from);
                });
                $result_query->when((isset($depth_per_to) && $depth_per_to > 0), function ($q) use ($depth_per_to) {
                    return $q->where('depth_per', '<=', $depth_per_to);
                });

                $result_query->when((isset($table_per_from) && $table_per_from > 0), function ($q) use ($table_per_from) {
                    return $q->where('table_per', '>=', $table_per_from);
                });
                $result_query->when((isset($table_per_to) && $table_per_to > 0), function ($q) use ($table_per_to) {
                    return $q->where('table_per', '<=', $table_per_to);
                });

                $min_length = strip_tags(substr($request->min_length,0,100));
                $max_length = strip_tags(substr($request->max_length,0,100));
                $width_min = strip_tags(substr($request->width_min,0,100));
                $width_max = strip_tags(substr($request->width_max,0,100));
                $depth_min = strip_tags(substr($request->depth_min,0,100));
                $depth_max = strip_tags(substr($request->depth_max,0,100));

                $result_query->when((isset($min_length) && $min_length > 0), function ($q) use ($min_length) {
                    return $q->where('length', '>=', $min_length);
                });
                $result_query->when((isset($max_length) && $max_length > 0), function ($q) use ($max_length) {
                    return $q->where('length', '<=', $max_length);
                });

                $result_query->when((isset($width_min) && $width_min > 0), function ($q) use ($width_min) {
                    return $q->where('width', '>=', $width_min);
                });
                $result_query->when((isset($width_max) && $width_max > 0), function ($q) use ($width_max) {
                    return $q->where('width', '<=', $width_max);
                });

                $result_query->when((isset($depth_min) && $depth_min > 0), function ($q) use ($depth_min) {
                    return $q->where('depth', '>=', $depth_min);
                });
                $result_query->when((isset($depth_max) && $depth_max > 0), function ($q) use ($depth_max) {
                    return $q->where('depth', '<=', $depth_max);
                });

                $cr_from = strip_tags(substr($request->cr_from,0,100));
                $cr_to = strip_tags(substr($request->cr_to,0,100));
                $crag_from = strip_tags(substr($request->crag_from,0,100));
                $crag_to = strip_tags(substr($request->crag_to,0,100));
                $pv_from = strip_tags(substr($request->pv_from,0,100));
                $pv_to = strip_tags(substr($request->pv_to,0,100));

                $pvag_from = strip_tags(substr($request->pvag_from,0,100));
                $pvag_to = strip_tags(substr($request->pvag_to,0,100));

                $result_query->when((isset($cr_from) && $cr_from > 0), function ($q) use ($cr_from) {
                    return $q->where('crown_height', '>=', $cr_from);
                });
                $result_query->when((isset($cr_to) && $cr_to > 0), function ($q) use ($cr_to) {
                    return $q->where('crown_height', '<=', $cr_to);
                });

                $result_query->when((isset($crag_from) && $crag_from > 0), function ($q) use ($crag_from) {
                    return $q->where('crown_angle', '>=', $crag_from);
                });
                $result_query->when((isset($crag_to) && $crag_to > 0), function ($q) use ($crag_to) {
                    return $q->where('crown_angle', '<=', $crag_to);
                });

                $result_query->when((isset($pv_from) && $pv_from > 0), function ($q) use ($pv_from) {
                    return $q->where('pavilion_depth', '>=', $pv_from);
                });
                $result_query->when((isset($pv_to) && $pv_to > 0), function ($q) use ($pv_to) {
                    return $q->where('pavilion_depth', '<=', $pv_to);
                });

                $result_query->when((isset($pvag_from) && $pvag_from > 0), function ($q) use ($pvag_from) {
                    return $q->where('pavilion_angle', '>=', $pvag_from);
                });
                $result_query->when((isset($pvag_to) && $pvag_to > 0), function ($q) use ($pvag_to) {
                    return $q->where('pavilion_angle', '<=', $pvag_to);
                });


                // if($this->input->post('min_price') >= '0' && $this->input->post('max_price') < '1000000.0')
                // {	$price ="net_dollar BETWEEN least(".$this->input->post('min_price')." , ".$this->input->post('max_price').") AND greatest(".$this->input->post('min_price').", ".$this->input->post('max_price').")";
                // 	$this->db->where($price);
                // }s

                // if(!empty($this->input->post('location')))
                // {
                // 	$location =$this->input->post('location');
                // 	$data2="";
                // 	$tmp=explode(",",$location);
                // 	foreach($tmp as $t)
                // 	{
                // 		$data2.= "$t,";
                // 	}
                // 	$data2s = trim($data2,",");
                // 	$this->db->where_in('country',explode(",",$data2s));
                // }

                // if(!empty($this->input->post('eye_clean')))
                // {
                // 	$main_query_eye="";
                // 	$chk = explode(",",$this->input->post('eye_clean'));
                // 	if(in_array("YES",$chk))
                // 	{
                // 		$main_query_eye = "(EyeC = 'YES') ";
                // 		$this->db->where($main_query_eye);
                // 	}

                // 	if(in_array("NO",$chk))
                // 	{
                // 		$main_query_eye = "(EyeC != 'YES') ";
                // 		$this->db->where($main_query_eye);
                // 	}
                // }

                // if(!empty($this->input->post('brown')))
                // {
                // 	$this->db->where_in('brown',explode(",",$this->input->post('brown')));
                // }
                // if(!empty($this->input->post('green')))
                // {
                // 	$this->db->where_in('green',explode(",",$this->input->post('green')));
                // }
                // if(!empty($this->input->post('milky')))
                // {
                // 	$this->db->where_in('Milky',explode(",",$this->input->post('milky')));
                // }

                // if(!empty($this->input->post('imagedetails')))
                // {
                // 	$imagedetails =$this->input->post('imagedetails');
                // 	$tmp=explode(",",$imagedetails);
                // 	foreach($tmp as $t)
                // 	{
                // 		if($t == "ALL"){
                // 			$this->db->where('aws_image !=','');
                // 		}
                // 		if($t == "IMAGE"){
                // 			$this->db->where('aws_image !=','');
                // 		}
                // 		if($t == "VIDEO"){
                // 			$this->db->where('video !=','');
                // 		}
                // 		if($t == "HA"){
                // 			$this->db->where('aws_heart !=','');
                // 		}
                // 		if($t == "ASSET"){
                // 			$this->db->where('aws_asset !=','');
                // 		}
                // 	}
                // }
            }

            $result_query->where('location', 1);
            $result_query->where('status', '0');
            $result_query->orderBy('carat', 'desc')->get();
            $diamond_data = $result_query->where('is_delete', 0)->get();
        }

        $filename = date('Y-m-d-His').'-Lab.csv';

        if($request->supplier_name == "true")
        {
            $result = Excel::store(new DiamondExportSupplier($diamond_data, $customer_id), $filename);
        }
        else
        {
            $result = Excel::store(new DiamondExport($diamond_data, $customer_id), $filename);
        }

		$json["file_name"] = $filename;
		echo json_encode($json);
	}

    public function DiamondStatus(){
        $data['certificate'] = '';
        $data['details_show'] = '';
        return view('admin.diamond-status')->with($data);
    }

    public function DiamondStatusPost(Request $request){
        $certificate = $request->certificate;

        $diamond_details = DiamondNatural::where('certificate_no','=',$certificate)->first();
        if($diamond_details == null){
            $diamond_details = DiamondLabgrown::where('certificate_no','=',$certificate)->first();
        }
        $order_details = Order::select('orders.*',DB::RAW('(SELECT country FROM customers WHERE customers.cus_id = orders.customer_id) AS country'))->where('certificate_no','=',$certificate)->with('user','orderdetail')->get();

        $invoices_perfoma = $pickup_details = '';
        if($order_details != null){
            $pickup_details = Pickups::select('*','pickups.created_at as pickup_date',DB::RAW('(select created_at from invoices where invoices.invoice_number=pickups.invoice_number AND is_deleted = 0) as invoice_created_date'),DB::RAW('(select created_at from export_list where export_list.export_number=pickups.export_number AND is_delete = 0) as export_created_date'))->where('cerificate_no','=',$certificate)->with('qc_list')->first();
            $invoices_perfoma = DB::table('invoices_perfoma')->where('certificate_no','like', '%'.$certificate.'%')->join('associates','associates.id','=','invoices_perfoma.associates_id')->orderBy('invoices_perfoma.created_at','desc')->first();
        }

        $data['timeline'] = TimelineCycle::where('certificate_no',$certificate)->groupBy('flow')->get()->toArray();

        $data['invoice'] = Invoice::with('associates')->where('certificate_no','like','%'.$certificate.'%')->where('is_deleted',0)->first();

        $data['certificate'] = $certificate;
        $data['details_show']='show';
        $data['diamond_details'] = $diamond_details;
        $data['order_details'] = $order_details;
        $data['pickup_details'] = $pickup_details;
        $data['invoices_perfoma'] = $invoices_perfoma;
        return view('admin.diamond-status')->with($data);
    }

    public function ImageVideoRequest(){

        $records = ImgVidRequest::select('*',
                            DB::RAW('(SELECT companyname from users where users.id = image_video_request.cus_id) as customer'),
                            DB::RAW('(SELECT companyname from users where users.id = image_video_request.sup_id) as supplier'))->orderBy('created_at','desc')->get();

        $data = $array = [];
        if(!empty($records))
        {
        foreach($records as $record){
            if($record->diamond_type == 'L'){
                $diamond_detail = DiamondLabgrown::where('certificate_no','=',$record->certificate_no)->first();
            }
            else{
                $diamond_detail = DiamondNatural::where('certificate_no','=',$record->certificate_no)->first();
            }

                if(!empty($diamond_detail))
                {
            $record = $record->toArray();
            $diamond_detail = $diamond_detail->toArray();

            $array[] = array_merge($diamond_detail,$record);
        }
            }
        $data['records'] = $array;
        $data['diamond_detail'] = $array;
        }
        return view('admin.extra.image-video-request')->with($data);
    }

    public function ImageVideoRequestPost(Request $request){
        $id = $request->id;
        $type = $request->type;
        if($type == 'image'){
            $content = $request->image;
        }
        else{
            $content = $request->video;
        }

        $data = ImgVidRequest::where('id',$id)->select('certificate_no')->first();

        $extension = $content->extension();
        $content_name = $type.'_'.$data->certificate_no.'_'.time().'.'.$extension;

        $content_url = url('/image-video/'.$content_name);

        if($type=='image'){
            $true = $request->image->storeAs('image-video',$content_name,'public_path');
        }
        else{
            $content->move('image-video',$content_name);
        }
        ImgVidRequest::where('id',$id)->update([$type => $content_url]);
        return Redirect()->back()->with(['Success' => $type.' Uploaded successfully!']);
    }

    public function ReplacementDiamond(){
        $data['certificate'] = '';
        $data['details_show'] = '';
        return view('admin.unloaded.replacement-diamond')->with($data);
    }

    public function ReplacementDiamondPost(Request $request){
        $certificate = $request->certificate;
        $diamond = DiamondNatural::where('certificate_no','=',$certificate)->first();
        if($diamond == null){
            $diamond = DiamondLabgrown::where('certificate_no','=',$certificate)->first();
        }

        if(!empty($diamond)){
            $clarity_arr= array('FL','IF','VVS1','VVS2','VS1','VS2','SI1','SI2','I1','I2','I3');
            $key = array_search($diamond->clarity,$clarity_arr);

            if($diamond->clarity != 'FL'){
                $plusclarity = $clarity_arr[$key-1];
            }
            else{
                $plusclarity =$diamond->clarity;
            }
            if($diamond->diamond_type == 'L'){
                $replacements = DiamondLabgrown::where('shape',$diamond->shape);
            }
            else{
                $replacements = DiamondNatural::where('shape',$diamond->shape);
            }

            $pluscolor = $diamond->color;
            $minuscolor = $diamond->color;
            $minuscolor--;
            $pluscolor++;
            $replacements =    $replacements->whereIn('clarity',[$plusclarity,$diamond->clarity])
                                            ->where('carat','>=',$diamond->carat)
                                            ->where('carat','<=', $diamond->carat+0.02)
                                            ->whereIn('color',[$minuscolor,$pluscolor,$diamond->color])
                                            ->where('cut',$diamond->cut)
                                            ->where('polish',$diamond->polish)
                                            ->where('symmetry',$diamond->symmetry)
                                            ->where('lab',$diamond->lab)

                                            ->where('table_per','>=',$diamond->table_per-1)
                                            ->where('table_per','<=', $diamond->table_per+1)

                                            ->where('depth_per','>=',$diamond->depth_per-1)
                                            ->where('depth_per','<=', $diamond->depth_per+1)

                                            ->where('length','>=',$diamond->length-0.5)
                                            ->where('length','<=', $diamond->length+0.5)
                                            ->where('width','>=',$diamond->width-0.5)
                                            ->where('width','<=', $diamond->width+0.5)
                                            ->where('depth','>=',$diamond->depth-0.5)
                                            ->where('depth','<=', $diamond->depth+0.05)
                                            ->where('certificate_no','!=', $certificate)
                                        ->where('is_delete', 0)
                                        // ->where('status', 0)
                                            ->get();
            $data['diamond_details'] = $diamond;
            $data['certificate'] = $certificate;
            $data['details_show'] = 'show';
            $data['replacements'] = $replacements;

            return view('admin.unloaded.replacement-diamond')->with($data);
        }
        else
        {
            $data['details_show'] = '';
            $data['certificate'] = $certificate;
            $data['diamond_details'] = $diamond;
            return view('admin.unloaded.replacement-diamond')->with($data);
        }
    }
}

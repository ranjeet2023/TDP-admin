<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use DB;
use Mail;

use App\Helpers\AppHelper;

use App\Models\Customer;
use App\Models\User;
use App\Models\DiamondLabgrown;
use App\Models\DiamondNatural;
use App\Models\Order;
use App\Models\WishList;
use App\Models\WhiteLabel;
use App\Models\CurrencyExchange;
use App\Models\WhiteLabelMarkup;

class ShopiApiController extends Controller
{
    public function GetConfig(Request $request)
    {
        $token = $request->token;
        if(!empty($token))
        {
            $white_label = WhiteLabel::where('token_key',$token)->first();
            if(!empty($white_label))
            {
                $compnay_name = DB::table('users')->where('id',$white_label->user_id)->first();
                $white_label_logo = !empty($white_label->markup_logo) ? url('uploads/white_label_logo/'.$white_label->markup_logo)  : 'https://i1.wp.com/wmfeimages.s3.amazonaws.com/wp-content/uploads/2017/03/10160311/659383134_orig.png';
                $data['diamond_type'] = explode(',',$white_label->diamond_type);
                $data['logo'] =  $white_label_logo;
                $data['companyname'] = $compnay_name->companyname;
                $data['shape'] = explode(',',strtolower($white_label->shape));
                $data['color'] = explode(',',$white_label->color);
                $data['clarity']   = explode(',',$white_label->clarity);
                $data['lab']       = explode(',',$white_label->lab);
                $data['cut']       = explode(',',$white_label->cut);
                $data['polish']    = explode(',',$white_label->polish);
                $data['symmetry']  = explode(',',$white_label->symmetry);
                $data['fluorescence'] = explode(',',$white_label->fluorescence);
                $data['eyeclean']    = explode(',',$white_label->eye_clean);
                $data['country']      = explode(',',$white_label->country);
                $data['currency_type'] = $white_label->currency_type;
                $data['currency_symbol'] = ($white_label->currency_type == "AUD") ? "AU$" :"$";

                return response()->json([
                    'success'=>true,
                    'message'=>"Parameter",
                    'data'=>$data
                ],201);
            }
            else
            {
                return response()->json([
                    'success'=>false,
                    'message'=>" Token Invalid Try Again !!",
                ]);
            }
        }
        else
        {
            return response()->json([
                'success'=>false,
                'message'=>"Token Missing"
            ]);
         }
    }

    public function SearchDiamond(Request $request)
    {
        if(!empty($sortby))
        {
            $sortby = $request->sortby;
        }
        else
        {
            $sortby = "carat";
        }
        $token = $request->token;
        if(empty($token))
        {
            return response()->json([
                'success'=>false,
                'message'=>"Token Missing"
            ]);
        }

        $white_label = WhiteLabel::where('token_key',$token)->first();
        if(!empty($white_label) && !empty($white_label->user_id))
        {
            $customer_id = $white_label->user_id;
            $customer_data = Customer::where('cus_id', $customer_id)->first();
            if(!empty($customer_data))
            {
            }
            else
            {
                return response()->json([
                    'success'=>false,
                    'message'=>'Token Not Valid'
                ]);
            }

            $diamond_type = trim(strtoupper(str_replace(' ','',$request->diamond_type)));
            if(!empty($diamond_type))
            {
                if($diamond_type == "NATURAL")
                {
                    $result_query = DiamondNatural::select('*')
                    ->where('carat', '>', 0.17)
                    ->where('orignal_rate','>',50)
                    ->orderBy($sortby);

                    $cus_discount = $customer_data->discount;
                }
                elseif($diamond_type == "LABGROWN")
                {
                    $result_query = DiamondLabgrown::select('*')
                    ->where('carat', '>', 0.17)
                    ->where('orignal_rate','>',50)
                    ->orderBy($sortby);

                    $cus_discount = $customer_data->lab_discount;
                }
                else
                {
                    return response()->json([
                        'success'=>false,
                        'message'=>"Select Valid Diamond Type (Natural or Labgrown)",
                    ]);
                }
            }
            else
            {
                return response()->json([
                    'success'=>false,
                    'message'=>"Select Diamond Type",
                ]);
            }

            if(!empty($request->certificate) && $request->certificate != 'undefined')
            {
                $result_query->where(function($query) use ($request) {
                        $stoneid = strtoupper($request->certificate);
                        $stoneids = explode(",", $stoneid);
                        $query->whereIn('certificate_no', $stoneids);
                    });
            }
            else
            {
                if($request->carat_size)
                {
                    $carat_size = $request->carat_size;
                    $carat = explode('-',$carat_size);
                    $carat_min = $carat[0];
                    $carat_max = $carat[1];

                    $result_query->where('carat', '>=', $carat_min);
                    $result_query->where('carat', '<=', $carat_max);
                }
                elseif($request->carat_range)
                {
                    $carat_range = $request->carat_range;
                    $result_query->where(function($query) use ($carat_range) {
                    $size = explode(",", $carat_range);
                    foreach ($size as $sizevalue) {
                        $query->orWhere(function($query_c) use ($sizevalue) {
                            $sizev = explode("-", $sizevalue);
                            if (!empty($sizev[0])) {
                                $query_c->where('carat', '>=', $sizev[0]);
                            }
                            if (!empty($sizev[1])) {
                                $query_c->where('carat', '<=', $sizev[1]);
                            }
                        });
                    }
                    });
                }
                else
                {
                    $result_query->where('carat', '>=', 0.00);
                }


                if(!empty($request->table))
                {
                    $table = $request->table;
                    $table = explode('-',$table);
                    $table_min = (float)$table[0];
                    $table_max = (float)$table[1];

                    $result_query->where('table_per', '>=', $table_min);
                    $result_query->where('table_per', '<=', $table_max);
                }
                else
                {
                    $result_query->where('table_per', '>', 1.00);
                    $result_query->where('table_per', '<', 100.00);
                }

                if(!empty($request->depth))
                {
                    $depth = $request->depth;
                    $depth = explode('-',$depth);
                    $depth_min = (float)$depth[0];
                    $depth_max = (float)$depth[1];

                    $result_query->where('depth', '>=', $depth_min);
                    $result_query->where('depth', '<=', $depth_max);
                }
                else
                {
                    $result_query->where('depth', '>', 1.00);
                    $result_query->where('depth', '<', 100.00);
                }

                if(!empty($request->price))
                {
                    $price = $request->price;
                    $price = explode('-',$price);
                    $price_min = (float)$price[0];
                    $price_max = (float)$price[1];

                    $result_query->where('net_dollar', '>=', $price_min);
                    $result_query->where('net_dollar', '<=', $price_max);
                }
                else
                {
                    $result_query->where('net_dollar', '>', 1.00);
                }


                if(!empty($request->color))
                {
                    $color = explode(',',$request->color);
                    $result_query->whereIn('color',$color);
                }
                if(!empty($request->clarity))
                {
                    $clarity = explode(',',$request->clarity);
                    $result_query->whereIn('clarity',$clarity);
                }
                if(!empty($request->certificate))
                {
                    $certificate = explode(',',$request->certificate);
                    $result_query->whereIn('certificate_no',$certificate);
                }
                if(!empty($request->lab))
                {
                    $lab = explode(',',$request->lab);
                    $result_query->whereIn('lab',$lab);
                }
                if(!empty($request->cut))
                {
                    $cut = explode(',',$request->cut);
                    $cut[] = '';
                    $result_query->whereIn('cut',$cut);
                }
                if(!empty($request->polish))
                {
                    $polish = explode(',',$request->polish);
                    $result_query->whereIn('polish',$polish);
                }
                if(!empty($request->symmetry))
                {
                    $symmetry = explode(',',$request->symmetry);
                    $result_query->whereIn('symmetry',$symmetry);
                }
                if(!empty($request->fluorescence))
                {
                    $fluorescence = explode(',',$request->fluorescence);
                    $result_query->whereIn('fluorescence',$fluorescence);
                }
                if(!empty($request->shape))
                {
                    $shape = explode(',',$request->shape);
                    $result_query->whereIn('shape',$shape);
                }
                if(!empty($request->eyeclean))
                {
                    $eyeclean = explode(',',$request->eyeclean);
                    $result_query->whereIn('eyeclean',$eyeclean);
                }
                if(!empty($request->country))
                {
                    $country = explode(',',$request->country);
                    $result_query->whereIn('country',$country);
                }
            }

            $result_query->where('location', 1);
            $result_query->where('status', '0');
            $result_query->where('is_delete', 0);

            $result = $result_query->paginate(16);

            $updatedItems = $result->getCollection();
            $diamond = array();

            if (!empty($white_label->currency_type) && in_array($white_label->currency_type, array('AUD','USD','EUR'))) {
                $rate = CurrencyExchange::select('currency_rate')->where('currency_name','=', $white_label->currency_type)->first();
                if(empty($rate)){
                    $currency_rate = 1;
                }
                else
                {
                    $currency_rate = $rate->currency_rate;
                }
            }
            else
            {
                $currency_rate = 1;
            }

            $markup_price_array = $this->markupPriceArray($customer_id);

            foreach ($updatedItems as $value) {

                $orignal_rate = $value->rate + (($value->rate * ($cus_discount)) / 100);
                $supplier_price = ($orignal_rate * $value->carat);

                $procurment_price = AppHelper::procurmentPrice($supplier_price);

                if(!empty($markup_price_array))
                {
                    $percent = $this->markupPrice($markup_price_array[$diamond_type], $procurment_price);
                    $procurment_price = $procurment_price + (($percent / 100) * $procurment_price);
                }
                $carat_price = $procurment_price / $value->carat;

                $d_result['sku'] = $value->id;
                $d_result['availability'] = $value->availability;
                $d_result['diamond_type'] = $value->diamond_type;
                $d_result['isFavorite'] = 'test';
                $d_result['shape'] = $value->shape;
                $d_result['carat'] = (string)$value->carat;
                $d_result['color'] = $value->color;
                $d_result['clarity'] = $value->clarity;
                $d_result['cut'] = $value->cut;
                $d_result['polish'] = $value->polish;
                $d_result['symmetry'] = $value->symmetry;
                $d_result['fluorescence'] = $value->fluorescence;
                $d_result['eyeclean'] = $value->eyeclean;
                $d_result['lab'] = $value->lab;

                $d_result['length'] = $value->length;
                $d_result['width'] = $value->width;
                $d_result['depth'] = $value->depth;
                $d_result['table'] = $value->table_per;
                $d_result['depth_per'] = $value->depth_per;
                $d_result['certificate_no'] = $value->certificate_no;

                $d_result['country'] = $value->country;

                $d_result['rate'] = (string)round($carat_price * $currency_rate, 2);
                $d_result['net_price'] = (string)round($procurment_price * $currency_rate, 2);
                $d_result['discount_main'] = '';
                $d_result['raprate'] = '';

                $d_result['image'] = $value->cloud_image;
                $d_result['video'] = $value->video;

                $d_result['heart_image'] = is_null($value->cloud_heart) ? $value->heart : $value->cloud_heart;
                $d_result['arrow_image'] = is_null($value->cloud_arrow) ? $value->arrow : $value->cloud_arrow;
                $d_result['asset_image'] = is_null($value->cloud_asset) ? $value->asset : $value->cloud_asset;

                $d_result['crown_angle'] = $value->crown_angle;
                $d_result['crown_height'] = $value->crown_height;
                $d_result['pavilion_angle'] = $value->pavilion_angle;
                $d_result['pavilion_depth'] = $value->pavilion_depth;

                $d_result['gridle'] = $value->gridle;
                $d_result['culet'] = $value->culet_condition;

                $d_result['eyeclean'] = $value->eyeclean;
                $d_result['milky'] = $value->milky;
                $d_result['shade'] = $value->shade;
                $d_result['key_symbols'] = $value->key_symbols;
                $d_result['treatment'] = $value->com_load_typelib;

                if (!empty($value->Certificate_link)) {
                    $d_result['certi_link'] = $value->certificate_link;
                } else {
                    if ($value->lab == 'IGI') {
                        $d_result['certi_link'] = 'https://www.igi.org/viewpdf.php?r=' . $value->certificate_no;
                    } elseif ($value->lab == 'GIA') {
                        $d_result['certi_link'] = 'http://www.gia.edu/report-check?reportno=' . $value->certificate_no;
                    } elseif ($value->lab == 'HRD') {
                        $d_result['certi_link'] = 'https://my.hrdantwerp.com/?id=34&record_number=' . $value->certificate_no;
                    } elseif ($value->lab == 'GCAL') {
                        $d_result['certi_link'] = 'https://www.gcalusa.com/certificate-search.html?certificate_id=' . $value->certificate_no;
                    } else {
                        $d_result['certi_link'] = "";
                    }
                }
                $diamond[] = $d_result;
            }

            $result->setCollection(collect($diamond));

            if($diamond_type == "NATURAL")
            {
                // $count = $result->count().' Natural Stone Found... ';
                return response()->json([
                    'success' => true,
                    'message' => 'Diamond Found...',
                    'data' => $result
                ],201);
            }
            elseif ($diamond_type == "LABGROWN") {
                $count = $result->count().' Labgrown Stone Found... ';
                return response()->json([
                    'success' => true,
                    'message' => $count,
                    'data' => $result
                ],201);
            }
        }
        else
        {
            return response()->json([
                'success'=>false,
                'message'=>" User Token Invalid Try Again !!",
            ]);
        }
    }

    public static function markupPriceArray($customer_id)
	{
        $markup_array = array();

        $markup_array['NATURAL'] = array();
        $markup_array['LABGROWN'] = array();
		$white_markup = WhiteLabelMarkup::select('*')->where('customer_id',$customer_id)->get();
        if (!empty($white_markup)) {
            foreach ($white_markup as $markup_row) {
                $markup_array[$markup_row->diamond_type][$markup_row->min_range][$markup_row->max_range] = $markup_row->percentage;
            }
        }
        return $markup_array;
	}

    function markupPrice($markup_price_array, $procurment_price) {
		foreach ($markup_price_array as $key => $value) {
			if ($key <= $procurment_price && key($value) >= $procurment_price) {
				return reset($value);
			}
		}
	}

    public function DiamondOrder(Request $request)
    {

        $token = $request->token;
        if(empty($token))
        {
            return response()->json([
                'success'=>false,
                'message'=>"Token Missing"
            ]);
        }

        $white_label = WhiteLabel::where('token_key',$token)->first();
        $stone = $request->data;
        if(!empty($white_label))
        {
            $customer_id = $white_label->user_id;
            $customer_data = Customer::select('*', DB::raw('(SELECT email FROM users WHERE id = customers.added_by) as staffemail'))->where('cus_id', $customer_id)->first();
            $user_data = User::select('firstname','lastname')->where('id',$customer_id)->first();

            $kyc_status = true;
            $message = 'Please complete your profile to buy more diamond';

            if ($kyc_status) {
                $discount_user = $customer_data->discount;
                $lab_discount_user = $customer_data->lab_discount;
                $salespersonemail = $customer_data->staffemail;
                $firstname =$user_data->firstname;
                $lastname = $user_data->lastname;

                $ip = $_SERVER['REMOTE_ADDR'];
                $date = date('Y-m-d H:i:s');

                $certi_value = '';
                $lab_value = '';
                $natural_value = '';
                $value_arr = $lab_value_arr = $natural_value_arr = array();
                $stone = json_decode($stone, true);

                foreach ($stone as $t) {
                    if ($t['diamond_type'] == "L") {
                        $lab_value .= "'" . $t['certi'] . "',";
                        $lab_value_arr[] = $t['certi'];
                    } else {
                        $natural_value .= "'" . $t['certi'] . "',";
                        $natural_value_arr[] = $t['certi'];
                    }

                    $certi_value .= "'" . $t['certi'] . "',";
                    $value_arr[] = $t['certi'];
                }

                $certi_value = trim($certi_value, ",");
                $natural_value = trim($natural_value, ",");
                $lab_value = trim($lab_value, ",");

                $pdftotalnetprice = 0;
                $pdftotalcarat = 0;
                $total_carat = 0;
                $total_stone = 0;
                $order_items_ids = $tablerecord = '';
                $total = 0;

                if (empty($certi_value)) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Please Select atleast One Diamond'
                    ], 201);
                }

                if (!empty($lab_value)) {
                    DiamondLabgrown::whereIn('certificate_no', $lab_value_arr)->update(array('status' => '1'));
                }
                if (!empty($natureal_value)) {
                    DiamondNatural::whereIn('certificate_no', $natural_value_arr)->update(array('status' => '1'));
                }

                $data = Order::whereIn('certificate_no', $value_arr)->get();

                if (!empty($data->toArray())) {
                    return response()->json([
                        'success' => true,
                        'message' => "Some Dimaond Already orders"
                    ]);
                } else {
                    WishList::whereIn('certificate_no', $value_arr)->where('customer_id', $customer_id)->delete();
                    // DB::table('view_diamond_detail')->whereIn('certificate_no', $value_arr)->delete();

                    $total_approved = 0; //$this->Dashboard_Model->sumApproveOrderAprice();
                    $total_confirm = 0; //!empty($total_approved->total_confirm) ? $total_approved->total_confirm : 0;
                    $total_a_confirm = 0; //!empty($total_approved->total_a_confirm) ? $total_approved->total_a_confirm : 0;

                    $dprice = $total_price = 0;
                    foreach ($stone as $value) {
                        if ($value['diamond_type'] == "L") {
                            $diamond_detail = DiamondLabgrown::select(
                                '*',
                                DB::raw('(SELECT pricechange FROM markup_setting WHERE (net_dollar BETWEEN `min_range` and max_range)) as aditional_discount'),
                                DB::raw("(SELECT caratprice FROM rap_list WHERE IF(`shape_name` = 'ROUND', shape = 'ROUND', shape != 'ROUND') AND color_name = `color` AND clarity_name = IF(`clarity` = 'FL','IF', `clarity`) AND low_size <= carat AND high_size >= carat) as raprate"),
                                DB::raw('(SELECT hold_allow FROM suppliers WHERE sup_id = supplier_id AND stock_status = "ACTIVE" LIMIT 1) as hold_allow'),
                                DB::raw('(SELECT email FROM users WHERE id = supplier_id) as suplier_email')
                            )
                                ->where('certificate_no', $value['certi'])->first();

                            if (empty($diamond_detail)) {
                                continue;
                            }
                            $loat_no_html = $diamond_detail->id;
                            $v_discount = $lab_discount_user;
                        } else {

                            $diamond_detail = DiamondNatural::select(
                                '*',
                                DB::raw('(SELECT pricechange FROM markup_setting WHERE (net_dollar BETWEEN `min_range` and max_range)) as aditional_discount'),
                                DB::raw("(SELECT caratprice FROM rap_list WHERE IF(`shape_name` = 'ROUND', shape = 'ROUND', shape != 'ROUND') AND color_name = `color` AND clarity_name = IF(`clarity` = 'FL','IF', `clarity`) AND low_size <= carat AND high_size >= carat) as raprate"),
                                DB::raw('(SELECT hold_allow FROM suppliers WHERE sup_id = supplier_id AND stock_status = "ACTIVE" LIMIT 1) as hold_allow'),
                                DB::raw('(SELECT email FROM users WHERE id = supplier_id) as suplier_email')
                            )
                                ->where('certificate_no', $value['certi'])->first();
                            if (empty($diamond_detail)) {
                                continue;
                            }
                            $loat_no_html = $diamond_detail->id;
                            $v_discount = $discount_user;
                        }

                        $total_stone = $total_stone + 1;
                        $suplier_email    = $diamond_detail->suplier_email;

                        // $cc_email		= $diamond_detail->broker_email;
                        // $salesemail		= $diamond_detail->sales_email;

                        $orignal_rate = $diamond_detail->rate + (($diamond_detail->rate * ($v_discount)) / 100);
                        $supplier_price = ($orignal_rate * $diamond_detail->carat);

                        if($supplier_price <= 1000)
                        {
                            $procurment_price = $supplier_price + 25;
                        }
                        else if($supplier_price >= 7000)
                        {
                            $procurment_price = $supplier_price + 140;
                        }
                        else if($supplier_price > 1000 && $supplier_price < 7000)
                        {
                            $procurment_price = $supplier_price + round((2 / 100) * $supplier_price, 2);
                        }
                        $carat_price = $procurment_price / $diamond_detail->carat;

                        $supplier_price = $orignal_rate * $diamond_detail->carat;
                        $supplier_discount = !empty($diamond_detail->raprate) ? round(($orignal_rate - $diamond_detail->raprate) / $diamond_detail->raprate * 100, 2) : 0;

                        $procurment_discount = !empty($diamond_detail->raprate) ? round(($carat_price - $diamond_detail->raprate) / $diamond_detail->raprate * 100, 2) : 0;
                        // $procurment_price = $supplier_price;

                        $return_price = 0;
                        if($value['return'] == "yes")
                        {
                            $return_price = round((1 / 100) * $procurment_price, 2);
                        }
                        $sale_price = $procurment_price + $return_price;
                        $sale_rate = $sale_price / $diamond_detail->carat;
                        $sale_discount = !empty($diamond_detail->raprate) ? round(($sale_rate - $diamond_detail->raprate) / $diamond_detail->raprate * 100, 2) : 0;

                        $buy_rate = $diamond_detail->orignal_rate;
                        $buy_price = round($buy_rate * $diamond_detail->carat, 5);
                        $buy_discount = !empty($diamond_detail->raprate) ? round(($buy_rate - $diamond_detail->raprate) / $diamond_detail->raprate * 100, 2) : 0;

                        // $total = $net_price + $total;
                        // $pdftotalnetprice = $pdftotalnetprice + $net_price;
                        // $pdftotalcarat = $pdftotalcarat + $carat;

                        $data_array = array(
                            'customer_id' => $customer_id,
                            'certificate_no' => $diamond_detail->certificate_no,
                            'ref_no' => $diamond_detail->ref_no,
                            'diamond_type' => $value['diamond_type'],
                            'sale_discount' => $sale_discount,
                            'sale_price' => $sale_price,
                            'sale_rate' => $sale_rate,
                            'buy_price' => $buy_price,
                            'buy_rate' => $buy_rate,
                            'buy_discount' => $buy_discount,
                            'return_price' => $return_price,
                            'ip' => $ip,
                            'created_at' => $date,
                        );
                        $last_order_id = Order::insertGetId($data_array);

                        $order_items_ids .= $last_order_id . ',';
                        if ($value['diamond_type'] == "L") {
                            DB::insert("INSERT INTO `orders_items` (`orders_id`,`customer_id`, `id`, `supplier_id`, `supplier_name`, `ref_no`, `availability`, `diamond_type`, `shape`, `carat`, `color`, `clarity`, `cut`, `polish`, `symmetry`, `fluorescence`, `lab`, `certificate_no`, `certificate_link`, `certificate_download`, `length`, `width`, `depth`, `location`, `city`, `country`, `milky`, `eyeclean`, `hna`, `depth_per`, `table_per`, `crown_angle`, `crown_height`, `pavilion_angle`, `pavilion_depth`, `discount`, `rap`, `orignal_rate`, `rate`, `net_dollar`, `key_symbols`, `fancy_color`, `fancy_intensity`, `fancy_overtone`, `image_status`, `cloud_image`, `image`, `video`, `heart`, `cloud_heart`, `arrow`, `cloud_arrow`, `asset`, `cloud_asset`, `canada_mark`, `cutlet`, `luster`, `gridle`, `gridle_per`, `girdle_thin`, `girdle_thick`, `shade`, `c_type`, `status`, `supplier_comments`, `culet_condition`, `hold_for`, `updated_at`, `created_at`, `is_delete`)
                                        SELECT '$last_order_id','$customer_id', `id`, `supplier_id`, `supplier_name`, `ref_no`, `availability`, 'L', `shape`, `carat`, `color`, `clarity`, `cut`, `polish`, `symmetry`, `fluorescence`, `lab`, `certificate_no`, `certificate_link`, `certificate_download`, `length`, `width`, `depth`, `location`, `city`, `country`, `milky`, `eyeclean`, `hna`, `depth_per`, `table_per`, `crown_angle`, `crown_height`, `pavilion_angle`, `pavilion_depth`, `discount`, '$diamond_detail->raprate', `orignal_rate`, `rate`, `net_dollar`, `key_symbols`, `fancy_color`, `fancy_intensity`, `fancy_overtone`, `image_status`, `cloud_image`, `image`, `video`, `heart`, `cloud_heart`, `arrow`, `cloud_arrow`, `asset`, `cloud_asset`, `canada_mark`, `cutlet`, `luster`, `gridle`, `gridle_per`, `girdle_thin`, `girdle_thick`, `shade`, `c_type`, `status`, `supplier_comments`, `culet_condition`, `hold_for`, `updated_at`, '$date', `is_delete`
                                        FROM diamond_labgrown WHERE certificate_no = '" . $diamond_detail->certificate_no . "'");

                            if ($diamond_detail->color == "fancy") {
                                $color = $diamond_detail->fancy_intensity . ' ' . $diamond_detail->fancy_overtone . ' ' . $diamond_detail->fancy_color;
                            } else {
                                $color = $diamond_detail->color;
                            }
                        } else {
                            DB::insert("INSERT INTO `orders_items` (`orders_id`,`customer_id`, `id`, `supplier_id`, `supplier_name`, `ref_no`, `availability`, `diamond_type`, `shape`, `carat`, `color`, `clarity`, `cut`, `polish`, `symmetry`, `fluorescence`, `lab`, `certificate_no`, `certificate_link`, `certificate_download`, `length`, `width`, `depth`, `location`, `city`, `country`, `milky`, `eyeclean`, `hna`, `depth_per`, `table_per`, `crown_angle`, `crown_height`, `pavilion_angle`, `pavilion_depth`, `discount`, `rap`, `orignal_rate`, `rate`, `net_dollar`, `key_symbols`, `fancy_color`, `fancy_intensity`, `fancy_overtone`, `image_status`, `cloud_image`, `image`, `video`, `heart`, `cloud_heart`, `arrow`, `cloud_arrow`, `asset`, `cloud_asset`, `canada_mark`, `cutlet`, `luster`, `gridle`, `gridle_per`, `girdle_thin`, `girdle_thick`, `shade`, `c_type`, `status`, `supplier_comments`, `culet_condition`, `hold_for`, `updated_at`, `created_at`, `is_delete`)
                                        SELECT '$last_order_id','$customer_id', `id`, `supplier_id`, `supplier_name`, `ref_no`, `availability`, 'W', `shape`, `carat`, `color`, `clarity`, `cut`, `polish`, `symmetry`, `fluorescence`, `lab`, `certificate_no`, `certificate_link`, `certificate_download`, `length`, `width`, `depth`, `location`, `city`, `country`, `milky`, `eyeclean`, `hna`, `depth_per`, `table_per`, `crown_angle`, `crown_height`, `pavilion_angle`, `pavilion_depth`, `discount`, '$diamond_detail->raprate', `orignal_rate`, `rate`, `net_dollar`, `key_symbols`, `fancy_color`, `fancy_intensity`, `fancy_overtone`, `image_status`, `cloud_image`, `image`, `video`, `heart`, `cloud_heart`, `arrow`, `cloud_arrow`, `asset`, `cloud_asset`, `canada_mark`, `cutlet`, `luster`, `gridle`, `gridle_per`, `girdle_thin`, `girdle_thick`, `shade`, `c_type`, `status`, `supplier_comments`, `culet_condition`, `hold_for`, `updated_at`, '$date', `is_delete`
                                        FROM diamond_natural WHERE certificate_no = '" . $diamond_detail->certificate_no . "'");

                            if ($diamond_detail->color == "fancy") {
                                $color = $diamond_detail->fancy_intensity . ' ' . $diamond_detail->fancy_overtone . ' ' . $diamond_detail->fancy_color;
                            } else {
                                $color = $diamond_detail->color;
                            }
                        }

                        $tablerecord .= '<table width="100%" style="border:#CEC9C9 solid thin;padding: 10px;font-size: 13px;">
                            <tr>
                                <td width="25%">
                                    <strong>' . $loat_no_html . '</strong>
                                </td>
                                <td width="30%">
                                    <span><strong>' . $diamond_detail->lab . ': </strong> <strong> ' . $diamond_detail->certificate_no . '</strong></span>
                                </td>
                                <td width="30%" align="right"> <strong> $/CT &nbsp;$' . number_format($carat_price, 2) . '</strong></td>
                            </tr>
                            <tr>
                                <td colspan="2" width="70%">
                                    <span style="font-weight: 600">' . $diamond_detail->shape . ' ' . $diamond_detail->carat . 'CT ' . $color . ' ' . $diamond_detail->clarity . ' ' . $diamond_detail->cut . ' ' . $diamond_detail->polish . ' ' . $diamond_detail->symmetry . ' ' . $diamond_detail->fluorescence . '</span>
                                </td>
                                <td width="30%" align="right"><strong>Total &nbsp;$' . number_format($sale_price, 2) . '</strong></td>
                            </tr>
                        </table>';

                        $singlerecord = '<table width="100%" style="border:#CEC9C9 solid thin;padding: 10px;font-size: 13px;">
                            <tr>
                                <td width="25%">
                                    <span><a href="" style="text-decoration-color: #4f4f4f"><strong>' . $diamond_detail->ref_no . '</strong></a></span>
                                </td>
                                <td width="30%">
                                    <span><strong>' . $diamond_detail->lab . ': </strong><a href="" style="text-decoration-color: #4f4f4f"> <strong> ' . $diamond_detail->certificate_no . '</strong></a></span>
                                </td>
                                <td width="30%" align="right"> <strong> $/CT &nbsp;$' . number_format($diamond_detail->orignal_rate, 2) . '</strong></td>
                            </tr>
                            <tr>
                                <td colspan="2" width="70%">
                                    <span style="font-weight: 600">' . $diamond_detail->shape . ' ' . $diamond_detail->carat . 'CT ' . $color . ' ' . $diamond_detail->clarity . ' ' . $diamond_detail->cut . ' ' . $diamond_detail->polish . ' ' . $diamond_detail->symmetry . ' ' . $diamond_detail->fluorescence . '</span>
                                </td>
                                <td width="30%" align="right"><strong>Total &nbsp;$' . number_format($diamond_detail->orignal_rate * $diamond_detail->carat, 2) . '</strong></td>
                            </tr>
                        </table>';

                        $supplier_email_data = array();
                        $supplier_email_data['firstname'] = $diamond_detail->supplier_name;
                        $supplier_email_data['text_message'] = $singlerecord;

                        Mail::send('emails.orders.hold-diamond-supplier', $supplier_email_data, function ($message) use ($suplier_email) {
                            $message->from(\Cons::EMAIL_SUPPLIER, 'Supplier');
                            $message->to($suplier_email);
                            $message->cc(\Cons::EMAIL_SUPPLIER);
                            $message->subject("Hold Diamond Request Received On " . date('d-m-Y H') . " | " . env('APP_NAME'));
                        });
                    }


                    $email_data['firstname'] = $firstname;
                    $email_data['text_message'] = $tablerecord;

                    //TODO::: Remove when Live
                    Mail::send('emails.orders.hold-confirm-diamond-customer', $email_data, function ($message) use ($last_order_id) {
                        $message->to(\Cons::EMAIL_SALE);
                        $message->subject('New order place please confirm - ' . Auth::user()->email . ' #' . $last_order_id);
                    });


                    $email_data['firstname'] = $firstname;
                    $email_data['text_message'] = $tablerecord;

                    Mail::send('emails.orders.hold-confirm-diamond-customer', $email_data, function ($message) use ($last_order_id) {
                        $message->to(Auth::user()->email);
                        $message->subject('Thank you for your order ' . date('d-m-Y') . " | " . env('APP_NAME'));
                    });
                }
                return response()->json([
                    'success' => true,
                    'message' => 'YOur order Placed'
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Please Upload KYC Dcoument'
                ], 401);
            }
        }
        else
        {
            return response()->json([
                'success'=>false,
                'message'=>"Invalide Token"
            ]);
        }
    }
}

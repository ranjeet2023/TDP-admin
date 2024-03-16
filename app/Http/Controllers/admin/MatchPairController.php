<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\AppHelper;
use Illuminate\Support\Facades\Auth;

use DB;

use App\Models\User;
use App\Models\DiamondNatural;
use App\Models\DiamondLabgrown;

class MatchPairController extends Controller
{
    public function matchPair(Request $request){
        $diamonds = DiamondNatural::where('carat','>',0.08)->limit(100)->get();

        $type = 'natural';
        if(count($diamonds) == 0){
            $diamonds = DiamondLabgrown::where('carat','>',0.08)->limit(100)->get();
            $type = 'lab_grown';
        }
        $data['diamonds'] = $diamonds;
        $data['type'] = $type;
        return view('admin.match-pair')->with($data);
    }

    public function matchPairSearch(Request $request){
        $diamond_type = ($request->type == 'natural') ? 'diamond_natural' : 'diamond_labgrown';
        $diamonds = DB::table($diamond_type)->select('*');

        if(!empty($request->min_carat) && !empty($request->min_carat)){
            $min_carat = $request->min_carat;
            $max_carat = $request->max_carat;

            $diamonds->where('carat','>=',$min_carat);
            $diamonds->where('carat','<=',$max_carat);
        }
        if(!empty($request->shape))
        {
            $diamonds->whereIn('shape',explode(",",$request->shape));
        }
        if(!empty($request->color))
        {
            $diamonds->whereIn('color',explode(",",$request->color));
        }
        if(!empty($request->clarity))
        {
            $diamonds->whereIn('clarity',explode(",",$request->clarity));
        }
        if(!empty($request->cut))
        {
            $diamonds->whereIn('cut',explode(",",$request->cut));
        }
        if(!empty($request->polish))
        {
            $diamonds->whereIn('polish',explode(",",$request->polish));
        }
        if(!empty($request->symmetry))
        {
            $diamonds->whereIn('symmetry',explode(",",$request->symmetry));
        }
        if(!empty($request->fluorescence))
        {
            $diamonds->whereIn('fluorescence',explode(",",$request->fluorescence));
        }
        if(!empty($request->location))
        {
            $diamonds->whereIn('country',explode(",",$request->location));
        }
        if(!empty($request->lab))
        {
            $diamonds->whereIn('lab',explode(",",$request->lab));
        }
        if(!empty($request->eyeclean))
        {
            $diamonds->whereIn('eyeclean',explode(",",$request->eyeclean));
        }
        if(!empty($request->c_type))
        {
            $diamonds->whereIn('c_type',explode(",",$request->c_type));
        }

        $start_from = $request->page * 5;

        $searched_diamonds = $diamonds->limit(100)->offset($start_from)->get();
        $match_diamonds = $diamonds->limit(5)->offset($start_from)->get();

        $diamonds = [];
        dd($searched_diamonds);
        foreach($match_diamonds as $diamond){

            $color_arr = ['D','E','F','G','H','I','J','K','L','M','N','OP','QR','ST','UV','WX','YZ'];
            $color_key = array_search($diamond->color,$color_arr);
            if($diamond->color == 'D'){
                $color_in = ['D',$color_arr[$color_key+1]];
            }elseif($diamond->color == 'YZ'){
                $color_in = ['YZ',$color_arr[$color_key-1]];
            }else{
                $color_in = [$color_arr[$color_key-1],$diamond->color,$color_arr[$color_key+1]];
            }

            $clarity_arr = ['FL','IF','VVS1','VVS2','VS1','VS2','SI1','SI2','I1','I2','I3'];
            $clarity_key = array_search($diamond->clarity,$clarity_arr);
            if($diamond->clarity == 'FL'){
                $clarity_in = ['FL',$clarity_arr[$clarity_key+1]];
            }elseif($diamond->clarity == 'I3'){
                $clarity_in = ['I3',$clarity_arr[$clarity_key-1]];
            }else{
                $clarity_in = [$clarity_arr[$clarity_key-1],$diamond->clarity,$clarity_arr[$clarity_key+1]];
            }

            $polish_arr = ['EX','VG','GD','FR','PR'];
            $polish_key = array_search($diamond->polish,$polish_arr);
            if($diamond->polish == 'EX'){
                $polish_in = ['EX',$polish_arr[$polish_key+1]];
            }elseif($diamond->polish == 'PR'){
                $polish_in = ['PR',$polish_arr[$polish_key-1]];
            }else{
                $polish_in = [$polish_arr[$polish_key-1],$diamond->polish,$polish_arr[$polish_key+1]];
            }

            $symmetry_key = array_search($diamond->symmetry,$polish_arr);
            if($diamond->symmetry == 'EX'){
                $symmetry_in = ['EX',$polish_arr[$symmetry_key+1]];
            }elseif($diamond->symmetry == 'PR'){
                $symmetry_in = ['PR',$polish_arr[$symmetry_key-1]];
            }else{
                $symmetry_in = [$polish_arr[$symmetry_key-1],$diamond->symmetry,$polish_arr[$symmetry_key+1]];
            }

            $flour_arr = ['NON','FNT','MED','SLIGHT','STG','VST','VSLT'];
            $flour_key = array_search($diamond->fluorescence,$flour_arr);
            if($diamond->fluorescence == 'NON'){
                $flour_in = ['NON',$flour_arr[$flour_key+1]];
            }elseif($diamond->polish == 'PR'){
                $flour_in = ['PR',$flour_arr[$flour_key-1]];
            }else{
                $flour_in = [$flour_arr[$flour_key-1],$diamond->fluorescence,$flour_arr[$flour_key+1]];
            }

            $match_pair = $searched_diamonds->filter(function($item) use($diamond,$color_in,$clarity_in,$polish_in,$symmetry_in,$flour_in) {
                return ($item->id != $diamond->id && whereBetween($item->carat,[($diamond->carat-0.02),($diamond->carat+0.02)]) && in_array($item->color,$color_in) && in_array($item->clarity,$clarity_in) && in_array($item->polish,$polish_in) && in_array($item->symmetry,$symmetry_in) && in_array($item->fluorescence,$flour_in));
            })->first();
            dd($match_pair);
        }
        //     $match_pair = DB::table($diamond_type)->select('*')->where('shape',$diamond->shape)->where('supplier_id',$diamond->supplier_id)
        //     ->whereBetween('carat',[$diamond->carat-0.02,$diamond->carat+0.02]);

        //     $color_arr = ['D','E','F','G','H','I','J','K','L','M','N','OP','QR','ST','UV','WX','YZ'];
        //     $color_key = array_search($diamond->color,$color_arr);
        //     if($diamond->color == 'D'){
        //         $color_in = ['D',$color_arr[$color_key+1]];
        //     }elseif($diamond->color == 'YZ'){
        //         $color_in = ['YZ',$color_arr[$color_key-1]];
        //     }else{
        //         $color_in = [$color_arr[$color_key-1],$diamond->color,$color_arr[$color_key+1]];
        //     }
        //     $clarity_arr = ['FL','IF','VVS1','VVS2','VS1','VS2','SI1','SI2','I1','I2','I3'];
        //     $clarity_key = array_search($diamond->clarity,$clarity_arr);
        //     if($diamond->clarity == 'FL'){
        //         $clarity_in = ['FL',$clarity_arr[$clarity_key+1]];
        //     }elseif($diamond->clarity == 'I3'){
        //         $clarity_in = ['I3',$clarity_arr[$clarity_key-1]];
        //     }else{
        //         $clarity_in = [$clarity_arr[$clarity_key-1],$diamond->clarity,$clarity_arr[$clarity_key+1]];
        //     }
        //     $polish_arr = ['EX','VG','GD','FR','PR'];
        //     $polish_key = array_search($diamond->polish,$polish_arr);
        //     if($diamond->polish == 'EX'){
        //         $polish_in = ['EX',$polish_arr[$polish_key+1]];
        //     }elseif($diamond->polish == 'PR'){
        //         $polish_in = ['PR',$polish_arr[$polish_key-1]];
        //     }else{
        //         $polish_in = [$polish_arr[$polish_key-1],$diamond->polish,$polish_arr[$polish_key+1]];
        //     }
        //     $symmetry_key = array_search($diamond->symmetry,$polish_arr);
        //     if($diamond->symmetry == 'EX'){
        //         $symmetry_in = ['EX',$polish_arr[$symmetry_key+1]];
        //     }elseif($diamond->symmetry == 'PR'){
        //         $symmetry_in = ['PR',$polish_arr[$symmetry_key-1]];
        //     }else{
        //         $symmetry_in = [$polish_arr[$symmetry_key-1],$diamond->symmetry,$polish_arr[$symmetry_key+1]];
        //     }

        //     $flour_arr = ['NON','FNT','MED','SLIGHT','STG','VST','VSLT'];
        //     $flour_key = array_search($diamond->fluorescence,$flour_arr);
        //     if($diamond->fluorescence == 'NON'){
        //         $flour_in = ['NON',$flour_arr[$flour_key+1]];
        //     }elseif($diamond->polish == 'PR'){
        //         $flour_in = ['PR',$flour_arr[$flour_key-1]];
        //     }else{
        //         $flour_in = [$flour_arr[$flour_key-1],$diamond->fluorescence,$flour_arr[$flour_key+1]];
        //     }
        //         $match_pair = $match_pair->wherenot('id',$diamond->id)->whereIn('color',$color_in)
        //                         ->whereIn('clarity',$clarity_in)
        //                         ->whereIn('polish',$polish_in)
        //                         ->whereIn('symmetry',$symmetry_in)
        //                         ->whereIn('fluorescence',$flour_in)
        //                         ->whereBetween('length',[$diamond->length-0.02,$diamond->length+0.02])
        //                         ->whereBetween('width',[$diamond->width-0.02,$diamond->width+0.02])
        //                         ->whereBetween('depth',[$diamond->depth-0.02,$diamond->depth+0.02])
        //                         ->first();
        //     $diamonds[] = $diamond;
        //     $diamonds[] = $match_pair;

        $data['diamonds'] = $diamonds;
        return json_encode($data);
    }
}

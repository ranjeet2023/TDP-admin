<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class PricingController extends Controller
{
    public function pricesetting()
    {
        $data['round'] = DB::table('markup_setting')->get();
        // dd($data['round']);
        return view('admin.pricing.price-setting')->with($data);
    }

    public function postPricesetting(Request $request)
    {
        foreach($request->toArray() as $key => $value)
        {
            DB::table('markup_setting')->where('price_id', $key)->update(array('pricechange' => $value));
        }

        return redirect("pricesetting")->with('success','Price Updated Successful');
    }

    public function shippingpricesetting()
    {
        $data['country_lists'] = DB::table('shipping_price')->groupby('location')->get();

        return view('admin.pricing.shipping-price-setting')->with($data);
    }

    public function shippingpricelist(Request $request)
    {
        $data['pricelist'] = DB::table('shipping_price')->where('location', $request->id)->get();

        return json_encode($data);
    }

    public function saveShippingPriceList(Request $request)
    {
        foreach($request->toArray() as $key => $value)
        {
            DB::table('shipping_price')->where('id', $key)->update(array('pricechange' => $value));
        }

        return true;
    }

    public function priceMarkupsetting()
    {
        $data['round'] = DB::table('price_markup_setting')->get();

        return view('admin.pricing.price-markup-setting')->with($data);
    }

    public function postPriceMarkupsetting(Request $request)
    {
        foreach($request->price_id as $key => $value)
        {
            DB::table('price_markup_setting')->where('price_id', $key)->update(array('pricechange' => $value));
        }

        return redirect("price-markup-setting")->with('success','Price Updated Successful');
    }

}

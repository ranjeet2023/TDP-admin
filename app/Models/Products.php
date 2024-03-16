<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProductList;

class Products extends Model
{
    use HasFactory;
    public $fillable = ['product_id','item_code','product_type','samedesign_code','product_name','product_title','slug','categoryid','category_name','appxmetalwgt','cwgt','parent_item','design_code','metaltype','metalstamp','cmetaltypename','cmetalstampname','metalwithstamp','settingtype','image','havechain','purity','mtype','cpurity','ctype','currency','productcost'];
    protected $table = 'products';
    public function productlist()
    {
        return $this->hasMany(ProductList::class,'product_id','product_id');
    }
    protected $hidden = [
        'id',
    ];
}

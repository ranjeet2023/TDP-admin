<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Products;

class ProductList extends Model
{
    use HasFactory;

    public $fillable = ['productid','product_type','stonetype','lab','certno','certurl','shape','fromcolor','tocolor','fromclarity','toclarity','fromcut','tocut','fromsize','tosize','pieces','totalwgt','price','isgem'];


    public function product()
    {
        return $this->belongsTo(Products::class);
    }
    protected $hidden = [
      'id',
    ];
}

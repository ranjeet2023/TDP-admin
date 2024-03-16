<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = "cart";

    public $timestamps = ["created_at"];
    const UPDATED_AT = null;

    public function users(){
        return $this->hasOne(User::class,'id','customer_id');
    }

    public function diamondNatural(){
        return $this->hasOne(DiamondNatural::class,'certificate_no','certificate_no');
    }

    public function diamondLabGrown(){
        return $this->hasOne(DiamondLabgrown::class,'certificate_no','certificate_no');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class returnDiamond extends Model
{
    use HasFactory;

    protected $table = 'returndiamond';

    public function orderItems(){
        return $this->hasOne(OrderItem::class,'certificate_no','certificate_no');
    }

}

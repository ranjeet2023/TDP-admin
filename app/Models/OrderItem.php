<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    protected $table = 'orders_items';

    public function customer(){
        return $this->hasOne(User::class,'id','customer_id');
    }

    public function supplier(){
        return $this->hasOne(User::class,'id','supplier_id');
    }

    public function invoiceNo(){
        return $this->hasOne(Pickups::class,'orders_id','orders_id');
    }

    public function exportNo(){
        return $this->hasOne(Pickups::class,'orders_id','orders_id');
    }

    public function orders(){
        return $this->hasOne(Order::class,'orders_id','orders_id');
    }
}

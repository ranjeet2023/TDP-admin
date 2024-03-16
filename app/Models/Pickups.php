<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pickups extends Model
{
    use HasFactory;

    protected $table = 'pickups';

    public function qc_list(){
        return $this->hasOne(QCList::class,'order_id','orders_id');
    }

    public function orders(){
        return $this->hasOne(Order::class,'orders_id','orders_id');
    }

    public function orderitems(){
        return $this->hasOne(OrderItem::class,'orders_id','orders_id');
    }

    public function invoices(){
        return $this->hasOne(Invoice::class,'invoice_number','invoice_number');
    }
}

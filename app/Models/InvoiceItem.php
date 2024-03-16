<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;
    protected $table = "invoice_items";

    public $timestamps = ["created_at"];
    const  UPDATED_AT = null;

    public function orders_items(){
        return $this->hasOne(OrderItem::class,'orders_id','orders_id');
    }
    public function Orders(){
        return $this->hasOne(Order::class,'orders_id','orders_id');
    }
    public function invoices(){
        return $this->hasOne(Invoice::class,'invoice_id','invoice_id');
    }
    public function customers(){
        return $this->hasOne(Customer::class,'cus_id','customer_id');
    }
    public function pickups(){
        return $this->hasOne(Pickups::class,'orders_id','orders_id');
    }

}

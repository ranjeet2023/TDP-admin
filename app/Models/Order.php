<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    // protected $with = ['orders_items'];
    public $timestamps = ["updated_at"];
    const CREATED_AT = null;

    public function orderdetail()
    {
        return $this->hasOne(OrderItem::class, 'orders_id','orders_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'customer_id');
    }

    public function pickups(){
        return $this->hasOne(Pickups::class,'orders_id','orders_id');
    }

    public function qc_list(){
        return $this->hasOne(QCList::class,'order_id','orders_id');
    }

    public function customer(){
        return $this->hasOne(Customer::class,'cus_id','customer_id');
    }

    public function order_comment(){
        return $this->hasMany(OrderComment::class,'order_id','orders_id');
    }

}

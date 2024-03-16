<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingDestination extends Model
{
    use HasFactory;

    protected $table = 'shipping_destination';

    protected $primaryKey = 'add_id';

    protected $guarded = [];

    public function user()
    {
        return $this->hasOne(User::class,'id','customer_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParcelGood extends Model
{
    use HasFactory;

    public function user() {
        return $this->hasOne(User::class,'id','customer_id');
    }

    Public function customers(){
        return $this->hasOne(User::class,'id','customer_id');
    }
}

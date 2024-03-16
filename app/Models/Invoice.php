<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $table="invoices";

    public function associates(){
        return $this->hasOne(Associates::class,'id','associates_id');
    }

    public function customers(){
        return $this->hasOne(User::class,'id','customer_id');
    }

}

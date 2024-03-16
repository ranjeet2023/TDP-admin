<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    public function createdbyuser(){
        return $this->hasOne(User::class,'id','created_by_userID');
    }

    public function assigntouser(){
        return $this->hasOne(User::class,'id','assign_to');
    }
    

}

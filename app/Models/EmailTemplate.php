<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    use HasFactory;
    protected $table = 'template';
    protected $guarded = [];

    public function createdbyuser(){
        return $this->hasOne(User::class,'id','user_id');
    }

}

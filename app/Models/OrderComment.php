<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderComment extends Model
{
    protected $table="order_comments";

    protected $primaryKey = 'order_id';
    use HasFactory;

    public function users(){
        return $this->hasOne(User::class,'id','user_id');
    }
}

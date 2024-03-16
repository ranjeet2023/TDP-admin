<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    /**
    * @var  string
    */
    protected $table = 'customers';

    public function user()
    {
        return $this->hasOne(User::class,'id','cus_id');
    }

    public function customeruser()
    {
        return $this->hasOne(User::class, 'id','cus_id')->where('user_type', 2)->where('is_delete',0);
    }

    public function staffname()
    {
        return $this->hasOne(static::class, 'added_by', 'id')->select('firstname','lastname','email');
    }

}

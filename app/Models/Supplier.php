<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    /**
    * @var  string
    */
    protected $primaryKey = 'sup_id';
    protected $table = 'suppliers';

    public function users(){
        return $this->hasOne(User::class,'id','sup_id');
    }
}

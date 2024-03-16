<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierStatusHistory extends Model
{
    use HasFactory;

    protected $primaryKey = 'sup_id';
    protected $table = 'supplier_status_history';

    public function users(){
        return $this->hasOne(user::class,'id','sup_id');
    }

    public function updatedBy(){
        return $this->hasOne(user::class,'id','updated_by');
    }
}


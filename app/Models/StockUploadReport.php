<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockUploadReport extends Model
{
    use HasFactory;
    protected $table = 'stock_upload_report';

    public function supplier()
    {
        return $this->hasOne(Supplier::class,'sup_id','supplier_id');
    }

    public function users(){
        return $this->hasOne(User::class,'id','supplier_id');
    }
}

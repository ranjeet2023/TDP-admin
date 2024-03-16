<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;

class DiamondNatural extends Model
{
    use Sortable;

    use HasFactory;

    protected $table = 'diamond_natural';

    protected $primaryKey = 'id';

    public $sortable = ['carat','net_dollar','orignal_rate'];

    public function suppliers(){
        return $this->hasOne(Supplier::class,'sup_id','supplier_id');
    }

    public function users(){
        return $this->hasOne(User::class,'id','supplier_id');
    }
}

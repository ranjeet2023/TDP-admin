<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchLog extends Model
{
    use HasFactory;

    protected $table = "search_log";
    protected $fillable = ['user_id','diamond_type','certificate_no','carat','shape','color','clarity','cut','polish','symmetry','fluorescence','lab','EyeC','search_date','ip'];

    public $timestamps  = false;

    public function users(){
        return $this->hasOne(User::class,'id','user_id');
    }
}

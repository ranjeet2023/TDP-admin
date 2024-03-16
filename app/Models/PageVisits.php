<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageVisits extends Model
{
    use HasFactory;

    protected $table = 'page_visits';

    public function users(){
        return $this->hasOne(User::class,'id','user_id');
    }
}

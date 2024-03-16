<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Lead;

class LeadsComment extends Model
{
    use HasFactory;

    protected $table="leads_comment";

    public function createdBy(){
        return $this->hasOne(User::class,'id','created_by');
    }

    public function leads()
    {
        return $this->belongsTo('App\Models\Lead');
    }

}

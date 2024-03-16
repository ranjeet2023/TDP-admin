<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyReporting extends Model
{
    use HasFactory;

    protected $table = 'daily_reporting';

    public function users(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}

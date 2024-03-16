<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apilog extends Model
{
    use HasFactory;
    protected $table = 'api_log';

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'customer_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IpProxy extends Model
{
    use HasFactory;
    protected $table = 'ip_proxy';

    protected  $fillable = ['ip','proxy','isp'];

    const CREATED_AT = null;
    const UPDATED_AT = null;
}

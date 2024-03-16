<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Associates extends Model
{
    use HasFactory;
    protected $table = 'associates';
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'mobile',
        'Account_number',
        'Bank_name ',
        'Bank_address',
        'Swift_code',
        'Address_code',
    ];
}

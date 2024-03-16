<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WishList extends Model
{
    use HasFactory;

    protected $table = 'wish_list';

    public $fillable = ['customer_id','certificate_no','diamond_type','is_delete'];

    public $timestamps = ["created_at"];
    const UPDATED_AT = null;

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserHasPermission extends Model
{
    use HasFactory;

    protected $table = 'user_has_permission';

    public function permission(){
        return $this->hasOne(Permission::class,'permission_id','permission_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;
    protected $table = 'login_history';

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'userid');
    }

    public function supplier()
    {
        return $this->hasOne(Supplier::class, 'sup_id', 'userid');
    }

    public function staff()
    {
        return $this->hasOne(Admin::class, 'id', 'userid');
    }

}

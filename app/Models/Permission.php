<?php

namespace App\Models;

// use Spatie\Permission\Models\Permission as PermissionModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    // public function __construct(array $attributes = [])
    // {
    //     $attributes['guard_name'] = $attributes['guard_name'] ?? config('auth.defaults.guard');

    //     parent::__construct($attributes);
    // }

    use HasFactory;
    protected $table = 'permission';
}

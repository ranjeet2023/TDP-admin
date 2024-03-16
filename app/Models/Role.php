<?php

namespace App\Models;

use Spatie\Permission\Models\Role as RoleModel;

class Role extends RoleModel
{
    public function __construct(array $attributes = [])
    {
        $attributes['guard_name'] = $attributes['guard_name'] ?? config('auth.defaults.guard');

        parent::__construct($attributes);
    }
    
    protected $with = ['permissions'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiamondConflict extends Model
{
    use HasFactory;

    protected $table = 'diamond_conflict';
    protected $guarded = [];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CageType extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'cage_types';

    protected $guarded = [];
}

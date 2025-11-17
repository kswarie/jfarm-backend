<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BirthDeath extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'birth_deaths';

    protected $guarded = [];
}

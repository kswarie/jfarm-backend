<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IncubationDetail extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'incubation_details';

    protected $guarded = [];
}

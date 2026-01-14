<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Birth extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'births';

    protected $guarded = [];

    public function stock() {
        return $this->belongsTo(Stock::class);
    }
}

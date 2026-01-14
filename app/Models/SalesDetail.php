<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesDetail extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'sales_details';

    protected $guarded = [];

    public function stock() {
        return $this->belongsTo(Stock::class);
    }

    public function sales() {
        return $this->belongsTo(Sales::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseDetail extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'purchase_details';

    protected $guarded = [];

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function purchase() {
        return $this->belongsTo(Purchase::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockTransaction extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'stock_transactions';

    protected $guarded = [];

    public function stock(): BelongsTo {
        return $this->belongsTo(Stock::class);
    }
}

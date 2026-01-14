<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeedUse extends Model
{
    use HasFactory;
//    use SoftDeletes;
    protected $table = 'feed_uses';

    protected $guarded = [];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'tenants';

    protected $guarded = [];

    public function sites() : BelongsToMany
    {
        return $this->belongsToMany(
            Site::class,
            'site_tenants',
            'tenant_id',
            'site_id'
        );
    }
}

<?php

namespace App\Helpers;

use App\Models\Site;
use App\Models\SiteTenant;
use App\Models\Tenant;
use Illuminate\Support\Facades\Auth;

class AuthHelper
{
    public static function getSiteTenant() {
        $user = Auth::guard('api')->user();
        $roles = Auth::guard('api')->parseToken()->getClaim('roles');
        $tenant = Tenant::find($user->tenant_id);
        $site_id = $tenant->sites()->where('site_tenants.status', 'active')->first()?->id;
//        $site_tenant = SiteTenant::where([
//            'tenant_id' => $tenant->id,
//            'status' => 'active'
//        ])->get();
//        $site = Site::find($site_tenant->site_id);

        return [
            'user_id' => $user->id,
            'full_name' => $user->name,
            'tenant_id' => $tenant->id,
            'tenant_name' => $tenant->name,
            'site_id' => $site_id,
            'roles' => $roles,
            'sites' => $tenant->sites()->where('site_tenants.status','active')->select(['sites.id','sites.name'])->get(),
        ];
    }
}

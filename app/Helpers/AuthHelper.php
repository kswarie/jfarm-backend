<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class AuthHelper
{
    public static function getSiteTenant() {
        $user_id = Auth::guard('api')->user()->id;

    }
}

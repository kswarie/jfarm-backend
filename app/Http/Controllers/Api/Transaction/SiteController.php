<?php

namespace App\Http\Controllers\Api\Transaction;

use App\Helpers\AuthHelper;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Site;
use App\Models\Stock;
use App\Resource\SiteResource;
use App\Resource\StockResource;
use Illuminate\Http\Request;

class SiteController extends Controller
{

    public function info(Request $request)
    {
        $infos = AuthHelper::getSiteTenant();
//        $current_siteid = $infos['site_id'];
        $sites = $infos['sites']->pluck('id')->toArray();
//        if(($key = array_search($current_siteid, $sites)) !== false) {
//            unset($sites[$key]);
//        }

        $data = Site::query()
            ->whereIn('id', $sites)
            ->get();

        $infos['sites'] = SiteResource::collection($data);
//dd($infos);
        return response()->json(ResponseHelper::successWithData($infos), 200);
    }
}

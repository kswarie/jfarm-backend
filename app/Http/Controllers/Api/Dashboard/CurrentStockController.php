<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Helpers\AuthHelper;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Resource\CurrentStockResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CurrentStockController extends Controller
{
    public function show()
    {
        $info = AuthHelper::getSiteTenant();
        $data = DB::select("
            SELECT
	            p.code, p.name, sum(s.qty) qty, s.uom
            FROM products p
                LEFT JOIN	stocks s on p.id = s.product_id
            WHERE s.site_id = {$info['site_id']}
            GROUP BY p.code, p.name, s.uom
            ORDER BY p.code
        ");
        return response()->json(ResponseHelper::successWithData(CurrentStockResource::collection($data)), 200);

    }
}

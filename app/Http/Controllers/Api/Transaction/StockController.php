<?php

namespace App\Http\Controllers\Api\Transaction;

use App\Helpers\AuthHelper;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Stock;
use App\Resource\StockResource;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $infos = AuthHelper::getSiteTenant();
        $data = Stock::query()
                ->where('site_id', $infos['site_id'])
                ->when($request->query('reg_date'), function ($q, $v) {
                    $q->whereDate('register_date', $v);
                })
                ->when($request->query('products'), function ($q, $v) {

                    $prod_id = Product::whereIn('code', explode(",", $v))->pluck('id')->toArray();
                    $q->whereIn('product_id', $prod_id);
                })
                ->when($request->query('is_available'), function ($q, $v) {
                    logger()->info('is_available : '. $v);
                    $q->where('qty', '>', 0);
                })
                ->get();

        return response()->json(ResponseHelper::successWithData(StockResource::collection($data)));
    }
}

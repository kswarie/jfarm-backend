<?php

namespace App\Http\Controllers\Api\Transaction;

use App\Helpers\AuthHelper;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Sales;
use App\Models\SalesDetail;
use App\Models\Stock;
use App\Models\StockTransaction;
use App\Resource\SalesResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        $infos = AuthHelper::getSiteTenant();
        logger()->info('info : ', $infos);
        $data = Sales::where(
            'site_id', $infos['site_id']
        )->with('details')->get();
        return response()->json(ResponseHelper::successWithData(SalesResource::collection($data)));
    }

    public function store(Request $request)
    {
        $infos = AuthHelper::getSiteTenant();
        $input = $request->all();
        $validator = Validator::make($input, [
            'sale_date' => 'required',
        ]);
        $input['site_id'] = $infos['site_id'];
        if ($validator->fails()) {
            return response()->json(ResponseHelper::errorCustom(400, $validator->errors()), 200);
        }
        logger()->info('input', $input);
        try {
            $data = DB::transaction(function () use ($input) {
                $sales = Sales::create([
                    'site_id' => $input['site_id'],
                    'sale_date' => $input['sale_date'],
                    'remarks' => $input['remarks'],
                ]);
                logger()->info('input', $input['sales_detail']);
                if (sizeof($input['sales_detail']) > 0) {
                    foreach ($input['sales_detail'] as $detail) {
                        $stock = Stock::with('product')->find($detail['stock_id']);
//                        logger()->info('stock : ', $stock);
                        $pd = SalesDetail::create([
                            'sales_id' => $sales->id,
                            'stock_id' => $stock->id,
//                            'age' => $detail['age'],
                            'uom' => $stock->product?->uom,
                            'quantity' => $detail['quantity'],
                            'unit_price' => 0,
                            'total_price' => 0,
                        ]);
                        $stock->update([
                            'qty' => $stock->qty - $detail['quantity']
                        ]);
                        StockTransaction::create([
                            'stock_id' => $stock->id,
                            'type' => 2,
                            'qty' => $detail['quantity'],
                            'reff' => 'sales_detail/'. $pd->id
                        ]);
                    }
                }
                return $sales;
            });

        } catch (\Exception $e) {
            logger()->info('error save' . $e->getMessage());
            return response()->json(ResponseHelper::errorCustom(500, $e->getMessage()), 200);
        }

        return response()->json(ResponseHelper::successWithData(new SalesResource($data)), 200);
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'sales_date' => 'required',
        ]);
        $data = Sales::find($id);
        if (empty($data)) {
            return response()->json(ResponseHelper::errorCustom(404, 'Data tidak ditemukan.'), 200);
        }
        $data->update([
            'sales_date' => $input['sales_date'],
            'remarks' => $input['remarks'],
        ]);
        return response()->json(ResponseHelper::successWithData(new SalesResource($data)), 200);

    }

    public function destroy($id)
    {
        $data = Sales::findOrFail($id);
        $data->delete();
        return response()->json(ResponseHelper::successWithoutData("Data berhasil dihapus"), 200);
    }

    public function show($id)
    {
        $data = Sales::findOrFail($id);
        return response()->json(ResponseHelper::successWithData(new SalesResource($data)), 200);

    }
}

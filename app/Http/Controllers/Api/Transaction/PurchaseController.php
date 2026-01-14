<?php

namespace App\Http\Controllers\Api\Transaction;

use App\Helpers\AuthHelper;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Stock;
use App\Models\StockTransaction;
use App\Resource\PurchaseResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $infos = AuthHelper::getSiteTenant();
        logger()->info('info : ', $infos);
        $data = Purchase::where(
            'site_id', $infos['site_id']
        )->with('details')->get();
        return response()->json(ResponseHelper::successWithData(PurchaseResource::collection($data)));
    }

    public function store(Request $request)
    {
        $infos = AuthHelper::getSiteTenant();
        $input = $request->all();
        $validator = Validator::make($input, [
            'purchase_date' => 'required',
        ]);
        $input['site_id'] = $infos['site_id'];
        if ($validator->fails()) {
            return response()->json(ResponseHelper::errorCustom(400, $validator->errors()), 200);
        }
        logger()->info('input', $input);
        try {
            $data = DB::transaction(function () use ($input) {
                $purchase = Purchase::create([
                    'site_id' => $input['site_id'],
                    'purchase_date' => $input['purchase_date'],
                    'remarks' => $input['remarks'],
                ]);
                logger()->info('input', $input['purchase_detail']);
                if (sizeof($input['purchase_detail']) > 0) {
                    foreach ($input['purchase_detail'] as $detail) {
                        $product = Product::find($detail['product_id']);

                        $pd = PurchaseDetail::create([
                            'purchase_id' => $purchase->id,
                            'product_id' => $detail['product_id'],
                            'age' => $detail['age'],
                            'uom' => $product['uom'],
                            'quantity' => $detail['quantity'],
                            'unit_price' => 0,
                            'total_price' => 0,
                        ]);

                        $stock = Stock::where('product_id', $detail['product_id'])
                            ->where('register_date', $input['purchase_date'])->first();
                        if (empty($stock)) {
                            $stock = Stock::create([
                                'site_id' => $input['site_id'],
                                'product_id' => $detail['product_id'],
                                'register_date' => $input['purchase_date'],
                                'days_old' => $detail['age'],
                                'qty' => $detail['quantity'],
                                'uom' => $product['uom']
                            ]);
                        }
                        else
                        {
                            $stock->update([
                               'qty' => $stock->qty + $detail['quantity']
                            ]);
                        }
                        StockTransaction::create([
                            'stock_id' => $stock->id,
                            'type' => 1,
                            'qty' => $detail['quantity'],
                            'reff' => 'purchase_detail/'. $pd->id
                        ]);
                    }
                }
                return $purchase;
            });

        } catch (\Exception $e) {
            logger()->info('error save' . $e->getMessage());
            return response()->json(ResponseHelper::errorCustom(500, $e->getMessage()), 200);
        }

        return response()->json(ResponseHelper::successWithData(new PurchaseResource($data)), 200);
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'purchase_date' => 'required',
            'total_price' => 'required',
        ]);
        $data = Purchase::find($id);
        if (empty($data)) {
            return response()->json(ResponseHelper::errorCustom(404, 'Data tidak ditemukan.'), 200);
        }
        $data->update([
            'purchase_date' => $input['purchase_date'],
            'total_price' => $input['total_price'],
            'remarks' => $input['remarks'],
        ]);
        return response()->json(ResponseHelper::successWithData(new PurchaseResource($data)), 200);

    }

    public function destroy($id)
    {
        $data = Purchase::findOrFail($id);
        $data->delete();
        return response()->json(ResponseHelper::successWithoutData("Data berhasil dihapus"), 200);
    }

    public function show($id)
    {
        $data = Purchase::findOrFail($id);
        return response()->json(ResponseHelper::successWithData(new PurchaseResource($data)), 200);

    }
}

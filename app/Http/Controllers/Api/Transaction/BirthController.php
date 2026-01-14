<?php

namespace App\Http\Controllers\Api\Transaction;

use App\Helpers\AuthHelper;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Birth;
use App\Models\Product;
use App\Models\Stock;
use App\Models\StockTransaction;
use App\Resource\BirthResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BirthController extends Controller
{
    public function index(Request $request)
    {
        $infos = AuthHelper::getSiteTenant();
//        $filter_date = $request->query('filter_date');
//        if(!empty($filter_date)){
//            logger()->info('filter date : ' .$filter_date);
//            $data = Incubation::where(
//                'site_id', $infos['site_id']
//            )->whereDate('created_at', $filter_date)->get();
//        } else {
//
//        }
        $data = Birth::where(
            'site_id', $infos['site_id']
        )->get();
        return response()->json(ResponseHelper::successWithData(BirthResource::collection($data)));
    }

    public function store(Request $request)
    {
        $infos = AuthHelper::getSiteTenant();

        $input = $request->all();
        logger()->info('input', $input);
        $validator = Validator::make($input, [
            'input_date' => 'required|date',
            'quantity' => 'required|numeric'
        ]);
//        $input['site_id'] = $infos['site_id'];
        if ($validator->fails()) {
            return response()->json(ResponseHelper::errorCustom(400, $validator->errors()), 200);
        }
        logger()->info('input', $input);
        try {

            $data = DB::transaction(function () use ($input, $infos) {
                $inc = Birth::create([
                    'site_id' => $infos['site_id'],
                    'input_date' => $input['input_date'],
                    'quantity' => $input['quantity'],
                    'remarks' => $input['remarks']
                ]);
                $stock = Stock::where('register_date', $input['input_date'])->first();
                $product = Product::where('code', 'C001')->first();
                if (empty($stock)) {
                    $stock = Stock::create([
                        'site_id' => $infos['site_id'],
                        'product_id' => $product->id,
                        'register_date' => $input['input_date'],
                        'days_old' => 0,
                        'qty' => $input['quantity'],
                        'uom' => 'pcs'
                    ]);
                }
                StockTransaction::create([
                    'stock_id' => $stock->id,
                    'type' => 1,
                    'qty' => $input['quantity'],
                    'reff' => 'birth/' . $inc->id
                ]);
                $inc->update([
                    'stock_id' => $stock->id,
                ]);
                return $inc;
            });

        } catch (\Exception $e) {
            logger()->info('error save : ' . $e->getMessage());
            return response()->json(ResponseHelper::errorCustom(500, $e->getMessage()), 200);
        }
        return response()->json(ResponseHelper::successWithData(new BirthResource($data)), 200);
    }

    public function update(Request $request, $id)
    {
        $infos = AuthHelper::getSiteTenant();
        $input = $request->all();
        $validator = Validator::make($input, [
            'input_date' => 'required|date',
            'quantity' => 'required|numeric'
        ]);
        $data = Birth::find($id);
        if (empty($data)) {
            return response()->json(ResponseHelper::errorCustom(404, 'Data tidak ditemukan.'), 200);
        }
        logger()->info('input', $input);
        try {

            $data = DB::transaction(function () use ($input, $data) {
                $old_qty = $data->quantity;
                $old_stock = Stock::find($data->stock_id);
                $new_stock = Stock::find($input['stock_id']);
                $stock_trx = StockTransaction::where('reff', 'birth/' . $data->id)
                    ->whereNull('deleted_at')->first();
                $data->update([
                    'stock_id' => $input['stock_id'],
                    'input_date' => $input['input_date'],
                    'quantity' => $input['quantity'],
                    'remarks' => $input['remarks'],
                ]);

                // jika stock new update berbeda dengan stock lama
                if ($new_stock->stock_id != $old_stock->stock_id) {
                    // update stock trx lama
                    $stock_trx->delete();
                    StockTransaction::create([
                        'stock_id' => $input['stock_id'],
                        'type' => 1,
                        'qty' => $input['quantity'],
                        'reff' => 'birth/' . $data->id
                    ]);
                    $new_stock->update([
                        'qty' => $new_stock->qty - $input['quantity'],
                        'updated_at' => Carbon::now()
                    ]);
                    $old_stock->update([
                        'qty' => $old_stock->qty + $old_qty,
                        'updated_at' => Carbon::now()
                    ]);
                } else {
                    $stock_trx->update(
                        ['qty' => $input['quantity'],
                            'updated_at' => Carbon::now()]
                    );
                    $old_stock->update([
                        'qty' => $old_stock->qty + $old_qty - $input['quantity'],
                        'updated_at' => Carbon::now()
                    ]);
                }

                return $data;
            });

        } catch (\Exception $e) {
            logger()->info('error save', $e->getMessage());
            return response()->json(ResponseHelper::errorCustom(500, $e->getMessage()), 200);
        }

        return response()->json(ResponseHelper::successWithData(new BirthResource($data)), 200);

    }

    public function destroy($id)
    {
        $obj = Birth::findOrFail($id);
        $data = DB::transaction(function () use ($obj) {
            $stock = Stock::find($obj->stock_id);
            $stock->update([
                'qty' => $stock->qty - $obj->quantity,
            ]);
            $stock_trx = StockTransaction::whereNull('deleted_at')->where('reff', 'birth/' . $obj->id);
            $stock_trx->delete();
            $obj->delete();
        });
        return response()->json(ResponseHelper::successWithoutData("Data berhasil dihapus"), 200);
    }

    public function show($id)
    {
        $data = Birth::findOrFail($id);
        return response()->json(ResponseHelper::successWithData(new BirthResource($data)), 200);

    }
}

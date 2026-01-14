<?php

namespace App\Http\Controllers\Api\Transaction;

use App\Helpers\AuthHelper;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\BirthDeath;
use App\Models\Product;
use App\Models\Stock;
use App\Models\StockTransaction;
use App\Resource\BirthDeathResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BirthDeathController extends Controller
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
        $data = BirthDeath::where(
            'site_id', $infos['site_id']
        )->get();
        return response()->json(ResponseHelper::successWithData(BirthDeathResource::collection($data)));
    }

    public function store(Request $request)
    {
        $infos = AuthHelper::getSiteTenant();

        $input = $request->all();
        $validator = Validator::make($input, [
            'cage_id' => 'required',
            'stock_id' => 'required',
            'type' => 'required|in:birth,death',
            'input_date' => 'required|date',
            'quantity' => 'required|numeric'
        ]);
        $input['site_id'] = $infos['site_id'];
        if ($validator->fails()) {
            return response()->json(ResponseHelper::errorCustom(400, $validator->errors()), 200);
        }
        logger()->info('input', $input);
        try {

            $data = DB::transaction(function () use ($input, $infos) {
                $inc = BirthDeath::create($input);
                $type = $input['type'] == 'birth' ? 'IN' : 'OUT';
                StockTransaction::create([
                    'stock_id' => $input['stock_id'],
                    'type' => $type,
                    'qty' => $input['quantity'],
                    'reff' => 'death/' . $inc->id
                ]);
                $stock = Stock::find($input['stock_id']);
                $quantity = $input['type'] == 'birth' ? $stock->qty + $input['quantity'] : $stock->qty - $input['quantity'];
                $stock->update([
                    'qty' => $quantity,
                    'updated_at' => Carbon::now()
                ]);

                return $inc;
            });

        } catch (\Exception $e) {
            logger()->info('error save', $e->getMessage());
            return response()->json(ResponseHelper::errorCustom(500, $e->getMessage()), 200);
        }
        return response()->json(ResponseHelper::successWithData(new IncubationResource($data)), 200);
    }

    public function update(Request $request, $id)
    {
        $infos = AuthHelper::getSiteTenant();
        $input = $request->all();
        $validator = Validator::make($input, [
            'cage_id' => 'required',
            'stock_id' => 'required',
            'input_date' => 'required|date',
            'quantity' => 'required|numeric'
        ]);
        $data = BirthDeath::find($id);
        if (empty($data)) {
            return response()->json(ResponseHelper::errorCustom(404, 'Data tidak ditemukan.'), 200);
        }
        logger()->info('input', $input);
        try {

            $data = DB::transaction(function () use ($input, $data) {
                $old_qty = $data->quantity;
                $old_stock = Stock::find($data->stock_id);
                $new_stock = Stock::find($input['stock_id']);
                $stock_trx = StockTransaction::where('reff', 'death/' . $data->id)
                    ->whereNull('deleted_at')->first();
                $data->update([
                    'cage_id' => $input['cage_id'],
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
                        'type' => 'OUT',
                        'qty' => $input['quantity'],
                        'reff' => 'death/' . $data->id
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

        return response()->json(ResponseHelper::successWithData(new IncubationResource($data)), 200);

    }

    public function destroy($id)
    {
        $obj = BirthDeath::findOrFail($id);
        $data = DB::transaction(function () use ($obj) {
            $stock = Stock::find($obj->stock_id);
            $stock->update([
                'qty' => $stock->qty + $obj->quantity,
            ]);
            $stock_trx = StockTransaction::whereNull('deleted_at')->where('reff', 'death/' . $obj->id);
            $stock_trx->delete();
            $obj->delete();
        });
        return response()->json(ResponseHelper::successWithoutData("Data berhasil dihapus"), 200);
    }

    public function show($id)
    {
        $data = BirthDeath::findOrFail($id);
        return response()->json(ResponseHelper::successWithData(new BirthDeathResource($data)), 200);

    }
}

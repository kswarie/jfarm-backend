<?php

namespace App\Http\Controllers\Api\Transaction;

use App\Helpers\AuthHelper;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Incubation;
use App\Models\IncubationDetail;
use App\Models\Product;
use App\Models\Stock;
use App\Models\StockTransaction;
use App\Resource\IncubationResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class IncubationController extends Controller
{
    public function index(Request $request)
    {
        $infos = AuthHelper::getSiteTenant();
        $filter_date = $request->query('filterDate');
        logger()->info('filter_date'. $filter_date);
        if(!empty($filter_date)){
            logger()->info('filter date : ' .$filter_date);
            $data = Incubation::where(
                'site_id', $infos['site_id']
            )->whereDate('created_at', $filter_date)->get();
        } else {
            $data = Incubation::where(
                'site_id', $infos['site_id']
            )->get();
        }
        return response()->json(ResponseHelper::successWithData(IncubationResource::collection($data)));
    }

    public function store(Request $request)
    {
        $infos = AuthHelper::getSiteTenant();

        $input = $request->all();
        $validator = Validator::make($input, [
            'start_date' => 'required',
            'egg_qty' => 'required|numeric'
        ]);
        $input['site_id'] = $infos['site_id'];
        if ($validator->fails()) {
            return response()->json(ResponseHelper::errorCustom(400, $validator->errors()), 200);
        }
        logger()->info('input', $input);
        try {

            $data = DB::transaction(function () use ($input, $infos) {
                $inc = Incubation::create($input);
                IncubationDetail::create([
                    'incubation_id' => $inc->id,
                    'egg_qty' => $inc->egg_qty,
                    'hatch_qty' => $inc->hatch_qty,
                    'damage_qty' => $inc->damage_qty,
                    'remarks' => $inc->remarks
                ]);
                $product = Product::where('code', 'C001')->first();
                $stock = Stock::create([
                    'site_id' => $infos['site_id'],
                    'product_id' => $product?->id,
                    'register_date' => Carbon::now(),
                    'days_old' => 0,
                    'qty' => $inc->egg_qty,
                    'uom' => 'pcs',
                    'remarks' => 'Incubation'
                ]);
                StockTransaction::create([
                    'stock_id' => $stock->id,
                    'type' => 'IN',
                    'qty' => $inc->egg_qty,
                    'reff' => 'incubation/'.$inc->batch_code
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
            'start_date' => 'required',
            'egg_qty' => 'required|numeric'
        ]);
        $input['site_id'] = $infos['site_id'];
        $data = Incubation::find($id);
        if (empty($data)) {
            return response()->json(ResponseHelper::errorCustom(404, 'Data tidak ditemukan.'), 200);
        }
        $data->update([
            'start_date' => $input['start_date'],
            'hatch_date' => $input['hatch_date'],
            'egg_qty' => $input['egg_qty'],
            'hatch_qty' => $input['hatch_qty'],
            'damage_qty' => $input['damage_qty'],
            'remarks' => $input['remarks'],
        ]);
        return response()->json(ResponseHelper::successWithData(new IncubationResource($data)), 200);

    }

    public function destroy($id)
    {
        $data = Incubation::findOrFail($id);
        $data->delete();
        return response()->json(ResponseHelper::successWithoutData("Data berhasil dihapus"), 200);
    }

    public function show($id)
    {
        $data = Incubation::findOrFail($id);
        return response()->json(ResponseHelper::successWithData(new IncubationResource($data)), 200);

    }
}

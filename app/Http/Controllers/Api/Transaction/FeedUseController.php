<?php

namespace App\Http\Controllers\Api\Transaction;

use App\Helpers\AuthHelper;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\FeedUse;
use App\Models\Stock;
use App\Models\StockTransaction;
use App\Resource\FeedUseResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FeedUseController extends Controller
{

    public function index()
    {
        return response()->json(ResponseHelper::successWithData(FeedUseResource::collection(FeedUse::all())));
    }

    public function store(Request $request)
    {
        $infos = AuthHelper::getSiteTenant();
        $input = $request->all();
        $input['site_id'] = $infos['site_id'];
        $validator = Validator::make($input, [
//            'site_id' => 'required',
            'stock_id' => 'required',
            'use_date' => 'required',
            'qty' => 'required|numeric'
        ]);
        if ($validator->fails()) {
            return response()->json(ResponseHelper::errorCustom(400, $validator->errors()), 200);
        }
        logger()->info('input', $input);
        try {
            $data = DB::transaction(function () use ($input) {
                $feed = FeedUse::create([
                    'site_id' => $input['site_id'],
                    'stock_id' => $input['stock_id'],
                    'use_date' => $input['use_date'],
                    'feed_name' => '',
                    'qty' => $input['qty'],
                    'remarks' => $input['remarks'],
                ]);

                $stock = Stock::find($input['stock_id']);
                $stock->update([
                    'qty' => $stock['qty'] - $input['qty']
                ]);
                StockTransaction::create([
                    'stock_id' => $stock->id,
                    'type' => 2,
                    'qty' => $input['qty'],
                    'reff' => 'feed_use/'. $feed->id
                ]);


                return $feed;
            });
        } catch (\Exception $e) {
            logger()->info('error save' . $e->getMessage());
            return response()->json(ResponseHelper::errorCustom(500, $e->getMessage()), 200);
        }
        return response()->json(ResponseHelper::successWithData(new FeedUseResource($data)), 200);
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'stock_id' => 'required',
            'use_date' => 'required',
            'qty' => 'required|numeric'
        ]);
        $data = FeedUse::find($id);
        if (empty($data)) {
            return response()->json(ResponseHelper::errorCustom(404, 'Data tidak ditemukan.'), 200);
        }
        $data->update([
//            'site_id' => $input['site_id'],
            'stock_id' => $input['stock_id'],
            'use_date' => $input['use_date'],
            'feed_name' => '',
            'qty' => $input['qty'],
            'remarks' => $input['remarks'],
        ]);
        return response()->json(ResponseHelper::successWithData(new FeedUseResource($data)), 200);

    }

    public function destroy($id)
    {
        $data = FeedUse::findOrFail($id);
        $data->delete();
        return response()->json(ResponseHelper::successWithoutData("Data berhasil dihapus"), 200);
    }

    public function show($id)
    {
        $data = FeedUse::findOrFail($id);
        return response()->json(ResponseHelper::successWithData(new FeedUseResource($data)), 200);

    }
}

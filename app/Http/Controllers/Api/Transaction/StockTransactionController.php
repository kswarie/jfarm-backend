<?php

namespace App\Http\Controllers\Api\Transaction;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\StockTransaction;
use App\Resource\StockTransactionResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StockTransactionController extends Controller
{
    public function index()
    {
        return response()->json(ResponseHelper::successWithData(StockTransactionResource::collection(StockTransaction::all())));
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'code' => 'required',
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(ResponseHelper::errorCustom(400, $validator->errors()), 200);
        }
        $data = StockTransaction::create($input);
        return response()->json(ResponseHelper::successWithData(new StockTransactionResource($data)), 200);
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'code' => 'required',
            'name' => 'required',
        ]);
        $data = StockTransaction::find($id);
        if (empty($data)) {
            return response()->json(ResponseHelper::errorCustom(404, 'Data tidak ditemukan.'), 200);
        }
        $data->update([
            'code' => $input['code'],
            'name' => $input['name'],
        ]);
        return response()->json(ResponseHelper::successWithData(new StockTransactionResource($data)), 200);

    }

    public function destroy($id)
    {
        $data = StockTransaction::findOrFail($id);
        $data->delete();
        return response()->json(ResponseHelper::successWithoutData("Data berhasil dihapus"), 200);
    }

    public function show($id)
    {
        $data = StockTransaction::findOrFail($id);
        return response()->json(ResponseHelper::successWithData(new StockTransactionResource($data)), 200);

    }
}

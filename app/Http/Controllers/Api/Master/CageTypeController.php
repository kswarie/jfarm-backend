<?php

namespace App\Http\Controllers\Api\Master;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\CageType;
use App\Resource\CageTypeResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CageTypeController extends Controller
{
    public function index()
    {
        return response()->json(ResponseHelper::successWithData(CageTypeResource::collection(CageType::all())));
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
        $data = CageType::create($input);
        return response()->json(ResponseHelper::successWithData(new CageTypeResource($data)), 200);
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'code' => 'required',
            'name' => 'required',
        ]);
        $data = CageType::find($id);
        if (empty($data)) {
            return response()->json(ResponseHelper::errorCustom(404, 'Data tidak ditemukan.'), 200);
        }
        $data->update([
            'code' => $input['code'],
            'name' => $input['name'],
        ]);
        return response()->json(ResponseHelper::successWithData(new CageTypeResource($data)), 200);

    }

    public function destroy($id)
    {
        $data = CageType::findOrFail($id);
        $data->delete();
        return response()->json(ResponseHelper::successWithoutData("Data berhasil dihapus"), 200);
    }

    public function show($id)
    {
        $data = CageType::findOrFail($id);
        return response()->json(ResponseHelper::successWithData(new CageTypeResource($data)), 200);

    }
}

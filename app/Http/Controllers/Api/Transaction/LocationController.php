<?php

namespace App\Http\Controllers\Api\Transaction;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Resource\LocationResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LocationController extends Controller
{
    public function index()
    {
        return response()->json(ResponseHelper::successWithData(LocationResource::collection(Location::all())));
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
            'status' => 'in:active,inactive',
        ]);
        if ($validator->fails()) {
            return response()->json(ResponseHelper::errorCustom(400, $validator->errors()), 200);
        }
        $data = Location::create($input);
        return response()->json(ResponseHelper::successWithData(new LocationResource($data)), 200);
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
            'status' => 'in:active,inactive',
        ]);
        $data = Location::find($id);
        if (empty($data)) {
            return response()->json(ResponseHelper::errorCustom(404, 'Data tidak ditemukan.'), 200);
        }
        $data->update([
            'name' => $input['name'],
            'description' => $input['description'],
            'status' => $input['status'],
        ]);
        return response()->json(ResponseHelper::successWithData(new LocationResource($data)), 200);

    }

    public function destroy($id)
    {
        $data = Location::findOrFail($id);
        $data->delete();
        return response()->json(ResponseHelper::successWithoutData("Data berhasil dihapus"), 200);
    }

    public function show($id)
    {
        $data = Location::findOrFail($id);
        return response()->json(ResponseHelper::successWithData(new LocationResource($data)), 200);

    }
}

<?php

namespace App\Http\Controllers\Api\Master;

use App\Helpers\AuthHelper;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Incubator;
use App\Models\IncubatorSite;
use App\Resource\IncubatorResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IncubatorController extends Controller
{
    public function index()
    {
        $info = AuthHelper::getSiteTenant();
        $intersect = array_intersect($info['roles'], ['ADMIN']);
        if(count($intersect) === 0) {
            $inc_id = IncubatorSite::where('site_id', $info['site_id'])->pluck('incubator_id')->toArray();
            $data = Incubator::whereIn('id', $inc_id)->get();
        } else {
            $data = Incubator::all();
        }
        return response()->json(ResponseHelper::successWithData(IncubatorResource::collection($data)));
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(ResponseHelper::errorCustom(400, $validator->errors()), 200);
        }
        $data = Incubator::create($input);
        return response()->json(ResponseHelper::successWithData(new IncubatorResource($data)), 200);
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
        ]);
        $data = Incubator::find($id);
        if (empty($data)) {
            return response()->json(ResponseHelper::errorCustom(404, 'Data tidak ditemukan.'), 200);
        }
        $data->update([
            'status' => $input['status'],
            'type' => $input['type'],
            'name' => $input['name'],
            'description' => $input['description'],
        ]);
        return response()->json(ResponseHelper::successWithData(new IncubatorResource($data)), 200);

    }

    public function destroy($id)
    {
        $data = Incubator::findOrFail($id);
        $data->delete();
        return response()->json(ResponseHelper::successWithoutData("Data berhasil dihapus"), 200);
    }

    public function show($id)
    {
        $data = Incubator::findOrFail($id);
        return response()->json(ResponseHelper::successWithData(new IncubatorResource($data)), 200);

    }
}

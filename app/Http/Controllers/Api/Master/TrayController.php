<?php

namespace App\Http\Controllers\Api\Master;

use App\Helpers\AuthHelper;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Tray;
use App\Models\TraySite;
use App\Resource\TrayResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TrayController extends Controller
{
    public function index()
    {

        $info = AuthHelper::getSiteTenant();
        $intersect = array_intersect($info['roles'], ['ADMIN']);
        if(count($intersect) === 0) {
            $inc_id = TraySite::where('site_id', $info['site_id'])->pluck('tray_id')->toArray();
            $data = Tray::whereIn('id', $inc_id)->get();
        } else {
            $data = Tray::all();
        }
        return response()->json(ResponseHelper::successWithData(TrayResource::collection($data)));
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'code' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(ResponseHelper::errorCustom(400, $validator->errors()), 200);
        }
        $data = Tray::create($input);
        return response()->json(ResponseHelper::successWithData(new TrayResource($data)), 200);
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'code' => 'required',
        ]);
        $data = Tray::find($id);
        if (empty($data)) {
            return response()->json(ResponseHelper::errorCustom(404, 'Data tidak ditemukan.'), 200);
        }
        $data->update([
            'status' => $input['status'],
            'code' => $input['name'],
            'description' => $input['description'],
        ]);
        return response()->json(ResponseHelper::successWithData(new TrayResource($data)), 200);

    }

    public function destroy($id)
    {
        $data = Tray::findOrFail($id);
        $data->delete();
        return response()->json(ResponseHelper::successWithoutData("Data berhasil dihapus"), 200);
    }

    public function show($id)
    {
        $data = Tray::findOrFail($id);
        return response()->json(ResponseHelper::successWithData(new TrayResource($data)), 200);

    }
}

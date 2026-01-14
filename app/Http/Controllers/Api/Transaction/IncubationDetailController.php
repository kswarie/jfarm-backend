<?php

namespace App\Http\Controllers\Api\Transaction;

use App\Helpers\AuthHelper;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\IncubationDetail;
use App\Resource\IncubationDetailResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IncubationDetailController extends Controller
{
    public function index($incubation_id)
    {
        $infos = AuthHelper::getSiteTenant();

        $data = IncubationDetail::where(
            'incubation_id', $incubation_id
        )->get();
        return response()->json(ResponseHelper::successWithData(IncubationDetailResource::collection($data)));
    }

    public function store(Request $request)
    {
        $infos = AuthHelper::getSiteTenant();

        $input = $request->all();
        $validator = Validator::make($input, [
            'egg_qty' => 'required|numeric',
            'damage_qty' => 'required|numeric',
            'hatch_qty' => 'required|numeric'
        ]);
        if ($validator->fails()) {
            return response()->json(ResponseHelper::errorCustom(400, $validator->errors()), 200);
        }
        logger()->info('input', $input);
        $data = IncubationDetail::create($input);
        return response()->json(ResponseHelper::successWithData(new IncubationDetailResource($data)), 200);
    }

    public function update(Request $request, $id)
    {
        $infos = AuthHelper::getSiteTenant();
        $input = $request->all();
        $validator = Validator::make($input, [
            'egg_qty' => 'required|numeric',
            'damage_qty' => 'required|numeric',
            'hatch_qty' => 'required|numeric'
        ]);
        $data = IncubationDetail::find($id);
        if (empty($data)) {
            return response()->json(ResponseHelper::errorCustom(404, 'Data tidak ditemukan.'), 200);
        }
        $data->update([
            'egg_qty' => $input['egg_qty'],
            'hatch_qty' => $input['hatch_qty'],
            'damage_qty' => $input['damage_qty'],
            'remarks' => $input['remarks'],
            'updated_at' => Carbon::now()
        ]);
        return response()->json(ResponseHelper::successWithData(new IncubationDetailResource($data)), 200);

    }

    public function destroy($id)
    {
        $data = IncubationDetail::findOrFail($id);
        $data->delete();
        return response()->json(ResponseHelper::successWithoutData("Data berhasil dihapus"), 200);
    }

    public function show($id)
    {
        $data = IncubationDetail::findOrFail($id);
        return response()->json(ResponseHelper::successWithData(new IncubationDetailResource($data)), 200);

    }
}

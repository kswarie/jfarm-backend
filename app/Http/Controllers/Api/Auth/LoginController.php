<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;

class LoginController extends Controller
{
    public function login(Request $request) {

        $input = $request->all();

        $messages = [
            'required' => ':attribute harus di isi',
        ];
        $validator = Validator::make($input, [
            'email' => 'required',
            'password' => 'required',
        ], $messages);
        if ($validator->fails()) {
            return response()->json(ResponseHelper::validateResponse($validator));
        }
        $user = User::where('email', $input['email'])->first();
        if (empty($user)) {
            return response()->json(ResponseHelper::errorCustom(204, 'Akun tidak ditemukan'), 200);
        }
        $checked_pwd = Hash::check($input['password'], $user->password);
        if (!$checked_pwd) {
            return response()->json(ResponseHelper::errorCustom(403, 'username atau password salah'), 403);
        } else {
            $credentials = $request->only('email', 'password');
            try {
                // attempt to verify the credentials and create a token for the user
//                 if (!$token = JWTAuth::attempt($credentials)) {
                if (!$token = auth()->guard('api')->attempt($credentials)) {
                    return response()->json(ResponseHelper::errorCustom(403, 'Email atau password salah'), 403);
                }
            } catch (JWTException $e) {
                // something went wrong whilst attempting to encode the token
                return response()->json(ResponseHelper::errorCustom(500, $e->getMessage()), 500);
            }

            $now = Carbon::now()->setTimezone('+7');
//            $expired_at = $now->addDays(14);
            return response()->json(ResponseHelper::successWithData([
                'access_token' => $token,
                'expired_at' => auth()->guard('api')->factory()->getTTL() * 60
            ]), 200);
        }
    }
}

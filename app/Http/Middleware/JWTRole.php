<?php

namespace App\Http\Middleware;

use App\Helpers\ResponseHelper;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JWTRole extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        try {
            $token_role = $this->auth->parseToken()->getClaim('roles');
            if (!is_array($token_role)) {
                $token_role = [$token_role];
            }

            // Jika salah satu role user cocok dengan role yang diperbolehkan
            $intersect = array_intersect($token_role, $roles);

            if (count($intersect) === 0) {
                return response()->json(['error' => 'Forbidden (role mismatch)'], 403);
            }
        } catch (JWTException $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json(ResponseHelper::errorCustom(401, 'Token is Invalid'), 401);
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return response()->json(ResponseHelper::errorCustom(401, 'Token has expired'), 401);
            } else {
                return response()->json(ResponseHelper::errorCustom(404, 'Authorization Token not found'), 404);
            }
        }

//        if ($token_role != $roles) {
        if (count($intersect) === 0) {
            return response()->json(ResponseHelper::errorCustom(401, 'Token is Invalid'), 401);
        }
        return $next($request);
    }
}

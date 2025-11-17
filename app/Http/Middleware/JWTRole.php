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
    public function handle(Request $request, Closure $next, $role = null): Response
    {
        try {
            $token_role = $this->auth->parseToken()->getClaim('role');
        } catch (JWTException $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json(ResponseHelper::errorCustom(401, 'Token is Invalid'), 401);
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return response()->json(ResponseHelper::errorCustom(401, 'Token has expired'), 401);
            } else {
                return response()->json(ResponseHelper::errorCustom(404, 'Authorization Token not found'), 404);
            }
        }

        if ($token_role != $role) {
            return response()->json(ResponseHelper::errorCustom(401, 'Token is Invalid'), 401);
        }
        return $next($request);
    }
}

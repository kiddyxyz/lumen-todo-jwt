<?php

namespace App\Http\Controllers;

use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use phpDocumentor\Reflection\Types\Object_;

class Controller extends BaseController
{

    public function decodeToken(Request $request){
        $auth = $request->header('Authorization');
        return JWT::decode($auth, env('JWT_KEY'), [ env('JWT_ALG') ]);
    }

    public function ok($values, $message){
        return response()->json([
            'code' => 200,
            'values' => $values ?? [],
            'message' => $message ?? ""
        ], 200);
    }

    public function notFound($values, $message){
        return response()->json([
            'code' => 404,
            'values' => $values ?? [],
            'message' => $message ?? ""
        ], 404);
    }

    public function unAuthorized($values, $message){
        return response()->json([
            'code' => 401,
            'values' => $values ?? [],
            'message' => $message ?? ""
        ], 401);
    }

    public function badRequest($values, $message){
        return response()->json([
            'code' => 400,
            'values' => $values ?? [],
            'message' => $message ?? ""
        ], 400);
    }

    public function internalServerError($values, $message){
        return response()->json([
            'code' => 503,
            'values' => $values ?? [],
            'message' => $message ?? ""
        ], 503);
    }


}

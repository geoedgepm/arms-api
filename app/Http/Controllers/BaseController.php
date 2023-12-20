<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator as CoreValidator;
use App\Http\Controllers\Controller;

class Validator extends CoreValidator {}

class BaseController extends Controller
{
    CONST NO_PERMISSION = 600;
    CONST PENDING = 601;
    CONST REJECTED = 602;
    public function response($data) {
        return response()->json([
            'data' => $data
        ]);
    }

    public function responses($datas) {
        return response()->json([
            'data' => $datas
        ]);
    }

    public function responseSuccess($status_code = 201) {
        return response()->json([
            'data' => [
                'success' => true
            ]
            ], $status_code);
    }

    public function responseError($type, $message = null, $code = null) {
        $errors = [
            'not_found'         => 404,
            'forbiden'          => 403,
            'bad_request'       => 400,
            'internal_server'   => 500
        ];

        $messages = [
            'not_found'         => 'Not Found',
            'forbiden'          => 'Forbiden',
            'bad_request'       => 'Bad Request',
            'internal_server'   => 'Internal Server Error'
        ];

        return response()->json([
            'error' => [
                'message' => $message ? $message : $messages[$type],
                'code'    => $code ? $code : $errors[$type]
            ],
        ], $errors[$type]);
    }

    public function validateCheck($fields, $rules) {
        return Validator::make($fields, $rules);
    }

    public function getAuthId(Request $request) {
        return $request->get('user')->id;
    }

    public function getUserType(Request $request) {
        return $request->get('user')->type;
    }

    public function getAuthProvinceId(Request $request) {
        return $request->get('user')->province_id;
    }
}
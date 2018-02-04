<?php
/**
 * Created by PhpStorm.
 * User: tooyz
 * Date: 11.01.2018
 * Time: 1:32
 */

namespace App\Http;


use App\Utils\R;

class Responder
{
    public function successResponse($data = [], $status = 200){
        return $this->makeResponse(
            $status, [
                "data" => $data
            ]
        );
    }

    public function errorResponse($error = R::COMMON_ERROR, $data = null, $status = 500, $stack = null){
        return $this->makeResponse($status, [
            "error" => $error,
            "data" => $data,
            "stack" => $stack
        ]);
    }

    private function makeResponse($code, $data){
        return \response()->json(
            $data, $code
        );
    }
}

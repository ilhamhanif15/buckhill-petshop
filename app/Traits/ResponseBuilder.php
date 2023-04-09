<?php 

namespace App\Traits;

trait ResponseBuilder
{
    public function responseSuccess($data, $message = "Operation Success.", $code = 200)
    {
        return response()->json([
            "message" => $message,
            "data" => $data
        ], $code);
    }

    public function errorResponse($message = "Operation Failed or something wrong happened.", $code = 400, $data = [])
    {
        return response()->json([
            "message" => $message,
            "data" => $data
        ], $code);
    }
}
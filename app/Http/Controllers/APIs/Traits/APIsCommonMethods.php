<?php

namespace App\Http\Controllers\APIs\Traits;

trait APIsCommonMethods
{

    /**
     * Return API response with message, data and status code.
     *
     * @param $message
     * @param $data
     * @param $code
     * @return response
     */
    public function apiResponse($message, $data, $code)
    {
        return response()->json([
            'status' => $message,
            'response' => $data,
        ])->setStatusCode($code);
    }
}

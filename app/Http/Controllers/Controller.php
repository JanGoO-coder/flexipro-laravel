<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * General Function for sending custom api json response
     */
    public function sendResponse($data, $message, $status = 200) {
        $response = [
            'data' => $data,
            'message' => $message
        ];
        return response()->json($response, $status);
    }

    /**
     * General Function for sending custom error api response
     */
    public function sendError($errorData, $message, $status = 500)
    {
        $response = [];
        $response['message'] = $message;
        if (!empty($errorData)) {
            $response['data'] = $errorData;
        }
        return response()->json($response, $status);
    }
}

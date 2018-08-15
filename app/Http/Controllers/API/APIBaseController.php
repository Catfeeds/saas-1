<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

class APIBaseController extends Controller
{
    // 发送成功请求
    public function sendResponse($result, $message)
    {
        $response = [
            'success' => true,
            'data' => $result,
            'message' => $message
        ];
        return response()->json($response, 200);
    }

    // 发送失败请求
    public function sendError($errorMessages = '', $code = 415)
    {
        $response = [
            'success' => false,
            'message' => $errorMessages,
        ];
        return response()->json($response, $code);
    }
}

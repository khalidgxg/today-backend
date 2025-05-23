<?php

namespace App\Http\Traits;

trait ApiResponseTrait
{
    protected function sendResponse($message, $data = null, $status = 200)
    {
        return response()->json([
            'message' => $message,
            'data' => $data
        ], $status);
    }
} 
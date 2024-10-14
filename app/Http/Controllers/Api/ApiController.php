<?php

namespace App\Http\Controllers\Api;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class ApiController
{
    use AuthorizesRequests;

    public function success(mixed $data, ?string $message = null, ?int $statusCode = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    public function failed(string $message, string $error, ?int $statusCode = 400)
    {
        return response()->json([
            'success' => false,
            'error' => $error,
            'message' => $message,
        ], $statusCode);
    }
}

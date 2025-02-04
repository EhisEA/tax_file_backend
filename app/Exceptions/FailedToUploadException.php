<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FailedToUploadException extends Exception
{
    public function render(Request $request): JsonResponse
    {
        return response()->json([
            'status' => false,
            'message' => 'Failed to upload image'
        ], 422);
    }
}

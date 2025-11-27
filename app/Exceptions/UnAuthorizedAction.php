<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UnAuthorizedAction extends Exception
{
    /**
     * Report the exception.
     */
    public function report(): void
    {
        //
    }

    /**
     * Render the exception as an HTTP response.
     */
    public function render(Request $request): JsonResponse|Response
    {
        if ($request->is('api/*')) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to perform this action.',
            ], 403);
        }

        return response($this->getMessage() ?: 'Unauthorized action.', 403);
    }
}

<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ResponseHelper
{
    /**
     * @param array $data
     * @param bool $success
     * @param int $status
     * @return JsonResponse
     */
    public static function response(array $data, bool $success, int $status = Response::HTTP_OK): JsonResponse
    {
        return response()->json(
            [
                'success' => $success,
                'data' => $data,
            ],
            $status,
            [
                'Content-Type' => 'application/json;charset=UTF-8',
                'Charset' => 'utf-8',
            ],
            JSON_UNESCAPED_UNICODE
        );
    }
}

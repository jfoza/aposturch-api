<?php

namespace App\Exceptions;

use App\Shared\Enums\EnvironmentEnum;
use App\Shared\Enums\MessagesEnum;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class HandlerExceptions
{
    public static function returnMethodNotAllowedHttpException(): JsonResponse
    {
        return response()->json(
            ['error' => MessagesEnum::METHOD_NOT_ALLOWED],
            Response::HTTP_METHOD_NOT_ALLOWED
        );
    }

    public static function returnNotFoundHttpException(): JsonResponse
    {
        return response()->json(
            ['error' => MessagesEnum::RESOURCE_NOT_FOUND],
            Response::HTTP_METHOD_NOT_ALLOWED
        );
    }

    public static function returnQueryException(Throwable $e): JsonResponse
    {
        $info = App::environment([EnvironmentEnum::LOCAL->value])
            ? $e->getMessage()
            : [];

        return response()->json(
            [
                'error' => MessagesEnum::INTERNAL_SERVER_ERROR,
                'info' => $info
            ],
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }

    public static function returnAppException(Throwable $e): JsonResponse
    {
        return response()->json(
            ['error' => json_decode($e->getMessage())],
            $e->getCode()
        );
    }

    public static function returnUnauthorizedHttpException(): JsonResponse
    {
        return response()->json(
            ['error' => MessagesEnum::UNAUTHORIZED],
            Response::HTTP_UNAUTHORIZED
        );
    }

    public static function returnThrottleRequestsException(): JsonResponse
    {
        return response()->json(
            ['error' => MessagesEnum::TOO_MANY_REQUESTS],
            Response::HTTP_TOO_MANY_REQUESTS
        );
    }

    public static function returnDefaultException(Throwable $e): JsonResponse
    {
        Log::info($e->getMessage());

        $info = App::environment([EnvironmentEnum::LOCAL->value])
            ? $e->getMessage()
            : [];

        return response()->json(
            [
                'error' => MessagesEnum::INTERNAL_SERVER_ERROR,
                'info' => $info
            ],
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }
}

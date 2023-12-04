<?php

namespace App\Base\Exceptions;

use App\Exceptions\AppException;
use App\Shared\Enums\EnvironmentEnum;
use App\Shared\Enums\MessagesEnum;
use Exception;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class EnvironmentException
{
    /**
     * @throws AppException
     */
    public static function dispatchException(Exception $e, int $httpStatus = null)
    {
        $info = App::environment([EnvironmentEnum::LOCAL->value])
            ? $e->getMessage()
            : MessagesEnum::INTERNAL_SERVER_ERROR;

        throw new AppException(
            $info,
            !is_null($httpStatus) ? $httpStatus : Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }
}

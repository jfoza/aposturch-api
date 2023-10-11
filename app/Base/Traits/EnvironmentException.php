<?php

namespace App\Base\Traits;

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
    public static function dispatchException(Exception $e)
    {
        $info = App::environment([EnvironmentEnum::LOCAL->value])
            ? $e->getMessage()
            : MessagesEnum::INTERNAL_SERVER_ERROR;

        throw new AppException(
            $info,
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }
}

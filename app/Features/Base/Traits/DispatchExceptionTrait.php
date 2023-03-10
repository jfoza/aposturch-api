<?php

namespace App\Features\Base\Traits;

use App\Shared\Enums\EnvironmentEnum;
use App\Shared\Enums\MessagesEnum;
use App\Exceptions\AppException;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

trait DispatchExceptionTrait
{
    /**
     * @throws AppException
     */
    public function dispatchException(\Exception $e)
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

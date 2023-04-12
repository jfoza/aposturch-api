<?php

namespace App\Shared\Helpers;

use App\Shared\Enums\MiddlewareEnum;

class MiddlewareHelper
{
    public static function getModuleAccess(string $ability): string
    {
        return MiddlewareEnum::MODULE_ACCESS.':'.$ability;
    }
}

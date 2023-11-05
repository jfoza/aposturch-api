<?php

use App\Shared\Enums\MiddlewareEnum;
use App\Shared\Enums\ModulesRulesEnum;
use App\Shared\Helpers\MiddlewareHelper;
use Illuminate\Support\Facades\Route;

Route::prefix('/admin/modules/membership')
    ->middleware([
        MiddlewareEnum::JWT_AUTH,
        MiddlewareEnum::USER_CHECK,
        MiddlewareHelper::getModuleAccess(ModulesRulesEnum::MEMBERSHIP_MODULE_VIEW->value)
    ])
    ->group(function () {
        Route::prefix('/churches')
            ->group(app_path('Modules/Membership/Church/Routes/churchRoute.php'));

        Route::prefix('/members')
            ->group(app_path('Modules/Membership/Members/Routes/membersRoute.php'));
    });

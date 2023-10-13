<?php

use App\Shared\Enums\MiddlewareEnum;
use App\Shared\Enums\ModulesRulesEnum;
use App\Shared\Helpers\MiddlewareHelper;
use Illuminate\Support\Facades\Route;

Route::prefix('/admin/modules/store')
    ->middleware([
        MiddlewareEnum::JWT_AUTH,
        MiddlewareEnum::USER_CHECK,
        MiddlewareHelper::getModuleAccess(ModulesRulesEnum::STORE_MODULE_VIEW->value)
    ])
    ->group(function () {
        Route::prefix('/categories')
            ->group(app_path('Modules/Store/Categories/Routes/routes.php'));

        Route::prefix('/subcategories')
            ->group(app_path('Modules/Store/Subcategories/Routes/routes.php'));
    });

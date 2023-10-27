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
        Route::prefix('/departments')
            ->group(app_path('Modules/Store/Departments/Routes/routes.php'));

        Route::prefix('/subcategories')
            ->group(app_path('Modules/Store/Subcategories/Routes/routes.php'));

        Route::prefix('/products')
            ->group(app_path('Modules/Store/Products/Routes/routes.php'));
    });

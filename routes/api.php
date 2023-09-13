<?php

use App\Shared\Enums\MiddlewareEnum;
use App\Shared\Enums\ModulesRulesEnum;
use App\Shared\Helpers\MiddlewareHelper;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'Welcome',
        'status'  => 'Ok'
    ]);
});

// AUTH
Route::prefix('/admin/auth')
    ->group(app_path('Features/Auth/Routes/authRoute.php'));

Route::prefix('/auth')
    ->group(app_path('Features/Auth/Routes/logoutRoute.php'));

// PUBLIC
Route::prefix('/cities')
    ->group(app_path('Features/City/Cities/Routes/citiesRoute.php'));

Route::prefix('/states')
    ->group(app_path('Features/City/States/Routes/stateRoute.php'));


Route::prefix('/zip-code')
    ->group(app_path('Features/ZipCode/Http/Routes/zipCodeRoute.php'));

// ADMIN
Route::prefix('admin')
    ->middleware([
        MiddlewareEnum::JWT_AUTH,
        MiddlewareEnum::ACTIVE_USER
    ])
    ->group(function () {
        Route::prefix('/users')
            ->group(app_path('Features/Users/Users/Routes/usersRoute.php'));

        Route::prefix('/admin-users')
            ->group(app_path('Features/Users/AdminUsers/Routes/adminUsersRoute.php'));

        Route::prefix('/profiles')
            ->group(app_path('Features/Users/Profiles/Routes/profilesRoute.php'));

        Route::prefix('/modules')
            ->group(app_path('Features/Module/Modules/Routes/modulesRoute.php'));

        // MODULES
        Route::prefix('/modules/membership/churches')
            ->middleware(MiddlewareHelper::getModuleAccess(ModulesRulesEnum::MEMBERSHIP_MODULE_VIEW->value))
            ->group(app_path('Modules/Membership/Church/Routes/churchRoute.php'));

        Route::prefix('/modules/membership/members')
            ->middleware(MiddlewareHelper::getModuleAccess(ModulesRulesEnum::MEMBERSHIP_MODULE_VIEW->value))
            ->group(app_path('Modules/Membership/Members/Routes/membersRoute.php'));
    });

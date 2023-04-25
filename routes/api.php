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
Route::prefix('/auth')
    ->group(app_path('Features/Auth/Routes/forgotPasswordRoute.php'));

Route::prefix('/auth')
    ->group(app_path('Features/Auth/Routes/sessionDestroyRoute.php'));

Route::prefix('/auth')
    ->group(app_path('Features/Auth/Routes/customerUserSessionsRoute.php'));

Route::prefix('/admin/auth')
    ->group(app_path('Features/Auth/Routes/adminUsersAuthRoute.php'));

// PUBLIC
Route::prefix('/cities')
    ->group(app_path('Features/City/Cities/Http/Routes/citiesRoute.php'));

Route::prefix('/states')
    ->group(app_path('Features/City/States/Http/Routes/stateRoute.php'));

Route::prefix('/customers')
    ->group(app_path('Features/Users/CustomerUsers/Http/Routes/publicCustomerUsersRoute.php'));

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
            ->group(app_path('Features/Users/Users/Http/Routes/usersRoute.php'));

        Route::prefix('/admin-users')
            ->group(app_path('Features/Users/AdminUsers/Routes/adminUsersRoute.php'));

        Route::prefix('/profiles')
            ->group(app_path('Features/Users/Profiles/Http/Routes/profilesRoute.php'));

        // MODULES
        Route::prefix('/modules/members/churches')
            ->middleware(MiddlewareHelper::getModuleAccess(ModulesRulesEnum::MEMBERS_MODULE_VIEW->value))
            ->group(app_path('Modules/Members/Church/Routes/churchRoute.php'));
    });

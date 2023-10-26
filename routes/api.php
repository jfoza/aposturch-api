<?php

use App\Shared\Enums\MiddlewareEnum;
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

// GENERAL
Route::prefix('/unique-code')
    ->middleware([
        MiddlewareEnum::JWT_AUTH,
        MiddlewareEnum::USER_CHECK
    ])
    ->group(app_path('Features/General/UniqueCodePrefixes/Routes/routes.php'));

// PUBLIC
Route::prefix('/cities')
    ->group(app_path('Features/City/Cities/Routes/citiesRoute.php'));

Route::prefix('/states')
    ->group(app_path('Features/City/States/Routes/stateRoute.php'));

Route::prefix('/zip-code')
    ->group(app_path('Features/ZipCode/Routes/zipCodeRoute.php'));

// ADMIN
Route::prefix('admin')
    ->middleware([
        MiddlewareEnum::JWT_AUTH,
        MiddlewareEnum::USER_CHECK
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
    });

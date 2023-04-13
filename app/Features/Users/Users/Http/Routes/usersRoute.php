<?php

use App\Shared\Enums\MiddlewareEnum;
use App\Features\Users\Users\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

Route::get(
    '/',
    [UsersController::class, 'index']
);

Route::put(
    '/password/{id}',
    [UsersController::class, 'updatePassword']
)->middleware([MiddlewareEnum::UUID]);

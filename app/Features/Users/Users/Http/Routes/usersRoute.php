<?php

use App\Features\Users\Users\Http\Controllers\UsersController;
use App\Shared\Enums\MiddlewareEnum;
use Illuminate\Support\Facades\Route;

Route::get(
    '/church/{id}',
    [UsersController::class, 'findAllByChurch']
)->middleware([MiddlewareEnum::UUID]);

<?php

use App\Shared\Enums\MiddlewareEnum;
use App\Features\Users\CustomerUsers\Http\Controllers\CustomerUsersController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CustomerUsersController::class, 'index']);

Route::get('/{id}', [CustomerUsersController::class, 'showById'])->middleware([MiddlewareEnum::UUID]);

Route::post('/', [CustomerUsersController::class, 'insert']);

Route::put('/{id}', [CustomerUsersController::class, 'update'])->middleware([MiddlewareEnum::UUID]);

Route::patch('/new-password/{id}', [
        \App\Features\Users\NewPasswordGenerations\Http\Controllers\NewPasswordGenerationsController::class,
        'update'
    ])->middleware([MiddlewareEnum::UUID]);

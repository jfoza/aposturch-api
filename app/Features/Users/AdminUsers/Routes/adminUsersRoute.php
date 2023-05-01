<?php

use App\Features\Users\AdminUsers\Controllers\AdminUsersController;
use App\Shared\Enums\MiddlewareEnum;
use Illuminate\Support\Facades\Route;

Route::get('/', [AdminUsersController::class, 'index']);

Route::get('/id/{id}', [AdminUsersController::class, 'showById'])->middleware([MiddlewareEnum::UUID]);

Route::post('/', [AdminUsersController::class, 'insert']);

Route::put('/{id}', [AdminUsersController::class, 'update'])->middleware([MiddlewareEnum::UUID]);

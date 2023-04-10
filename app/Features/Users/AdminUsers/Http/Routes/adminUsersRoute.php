<?php

use App\Shared\Enums\MiddlewareEnum;
use App\Features\Users\AdminUsers\Http\Controllers\AdminUsersController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AdminUsersController::class, 'index']);

Route::get('/me', [AdminUsersController::class, 'showLoggedUserResource']);

Route::get('/count/profiles', [AdminUsersController::class, 'showCountByProfiles']);

Route::get('/id/{id}', [AdminUsersController::class, 'showById'])->middleware([MiddlewareEnum::UUID]);

Route::post('/', [AdminUsersController::class, 'insert']);

Route::put('/{id}', [AdminUsersController::class, 'update'])->middleware([MiddlewareEnum::UUID]);

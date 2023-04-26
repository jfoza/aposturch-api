<?php

use App\Features\Users\AdminUsers\Controllers\AdminUsersController;
use App\Shared\Enums\MiddlewareEnum;
use Illuminate\Support\Facades\Route;

Route::get('/', [AdminUsersController::class, 'index']);

Route::get('/me', [AdminUsersController::class, 'showLoggedUserResource']);

Route::get('/count/profiles', [AdminUsersController::class, 'showCountByProfiles']);

Route::get('/id/{id}', [AdminUsersController::class, 'showById'])->middleware([MiddlewareEnum::UUID]);

Route::get('/responsible/church/{id}', [AdminUsersController::class, 'showResponsibleChurch'])->middleware([MiddlewareEnum::UUID]);

Route::get('/profile-unique-name/{profileUniqueName}', [AdminUsersController::class, 'showByProfileUniqueName'])
    ->middleware([MiddlewareEnum::PROFILE_UNIQUE_NAME]);

Route::post('/', [AdminUsersController::class, 'insert']);

Route::put('/id/{id}', [AdminUsersController::class, 'update'])->middleware([MiddlewareEnum::UUID]);

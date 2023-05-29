<?php

use App\Features\Users\Users\Controllers\UsersController;
use App\Shared\Enums\MiddlewareEnum;
use Illuminate\Support\Facades\Route;

Route::get('/me', [UsersController::class, 'showLoggedUserResource']);

Route::get('/email/{email}', [UsersController::class, 'userEmailAlreadyExists'])->middleware(MiddlewareEnum::EMAIL);

Route::put('/status/id/{id}', [UsersController::class, 'updateStatus'])->middleware(MiddlewareEnum::UUID);

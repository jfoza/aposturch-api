<?php

use App\Modules\Membership\Members\Controllers\MembersController;
use App\Shared\Enums\MiddlewareEnum;
use Illuminate\Support\Facades\Route;

Route::get('/', [MembersController::class, 'index']);

Route::get('/user/{id}', [MembersController::class, 'showByUserId'])->middleware([MiddlewareEnum::UUID]);

Route::post('/', [MembersController::class, 'insert']);

Route::put('/id/{id}', [MembersController::class, 'update'])->middleware([MiddlewareEnum::UUID]);

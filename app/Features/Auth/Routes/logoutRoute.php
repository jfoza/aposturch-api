<?php

use App\Features\Auth\Controllers\AuthController;
use App\Shared\Enums\MiddlewareEnum;
use Illuminate\Support\Facades\Route;

Route::get('/logout', [AuthController::class, 'destroy'])->middleware([MiddlewareEnum::JWT_AUTH]);

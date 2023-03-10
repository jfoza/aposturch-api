<?php

use App\Shared\Enums\MiddlewareEnum;
use App\Features\Auth\Http\Controllers\ForgotPasswordController;
use Illuminate\Support\Facades\Route;

Route::post('/password/forgot',
    [ForgotPasswordController::class, 'sendEmail']
);

Route::put('/password/reset/{code}',
    [ForgotPasswordController::class, 'resetPassword']
)->middleware([MiddlewareEnum::CODE->value]);

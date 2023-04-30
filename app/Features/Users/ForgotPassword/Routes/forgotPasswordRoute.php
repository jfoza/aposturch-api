<?php

use App\Features\Users\ForgotPassword\Controllers\ForgotPasswordController;
use App\Shared\Enums\MiddlewareEnum;
use Illuminate\Support\Facades\Route;

Route::post('/password/forgot',
    [ForgotPasswordController::class, 'sendEmail']
);

Route::put('/password/reset/{code}',
    [ForgotPasswordController::class, 'resetPassword']
)->middleware([MiddlewareEnum::CODE]);

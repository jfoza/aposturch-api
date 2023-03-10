<?php

use App\Shared\Enums\MiddlewareEnum;
use App\Features\Users\CustomerUsers\Http\Controllers\PublicCustomerUsersController;
use Illuminate\Support\Facades\Route;

Route::get('/my-account', [PublicCustomerUsersController::class, 'show'])
    ->middleware([
        MiddlewareEnum::JWT_AUTH->value,
        MiddlewareEnum::ACTIVE_USER->value,
    ]);

Route::get('/email/exists/{email}', [PublicCustomerUsersController::class, 'emailExists'])
    ->middleware([
        MiddlewareEnum::EMAIL->value
    ]);

Route::post('/', [PublicCustomerUsersController::class, 'insert']);

Route::post('/email/resend', [PublicCustomerUsersController::class, 'resendEmail'])
    ->middleware([
        MiddlewareEnum::EMAIL->value
    ]);

Route::put('/authorize/{id}', [PublicCustomerUsersController::class, 'authorizeCustomerUser'])
    ->middleware([
        MiddlewareEnum::UUID->value
    ]);

Route::put('/my-account', [PublicCustomerUsersController::class, 'update'])
    ->middleware([
        MiddlewareEnum::JWT_AUTH->value,
        MiddlewareEnum::ACTIVE_USER->value,
    ]);

Route::put('/password/update', [PublicCustomerUsersController::class, 'updateCustomerUserPassword'])
    ->middleware([
        MiddlewareEnum::JWT_AUTH->value,
        MiddlewareEnum::ACTIVE_USER->value,
    ]);

<?php

use App\Shared\Enums\MiddlewareEnum;
use App\Features\Users\CustomerUsers\Http\Controllers\PublicCustomerUsersController;
use Illuminate\Support\Facades\Route;

Route::get('/my-account', [PublicCustomerUsersController::class, 'show'])
    ->middleware([
        MiddlewareEnum::JWT_AUTH,
        MiddlewareEnum::ACTIVE_USER,
    ]);

Route::get('/email/exists/{email}', [PublicCustomerUsersController::class, 'emailExists'])
    ->middleware([
        MiddlewareEnum::EMAIL
    ]);

Route::post('/', [PublicCustomerUsersController::class, 'insert']);

Route::post('/email/resend', [PublicCustomerUsersController::class, 'resendEmail'])
    ->middleware([
        MiddlewareEnum::EMAIL
    ]);

Route::put('/authorize/{id}', [PublicCustomerUsersController::class, 'authorizeCustomerUser'])
    ->middleware([
        MiddlewareEnum::UUID
    ]);

Route::put('/my-account', [PublicCustomerUsersController::class, 'update'])
    ->middleware([
        MiddlewareEnum::JWT_AUTH,
        MiddlewareEnum::ACTIVE_USER,
    ]);

Route::put('/password/update', [PublicCustomerUsersController::class, 'updateCustomerUserPassword'])
    ->middleware([
        MiddlewareEnum::JWT_AUTH,
        MiddlewareEnum::ACTIVE_USER,
    ]);

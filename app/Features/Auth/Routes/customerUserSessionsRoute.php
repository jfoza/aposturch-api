<?php

use App\Features\Auth\Controllers\SessionsCustomerUserController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [SessionsCustomerUserController::class, 'create']);

<?php

use App\Features\Auth\Http\Controllers\SessionsCustomerUserController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [SessionsCustomerUserController::class, 'create']);

<?php

use App\Features\Auth\Http\Controllers\SessionsAdminUserController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [SessionsAdminUserController::class, 'create']);

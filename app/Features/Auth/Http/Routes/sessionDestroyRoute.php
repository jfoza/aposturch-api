<?php

use App\Features\Auth\Http\Controllers\SessionsAdminUserController;
use Illuminate\Support\Facades\Route;

Route::get('/logout', [SessionsAdminUserController::class, 'destroy'])->middleware(['jwt.auth']);

<?php

use App\Features\Auth\Controllers\AdminUsersAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/logout', [AdminUsersAuthController::class, 'destroy'])->middleware(['jwt.auth']);

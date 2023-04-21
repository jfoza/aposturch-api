<?php

use App\Features\Auth\Controllers\AdminUsersAuthController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AdminUsersAuthController::class, 'create']);

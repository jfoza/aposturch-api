<?php

use App\Features\Auth\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'create']);
Route::post('/login/google', [AuthController::class, 'createWithGoogle']);

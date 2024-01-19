<?php

use Illuminate\Support\Facades\Route;
use App\Features\Auth\Controllers\AuthController;


Route::post('/login', [AuthController::class, 'create']);
Route::post('/login/google', [AuthController::class, 'createWithGoogle']);

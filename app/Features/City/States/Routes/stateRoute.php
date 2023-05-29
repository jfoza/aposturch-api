<?php

use App\Features\City\States\Controllers\StateController;
use App\Shared\Enums\MiddlewareEnum;
use Illuminate\Support\Facades\Route;

Route::get('/', [StateController::class, 'index']);

Route::get('/id/{id}', [StateController::class, 'showById'])->middleware([MiddlewareEnum::UUID]);

Route::get('/uf/{uf}', [StateController::class, 'showByUF']);

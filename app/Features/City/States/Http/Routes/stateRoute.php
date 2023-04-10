<?php

use App\Shared\Enums\MiddlewareEnum;
use App\Features\City\States\Http\Controllers\StateController;
use Illuminate\Support\Facades\Route;

Route::get('/', [StateController::class, 'index']);

Route::get('/id/{id}', [StateController::class, 'showById'])->middleware([MiddlewareEnum::UUID]);

Route::get('/uf/{uf}', [StateController::class, 'showByUF']);

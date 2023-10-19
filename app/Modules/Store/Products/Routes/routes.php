<?php

use App\Modules\Store\Products\Controllers\ProductsController;
use App\Shared\Enums\MiddlewareEnum;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProductsController::class, 'index']);
Route::get('/id/{id}', [ProductsController::class, 'showById']);
Route::post('/', [ProductsController::class, 'insert']);
Route::put('/id/{id}', [ProductsController::class, 'update'])->middleware([MiddlewareEnum::UUID]);
Route::put('/status', [ProductsController::class, 'updateStatus']);
Route::delete('/id/{id}', [ProductsController::class, 'delete'])->middleware([MiddlewareEnum::UUID]);

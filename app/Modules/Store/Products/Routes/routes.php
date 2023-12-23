<?php

use App\Modules\Store\Products\Controllers\ProductsController;
use App\Modules\Store\Products\Controllers\ProductsPersistenceController;
use App\Shared\Enums\MiddlewareEnum;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProductsController::class, 'index']);
Route::get('/id/{id}', [ProductsController::class, 'showById'])->middleware([MiddlewareEnum::UUID]);
Route::get('/unique-name/{uniqueName}', [ProductsController::class, 'showByUniqueName']);
Route::post('/', [ProductsPersistenceController::class, 'insert']);
Route::put('/id/{id}', [ProductsPersistenceController::class, 'update'])->middleware([MiddlewareEnum::UUID]);
Route::put('/status', [ProductsPersistenceController::class, 'updateStatus']);
Route::delete('/id/{id}', [ProductsPersistenceController::class, 'delete'])->middleware([MiddlewareEnum::UUID]);

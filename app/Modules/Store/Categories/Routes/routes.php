<?php

use App\Modules\Store\Categories\Controllers\CategoriesController;
use App\Shared\Enums\MiddlewareEnum;
use Illuminate\Support\Facades\Route;

Route::get('/', [CategoriesController::class, 'index']);
Route::get('/id/{id}', [CategoriesController::class, 'showById']);
Route::post('/', [CategoriesController::class, 'insert']);
Route::put('/id/{id}', [CategoriesController::class, 'update'])->middleware([MiddlewareEnum::UUID]);
Route::delete('/id/{id}', [CategoriesController::class, 'delete'])->middleware([MiddlewareEnum::UUID]);

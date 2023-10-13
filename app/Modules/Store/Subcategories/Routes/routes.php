<?php

use App\Modules\Store\Subcategories\Controllers\SubcategoriesController;
use App\Shared\Enums\MiddlewareEnum;
use Illuminate\Support\Facades\Route;

Route::get('/', [SubcategoriesController::class, 'index']);
Route::get('/id/{id}', [SubcategoriesController::class, 'showById']);
Route::post('/', [SubcategoriesController::class, 'insert']);
Route::put('/id/{id}', [SubcategoriesController::class, 'update'])->middleware([MiddlewareEnum::UUID]);
Route::delete('/id/{id}', [SubcategoriesController::class, 'delete'])->middleware([MiddlewareEnum::UUID]);

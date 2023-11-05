<?php

use App\Modules\Store\Departments\Controllers\DepartmentsController;
use App\Shared\Enums\MiddlewareEnum;
use Illuminate\Support\Facades\Route;

Route::get('/', [DepartmentsController::class, 'index']);
Route::get('/id/{id}', [DepartmentsController::class, 'showById'])->middleware([MiddlewareEnum::UUID]);
Route::post('/', [DepartmentsController::class, 'insert']);
Route::put('/id/{id}', [DepartmentsController::class, 'update'])->middleware([MiddlewareEnum::UUID]);
Route::put('/status', [DepartmentsController::class, 'updateStatus']);
Route::delete('/id/{id}', [DepartmentsController::class, 'delete'])->middleware([MiddlewareEnum::UUID]);

<?php

use Illuminate\Support\Facades\Route;
use App\Shared\Enums\MiddlewareEnum;
use App\Modules\Members\Church\Controllers\ChurchController;
use App\Modules\Members\Church\Controllers\ChurchUploadImageController;

Route::get('/', [ChurchController::class, 'index']);

Route::get('/{id}', [ChurchController::class, 'show'])->middleware([MiddlewareEnum::UUID]);

Route::post('/', [ChurchController::class, 'insert']);

Route::put('/{id}', [ChurchController::class, 'update'])->middleware([MiddlewareEnum::UUID]);

Route::delete('/{id}', [ChurchController::class, 'delete'])->middleware([MiddlewareEnum::UUID]);

// UPLOAD CHURCH IMAGE
Route::post('/upload/image', [ChurchUploadImageController::class, 'store']);

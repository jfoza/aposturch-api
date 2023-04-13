<?php

use Illuminate\Support\Facades\Route;
use App\Shared\Enums\MiddlewareEnum;
use App\Modules\Members\Church\Controllers\ChurchController;
use App\Modules\Members\Church\Controllers\ChurchUploadImageController;

Route::get('/', [ChurchController::class, 'index']);

Route::get('/id/{id}', [ChurchController::class, 'show'])->middleware([MiddlewareEnum::UUID]);

Route::get(
    '/unique-name/{uniqueName}',
    [ChurchController::class, 'showByUniqueName'])
    ->middleware(
        [MiddlewareEnum::CHURCH_UNIQUE_NAME]
    );

Route::post('/', [ChurchController::class, 'insert']);

Route::put('/id/{id}', [ChurchController::class, 'update'])->middleware([MiddlewareEnum::UUID]);

Route::delete('/{id}', [ChurchController::class, 'delete'])->middleware([MiddlewareEnum::UUID]);

// UPLOAD CHURCH IMAGE
Route::post('/upload/image', [ChurchUploadImageController::class, 'store']);

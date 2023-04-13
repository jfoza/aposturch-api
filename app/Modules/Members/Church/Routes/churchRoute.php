<?php

use Illuminate\Support\Facades\Route;
use App\Shared\Enums\MiddlewareEnum;
use App\Modules\Members\Church\Controllers\ChurchListController;
use App\Modules\Members\Church\Controllers\ChurchPersistenceController;
use App\Modules\Members\Church\Controllers\ChurchUploadImageController;

// LISTS
Route::get('/', [ChurchListController::class, 'index']);

Route::get('/id/{id}', [ChurchListController::class, 'show'])->middleware([MiddlewareEnum::UUID]);

Route::get(
    '/unique-name/{uniqueName}',
    [ChurchListController::class, 'showByUniqueName'])
    ->middleware(
        [MiddlewareEnum::CHURCH_UNIQUE_NAME]
    );

// PERSISTENCE
Route::post('/', [ChurchPersistenceController::class, 'insert']);

Route::put('/id/{id}', [ChurchPersistenceController::class, 'update'])->middleware([MiddlewareEnum::UUID]);

Route::delete('/{id}', [ChurchPersistenceController::class, 'delete'])->middleware([MiddlewareEnum::UUID]);

Route::delete('/user/relationship/{id}', [ChurchPersistenceController::class, 'deleteRelationship'])->middleware([MiddlewareEnum::UUID]);

// UPLOAD CHURCH IMAGE
Route::post('/upload/image', [ChurchUploadImageController::class, 'store']);

<?php

use App\Modules\Membership\Church\Controllers\ChurchListController;
use App\Modules\Membership\Church\Controllers\ChurchPersistenceController;
use App\Modules\Membership\Church\Controllers\ChurchUploadImageController;
use App\Shared\Enums\MiddlewareEnum;
use Illuminate\Support\Facades\Route;

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

Route::delete('/user/member-relationship/{id}', [ChurchPersistenceController::class, 'deleteMemberRelationship'])->middleware([MiddlewareEnum::UUID]);

Route::delete('/user/responsible-relationship/{id}', [ChurchPersistenceController::class, 'deleteResponsibleRelationship'])->middleware([MiddlewareEnum::UUID]);

// UPLOAD CHURCH IMAGE
Route::post('/upload/image', [ChurchUploadImageController::class, 'store']);

<?php

use App\Modules\Membership\Church\Controllers\ChurchListController;
use App\Modules\Membership\Church\Controllers\ChurchPersistenceController;
use App\Modules\Membership\Church\Controllers\ChurchUploadImageController;
use App\Shared\Enums\MiddlewareEnum;
use Illuminate\Support\Facades\Route;

// LISTS
Route::get('/', [ChurchListController::class, 'index']);

Route::get('/id/{id}', [ChurchListController::class, 'show'])->middleware([MiddlewareEnum::UUID]);

Route::get('/user', [ChurchListController::class, 'showByUserLogged']);

Route::get(
    '/unique-name/{uniqueName}',
    [ChurchListController::class, 'showByUniqueName'])
    ->middleware(
        [MiddlewareEnum::CHURCH_UNIQUE_NAME]
    );

Route::post('/', [ChurchPersistenceController::class, 'insert']);

Route::put('/id/{id}', [ChurchPersistenceController::class, 'update'])->middleware([MiddlewareEnum::UUID]);

Route::delete('/{id}', [ChurchPersistenceController::class, 'delete'])->middleware([MiddlewareEnum::UUID]);

Route::post('/upload/image', [ChurchUploadImageController::class, 'store']);

<?php

use App\Features\General\UniqueCodePrefixes\Controllers\UniqueCodeGeneratorController;
use App\Features\General\UniqueCodePrefixes\Controllers\UniqueCodePrefixesController;
use App\Shared\Enums\MiddlewareEnum;
use Illuminate\Support\Facades\Route;

Route::get('/generator', [UniqueCodeGeneratorController::class, 'generateUniqueCode']);

Route::prefix('/prefixes')
    ->group(function() {
        Route::get('/', [UniqueCodePrefixesController::class, 'index']);
        Route::get('/id/{id}', [UniqueCodePrefixesController::class, 'showById'])->middleware([MiddlewareEnum::UUID]);
        Route::post('/', [UniqueCodePrefixesController::class, 'insert']);
        Route::put('/id/{id}', [UniqueCodePrefixesController::class, 'update'])->middleware([MiddlewareEnum::UUID]);
        Route::delete('/id/{id}', [UniqueCodePrefixesController::class, 'delete'])->middleware([MiddlewareEnum::UUID]);
    });

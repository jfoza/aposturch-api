<?php

use App\Shared\Enums\MiddlewareEnum;
use App\Features\City\Cities\Http\Controllers\CityController;
use Illuminate\Support\Facades\Route;

Route::get('/uf/{uf}', [CityController::class, 'showByUF']);

Route::get('/id/{id}', [CityController::class, 'showById'])->middleware([MiddlewareEnum::UUID->value]);

Route::get('/in-persons', [CityController::class, 'showInPersons']);

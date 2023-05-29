<?php

use App\Features\City\Cities\Controllers\CityController;
use App\Shared\Enums\MiddlewareEnum;
use Illuminate\Support\Facades\Route;

Route::get('/uf/{uf}', [CityController::class, 'showByUF']);

Route::get('/id/{id}', [CityController::class, 'showById'])->middleware([MiddlewareEnum::UUID]);

Route::get('/in-persons', [CityController::class, 'showInPersons']);

Route::get('/in-churches', [CityController::class, 'showInChurches']);

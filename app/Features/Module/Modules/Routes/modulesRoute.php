<?php

use App\Features\Module\Modules\Controllers\ModulesController;
use Illuminate\Support\Facades\Route;

Route::get('/list', [ModulesController::class, 'showByUserLogged']);

<?php

use App\Features\ZipCode\Controllers\ZipCodeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ZipCodeController::class, 'showByZipCode']);


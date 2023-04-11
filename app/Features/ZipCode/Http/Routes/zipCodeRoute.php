<?php

use App\Features\ZipCode\Http\Controllers\ZipCodeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ZipCodeController::class, 'showByZipCode']);


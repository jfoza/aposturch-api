<?php

use App\Features\Users\Profiles\Http\Controllers\ProfilesController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProfilesController::class, 'index']);

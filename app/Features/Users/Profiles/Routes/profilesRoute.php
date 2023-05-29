<?php

use App\Features\Users\Profiles\Controllers\ProfilesController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProfilesController::class, 'index']);
Route::get('/members', [ProfilesController::class, 'showInListMembers']);

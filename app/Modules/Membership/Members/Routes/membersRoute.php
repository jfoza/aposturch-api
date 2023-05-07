<?php

use App\Modules\Membership\Members\Controllers\MembersController;
use Illuminate\Support\Facades\Route;

Route::get('/responsible', [MembersController::class, 'getMembersResponsible']);

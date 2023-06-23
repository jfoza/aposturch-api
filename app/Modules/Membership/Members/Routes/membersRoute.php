<?php

use App\Modules\Membership\Members\Controllers\MembersController;
use App\Modules\Membership\Members\Controllers\UpdateMembersController;
use App\Shared\Enums\MiddlewareEnum;
use Illuminate\Support\Facades\Route;

Route::get('/', [MembersController::class, 'index']);

Route::get('/user/{id}', [MembersController::class, 'showByUserId'])->middleware([MiddlewareEnum::UUID]);

Route::post('/', [MembersController::class, 'insert']);

Route::put('/general-data/id/{id}',  [UpdateMembersController::class, 'updateGeneralData'])->middleware([MiddlewareEnum::UUID]);
Route::put('/address-data/id/{id}',  [UpdateMembersController::class, 'updateAddressData'])->middleware([MiddlewareEnum::UUID]);
Route::put('/church-data/id/{id}',   [UpdateMembersController::class, 'updateChurchData'])->middleware([MiddlewareEnum::UUID]);
Route::put('/modules-data/id/{id}',  [UpdateMembersController::class, 'updateModulesData'])->middleware([MiddlewareEnum::UUID]);
Route::put('/profile-data/id/{id}',  [UpdateMembersController::class, 'updateProfileData'])->middleware([MiddlewareEnum::UUID]);
Route::put('/password-data/id/{id}', [UpdateMembersController::class, 'updatePasswordData'])->middleware([MiddlewareEnum::UUID]);

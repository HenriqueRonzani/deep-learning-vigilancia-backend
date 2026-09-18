<?php

use App\Http\Controllers\InspectionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('users')
    ->group(function () {
        Route::post('', [UserController::class, 'create']);
        Route::put('{id}', [UserController::class, 'update']);
        Route::patch('{id}', [UserController::class, 'updatePassword']);
        Route::get('', [UserController::class, 'getAll']);
        Route::get('{id}', [UserController::class, 'getById']);
        Route::delete('{id}', [UserController::class, 'delete']);
    });

Route::prefix('inspections')
    ->group(function () {
        Route::post('', [InspectionController::class, 'create']);
    });

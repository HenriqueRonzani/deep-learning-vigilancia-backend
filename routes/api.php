<?php

use App\Http\Controllers\InspectionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('inspections')
    ->group(function () {
        Route::post('', [InspectionController::class, 'create']);
        Route::put('{id}', [InspectionController::class, 'update']);
        Route::get('', [InspectionController::class, 'getAll']);
        Route::get('{id}', [InspectionController::class, 'getById']);
        Route::delete('{id}', [InspectionController::class, 'delete']);
    });

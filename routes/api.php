<?php

use App\Http\Controllers\FileReportController;
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
    ->controller(InspectionController::class)
    ->group(function () {
        Route::post('', 'create');
        Route::post('{id}/batch-upload-urls', 'batchUploadUrls');
        Route::post('{id}/process', 'process');

        Route::get('{id}/status', 'statusPolling');

        Route::patch('{id}/status', 'updateStatus');
        Route::put('{id}', 'update');
        Route::get('', 'getAll');
        Route::get('{id}', 'getById');
    });

Route::prefix('file-report')
    ->controller(FileReportController::class)
    ->group(function () {
        Route::post('batch', 'createBatch');
        Route::patch('{id}/feedback', 'feedback');

    });

<?php

use App\Http\Controllers\FileReportController;
use App\Http\Controllers\InspectionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

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

<?php

use Illuminate\Support\Facades\Route;
use L5Swagger\Http\Controllers\SwaggerController;
Route::get('/', function () {
    return view('welcome');
});
Route::post('/api/documentation', [SwaggerController::class, 'api'])->name('l5-swagger.default.api');
Route::get('/api/docs/{jsonFile}', function ($jsonFile) {
    $filePath = storage_path("api-docs/{$jsonFile}");
    if (file_exists($filePath)) {
        return response()->file($filePath, ['Content-Type' => 'application/json']);
    }
    return response()->json(['error' => 'File not found'], 404);
});

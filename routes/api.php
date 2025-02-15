<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PreferenceController;
use App\Http\Controllers\ArticleController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/articles', [ArticleController::class, 'index']); // Fetch all articles
    Route::get('/articles/{id}', [ArticleController::class, 'show']); // Fetch single article
    Route::get('/articles/search', [ArticleController::class, 'search']); // Search articles
    Route::get('/preferences', [PreferenceController::class, 'index']); // Get user preferences
    Route::post('/preferences', [PreferenceController::class, 'store']); // Set user preferences
    Route::put('/preferences', [PreferenceController::class, 'update']); // Update preferences
});
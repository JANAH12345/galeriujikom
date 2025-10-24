<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PetugasController;
use App\Http\Controllers\Api\KategoriController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\AgendaController;
use App\Http\Controllers\Api\GalleryController;
use App\Http\Controllers\Api\FotoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Authentication routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Petugas routes
    Route::apiResource('petugas', PetugasController::class);
    
    // Kategori routes
    Route::apiResource('kategori', KategoriController::class);
    
    // Post routes
    Route::apiResource('posts', PostController::class);
    
    // Profile routes
    Route::apiResource('profile', ProfileController::class);
    
    // Agenda routes
    Route::apiResource('agenda', AgendaController::class);
    
    // Gallery routes
    Route::apiResource('gallery', GalleryController::class);
    
    // Foto routes
    Route::apiResource('foto', FotoController::class);
});

// Public routes (if needed)
Route::get('/kategori', [KategoriController::class, 'index']);
Route::get('/posts', [PostController::class, 'index']);
Route::get('/agenda', [AgendaController::class, 'index']);

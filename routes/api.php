<?php

use App\Http\Controllers\API\SearchController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public API Routes
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Search API
Route::prefix('search')->group(function () {
    Route::get('/', [SearchController::class, 'search'])->name('api.search');
    Route::get('/suggestions', [SearchController::class, 'suggestions'])->name('api.search.suggestions');
});

// API Version 1
Route::prefix('v1')->name('api.v1.')->group(function () {
    // Public routes
    Route::get('/status', function () {
        return response()->json(['status' => 'OK']);
    })->name('status');

    // Add more v1 API routes here
});

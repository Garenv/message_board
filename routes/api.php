<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ThreadController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

// Auth routes
Route::middleware('auth:sanctum')->group( function () {
    /**
     * Doug - Endpoints aren't organized in a RESTful pattern
     */
    Route::get('getUser/{userId}', [UserController::class, 'getUser']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('createThread', [ThreadController::class, 'createThread']);
    Route::get('getThreadMessages/{threadId}', [ThreadController::class, 'getThreadMessages']);
    Route::get('getUserThreads/{userId}', [ThreadController::class, 'getUserThreads']);
    Route::post('createThreadMessage/{threadId}', [ThreadController::class, 'createThreadMessage']);
    Route::get('searchThreadMessages/{threadId}', [ThreadController::class, 'searchThreadMessages']);
});

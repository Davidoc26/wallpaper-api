<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ImageController;
use Illuminate\Support\Facades\Route;

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
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/images', [ImageController::class, 'store']);
    Route::post('/images/upload-all', [ImageController::class, 'storeAll']);
    Route::post('/images/{image}/add-category/', [ImageController::class, 'addCategory']);

    Route::patch('/categories/{category:slug}', [CategoryController::class, 'update']);
    Route::post('/categories', [CategoryController::class, 'store']);
});
Route::get('/images/{image}/download', [ImageController::class, 'download']);
Route::get('/images/{image}', [ImageController::class, 'show']);

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{slug}', [CategoryController::class, 'show']);

Route::post('/auth/login', [LoginController::class, 'login']);
Route::post('/auth/register', [LoginController::class, 'register']);

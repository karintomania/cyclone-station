<?php

use App\Http\Controllers\Auth\CreateAccountController;
use App\Http\Controllers\Auth\IssueTokenController;
use App\Http\Controllers\ItemController;
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

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('items', ItemController::class);
});

Route::prefix('auth')->group(function () {
    Route::get('/token', IssueTokenController::class);
    Route::post('/createAccount', CreateAccountController::class);
});

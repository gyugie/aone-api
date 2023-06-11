<?php

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

Route::post('user/register', \App\Http\Controllers\API\UserRegisterController::class)->name('user.register');
Route::post('user/login', [\App\Http\Controllers\API\AuthController::class, 'login'])->name('user.login');

Route::middleware('auth:sanctum')->group(function () {
    Route::resource('users', \App\Http\Controllers\API\UserController::class);
});

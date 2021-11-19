<?php

use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Auth\LogoutController;
use App\Http\Controllers\API\Auth\RegisterController;
use App\Http\Controllers\API\Auth\ResetPasswordController;
use App\Http\Controllers\API\Auth\TokenController;
use Illuminate\Http\Request;
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

Route::post('/login', [LoginController::class, 'signIn']);
Route::post('/register', [RegisterController::class, 'signUp']);
Route::post('/reset', [ResetPasswordController::class, 'resetPassword']);
Route::post('/reset/check', [ResetPasswordController::class, 'resetCheck']);

Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::get('/user', function(Request $request) {
        return response()->json(['user' => $request->user()]);
    });
    Route::post('/logout', [LogoutController::class, 'logout']);
    Route::get('/check', [TokenController::class, 'checkToken']);
    Route::post('/refresh', [TokenController::class, 'refreshToken']);
});

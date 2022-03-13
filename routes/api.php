<?php

use App\Http\Controllers\AuthApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
//use App\Http\Controllers\passportAuthController;
use App\Http\Controllers\VerificationApiController;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Http\Response;


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//add this middleware to ensure that every request is authenticated and verity
Route::middleware('auth:api', 'verified')->group(function () {
    Route::post('/logout', [AuthApiController::class, 'logout']);

});

Route::post('/register', [AuthApiController::class, 'register']);
Route::post('/VerifyOTP', [AuthApiController::class, 'VerifyOTPCode']);

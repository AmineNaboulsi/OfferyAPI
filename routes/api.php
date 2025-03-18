<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CompetencesController;
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



Route::post('/login' , [AuthController::class , 'signin'])->name("login");
Route::get('/login' , function(){
    return "Login";
});
Route::post('/register' , [AuthController::class , 'register'])->name("register");

//Route::apiResource('offers', OfferController::class);

Route::group([
    'middleware' => 'auth:api',
], function ($router) {
    Route::post('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/upload', [UserController::class, 'upload']);
    Route::post('/postule', [UserController::class, 'postule']);
    Route::apiResource('competences', CompetencesController::class);
});


<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CompetencesController;
use App\Http\Controllers\UserCompetences;
use App\Http\Controllers\RoleController;
use App\Models\User;
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

Route::get('/users', function (Request $request) {
    return User::all();
});
Route::group([
    'middleware' => 'auth:api',
], function ($router) {
    Route::post('/user', function (Request $request) {
        return response()->json([
            auth()->user()->with('competences')->get() ,
        ]);
    });

    Route::post('/upload', [UserController::class, 'upload']);
    Route::post('/postule', [UserController::class, 'postule']);
    Route::post('/user/role', [UserController::class, 'affectrole']);
    Route::apiResource('competences', CompetencesController::class);
    Route::post('addCompetence/{user:id}', [UserCompetences::class, 'AddCompetence']);
    Route::apiResource('offers', OfferController::class);

});

Route::apiResource('roles', RoleController::class);

<?php

use App\Http\Controllers\IngredientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReservationController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::apiResource('/ingredients',IngredientController::class);

//ingrdents api
Route::apiResource('ingredients',IngredientController::class)->except('destroy');

Route::get('ingredients/status/{id}',[IngredientController::class,'changeStatus']);
Route::get('search/ingredient',[IngredientController::class,'search']);
Route::get('active/ingredient',[IngredientController::class,'getActiveIngredients']);



//product api
Route::apiResource('products',ProductController::class)->except('destroy');
Route::get('products/status/{id}',[ProductController::class,'changeStatus']);

//reservation api for admin
Route::get('reservation',[ReservationController::class ,'index']);
Route::get('reservation/{id}',[ReservationController::class ,'getReservationByTableId']);
Route::get('reservation',[ReservationController::class ,'getReservationByDate']);



<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\OrderController;



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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Route::middleware('auth:sanctum')->prefix('users')->group(function(){
//     Route::get('/auth',[UserController::class, 'UserDetails']);

// });

Route::prefix('users')->group(function(){
    Route::get('',[UserController::class, 'index']);
    Route::post('',[UserController::class, 'store']);
    Route::get('/{id}',[UserController::class, 'show']);
    Route::put('/{id}',[UserController::class, 'update']);
    Route::delete('/{id}',[UserController::class, 'destroy']);
});



Route::prefix('tables')->group(function(){
    Route::get('',[TableController::class, 'index']);
    Route::post('',[TableController::class, 'store']);
    Route::get('available',[TableController::class, 'getAvailableTables']);
    Route::get('/{id}',[TableController::class, 'show']);
    Route::put('/{id}',[TableController::class, 'update']);
    Route::get('status/{id}',[TableController::class, 'changeStatus']);
    Route::get('available',[TableController::class, 'getAvailableTables']);

});


Route::prefix('orders')->group(function(){
    Route::get('',[OrderController::class, 'index']);
    Route::post('',[OrderController::class, 'store']);
 
});

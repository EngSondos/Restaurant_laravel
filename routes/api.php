<?php

use App\Http\Controllers\IngredientController;
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

Route::prefix('/ingredients')->controller(IngredientController::class)->group(function(){
    Route::post('','store');
    Route::get('','index');
    Route::get('/{id}','show');
    Route::put('/{id}','update');
    Route::get('/status/{id}','changeStatus');
});

<?php

use App\Http\Controllers\IngredientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CartegoryController;
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
Route::put('product/update/ingredients/{product}',[ProductController::class,'updateIngredientsForProduct']);
Route::get('search/product',[ProductController::class,'search']);
Route::get('active/product',[ProductController::class,'getActiveProducts']);

//reservation api for admin
Route::get('reservation',[ReservationController::class ,'index']);
Route::get('reservation/date',[ReservationController::class ,'getReservationByDate']);
Route::get('reservation/{id}',[ReservationController::class ,'getReservationByTableId']);


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
    Route::get('orders/{id}',[TableController::class, 'getOrders']);


});


Route::prefix('orders')->group(function(){
    Route::get('',[OrderController::class, 'index']);
    Route::post('',[OrderController::class, 'store']);

});
Route::prefix('category')->controller(CartegoryController::class)->name('category')->group(function (){
    Route::get('/','index')->name('.index');

    Route::post('/','store')->name('.store');

    Route::get('/{category}/edit','edit')->name('.edit');

    Route::put('/{category}','update')->name('.update');

    Route::get('/show','show')->name('.show');

    Route::delete('/{category}','destroy')->name('.destroy');

});


Route::prefix('cart')->controller(CartController::class)->name('cart')->group(function (){
    Route::get('/','index')->name('.index');

    Route::post('/','store')->name('.store');

    Route::delete('/','destroyall')->name('.destroyall');

    Route::put('/{cart}','update')->name('.update');

    Route::get('/show','show')->name('.show');

    Route::delete('/{cart}','destroy')->name('.destroy');
});


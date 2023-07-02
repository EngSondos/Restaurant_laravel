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

// Route::apiResource('/ingredients',);





//Ingrdents API Methods
Route::apiResource('ingredients',IngredientController::class)->except('destroy');
Route::controller(IngredientController::class)->group(function(){
    Route::get('ingredients/status/{id}','changeStatus');

    Route::get('search/ingredient','search');

    Route::get('active/ingredient','getActiveIngredients');
});

//Products API Methods
Route::apiResource('products',ProductController::class)->except('destroy');
Route::controller(ProductController::class)->group(function(){
    Route::get('products/status/{id}','changeStatus');

    Route::put('product/update/ingredients/{product}','updateIngredientsForProduct');
});
//reservation for user -->
Route::prefix('reservation')->controller(ReservationController::class)->group(function(){
    Route::post('',[ReservationController::class,'store']);
    Route::get('/date/{table_id}',[ReservationController::class,'getAvailableDateByTableId']);

    Route::get('search/product','search');

    Route::get('active/product','getActiveProducts');
});

//Reservation API Methods For Admin
Route::prefix('reservation')->controller(ReservationController::class)->group(function(){
    Route::get('','index');

    Route::get('/date','getReservationByDate');

    Route::get('/{id}','getReservationByTableId');
});



// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


// Route::middleware('auth:sanctum')->prefix('users')->group(function(){
//     Route::get('/auth','UserDetails');

// });

Route::prefix('users')->group(function(){
    Route::get('',[UserController::class, 'index']);
    Route::post('',[UserController::class, 'store']);
    Route::get('/search',[UserController::class, 'search']);
    Route::get('/{id}',[UserController::class, 'show']);
    Route::put('/{id}',[UserController::class, 'update']);
    Route::delete('/{id}',[UserController::class, 'destroy']);

});

//Users API Methods For Admin
Route::prefix('users')->controller(UserController::class)->group(function(){
    Route::get('','index');

    Route::post('','store');

    Route::get('/{id}','show');

    Route::put('/{id}','update');

    Route::delete('/{id}','destroy');
});


//Tables API Methods For Admin
Route::prefix('tables')->controller(TableController::class)->group(function(){
    Route::get('','index');

    Route::post('','store');

    Route::get('available','getAvailableTables');

    Route::get('/{id}','show');

    Route::put('/{id}','update');

    Route::get('status/{id}','changeStatus');

    Route::get('available','getAvailableTables');
});

//Orders API Methods For Waiter
Route::prefix('orders')->controller(OrderController::class)->group(function(){
    Route::get('','index');

    Route::post('','store');

    Route::get('/tables/prepare','getTablesWithPreparedOrders');

    // Route::get('/{id}','show');

    Route::get('tables/{id}','getOrderTable');

    Route::post('/{order_id}/status/{new_status}','UpdateOrderStatus');
});

//Categories API Methods For Admin
Route::prefix('category')->controller(CartegoryController::class)->group(function (){
    Route::get('/','index');

    Route::post('/','store');

    Route::get('/{category}/edit','edit');

    Route::put('/{category}','update');

    Route::get('/show','show');

    Route::delete('/{category}','destroy');
});

//Cart API Methods For
Route::prefix('cart')->controller(CartController::class)->group(function (){
    Route::get('/','index');

    Route::post('/','store');

    Route::put('/','update');

    Route::delete('/','destroy');
});


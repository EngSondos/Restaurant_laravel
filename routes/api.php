<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CartegoryController;
use App\Http\Controllers\CustomerAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderProductController;
use App\Http\Controllers\StaffAuthController;

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



// //Register & Login
// Route::prefix('auth/customer')->controller(CustomerAuthController::class)->group(function () {

//     Route::post('/login', 'login');
//     Route::post('/register', 'register');
//     Route::post('/logout', 'logout');
//     Route::post('/refresh', 'refresh');
//     Route::get('/user-profile', 'userProfile');
// });

// Route::prefix('auth/user')->controller(StaffAuthController::class)->group(function () {

//     Route::post('/login', 'login');
//     Route::post('/register', 'register');
//     Route::post('/logout', 'logout');
//     Route::post('/refresh', 'refresh');
//     Route::get('/user-profile', 'userProfile');
// });





// //Ingrdents API Methods
// Route::apiResource('ingredients', IngredientController::class)->except('destroy');

// Route::middleware("auth:users")->controller(IngredientController::class)->group(function () {
//     Route::get('ingredients/status/{id}', 'changeClosed');

//     Route::get('search/ingredient', 'search');

//     Route::get('active/ingredient', 'getActiveIngredients');
// });

// //Products API Methods
// Route::middleware("auth:users")->apiResource('products', ProductController::class)->except('destroy');

// Route::controller(ProductController::class)->group(function () {
//     Route::get('products/status/{id}', 'changeStatus');

//     Route::put('product/update/ingredients/{product}', 'updateIngredientsForProduct');

//     Route::get('search/product', 'search');

//     Route::get('active/product', 'getActiveProducts');
// });

// //Reservation API
// Route::prefix('reservation')->controller(ReservationController::class)->group(function () {
//     //reservation for user -->
//     Route::middleware("auth:users")->group(function () {
//         //for admin
//         Route::get('', 'index');

//         Route::get('/date', 'getReservationByDate');

//         Route::get('/{id}', 'getReservationByTableId');

//         //cancel reservation --> cashair
//         Route::put('/status/cancel/{id}', 'cancelReservation');

//         Route::put('/status/accept/{id}', 'AcceptReservation');
//     });
    
//     Route::middleware("auth:customers")->group(function () {
//         Route::post('', 'store')->middleware();

//         Route::get('/date/{table_id}', 'getAvailableDateByTableId');
//     });
// });

// //Users API Methods For Admin
// Route::prefix('users')->middleware("auth:users")->controller(UserController::class)->group(function () {
//     Route::get('', 'index');

//     Route::get('/search', 'search');

//     Route::post('', 'store');

//     Route::get('/{id}', 'show');

//     Route::put('/{id}', 'update');

//     Route::delete('/{id}', 'destroy');
// });


// //Tables API Methods For Admin
// Route::prefix('tables')->middleware("auth:users")->controller(TableController::class)->group(function () {
//     Route::get('', 'index');

//     Route::post('', 'store');

//     Route::get('available', 'getAvailableTables');

//     Route::get('/{id}', 'show');

//     Route::put('/{id}', 'update');

//     Route::get('status/{id}', 'changeStatus');

//     Route::get('available/inday', 'availbeTablesInTheDay');
// });

// //Orders API Methods For Waiter
// Route::prefix('orders')->middleware("auth:users")->controller(OrderController::class)->group(function () {
//     Route::get('', 'index');

//     Route::post('', 'store');

//     Route::get('/tables/prepare', 'getTablesWithPreparedOrders');

//     Route::get('prepare', 'prepareOrders');

//     Route::get('served', 'servedOrders');


//     Route::get('/{id}', 'show');

//     Route::get('tables/{id}', 'getOrderTable');

//     Route::post('/{order_id}/status/{new_status}', 'UpdateOrderStatus');

//     Route::put('kitchen/{id}', 'changeOrderStatus');
// });

// Route::prefix('order_products')->middleware("auth:users")->controller(OrderProductController::class)->group(function () {

//     Route::put('{orderId}/cancel/{orderProductId}', 'cancelOrderProducts');
//     Route::put('{orderId}/complete/{orderProductId}', 'completeOrderProducts');
// });

// //Categories API Methods For Admin
// Route::prefix('category')->middleware("auth:users")->controller(CartegoryController::class)->group(function () {
//     Route::get('/', 'index');

//     Route::post('/', 'store');

//     Route::get('/{category}/edit', 'edit');

//     Route::put('/{category}', 'update');

//     Route::get('/show', 'show');

//     Route::delete('/{category}', 'destroy');
// });

// //Cart API Methods For
// Route::prefix('cart')->middleware("auth:users")->controller(CartController::class)->group(function () {
//     Route::get('/', 'index');

//     Route::post('/', 'store');

//     Route::put('/', 'update');

//     Route::delete('/', 'destroy');
// });

//-------------------------------------------------------Without Middelwares---------------------------------------------------




//Ingrdents API Methods
Route::apiResource('ingredients', IngredientController::class)->except('destroy');

Route::controller(IngredientController::class)->group(function () {
    Route::get('ingredients/status/{id}', 'changeClosed');

    Route::get('search/ingredient', 'search');

    Route::get('active/ingredient', 'getActiveIngredients');
});

//Products API Methods
Route::apiResource('products', ProductController::class)->except('destroy');

Route::controller(ProductController::class)->group(function () {
    Route::get('products/status/{id}', 'changeStatus');

    Route::put('product/update/ingredients/{product}', 'updateIngredientsForProduct');

    Route::get('search/product', 'search');

    Route::get('active/product', 'getActiveProducts');
});

//Reservation API
Route::prefix('reservation')->controller(ReservationController::class)->group(function () {
    
    //for admin
    Route::get('', 'index');

    Route::get('/date', 'getReservationByDate');

    Route::get('/{id}', 'getReservationByTableId');

    //cancel reservation --> cashair
    Route::put('/status/cancel/{id}', 'cancelReservation');

    Route::put('/status/accept/{id}', 'AcceptReservation');

    //reservation for user -->
    Route::post('', 'store')->middleware();

    Route::get('/date/{table_id}', 'getAvailableDateByTableId');
});

//Users API Methods For Admin
Route::prefix('users')->controller(UserController::class)->group(function () {
    Route::get('', 'index');

    Route::get('/search', 'search');

    Route::post('', 'store');

    Route::get('/{id}', 'show');

    Route::put('/{id}', 'update');

    Route::delete('/{id}', 'destroy');
});

//Tables API Methods For Admin
Route::prefix('tables')->controller(TableController::class)->group(function () {
    Route::get('', 'index');

    Route::post('', 'store');

    Route::get('available', 'getAvailableTables');

    Route::get('/{id}', 'show');

    Route::put('/{id}', 'update');

    Route::get('status/{id}', 'changeStatus');

    Route::get('available/inday', 'availbeTablesInTheDay');
});

//Orders API Methods For Waiter
Route::prefix('orders')->controller(OrderController::class)->group(function () {
    Route::get('', 'index');

    Route::post('', 'store');

    Route::get('/tables/prepare', 'getTablesWithPreparedOrders');

    Route::get('prepare', 'prepareOrders');

    Route::get('served', 'servedOrders');


    Route::get('/{id}', 'show');

    Route::get('tables/{id}', 'getOrderTable');

    Route::post('/{order_id}/status/{new_status}', 'UpdateOrderStatus');

    Route::put('kitchen/{id}', 'changeOrderStatus');
});

Route::prefix('order_products')->controller(OrderProductController::class)->group(function () {

    Route::put('{orderId}/cancel/{orderProductId}', 'cancelOrderProducts');
    Route::put('{orderId}/complete/{orderProductId}', 'completeOrderProducts');
});

//Categories API Methods For Admin
Route::prefix('category')->controller(CartegoryController::class)->group(function () {
    Route::get('/', 'index');

    Route::post('/', 'store');

    Route::get('/{category}/edit', 'edit');

    Route::put('/{category}', 'update');

    Route::get('/show', 'show');

    Route::delete('/{category}', 'destroy');
});

//Cart API Methods For
Route::prefix('cart')->controller(CartController::class)->group(function () {
    Route::get('/', 'index');

    Route::post('/', 'store');

    Route::put('/', 'update');

    Route::delete('/', 'destroy');
});

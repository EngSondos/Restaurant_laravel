<?php

use App\Http\Controllers\CartegoryController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('category')->controller(CartegoryController::class)->name('category')->group(function (){
    Route::get('/','index')->name('.index');

    Route::post('/','store')->name('.store');

    Route::get('/{category}/edit','edit')->name('.edit');

    Route::put('/{category}','update')->name('.update');

    Route::get('/show','show')->name('.show');
    
    Route::delete('/{category}','destroy')->name('.delete');
});


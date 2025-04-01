<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
*/


Route::prefix('auth')->group(function(){
   Route::post('register',[\App\Http\Controllers\AuthController::class, 'register']);
   Route::post('login',[\App\Http\Controllers\AuthController::class, 'login'])->middleware(['throttle:login']);
   Route::post('logout',[\App\Http\Controllers\AuthController::class, 'logout'])->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function(){
    Route::post('product/create',[\App\Http\Controllers\ProductController::class,'newProduct']);
    Route::delete('product/{id}',[\App\Http\Controllers\ProductController::class,'deleteProduct']);

    Route::get('test', function(Request $request){
        //$user = \Illuminate\Support\Facades\Auth::user();
        $user = $request->user();
        \Illuminate\Support\Facades\Gate::authorize('is_admin');
    });
    Route::post('orders', [\App\Http\Controllers\OrderController::class, 'createOrder']);
    Route::get('order/{id}',[\App\Http\Controllers\OrderController::class, 'showOrder']);
    Route::put('orderstatus', [\App\Http\Controllers\OrderController::class, 'updateStatus']);

    Route::group([
        'prefix' => 'admin',
        'middleware' => 'admin',
        'as' => 'admin'
    ], function(){

    });
    Route::middleware('db.restricted')->group(function(){

    });
});

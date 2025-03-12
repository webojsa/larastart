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

    Route::group([
        'prefix' => 'admin',
        'middleware' => 'admin',
        'as' => 'admin'
    ], function(){

    });
    Route::middleware('db.restricted')->group(function(){

    });
});

Route::get('test', function(){
    \Illuminate\Support\Facades\Gate::authorize('is_admin');
});

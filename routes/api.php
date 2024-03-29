<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\Auth\AuthController;

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

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */

Route::prefix('auth/user')->group(function (){

    Route::post('/login',[AuthController::class,'loginUser']);
    Route::post('/register',[AuthController::class,'createUser']);
    Route::post('/logout',[AuthController::class,'logout'])->middleware(['auth:sanctum']);
});

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('post',[PostController::class,'index']);
    Route::get('post/{post}',[PostController::class,'show']);
    Route::post('post',[PostController::class,'store']);
    Route::post('post/{post}',[PostController::class,'update']);
    Route::delete('post/{post}',[PostController::class,'destroy']);
   /*  Route::apiResource('post',PostController::class); */

});

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ApiAuth;

use App\Http\Controllers\API\Login\LoginController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login',[LoginController::class,'login']);
Route::get('/token/validate',[LoginController::class,'tokenValidate'])->middleware([ApiAuth::class]);

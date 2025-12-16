<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ApiAuth;

use App\Http\Controllers\API\Login\LoginController;
use App\Http\Controllers\API\Repayment\RepaymentController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login',[LoginController::class,'login']);
Route::get('/token/validate',[LoginController::class,'tokenValidate'])->middleware([ApiAuth::class]);

#Repayment
Route::post('/repayment/list',[RepaymentController::class,'list'])->middleware([ApiAuth::class]);
Route::post('/repayment/add',[RepaymentController::class,'addNew'])->middleware([ApiAuth::class]);

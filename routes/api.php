<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ApiAuth;

use App\Http\Controllers\API\Login\LoginController;
use App\Http\Controllers\API\Repayment\RepaymentController;
use App\Http\Controllers\API\Master\MasterController;
use App\Http\Controllers\API\User\UserController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/login',[LoginController::class,'login']);
Route::get('/token/validate',[LoginController::class,'tokenValidate'])->middleware([ApiAuth::class]);

#Repayment
Route::post('/repayment/list',[RepaymentController::class,'list'])->middleware([ApiAuth::class]);
Route::post('/repayment/add',[RepaymentController::class,'addNew'])->middleware([ApiAuth::class]);

#Users
Route::get('/users/list',[UserController::class,'list'])->middleware([ApiAuth::class]);

#Masters
Route::get('/master/bank/list',[MasterController::class,'bankList'])->middleware([ApiAuth::class]);
Route::get('/master/year/list',[MasterController::class,'yearList'])->middleware([ApiAuth::class]);

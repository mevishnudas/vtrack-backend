<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ApiAuth;

use App\Http\Controllers\API\Login\LoginController;
use App\Http\Controllers\API\Repayment\RepaymentController;
use App\Http\Controllers\API\Master\MasterController;
use App\Http\Controllers\API\User\UserController;
use App\Http\Controllers\API\Splitwise\SplitwiseController;
use App\Http\Controllers\API\CreditCard\CreditCardController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/login',[LoginController::class,'login']);
Route::get('/token/validate',[LoginController::class,'tokenValidate'])->middleware([ApiAuth::class]);

#Repayment
Route::post('/repayment/list',[RepaymentController::class,'list'])->middleware([ApiAuth::class]);
Route::post('/repayment/add',[RepaymentController::class,'addNew'])->middleware([ApiAuth::class]);
Route::post('/repayment/update',[RepaymentController::class,'update'])->middleware([ApiAuth::class]);
#EMI
Route::post('/repayment/emi/add',[RepaymentController::class,'emiAdd'])->middleware([ApiAuth::class]);
Route::post('/repayment/emi/list',[RepaymentController::class,'emiList'])->middleware([ApiAuth::class]);
Route::post('/repayment/emi/schedule/add',[RepaymentController::class,'emiScheduleAdd'])->middleware([ApiAuth::class]);
Route::post('/repayment/emi/schedule/update',[RepaymentController::class,'emiScheduleUpdate'])->middleware([ApiAuth::class]);
Route::post('/repayment/emi/schedule/list',[RepaymentController::class,'emiScheduleList'])->middleware([ApiAuth::class]);

#Splitwise
Route::get('/splitwise/expense/summary',[SplitwiseController::class,'expenseSummary'])->middleware([ApiAuth::class]);
Route::post('/splitwise/expense/add',[SplitwiseController::class,'expenseAdd'])->middleware([ApiAuth::class]);
Route::post('/splitwise/expense/settle-up',[SplitwiseController::class,'expenseSettleUp'])->middleware([ApiAuth::class]);
Route::post('/splitwise/expense/list',[SplitwiseController::class,'expenseList'])->middleware([ApiAuth::class]);
//Route::post('/splitwise/expense/transaction/list',[SplitwiseController::class,'expenseTransactionList'])->middleware([ApiAuth::class]);
Route::post('/splitwise/expense/friend/summary',[SplitwiseController::class,'expenseFriendSummary'])->middleware([ApiAuth::class]);

#Credit Card
Route::get('/credit-card/list',[CreditCardController::class,'list'])->middleware([ApiAuth::class]);
Route::post('/credit-card/detail',[CreditCardController::class,'details'])->middleware([ApiAuth::class]);

#Users
Route::get('/users/list',[UserController::class,'list'])->middleware([ApiAuth::class]);
Route::post('/users/add',[UserController::class,'addUser'])->middleware([ApiAuth::class]);
Route::get('/users/friends/list',[UserController::class,'friendsList'])->middleware([ApiAuth::class]);

#Masters
Route::get('/master/bank/list',[MasterController::class,'bankList'])->middleware([ApiAuth::class]);
//Route::get('/master/credit-card/list',[MasterController::class,'creditCardList'])->middleware([ApiAuth::class]);
Route::get('/master/year/list',[MasterController::class,'yearList'])->middleware([ApiAuth::class]);
Route::get('/master/payment/status',[MasterController::class,'paymentStatus'])->middleware([ApiAuth::class]);
Route::get('/master/emi/status',[MasterController::class,'emiStatus'])->middleware([ApiAuth::class]);
Route::get('/master/emi/principle/status',[MasterController::class,'emiPrincipleStatus'])->middleware([ApiAuth::class]);
Route::post('/master/emi/status/update',[RepaymentController::class,'emiStatusUpdate'])->middleware([ApiAuth::class]);

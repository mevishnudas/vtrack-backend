<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ApiAuth;

use App\Http\Controllers\API\Login\LoginController;
use App\Http\Controllers\API\Dashboard\DashboardController;
use App\Http\Controllers\API\Repayment\RepaymentController;
use App\Http\Controllers\API\Master\MasterController;
use App\Http\Controllers\API\User\UserController;
use App\Http\Controllers\API\Splitwise\SplitwiseController;
use App\Http\Controllers\API\CreditCard\CreditCardController;
use App\Http\Controllers\API\Cron\CronController;
use App\Http\Controllers\API\Expense\ExpenseController;
// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/login',[LoginController::class,'login']);
Route::get('/token/validate',[LoginController::class,'tokenValidate'])->middleware([ApiAuth::class]);

#Dashboard
Route::get('/dashboard/summary',[DashboardController::class,'summary'])->middleware([ApiAuth::class]);

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
Route::post('/repayment/emi/status/update',[RepaymentController::class,'emiStatusUpdate'])->middleware([ApiAuth::class]);

#Splitwise
Route::get('/splitwise/expense/summary',[SplitwiseController::class,'expenseSummary'])->middleware([ApiAuth::class]);
Route::post('/splitwise/expense/add',[SplitwiseController::class,'expenseAdd'])->middleware([ApiAuth::class]);
Route::post('/splitwise/expense/settle-up',[SplitwiseController::class,'expenseSettleUp'])->middleware([ApiAuth::class]);
Route::post('/splitwise/expense/list',[SplitwiseController::class,'expenseList'])->middleware([ApiAuth::class]);
//Route::post('/splitwise/expense/transaction/list',[SplitwiseController::class,'expenseTransactionList'])->middleware([ApiAuth::class]);
Route::post('/splitwise/expense/friend/summary',[SplitwiseController::class,'expenseFriendSummary'])->middleware([ApiAuth::class]);
Route::post('/splitwise/expense/friend/transaction/update',[SplitwiseController::class,'expenseFriendTransactionUpdate'])->middleware([ApiAuth::class]);
Route::post('/splitwise/expense/friend/transaction/delete',[SplitwiseController::class,'expenseFriendTransactionDelete'])->middleware([ApiAuth::class]);


#Credit Card
Route::get('/credit-card/list',[CreditCardController::class,'list'])->middleware([ApiAuth::class]);
Route::post('/credit-card/detail',[CreditCardController::class,'details'])->middleware([ApiAuth::class]);
Route::post('/credit-card/bill/add',[CreditCardController::class,'billAdd'])->middleware([ApiAuth::class]);
Route::post('/credit-card/bill/update',[CreditCardController::class,'billUpdate'])->middleware([ApiAuth::class]);
Route::post('/credit-card/bill/delete',[CreditCardController::class,'billDelete'])->middleware([ApiAuth::class]);

#Expense
Route::get('/expense/category/list',[ExpenseController::class,'categoryList'])->middleware([ApiAuth::class]);
Route::post('/expense/category/add',[ExpenseController::class,'categoryAdd'])->middleware([ApiAuth::class]);
Route::post('/expense/add',[ExpenseController::class,'expenseAdd'])->middleware([ApiAuth::class]);


#Users
Route::get('/users/list',[UserController::class,'list'])->middleware([ApiAuth::class]);
Route::post('/users/add',[UserController::class,'addUser'])->middleware([ApiAuth::class]);
Route::get('/users/friends/list',[UserController::class,'friendsList'])->middleware([ApiAuth::class]);

#Masters
Route::get('/master/bank/list',[MasterController::class,'bankList'])->middleware([ApiAuth::class]);
//Route::get('/master/credit-card/list',[MasterController::class,'creditCardList'])->middleware([ApiAuth::class]);
Route::get('/master/year/list',[MasterController::class,'yearList'])->middleware([ApiAuth::class]);
Route::get('/master/payment/status',[MasterController::class,'paymentStatus'])->middleware([ApiAuth::class]);
Route::get('/master/credit-card/payment/status',[MasterController::class,'creditCardPaymentStatusList'])->middleware([ApiAuth::class]);
Route::get('/master/emi/status',[MasterController::class,'emiStatus'])->middleware([ApiAuth::class]);
Route::get('/master/emi/principle/status',[MasterController::class,'emiPrincipleStatus'])->middleware([ApiAuth::class]);

Route::get('/cron/run',[CronController::class,'run']);
//Route::get('/database/backup/download',[CronController::class,'databaseBackup']);

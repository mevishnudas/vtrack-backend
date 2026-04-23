<?php

namespace App\Http\Controllers\API\Cron;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\Repayment\RepaymentController;

class CronController extends Controller
{
    public function run(){

        //Run Account Summary at 06:00 AM
        if (now()->format('H:i') === '06:00') {
            RepaymentController::accountSummary();
        }

        return response(["msg"=>"Success"],200);
    }
}

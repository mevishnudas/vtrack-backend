<?php

namespace App\Http\Controllers\API\Cron;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\Repayment\RepaymentController;
use Illuminate\Support\Facades\Log;

class CronController extends Controller
{
    public function run(){

        //Run Account Summary at 06:00 AM & 6:00 PM
        $current_time = now()->format('H:i');
        //Log::info("CRON TIME: " . $current_time);
        if ($current_time === '05:30'|| $current_time === '18:30') {
            RepaymentController::accountSummary();
        }

        return response(["msg"=>"Success","data"=>$current_time],200);
    }
}

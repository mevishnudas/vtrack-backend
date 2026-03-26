<?php

namespace App\Http\Controllers\API\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\API\CreditCard\CreditCard;
use App\Http\Controllers\API\CreditCard\CreditCardController;

class DashboardController extends Controller
{
    public function summary(Request $request){

        $data = array();
        $data["credit_summary"] = self::creditCardSummary();

        $data["repayment_summary"] = array(
            "today"=>array(
                "total"=>0,
                "received"=>0,
                "pending"=>0,
                "partially"=>0
            ),
            "tomorrow"=>array(
                "total"=>0,
                "received"=>0,
                "pending"=>0,
                "partially"=>0
            ),
            "this_month"=>array(
                "total"=>0,
                "received"=>0,
                "pending"=>0,
                "partially"=>0
            ),
            "last_month"=>array(
                "total"=>0,
                "received"=>0,
                "pending"=>0,
                "partially"=>0
            )
        );
        return response(["msg"=>"Success","data"=>$data],200);
    }

    public function creditCardSummary(){
        $data = array();
        $creditCardPaymentSummary = CreditCardController::creditCardPaymentSummary();
        $total = collect($creditCardPaymentSummary)->where("payment_status","PENDING")->sum("amount");

        $data = array(
            "total_amount_due"=>$total,
            "total_cards"=>count($creditCardPaymentSummary)
        );

        return $data;
    }
}

<?php

namespace App\Http\Controllers\API\CreditCard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\API\CreditCard\CreditCard;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class CreditCardController extends Controller
{
    public function list(){

        $data = self::creditCardPaymentSummary();
        return response(["status"=>200,"msg"=>"Success","data"=>$data],200);
    }

    public static function creditCardPaymentSummary() {
        $creditCardList = CreditCard::creditCardList();

        $creditCardIds = collect($creditCardList)->pluck('id')->values();
        $paymentHistory = CreditCard::creditCardPaymentHistoryLatest($creditCardIds);

        $data = array();
        foreach ($creditCardList as $creditCardList_row) {

            $latestPayment = collect($paymentHistory)->where("credit_card_id",$creditCardList_row->id)->first();

            $data[] = array(
                "id"=>$creditCardList_row->id,
                "name"=>$creditCardList_row->name,
                "variant_name"=>$creditCardList_row->variant_name,
                "last_digit"=>$creditCardList_row->last_digit,

                "payment_date"=>(string)@$latestPayment->payment_date,
                "amount"=>(string)@$latestPayment->amount,
                "payment_status"=>(string)@$latestPayment->payment_status
            );
        }

        $data = collect($data)->sortBy("payment_date")->values();
        return $data;
    }

    public static function creditCardBillSummary(){

        $data = array();
        $data["total_cards"] = CreditCard::totalCreditCardCount();
        $latest_bill = CreditCard::getLatestPaymentDate();

        $current_month_date = !empty(@$latest_bill->payment_date)?date('Y-m-d',strtotime(@$latest_bill->payment_date)):date('Y-m-d');
        $prev_month_date = !empty(@$latest_bill->payment_date)?date('Y-m-d',strtotime(@$latest_bill->payment_date.' -1 Month')):date('Y-m-d',strtotime('-1 Month'));

        #Current Month
        $current_date = Carbon::parse($current_month_date);
        $sort_data = array();
        $sort_data["start_date"] = $current_date->startOfMonth()->toDateString();
        $sort_data["end_date"]   = $current_date->endOfMonth()->toDateString();
        $data["current_bill"] = CreditCard::creditCardPaymentHistoryDateWiseSum($sort_data);
        $data["total_amount_due"] = CreditCard::creditCardPaymentHistoryDateWiseDueAmount($sort_data);

        #Last Month
        $last_month_date = Carbon::parse($prev_month_date);
        $sort_data = array();
        $sort_data["start_date"] = $last_month_date->startOfMonth()->toDateString();
        $sort_data["end_date"]   = $last_month_date->endOfMonth()->toDateString();
        $data["prev_month_bill"] = CreditCard::creditCardPaymentHistoryDateWiseSum($sort_data);

        return $data;
    }

    public function details(Request $request){

        $validator = Validator::make($request->all(), [
            "id"=>'required|exists:bank,id',
        ]);
        if ($validator->fails()) {
            return response(["status"=>401,"msg"=>"Invalid Parameters","data"=>$validator->errors()],401);
        }else{

            $data = array();
            $creditCard = CreditCard::creditCardInfo($request->id);
            $paymentHistory = CreditCard::creditCardPaymentHistory($request->id);

            $data["card_info"] = $creditCard;
            $data["payment_history"] = $paymentHistory;
            return response(["status"=>200,"msg"=>"Success","data"=>$data],200);
        }
    }


    public function billAdd(Request $request){

        $userInfo = app('userData');
        $validator = Validator::make($request->all(), [
            'id' => ['required',
                Rule::exists('bank','id')->where(function ($query) use ($userInfo) {
                    $query->where('user_id', $userInfo["id"])
                    ->where('bank_type', "CREDIT_CARD");
            })],
            'amount'=>'required|numeric',
            'payment_date'=>'required|date',
            'remarks'=>'present|nullable'
        ]);
        if ($validator->fails()) {
            return response(["status"=>401,"msg"=>"Invalid Parameters","data"=>$validator->errors()],401);
        }else{

            $insert_data = array(
                "credit_card_id"=>$request->id,
                "amount"=>$request->amount,
                "payment_date"=>date('Y-m-d',strtotime($request->payment_date)),
                "remarks"=>$request->remarks,
                "user_id"=>$userInfo["id"]
            );
            CreditCard::addToPaymentHistory($insert_data);

            return response(["msg"=>"Success"],200);
        }

    }

    public function billUpdate(Request $request){

        $userInfo = app('userData');
        $validator = Validator::make($request->all(), [
            'id' => ['required',
                Rule::exists('credit_card_payment_history','id')->where(function ($query) use ($userInfo) {
                    $query->where('user_id', $userInfo["id"]);
            })],
            'amount'=>'required|numeric',
            'payment_date'=>'required|date',
            'payment_status'=>'required|in:PENDING,PAID,PARTIALLY_PAID,PAID_VERIFIED',
            'remarks'=>'present|nullable'
        ]);
        if ($validator->fails()) {
            return response(["status"=>401,"msg"=>"Invalid Parameters","data"=>$validator->errors()],401);
        }else{

            $update_data = array(
                "amount"=>$request->amount,
                "payment_date"=>date('Y-m-d',strtotime($request->payment_date)),
                "payment_status"=>$request->payment_status,
                "remarks"=>$request->remarks
            );
            CreditCard::updatePaymentHistory($update_data,$request->id);
            return response(["msg"=>"Success"],200);
        }
    }


    public function billDelete(Request $request){

        $userInfo = app('userData');
        $validator = Validator::make($request->all(), [
            'id' => ['required',
                Rule::exists('credit_card_payment_history','id')->where(function ($query) use ($userInfo) {
                    $query->where('user_id', $userInfo["id"]);
            })]
        ]);
        if ($validator->fails()) {
            return response(["status"=>401,"msg"=>"Invalid Parameters","data"=>$validator->errors()],401);
        }else{
            CreditCard::deletePaymentHistory($request->id);
            return response(["msg"=>"Success"],200);
        }
    }

}

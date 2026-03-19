<?php

namespace App\Http\Controllers\API\CreditCard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\API\CreditCard\CreditCard;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CreditCardController extends Controller
{
    public function list(){

        $creditCardList = CreditCard::creditCardList();
        $data = collect($creditCardList)->unique('id')->values();
        return response(["status"=>200,"msg"=>"Success","data"=>$data],200);
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


}

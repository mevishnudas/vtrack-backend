<?php

namespace App\Http\Controllers\API\CreditCard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\API\CreditCard\CreditCard;
use Illuminate\Support\Facades\Validator;

class CreditCardController extends Controller
{
    public function list(){
        $creditCardList = CreditCard::creditCardList();
        return response(["status"=>200,"msg"=>"Success","data"=>$creditCardList],200);
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


}

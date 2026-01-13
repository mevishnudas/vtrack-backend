<?php

namespace App\Http\Controllers\API\Splitwise;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\API\Splitwise\Splitwise;

class SplitwiseController extends Controller
{
    public function expenseAdd(Request $request){

        $validator = Validator::make($request->all(), [
            'split_method'=>'required|in:equally,ows_you,you_ows',

            'friends' =>'required|array|max:255',
            'friends.*'=>'numeric|exists:user,id',

            'amount' =>'required|numeric',
            'remarks'=>'present|nullable',
        ]);

        if ($validator->fails()) {
            return response(["status"=>401,"msg"=>"Invalid Parameters","data"=>$validator->errors()],401);
        }

        //if validation fine
        $userInfo = app('userData');
        $amount = $request->amount;
        $split_by = count($request->friends);

        switch ($request->split_method) {
            case 'ows_you':
                $split_amount = round($amount/$split_by,2);
                break;

            case 'you_ows':
                # code...
                break;

            default:
                $split_by +=1;
                $split_amount = round($amount/$split_by,2);
                break;
        }

        $insert_data = array();
        $transactions = array();
        foreach ($request->friends as $friend) {

            $insert_data[] = array(
                "friend_id"=>$friend,
                "amount"=>$split_amount,
                "remarks"=>$request->remarks,
                "user_id"=>$userInfo["id"]
            );

            $transactions[] = array(
                "friend_id"=>$friend,
                "amount"=>$split_amount
            );

        }

        if(!empty($insert_data))
        {
            $response = Splitwise::expenseAdd($insert_data);
            self::incrementExpenseSummary($transactions);
        }
        return response(["status"=>200,"msg"=>"Success"],200);
    }

    protected function incrementExpenseSummary($transactions){

        foreach ($transactions as $transaction) {
            Splitwise::incrementExpenseSummary($transaction);
        }
        return true;
    }


    public function expenseList(Request $request){
        $validator = Validator::make($request->all(), [
            //
        ]);

        if ($validator->fails()) {
            return response(["status"=>401,"msg"=>"Invalid Parameters","data"=>$validator->errors()],401);
        }

        $data = array();
        $data["ows_you"] = Splitwise::expenseSummaryList(true); //Ows you
        $data["you_owe"] = Splitwise::expenseSummaryList(false); //You owe

        return response(["status"=>200,"msg"=>"Success","data"=>$data],200);
    }


//end class
}

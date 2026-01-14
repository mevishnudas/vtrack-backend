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

        $ows_you = true;
        switch ($request->split_method) {
            case 'ows_you':
                $split_amount = $amount;

                if($split_by>0){
                    $split_amount = $amount/$split_by;
                }

                $split_amount = round($split_amount,2);
                break;

            case 'you_ows':
                # code...
                $ows_you = false;
                break;

            default:
                $split_by +=1;
                $split_amount = round($amount/$split_by,2);
                break;
        }

        $insert_data = array();
        $transactions = array();
        foreach ($request->friends as $friend) {

            $from_user = $ows_you?$userInfo["id"]:$friend;
            $to_user = $ows_you?$friend:$userInfo["id"];

            $insert_data[] = array(
                "from_user"=>$from_user,
                "to_user"=>$to_user,

                "amount"=>$split_amount,
                "remarks"=>$request->remarks,

                "user_id"=>$userInfo["id"] //who created the record
            );

            $transactions[] = array(
                "from_user"=>$from_user,
                "to_user"=>$to_user,

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

        $userInfo = app('userData');

        $data = array();
        $expenseSummaryList = Splitwise::expenseSummaryList(true);
        //$data["you_owe"] = Splitwise::expenseSummaryList(false); //You owe

        $data["ows_you"] = array();
        $data["you_owe"] = array();
        foreach ($expenseSummaryList as $expenseSummaryList_row) {

            if($expenseSummaryList_row->from_user==$userInfo["id"]&&$expenseSummaryList_row->balance>0)
            {
                $data["ows_you"][] = array(
                    "id"=>$expenseSummaryList_row->to_user,
                    "name"=>$expenseSummaryList_row->to_user_name ,
                    "balance"=>$expenseSummaryList_row->balance,
                );

            }else{
                $data["you_owe"][] = array(
                    "id"=>$expenseSummaryList_row->to_user,
                    "name"=>$expenseSummaryList_row->to_user_name,
                    "balance"=>$expenseSummaryList_row->balance
                );
            }

        }

        return response(["status"=>200,"msg"=>"Success","data"=>$data],200);
    }


//end class
}

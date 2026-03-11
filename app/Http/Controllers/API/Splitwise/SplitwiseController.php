<?php

namespace App\Http\Controllers\API\Splitwise;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\API\Splitwise\Splitwise;

class SplitwiseController extends Controller
{
    public function expenseSummary(Request $request){
        $expenseSummary = Splitwise::expenseSummary();

        $data = array();
        $data["ows_you"] = abs($expenseSummary["ows_you"]);
        $data["you_owe"] = abs($expenseSummary["you_owe"]);
        $data["total"] = abs($expenseSummary["ows_you"]+$expenseSummary["you_owe"]);

        return response(["status"=>200,"msg"=>"Success","data"=>$data],200);
    }

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
                $ows_you = false;

                $split_amount = $amount;

                if($split_by>0){
                    $split_amount = $amount/$split_by;
                }

                $split_amount = round($split_amount,2);
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
            self::modifyExpenseSummary($transactions);
        }
        return response(["status"=>200,"msg"=>"Success"],200);
    }

    protected function modifyExpenseSummary($transactions){

        foreach ($transactions as $transaction) {
            Splitwise::modifyExpenseSummary($transaction);
        }
        return true;
    }

    public function expenseSettleUp(Request $request){

        $validator = Validator::make($request->all(), [
            'friend' =>'required|numeric|exists:user,id',
            'amount' =>'required|numeric|gt:0',
            'remarks'=>'present|nullable',
            'payment_mode'=>'required|in:PAID,RECEIVED'
        ]);

        if ($validator->fails()) {
            return response(["status"=>401,"msg"=>"Invalid Parameters","data"=>$validator->errors()],401);
        }

        //if validation fine
        $userInfo = app('userData');

        //Checking who settled up
        $from_user = $userInfo["id"];
        $to_user = $request->friend;

        if($request->payment_mode=="RECEIVED"){
            $from_user = $request->friend;
            $to_user = $userInfo["id"];
        }

        $insert_data = array(
            "from_user"=>$from_user,
            "to_user"=>$to_user,

            "amount"=>$request->amount,
            "remarks"=>$request->remarks,

            "payment_mode"=>"SETTLE_UP",

            "user_id"=>$userInfo["id"] //who created the record
        );

        $transactions = array(
            "from_user"=>$from_user,
            "to_user"=>$to_user,

            "amount"=>$request->amount
        );

        $response = Splitwise::expenseAdd($insert_data);
        self::modifyExpenseSummary([$transactions]);

        return response(["status"=>200,"msg"=>"Success"],200);
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
        $expenseSummaryList = Splitwise::expenseSummaryList();

        $data["ows_you"] = array();
        $data["you_owe"] = array();

        foreach ($expenseSummaryList as $expenseSummaryList_row) {

            if($expenseSummaryList_row->balance>0){
                $data["ows_you"][] = $expenseSummaryList_row;
            }else{

                $temp_array = (array)$expenseSummaryList_row;
                $temp_array["balance"] = abs($expenseSummaryList_row->balance);
                $data["you_owe"][] = $temp_array;
            }

        }

        return response(["status"=>200,"msg"=>"Success","data"=>$data],200);
    }

    public function expenseTransactionList(Request $request){

        $validator = Validator::make($request->all(), [
            'friend' =>'required|numeric|exists:user,id',
        ]);

        if ($validator->fails()) {
            return response(["status"=>401,"msg"=>"Invalid Parameters","data"=>$validator->errors()],401);
        }else{

            $userInfo = app('userData');
            $expenseTransactionList = Splitwise::expenseTransactionList($request->friend);

            $data = array();
            foreach ($expenseTransactionList as $expenseTransactionList_row) {

                $temp_array = array(
                    "id"=>$expenseTransactionList_row->id,
                    "date"=>date('Y-m-d H:i:s',strtotime($expenseTransactionList_row->date)),
                    "amount"=>$expenseTransactionList_row->amount,
                    "remarks"=>$expenseTransactionList_row->remarks,
                    "payment_mode"=>$expenseTransactionList_row->payment_mode
                );

                if($expenseTransactionList_row->from_user_id==$userInfo["id"]){
                    //You paid
                    $temp_array["name"] = $expenseTransactionList_row->to_user_name;
                    $temp_array["payment_type"] = "PAID";
                }else{
                    //You received
                    $temp_array["name"] = $expenseTransactionList_row->from_user_name;
                    $temp_array["payment_type"] = "RECEIVED";
                }

                $data[] = $temp_array;

            }

            return response(["status"=>200,"msg"=>"Success","data"=>$data],200);
        }

    }

    public function expenseFriendSummary(Request $request){

        $validator = Validator::make($request->all(), [
            'friend' =>'required|numeric|exists:user,id',
        ]);

        if ($validator->fails()) {
            return response(["status"=>401,"msg"=>"Invalid Parameters","data"=>$validator->errors()],401);
        }else{

            $data = array();
            $userInfo = app('userData');

            $expenseFriendSummary = Splitwise::expenseFriendSummary($request->friend);
            if(empty($expenseFriendSummary)){
                $data["summary"] = array();
                $data["transactions"] = array();
                return response(["status"=>200,"msg"=>"Success","data"=>$data],200);
            }


            if($expenseFriendSummary->balance>0){
                $ows_status = "OWS_YOU";
            }elseif($expenseFriendSummary->balance<0){
                $ows_status = "YOU_OWS";
            }else{
                $ows_status = "SETTLED_UP";
            }

            $data["summary"] = array(
                "id"=>$expenseFriendSummary->id,
                "name"=>$expenseFriendSummary->name,
                "balance"=>abs($expenseFriendSummary->balance),
                "ows_status"=>$ows_status
            );


            $data["transactions"] = array();
            $expenseTransactionList = Splitwise::expenseTransactionList($request->friend);
            foreach ($expenseTransactionList as $expenseTransactionList_row) {

                $temp_array = array(
                    "id"=>$expenseTransactionList_row->id,
                    "date"=>date('Y-m-d H:i:s',strtotime($expenseTransactionList_row->date)),
                    "amount"=>$expenseTransactionList_row->amount,
                    "remarks"=>(string)$expenseTransactionList_row->remarks,
                    "payment_mode"=>$expenseTransactionList_row->payment_mode
                );

                if($expenseTransactionList_row->from_user_id==$userInfo["id"]){
                    //You paid
                    $temp_array["name"] = $expenseTransactionList_row->to_user_name;
                    $temp_array["payment_type"] = "PAID";
                }else{
                    //You received
                    $temp_array["name"] = $expenseTransactionList_row->from_user_name;
                    $temp_array["payment_type"] = "RECEIVED";
                }

                $data["transactions"][] = $temp_array;

            }

            return response(["status"=>200,"msg"=>"Success","data"=>$data],200);
        }

        //end func
    }


//end class
}

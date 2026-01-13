<?php

namespace App\Models\API\Splitwise;

use Illuminate\Database\Eloquent\Model;
use DB;

class Splitwise extends Model
{
    public static function expenseAdd($insert_data){
        $response = DB::table("splitwise_transactions")
                        ->insert($insert_data);
        return $response;
    }

    public static function expenseSummaryList($positive=true){
        $userInfo = app('userData');
        $query = DB::table("splitwise_summary")
                            ->join('user', 'user.id', '=', 'splitwise_summary.friend_id')
                            ->select(
                                "user.name as friend_name",
                                "splitwise_summary.friend_id",
                                "splitwise_summary.balance"
                            )
                            ->where("splitwise_summary.user_id",$userInfo["id"])
                            ->where("splitwise_summary.status",1);
        if($positive)
        {
            $query->where("balance",">",0);
        }
        else{
            $query->where("balance","<",0);
        }

        $response = $query->get();
        return $response;
    }


    public static function incrementExpenseSummary($transaction){

        $userInfo = app('userData');
        //check data
        $checkData =  DB::table('splitwise_summary')
                        ->where('friend_id', $transaction["friend_id"])
                        ->where('user_id', $userInfo["id"])
                        ->where('status',1)
                    ->first();

        if(empty($checkData)){
            $response =  DB::table('splitwise_summary')
                          ->insert([
                                    "friend_id"=>$transaction["friend_id"],
                                    "user_id"=>$userInfo["id"],
                                    "balance"=>$transaction["amount"]
                                  ]);
        }
        else{

            //increment value
            $response =  DB::table('splitwise_summary')
                            ->where('id', $checkData->id)
                            ->increment('balance', $transaction["amount"]);
        }

        return true;
    }

    // public static function decrementExpenseSummary(){
    //     $response =  DB::table('splitwise_summary')
    //         ->where('id', $id)
    //         ->decrement('amount', $addValue);
    //     return $response;
    // }


}

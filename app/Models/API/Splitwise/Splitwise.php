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
                            ->join('user as to_user', 'to_user.id', '=', 'splitwise_summary.to_user')
                            ->join('user as from_user', 'from_user.id', '=', 'splitwise_summary.from_user')
                            ->select(
                                "from_user.name as from_user_name",
                                "to_user.name as to_user_name",

                                "splitwise_summary.from_user",
                                "splitwise_summary.to_user",

                                "splitwise_summary.balance"
                            )
                            ->where(function ($q) use ($userInfo) {
                                $q->where('from_user', $userInfo['id'])
                                ->orWhere('to_user', $userInfo['id']);
                            })
                            //->where("splitwise_summary.user_id",$userInfo["id"])
                            ->where("splitwise_summary.status",1);
        // if($positive)
        // {
        //     $query->where("balance",">",0);
        // }
        // else{
        //     $query->where("balance","<",0);
        // }

        $query->where("balance","!=",0);

        $response = $query->get();
        return $response;
    }


    public static function incrementExpenseSummary($transaction){

        $userInfo = app('userData');
        //check data
        $checkData =  DB::table('splitwise_summary')
                        ->where(function ($q) use ($transaction) {
                            $q->where('from_user', $transaction['from_user'])
                            ->where('to_user', $transaction['to_user']);
                        })
                        ->orWhere(function ($q) use ($transaction) {
                            $q->where('from_user', $transaction['to_user'])
                            ->where('to_user', $transaction['from_user']);
                        })
                        ->where('status',1)
                    ->first();

        if(empty($checkData)){
            $response =  DB::table('splitwise_summary')
                          ->insert([
                                    "from_user"=>$transaction["from_user"],
                                    "to_user"=>$transaction["to_user"],
                                    "balance"=>$transaction["amount"],

                                    "user_id"=>$userInfo["id"]
                                  ]);
        }
        else{

            //increment value
            $query =  DB::table('splitwise_summary')
                          ->where('id', $checkData->id);

            if($checkData->from_user==$userInfo["id"]){
                $query->increment('balance', $transaction["amount"]);
            }else{
                //if it to user
                $query->decrement('balance', $transaction["amount"]);
            }

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

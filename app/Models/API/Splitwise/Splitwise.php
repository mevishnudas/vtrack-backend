<?php

namespace App\Models\API\Splitwise;

use Illuminate\Database\Eloquent\Model;
use DB;

class Splitwise extends Model
{

    public static function expenseSummary(){
        $userInfo = app('userData');

        $data = array();
        $data["ows_you"] = 0;
        $data["you_owe"] = 0;

        $data["you_owe"] = abs(DB::table("splitwise_summary")
                            ->where("status",1)
                            ->where("from_user",$userInfo["id"])
                            ->where('balance', '<', 0)
                            ->sum('balance'));

        $data["ows_you"] = abs(DB::table("splitwise_summary")
                            ->where("status",1)
                            ->where("from_user",$userInfo["id"])
                            ->where('balance', '>', 0)
                            ->sum('balance'));
        return $data;
    }

    public static function expenseAdd($insert_data){
        $response = DB::table("splitwise_transactions")
                        ->insert($insert_data);
        return $response;
    }

    public static function expenseSummaryList(){

        $userInfo = app('userData');
        $query = DB::table("splitwise_summary");
        $query->join('user', 'user.id', '=', 'splitwise_summary.to_user');

        $query->select(
            "user.id",
            "user.name",
            "splitwise_summary.balance"
        )
        ->where('splitwise_summary.from_user', $userInfo['id'])
        ->where("splitwise_summary.status",1);

        $response = $query->get();
        return $response;
    }

    // public static function expenseSummaryListYouOws(){

    //     $userInfo = app('userData');
    //     $query = DB::table("splitwise_summary");
    //     $query->join('user', 'user.id', '=', 'splitwise_summary.from_user');

    //     $query->select(
    //         "user.id",
    //         "user.name",
    //         "splitwise_summary.balance"
    //     )
    //     ->where('splitwise_summary.to_user', $userInfo['id'])
    //     ->where("splitwise_summary.status",1);

    //     $response = $query->get();
    //     return $response;
    // }

    /*public static function expenseSummaryList($ows_you=true){

        $userInfo = app('userData');
        $query = DB::table("splitwise_summary");

        if($ows_you){
            $query->join('user', 'user.id', '=', 'splitwise_summary.to_user');
        }else{
            $query->join('user', 'user.id', '=', 'splitwise_summary.from_user');
        }

        $query->select(
            "user.id",
            "user.name",
            "splitwise_summary.balance"
        )
        ->where('from_user', $userInfo['id'])
        ->whereNot('user.id',$userInfo['id'])

        ->where("splitwise_summary.status",1);

        if($ows_you){
            $query->where("splitwise_summary.balance",">",0);
        }else{
            $query->where("splitwise_summary.balance","<",0);
        }

        $response = $query->get();
        return $response;
    }*/


    public static function incrementExpenseSummary($transaction){

        $userInfo = app('userData');

        //------------- First From Side -----------------//
        $checkData =  DB::table('splitwise_summary')
                        ->where('from_user', $transaction['from_user'])
                        ->where('to_user', $transaction['to_user'])
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
                          ->where('id', $checkData->id)
                          ->increment('balance', $transaction["amount"]);

        }

        //----------------- Second to Side ------------------//
        $checkData =  DB::table('splitwise_summary')
                ->where('from_user', $transaction['to_user'])
                ->where('to_user', $transaction['from_user'])
                ->where('status',1)
        ->first();

        if(empty($checkData)){
            $response =  DB::table('splitwise_summary')
                            ->insert([
                                        "from_user"=>$transaction["to_user"],
                                        "to_user"=>$transaction["from_user"],
                                        "balance"=>(0-$transaction["amount"]),
                                        "user_id"=>$userInfo["id"]
                                ]);
        }else{

            //decrement value
            $query =  DB::table('splitwise_summary')
                          ->where('id', $checkData->id)
                          ->decrement('balance', $transaction["amount"]);

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

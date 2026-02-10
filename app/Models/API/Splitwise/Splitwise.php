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

        $data["you_owe"] = DB::table("splitwise_summary")
                            ->where("status",1)
                            ->where("from_user",$userInfo["id"])
                            ->where('balance', '<', 0)
                            ->sum('balance');

        $data["ows_you"] = DB::table("splitwise_summary")
                            ->where("status",1)
                            ->where("from_user",$userInfo["id"])
                            ->where('balance', '>', 0)
                            ->sum('balance');
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
        ->where("splitwise_summary.status",1)
        ->whereNot("splitwise_summary.balance",0);

        $response = $query->get();
        return $response;
    }


    public static function modifyExpenseSummary($transaction){

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

    public static function expenseTransactionList($friend){

        $userInfo = app('userData');

        $query = DB::table("splitwise_transactions")

                    ->join('user as from_user', 'from_user.id', '=', 'splitwise_transactions.from_user')
                    ->join('user as to_user', 'to_user.id', '=', 'splitwise_transactions.to_user')

                    ->select(
                        'to_user.id as to_user_id',
                        'to_user.name as to_user_name',

                        'from_user.id as from_user_id',
                        'from_user.name as from_user_name',

                        'splitwise_transactions.id',
                        'splitwise_transactions.date',
                        'splitwise_transactions.amount',
                        'splitwise_transactions.remarks',

                        'splitwise_transactions.payment_mode',
                    );

                    //$query->where('splitwise_transactions.from_user', $friend);
                    $query->where(function ($q) use ($friend, $userInfo) {
                        $q->where(function ($q) use ($friend, $userInfo) {
                                $q->where('splitwise_transactions.from_user', $friend)
                                ->where('splitwise_transactions.to_user', $userInfo['id']);
                            })
                        ->orWhere(function ($q) use ($friend, $userInfo) {
                            $q->where('splitwise_transactions.from_user', $userInfo['id'])
                            ->where('splitwise_transactions.to_user', $friend);
                        });
                    });

        $response = $query
                    ->limit(50)
                    ->orderBy('splitwise_transactions.date', 'desc')
                    ->get();
        return $response;
    }

    public static function expenseFriendSummary($friend_id){

        $userInfo = app('userData');

        $query = DB::table("splitwise_summary");
        $query->join('user', 'user.id', '=', 'splitwise_summary.to_user');

        $query->select(
            "user.id",
            "user.name",
            "splitwise_summary.balance"
        )
        ->where('splitwise_summary.from_user', $userInfo['id'])
        ->where('splitwise_summary.to_user', $friend_id)
        ->where("splitwise_summary.status",1);

        $response = $query->first();
        return $response;
    }


}

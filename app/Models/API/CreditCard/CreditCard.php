<?php

namespace App\Models\API\CreditCard;

use Illuminate\Database\Eloquent\Model;
use DB;

class CreditCard extends Model
{
    public static function creditCardList(){

        $userInfo = app('userData');
        $response = DB::table("bank")
                    ->select(
                        "bank.id",
                        "bank.name",
                        "bank.variant_name",
                        "bank.last_digit"
                    )
                    ->where("bank.bank_type","CREDIT_CARD")
                    ->where("bank.status",1)
                    ->where("bank.user_id",$userInfo['id'])
                    ->get();
        return $response;
    }

    public static function totalCreditCardCount(){

        $userInfo = app('userData');
        $response = DB::table("bank")
                    //->select("bank.id")
                    ->where("bank.bank_type","CREDIT_CARD")
                    ->where("bank.status",1)
                    ->where("bank.user_id",$userInfo['id'])
                    ->count();

        return $response;
    }

    // public static function creditCardCount(){

    //     $userInfo = app('userData');
    //     $response = DB::table("bank")
    //                 ->select(
    //                     "bank.id",
    //                     "bank.name",
    //                     "bank.variant_name",
    //                     "bank.last_digit",
    //                 )
    //                 ->where("bank.bank_type","CREDIT_CARD")
    //                 ->where("bank.status",1)
    //                 ->where("bank.user_id",$userInfo['id'])
    //                 ->count();
    //     return $response;
    // }

    public static function creditCardPaymentHistoryLatest($ids){

        $latestRecords = DB::table('credit_card_payment_history')
                        ->whereIn('credit_card_id', $ids)
                        ->whereIn('id', function($query) {
                            $query->selectRaw('MAX(id)')
                                ->from('credit_card_payment_history')
                                ->groupBy('credit_card_id');
                        })
                        ->get();
        return $latestRecords;
    }

    public static function creditCardPaymentHistoryDateWiseDueAmount($sort_data){
        $userInfo = app('userData');
        $total_amount = DB::table('credit_card_payment_history')
                        ->join("bank","bank.id","=","credit_card_payment_history.credit_card_id")
                        //->whereIn('credit_card_id', $ids)
                        ->where("bank.user_id",$userInfo["id"])
                        ->where("credit_card_payment_history.payment_status","PENDING")
                        ->whereBetween('payment_date', [$sort_data["start_date"], $sort_data["end_date"]])
                        ->sum('amount');
        return $total_amount;
    }

    public static function creditCardPaymentHistoryDateWiseSum($sort_data){
        $userInfo = app('userData');
        $total_amount = DB::table('credit_card_payment_history')
                        ->join("bank","bank.id","=","credit_card_payment_history.credit_card_id")
                        //->whereIn('credit_card_id', $ids)
                        ->where("bank.user_id",$userInfo["id"])
                        ->whereBetween('payment_date', [$sort_data["start_date"], $sort_data["end_date"]])
                        ->sum('amount');
        return $total_amount;
    }

    public static function getLatestPaymentDate(){
        $userInfo = app('userData');
        $latestPaymentRecord = DB::table('credit_card_payment_history')
                        ->join("bank","bank.id","=","credit_card_payment_history.credit_card_id")
                        ->where("bank.user_id",$userInfo['id'])
                        ->orderby("credit_card_payment_history.payment_date","DESC")
                        ->first();
        return $latestPaymentRecord;
    }


    public static function creditCardInfo($id){

        $userInfo = app('userData');

        $response = DB::table('bank')
                    ->select(
                        "bank.id",
                        "bank.name",
                        "bank.variant_name",
                        "bank.last_digit",
                        "bank.statement_password"
                    )
                    ->where("id",$id)
                    ->where("status",1)
                    ->where("user_id",$userInfo['id'])
                    ->first();

        return $response;
    }

    public static function creditCardPaymentHistory($id){

        $userInfo = app('userData');
        $response = DB::table('credit_card_payment_history')
                    ->select(
                        "id",
                        "credit_card_id",
                        "payment_date",
                        "amount",
                        "payment_status",
                        "remarks"
                    )
                    ->where("credit_card_id",$id)
                    ->where("status",1)
                    ->where("user_id",$userInfo['id'])
                    ->orderBy("payment_date",'desc')
                    ->limit(20)
                    ->get();

        return $response;
    }

    public static function addToPaymentHistory($insert_data){
        $response = DB::table("credit_card_payment_history")
                    ->insertGetId($insert_data);
        return $response;
    }

    public static function updatePaymentHistory($update_data,$id){
        $response = DB::table("credit_card_payment_history")
                    ->where("id",$id)
                    ->update($update_data);
        return $response;
    }


    public static function deletePaymentHistory($id){
        $response = DB::table("credit_card_payment_history")
                    ->where("id",$id)
                    ->delete();
        return $response;
    }

}

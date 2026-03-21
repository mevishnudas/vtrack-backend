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
                        "bank.last_digit",

                        "credit_card_payment_history.payment_date",
                        "credit_card_payment_history.amount",
                        "credit_card_payment_history.payment_status"
                    )
                    ->leftJoin('credit_card_payment_history', 'bank.id', '=', 'credit_card_payment_history.credit_card_id')
                    ->where("bank.bank_type","CREDIT_CARD")
                    ->where("bank.status",1)
                    ->where("bank.user_id",$userInfo['id'])
                    //->groupBy('bank.id');
                    //->distinct()
                    ->orderBy('credit_card_payment_history.payment_date', 'desc')
                    ->get();
        return $response;
    }

    public static function creditCardInfo($id){

        $userInfo = app('userData');

        $response = DB::table('bank')
                    ->select(
                        "bank.id",
                        "bank.name",
                        "bank.variant_name",
                        "bank.last_digit"
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

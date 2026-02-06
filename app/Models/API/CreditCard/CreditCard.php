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
                        "bank.nick_name as name",
                        "bank.last_digit"
                    )
                    ->where("bank_type","CREDIT_CARD")
                    ->where("status",1)
                    ->where("user_id",$userInfo['id'])
                    ->get();
        return $response;
    }

    public static function creditCardInfo($id){

        $userInfo = app('userData');

        $response = DB::table('bank')
                    ->select(
                        "bank.id",
                        "bank.nick_name as name",
                        "bank.last_digit"
                    )
                    ->where("id",$id)
                    ->where("status",1)
                    ->where("user_id",$userInfo['id'])
                    ->first();

        return $response;
    }

}

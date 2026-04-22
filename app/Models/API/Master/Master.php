<?php

namespace App\Models\API\Master;

use Illuminate\Database\Eloquent\Model;
use DB;

class Master extends Model
{
    public static function bankList(){
        $response = DB::table("bank")
                    ->select(
                        "id",
                        "name",
                        "variant_name"
                    )
                    ->where("status",1)->get();
        return $response;
    }

    // public static function creditCardList(){
    //     $response = DB::table("bank")
    //                 ->select(
    //                     "id",
    //                     "name",
    //                     "nick_name",
    //                     "last_digit",
    //                     "color_code"
    //                 )
    //                 ->where("bank_type","CREDIT_CARD")
    //                 ->where("status",1)
    //                 ->get();
    //     return $response;
    // }

    public static function sourceBankList(){
        $response = DB::table("bank")
                    ->select(
                        "id",
                        "name"
                    )
                    ->where("bank_type","BANK")
                    ->where("status",1)
                    ->get();
        return $response;
    }
}

<?php

namespace App\Models\API\User;

use Illuminate\Database\Eloquent\Model;
use DB;

class User extends Model
{
    public static function allUserlist(){
        $response = DB::table("user")
                        ->select(
                            'id',
                            'name'
                        )
                        ->where("payee_status",1)
                        ->get();
        return $response;
    }

    public static function userAdd($insert_data){
        $response = DB::table("user")
                        ->insert($insert_data);
        return $response;
    }

    public static function friendsList(){
        $userInfo = app('userData');
        $response = DB::table("user")
                        ->select(
                            'id',
                            'name'
                        )
                        ->where("payee_status",1)
                        ->where("id","!=",$userInfo["id"])
                        ->get();
        return $response;
    }

}

<?php

namespace App\Models\API\Login;

use Illuminate\Database\Eloquent\Model;
use DB;

class Login extends Model
{
    public static function loginCheck($sort_data){
        $response = DB::table("user")
                    ->where(function ($query) use($sort_data){
                        $query->where("phone",$sort_data["username"])
                              ->orWhere("email",$sort_data["username"]);
                    })
                    ->where("password",md5($sort_data["password"]))
                    ->where("status",1)
                    ->first();
        return $response;
    }

    public static function tokenUpdate($token,$id){
        $response = DB::table("user")
                    ->where("id",$id)
                    ->where("status",1)
                    ->update(["token"=>$token]);
        return $response;
    }

    public static function getUserInfo($token){
        $response = DB::table("user")
                        ->where("token",$token)
                        ->where("status",1)
                        ->first();
        return $response;
    }

}

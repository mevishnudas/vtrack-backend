<?php

namespace App\Http\Controllers\API\Login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(Request $request){
        $data = array();
        $data["name"] = "Vishnu";
        $data["token"] = "123xxxx123xxxx123xxx123";
        return response(["status"=>200,"msg"=>"Success","data"=>$data],200);
    }

    public function tokenValidate(Request $request){
        $data = array();
        return response(["status"=>200,"msg"=>"Success"],200);
    }
}

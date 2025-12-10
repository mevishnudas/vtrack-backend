<?php

namespace App\Http\Controllers\API\Login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\API\Login\Login;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function login(Request $request){


        $validator = Validator::make($request->all(), [
            'username' => 'required|string|min:5|max:30',
            'password' => 'required|string|min:5|max:15',
        ]);

        if ($validator->fails()) {
            return response(["status"=>401,"msg"=>"Invalid Parameters"],401);
        }else{

            $sort_data = array(
                "username"=>$request->username,
                "password"=>$request->password
            );
            $userInfo = Login::loginCheck($sort_data);
            // echo json_encode($userInfo);die();
            // echo $userInfo->id;die();
            if(empty($userInfo)){
                return response(["status"=>401,"msg"=>"Invalid Credentials !"],401);
            }else{

                $token = Str::random(50);
                Login::tokenUpdate($token,$userInfo->id);

                $data = array();
                $data["name"] = $userInfo->name;
                $data["token"] = $token;
                return response(["status"=>200,"msg"=>"Success","data"=>$data],200);
            }
        }

    }

    public function tokenValidate(Request $request){
        $data = array();
        return response(["status"=>200,"msg"=>"Success"],200);
    }
}

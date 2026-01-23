<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\API\User\User;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function list(Request $request){
        //$data = array();
        $userList = User::allUserlist();
        return response(["status"=>200,"msg"=>"Success","data"=>$userList],200);
    }

    public function addUser(Request $request){

        $validator = Validator::make($request->all(), [
            'name' =>'required|string|min:2|max:30',
            'phone' =>'required|digits_between:10,15|unique:user,phone',
            'email' =>'email|present|nullable|unique:user,email',
        ]);

        if ($validator->fails()) {
            return response(["status"=>401,"msg"=>"Invalid Parameters","data"=>$validator->errors()],401);
        }else{

            //if validation fine
            $userInfo = app('userData');

            // Add User User::userAdd();
            $insert_data = array(
                "name"=>$request->name,
                "phone"=>$request->phone,

                "user_id"=>$userInfo["id"],
                "payee_status"=>1,
                "status"=>0
            );

            //only add if not empty
            if(!empty($request->email)){
                $insert_data["email"] = $request->email;
            }

            $response = User::userAdd($insert_data);
            return response(["status"=>200,"msg"=>"Success"],200);
        }
    }

    public function friendsList(Request $request){
        $friendsList = User::friendsList();
        return response(["status"=>200,"msg"=>"Success","data"=>$friendsList],200);
    }


}

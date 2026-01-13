<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\API\User\User;

class UserController extends Controller
{
    public function list(Request $request){
        //$data = array();
        $userList = User::allUserlist();
        return response(["status"=>200,"msg"=>"Success","data"=>$userList],200);
    }

    public function friendsList(Request $request){
        $friendsList = User::friendsList();
        return response(["status"=>200,"msg"=>"Success","data"=>$friendsList],200);
    }


}

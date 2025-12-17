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
}

<?php

namespace App\Http\Controllers\API\Expense;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\API\Expense\Expense;
use Illuminate\Support\Facades\Validator;

class ExpenseController extends Controller
{
    public function expenseAdd(Request $request){
        return response(["status"=>200,"msg"=>"Success"],200);
    }

    public function categoryList(Request $request){
        $response = Expense::categoryList();
        return response(["status"=>200,"msg"=>"Success","data"=>$response],200);
    }

    public function categoryAdd(Request $request){

        $validator = Validator::make($request->all(), [
            "name"=>"required|string|min:2|max:20"
        ]);

        if ($validator->fails()) {
            return response(["status"=>401,"msg"=>"Invalid Parameters","data"=>$validator->errors()],401);
        }else{

            $userInfo = app('userData');
            $insert_data = array(
                "name"=>$request->name,
                "user_id"=>$userInfo["id"]
            );

            $response = Expense::categoryAdd($insert_data);
            return response(["status"=>200,"msg"=>"Success"],200);
        }

    }


}

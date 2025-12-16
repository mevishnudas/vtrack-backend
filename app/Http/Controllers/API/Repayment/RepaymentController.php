<?php

namespace App\Http\Controllers\API\Repayment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\API\Repayment\Repayment;

class RepaymentController extends Controller
{
    public function addNew(Request $request){

         $validator = Validator::make($request->all(), [
            'payee' => 'required|numeric|exists:user,id',
            'amount' => 'required|numeric',
            'pr_fee'=>'required|numeric',
            'charges'=>'required|numeric',
            'payment_date'=>'required|date',
            'distributed_date'=>'required|date',
            'remarks'=>'present|nullable',
        ]);

        if ($validator->fails()) {
            return response(["status"=>401,"msg"=>"Invalid Parameters","data"=>$validator->errors()],401);
        }else{

            //User Info
            $userInfo = app('userData');

            //Insert Data
            $insert_data = array();
            $insert_data["amount"] = $request->amount;
            $insert_data["pr_fee"] = $request->pr_fee;
            $insert_data["total"] = round(($request->amount+$request->payee+$request->charges));
            $insert_data["payment_date"] = date('Y-m-d',strtotime($request->payment_date));
            $insert_data["distributed_date"] = date('Y-m-d',strtotime($request->distributed_date));
            $insert_data["remarks"] = $request->remarks;
            $insert_data["payee_id"] = $request->payee;
            $insert_data["user_id"] = $userInfo["id"];

            Repayment::addNew($insert_data);
            return response(["status"=>200,"msg"=>"Success"],200);
        }

    }

    public function list(Request $request){



    }
}

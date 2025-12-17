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
            //'payee' => 'required|numeric',
            'amount' => 'required|numeric',
            'pr_fee'=>'required|numeric',
            'charges'=>'required|numeric',
            'payment_date'=>'required|date',
            'distributed_date'=>'required|date',
            'remarks'=>'present|nullable',
            'from'=>'required|numeric|exists:bank,id',
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
            $insert_data["charges"] = $request->charges;
            $insert_data["total"] = round(($request->amount+$request->payee+$request->charges));

            $insert_data["payment_date"] = date('Y-m-d',strtotime($request->payment_date));
            $insert_data["distributed_date"] = date('Y-m-d',strtotime($request->distributed_date));

            $insert_data["remarks"] = $request->remarks;
            $insert_data["payee_id"] = $request->payee;
            $insert_data["user_id"] = $userInfo["id"];
            $insert_data["source"] = $request->from;

            Repayment::addNew($insert_data);
            return response(["status"=>200,"msg"=>"Success"],200);
        }

    }

    public function list(Request $request){

        $validator = Validator::make($request->all(), [
            //"month"=>'required|numeric',
        ]);
        if ($validator->fails()) {
            return response(["status"=>401,"msg"=>"Invalid Parameters","data"=>$validator->errors()],401);
        }else{

            $data = array();
            $repaymentList = Repayment::list();
            //User Info
            $userInfo = app('userData');
            foreach ($repaymentList as $repaymentList_row) {

                $data[] = array(
                    "id"=>$repaymentList_row->id,

                    "amount"=>$repaymentList_row->amount,
                    "pr_fee"=>$repaymentList_row->pr_fee,
                    "charges"=>$repaymentList_row->charges,
                    "total"=>$repaymentList_row->total,

                    "payment_date"=>$repaymentList_row->payment_date,
                    "distributed_date"=>$repaymentList_row->distributed_date,

                    "remarks"=>$repaymentList_row->remarks,
                    "payee_id"=>$repaymentList_row->payee_id,
                    "payee"=>$repaymentList_row->payee,

                    "payment_status"=>$repaymentList_row->payment_status,

                    "from_id"=>$repaymentList_row->from_id,
                    "from"=>$repaymentList_row->from,

                    "user_id"=>$userInfo["id"]
                );
            }

            return response(["status"=>200,"msg"=>"Success","data"=>$data],200);
        }
    }

}

<?php

namespace App\Http\Controllers\API\Repayment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\API\Repayment\Repayment;
use Carbon\Carbon;

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
            $insert_data["total"] = round(($request->amount+$request->pr_fee+$request->charges));

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

    public function update(Request $request){

         $validator = Validator::make($request->all(), [
            'record_id'=>'required|numeric|exists:repayment,id',
            'amount' => 'required|numeric',
            'pr_fee'=>'required|numeric',
            'charges'=>'required|numeric',
            'payment_date'=>'required|date',
            'distributed_date'=>'required|date',
            'remarks'=>'present|nullable',
            'from'=>'required|numeric|exists:bank,id',
            'payment_status'=>'required|in:PENDING,RECEIVED,PARTIALLY_PAID'
        ]);

        if ($validator->fails()) {
            return response(["status"=>401,"msg"=>"Invalid Parameters","data"=>$validator->errors()],401);
        }else{

            $update_data = array();
            $update_data = array();
            $update_data["amount"] = $request->amount;
            $update_data["pr_fee"] = $request->pr_fee;
            $update_data["charges"] = $request->charges;
            $update_data["total"] = round(($request->amount+$request->pr_fee+$request->charges));

            $update_data["payment_date"] = date('Y-m-d',strtotime($request->payment_date));
            $update_data["distributed_date"] = date('Y-m-d',strtotime($request->distributed_date));

            $update_data["remarks"] = $request->remarks;
            $update_data["source"] = $request->from;

            $update_data["payment_status"] = $request->payment_status;

            Repayment::updateRecord($update_data,$request->record_id);
            return response(["status"=>200,"msg"=>"Success"],200);
        }
    }


    public function list(Request $request){

        $validator = Validator::make($request->all(), [
            "year"=>'required|numeric|in:2023,2024,2025,2026',
            "month"=>'required|numeric|in:1,2,3,4,5,6,7,8,9,10,11,12',
            "payee"=>'present|nullable|numeric'
        ]);
        if ($validator->fails()) {
            return response(["status"=>401,"msg"=>"Invalid Parameters","data"=>$validator->errors()],401);
        }else{

            $data = array();

            //Date Format
            $startDate = Carbon::create($request->year, $request->month, 1)->startOfDay();
            $endDate   = Carbon::create($request->year, $request->month, 1)->endOfMonth()->endOfDay();

            $sort_data = array();
            $sort_data["start_date"] = $startDate;
            $sort_data["end_date"] = $endDate;
            $sort_data["payee"] = $request->payee;
            $repaymentList = Repayment::list($sort_data);

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

    public function emiAdd(Request $request){

        $validator = Validator::make($request->all(), [
            'payee' => 'required|numeric|exists:user,id',
            'from'=>'required|numeric|exists:bank,id',

            'amount' => 'required|numeric',
            'pr_fee'=>'required|numeric',
            'emi'=>'required|numeric',
            'duration'=>'required|numeric',

            'distributed_date'=>'required|date',
            'payment_date'=>'required|date',

            'remarks'=>'present|nullable'
        ]);

        if ($validator->fails()) {
            return response(["status"=>401,"msg"=>"Invalid Parameters","data"=>$validator->errors()],401);
        }else{
            //User Info
            $userInfo = app('userData');

            $insert_data = array();
            $insert_data["payee"] = $request->payee;
            $insert_data["source"] = $request->from;

            $insert_data["amount"] = $request->amount;
            $insert_data["pr_fee"] = $request->pr_fee;
            $insert_data["emi"] = $request->emi;
            $insert_data["duration"] = $request->duration;

            $insert_data["remarks"] = $request->remarks;
            $insert_data["user_id"] = $userInfo["id"];

            $insert_data["distributed_date"] = date('Y-m-d',strtotime($request->distributed_date));
            $insert_data["payment_date"] = date('Y-m-d',strtotime($request->payment_date));

            Repayment::emiAdd($insert_data);
            return response(["status"=>200,"msg"=>"Success"],200);
        }
    }


}

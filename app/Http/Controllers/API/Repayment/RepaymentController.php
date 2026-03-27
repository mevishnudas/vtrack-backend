<?php

namespace App\Http\Controllers\API\Repayment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\API\Repayment\Repayment;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Database\Query\Builder;

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
            "payee"=>'sometimes|nullable|exclude_if:payee,0|integer|exists:user,id'
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

                    "amount"=>round($repaymentList_row->amount),
                    "pr_fee"=>round($repaymentList_row->pr_fee),
                    "charges"=>round($repaymentList_row->charges),
                    "total"=>round($repaymentList_row->total),

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

    public function emiList(Request $request){

        $validator = Validator::make($request->all(), [
            //"year"=>'required|numeric|min:2023|max:2026',
            //"month"=>'required|numeric|min:1|max:12',
            "status"=>'required|string|in:OPEN,CLOSED,PRE_CLOSED',
            "payee"=>'sometimes|nullable|exclude_if:payee,0|integer|exists:user,id',
            "bank"=>'sometimes|nullable|exclude_if:bank,0|integer|exists:bank,id'
        ]);
        if ($validator->fails()) {
            return response(["status"=>401,"msg"=>"Invalid Parameters","data"=>$validator->errors()],401);
        }else{

            //Date Format
            $sort_data = array();
            $sort_data["status"] = $request->status;
            $sort_data["payee"] = $request->payee;
            $sort_data["bank"] = $request->bank;

            $emiList = Repayment::emiList($sort_data);

            $data = array();
            foreach ($emiList as $emiList_row) {

                $data[] = array(
                    "id"=>$emiList_row->id,
                    "payee_id"=>$emiList_row->payee_id,
                    "payee"=>$emiList_row->payee,

                    "amount"=>round($emiList_row->amount),
                    "emi"=>round($emiList_row->emi),
                    "pr_fee"=>round($emiList_row->pr_fee),

                    "duration"=>$emiList_row->duration,
                    "paid"=>$emiList_row->paid,

                    "payment_date"=>$emiList_row->payment_date,
                    "distributed_date"=>$emiList_row->distributed_date,

                    "source"=>$emiList_row->source,
                    "source_id"=>$emiList_row->source_id,

                    "status"=>$emiList_row->emi_status,
                    "remarks"=>$emiList_row->remarks
                );
            }

            return response(["status"=>200,"msg"=>"Success","data"=>$data],200);

        }
    }

    public function emiScheduleList(Request $request){
        //User Info
        $userInfo = app('userData');
        $validator = Validator::make($request->all(), [
        'id' => ['required',
                Rule::exists('repayment_emi','id')->where(function ($query) use ($userInfo) {
                $query->where('user_id', $userInfo["id"]);
            })]
        ]);

        if ($validator->fails()) {
            return response(["status"=>401,"msg"=>"Invalid Parameters","data"=>$validator->errors()],401);
        }else{

            $emiSchedule = Repayment::emiSchedule($request->id);

            $result = collect($emiSchedule)
                ->map(fn ($item) => [
                    ...(array) $item,
                    'amount' => round($item->amount),
                ])
            ->all();

            return response(["status"=>200,"msg"=>"Success","data"=>$result],200);
        }
    }

    public function emiScheduleAdd(Request $request){

        $userInfo = app('userData');
        $validator = Validator::make($request->all(), [
        'id' => ['required',
                Rule::exists('repayment_emi','id')->where(function ($query) use ($userInfo) {
                $query->where('user_id', $userInfo["id"]);
            })],
        "principle"=>"required|numeric",
        "amount"=>"required|numeric",
        "payment_date"=>"required|date",
        "remarks"=>"present|nullable"
        ]);

        if ($validator->fails()) {
            return response(["status"=>401,"msg"=>"Invalid Parameters","data"=>$validator->errors()],401);
        }else{

            $insert_data = array(
                "emi_id"=>$request->id,
                "principle"=>$request->principle,
                "amount"=>$request->amount,
                "payment_date"=>date('Y-m-d',strtotime($request->payment_date)),
                "remarks"=>$request->remarks,
            );
            $emiSchedule = Repayment::emiScheduleAdd($insert_data);

            return response(["status"=>200,"msg"=>"Success"],200);
        }
    }

    public function emiScheduleUpdate(Request $request){

        $userInfo = app('userData');
        $validator = Validator::make($request->all(), [
            'id' =>'required|numeric|exists:repayment_emi_schedule,id',
            "status"=>"required|in:PENDING,PAID",
            "remarks"=>"present|nullable"
        ]);

        if ($validator->fails()) {
            return response(["status"=>401,"msg"=>"Invalid Parameters","data"=>$validator->errors()],401);
        }else{

            //user permission check
            $emiScheduleCheck = Repayment::emiScheduleCheck($userInfo["id"]);

            if(!empty($emiScheduleCheck)){
                $update_data = array(
                    "payment_status"=>$request->status,
                    "remarks"=>$request->remarks
                );
                Repayment::emiScheduleUpdate($update_data,$request->id);
            }
            return response(["status"=>200,"msg"=>"Success"],200);
        }
    }

    public function emiStatusUpdate(Request $request){
         $validator = Validator::make($request->all(), [
            "id"=>'required|numeric|exists:repayment_emi,id',
            "paid"=>'required|numeric',
            "status"=>'required|string|in:OPEN,CLOSED,PRE_CLOSED',
            "remarks"=>'present|nullable'
        ]);
        if ($validator->fails()) {
            return response(["status"=>401,"msg"=>"Invalid Parameters","data"=>$validator->errors()],401);
        }else{

            $update_data = array(
                "paid"=>$request->paid,
                "emi_status"=>$request->status,
                "remarks"=>$request->remarks,
                "status_change_date"=>date('Y-m-d H:i:s')
            );
            Repayment::updateEMIStatus($update_data,$request->id);

            return response(["status"=>200,"msg"=>"Success"],200);
        }

    }

}

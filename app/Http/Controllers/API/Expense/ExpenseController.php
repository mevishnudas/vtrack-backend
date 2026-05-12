<?php

namespace App\Http\Controllers\API\Expense;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\API\Expense\Expense;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class ExpenseController extends Controller
{
    public function overview(Request $request){
        $data = array();

        $current_date = date('Y-m-d');
        $data["this_year"] = Expense::overview(["start_date"=>date('Y-01-01'),"end_date"=>$current_date]);

        //Last Month
        $startDate = Carbon::now()->subMonthNoOverflow()->firstOfMonth()->format('Y-m-d');
        $endDate = Carbon::now()->subMonthNoOverflow()->lastOfMonth()->format('Y-m-d');
        $data["last_month"] = Expense::overview(["start_date"=>$startDate,"end_date"=>$endDate]);

        //This Month
        $startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
        $data["this_month"] = Expense::overview(["start_date"=>$startDate,"end_date"=>$endDate]);

        //Yesterday
        $yesterday = Carbon::yesterday()->format('Y-m-d');
        $data["yesterday"] = Expense::overview(["start_date"=>$yesterday,"end_date"=>$yesterday]);

        //Today
        $today = Carbon::today()->format('Y-m-d');
        $data["today"] = Expense::overview(["start_date"=>$today,"end_date"=>$today]);

        return response(["msg"=>"Success","data"=>$data],200);
    }

    public function expenseList(Request $request){
        $validator = Validator::make($request->all(), [
            "date"=>"required|date|date_format:Y-m-d"
        ]);

        if ($validator->fails()) {
            return response(["status"=>401,"msg"=>"Invalid Parameters","data"=>$validator->errors()],401);
        }else{

            $data = array();
            $sort_data = array(
                "date"=>$request->date
            );
            $data = Expense::expenseList($sort_data);
            return response(["status"=>200,"msg"=>"Success","data"=>$data],200);
        }
    }

    public function expenseAdd(Request $request){

        $userInfo = app('userData');
        $validator = Validator::make($request->all(), [
            "title"=>"required|string|min:2|max:80",
            "date"=>"required|date|date_format:Y-m-d",
            "amount"=>"required|numeric",
            "category"=>[
                    'required',
                    Rule::exists('expense_category', 'id')
                    ->where(function ($query) use ($userInfo) {
                        $query->where('status', 1)
                            ->where(function ($q) use ($userInfo) {
                                $q->where('user_id', 0)
                                    ->orWhere('user_id', $userInfo['id']);
                            });
                    }),
            ],
            "notes"=>"present|nullable|string",
        ]);

        if ($validator->fails()) {
            return response(["status"=>401,"msg"=>"Invalid Parameters","data"=>$validator->errors()],401);
        }else{

            $insert_data = array(
                "title"=>$request->title,
                "amount"=>$request->amount,
                "notes"=>$request->notes,
                "category_id"=>$request->category,
                "transaction_date"=>date('Y-m-d',strtotime($request->date)),
                "user_id"=>$userInfo['id']
            );
            Expense::addExpense($insert_data);
            return response(["status"=>200,"msg"=>"Success"],200);
        }
    }

    public function expenseUpdate(Request $request){

        $userInfo = app('userData');
        $validator = Validator::make($request->all(), [
            "id"=>[
                    'required',
                    Rule::exists('expense', 'id')
                     ->where(function ($query) use ($userInfo) {
                        $query->where('status', 1)
                            ->where('user_id', $userInfo["id"]);
                     }),
            ],
            "title"=>"required|string|min:2|max:80",
            "date"=>"required|date|date_format:Y-m-d",
            "amount"=>"required|numeric",
            "category"=>[
                    'required',
                    Rule::exists('expense_category', 'id')
                    ->where(function ($query) use ($userInfo) {
                        $query->where('status', 1)
                            ->where(function ($q) use ($userInfo) {
                                $q->where('user_id', 0)
                                    ->orWhere('user_id', $userInfo['id']);
                            });
                    }),
            ],
            "notes"=>"present|nullable|string",
        ]);
        if ($validator->fails()) {
            return response(["status"=>401,"msg"=>"Invalid Parameters","data"=>$validator->errors()],401);
        }else{

            $update_data = array(
                "title"=>$request->title,
                "amount"=>$request->amount,
                "notes"=>$request->notes,
                "category_id"=>$request->category,
                "transaction_date"=>date('Y-m-d',strtotime($request->date)),
                "user_id"=>$userInfo['id']
            );
            Expense::updateExpense($update_data,$request->id);

            return response(["status"=>200,"msg"=>"Success"],200);
        }
    }

    public function expenseDelete(Request $request){
        $userInfo = app('userData');
        $validator = Validator::make($request->all(), [
            "id"=>[
                    'required',
                    Rule::exists('expense', 'id')
                     ->where(function ($query) use ($userInfo) {
                        $query->where('status', 1)
                            ->where('user_id', $userInfo["id"]);
                     }),
            ]
        ]);
        if ($validator->fails()) {
            return response(["status"=>401,"msg"=>"Invalid Parameters","data"=>$validator->errors()],401);
        }else{

            Expense::deleteExpense($request->id);
            return response(["status"=>200,"msg"=>"Success"],200);
        }
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

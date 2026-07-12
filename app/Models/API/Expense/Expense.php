<?php

namespace App\Models\API\Expense;

use Illuminate\Database\Eloquent\Model;
use DB;

class Expense extends Model
{

    public static function overview($sort_date){
        $userInfo = app('userData');
        $response = DB::table("expense")
                        ->where("expense.user_id",$userInfo["id"])
                        ->where("expense.status",1)
                        ->whereBetween('transaction_date', [$sort_date["start_date"], $sort_date["end_date"]])
                        ->sum("amount");
        return $response;
    }

    public static function expenseList($sort_data){

        $userInfo = app('userData');
        $response = DB::table("expense")
                    ->select(
                        "expense.id",
                        "expense.title",
                        "expense.amount",
                        "expense.notes",
                        "expense.category_id",
                        "expense_category.name as category_name",
                        "expense.transaction_date",
                        "expense.date"
                    )
                    ->join("expense_category","expense_category.id","=","expense.category_id")
                    ->where("expense.user_id",$userInfo["id"])
                    ->where("expense.status",1)
                    ->where("expense.transaction_date",$sort_data["date"])
                    ->orderBy("expense.id","DESC")
                    ->get();

        return $response;
    }

    public static function addExpense($insert_data) {
        $response = DB::table("expense")
                    ->insert($insert_data);
        return $response;
    }

    public static function updateExpense($update_data,$id) {
        $userInfo = app('userData');

        $response = DB::table("expense")
                    ->where("id",$id)
                    ->where("user_id",$userInfo["id"])
                    ->update($update_data);
        return $response;
    }

    public static function deleteExpense($id) {
        $userInfo = app('userData');

        $response = DB::table("expense")
                    ->where("id",$id)
                    ->where("user_id",$userInfo["id"])
                    ->delete();
        return $response;
    }

    public static function categoryList(){
        $userInfo = app('userData');

        $response = DB::table("expense_category")
                    ->select(
                        "id",
                        "name"
                    )
                    ->where(function ($query) use ($userInfo) {
                        $query->where('user_id', $userInfo['id'])
                            ->orWhere('user_id', 0);
                    })
                    ->orderBy("name","asc")
                    ->get();
        return $response;
    }

    public static function categoryAdd($insert_data){
        $response = DB::table("expense_category")
                    ->insert($insert_data);
        return $response;
    }

    public static function summaryCategoryWise($sort_data){
        $userInfo = app('userData');

        $response = DB::table('expense_category')
            ->leftJoin('expense', 'expense_category.id', '=', 'expense.category_id')
            ->where("expense.user_id",$userInfo["id"])
            ->whereBetween('expense.transaction_date', [$sort_data["start_date"], $sort_data["end_date"]])
            ->select(
                'expense_category.id',
                'expense_category.name',
                DB::raw('COALESCE(SUM(expense.amount), 0) as total_amount')
            )
            ->groupBy('expense_category.id', 'expense_category.name')
            ->get();

        return $response;
    }


}

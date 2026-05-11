<?php

namespace App\Models\API\Expense;

use Illuminate\Database\Eloquent\Model;
use DB;

class Expense extends Model
{
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


}

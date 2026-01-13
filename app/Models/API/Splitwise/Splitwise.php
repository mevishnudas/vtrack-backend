<?php

namespace App\Models\API\Splitwise;

use Illuminate\Database\Eloquent\Model;
use DB;

class Splitwise extends Model
{
    public static function expenseAdd($insert_data){
        $response = DB::table("splitwise_debit")
                        ->insert($insert_data);
        return $response;
    }
}

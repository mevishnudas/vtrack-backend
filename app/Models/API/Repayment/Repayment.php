<?php

namespace App\Models\API\Repayment;

use Illuminate\Database\Eloquent\Model;
use DB;

class Repayment extends Model
{
    public static function addNew($insert_data){
        $response = DB::table("repayment")->insert($insert_data);
        return $response;
    }
}

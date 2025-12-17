<?php

namespace App\Models\API\Master;

use Illuminate\Database\Eloquent\Model;
use DB;

class Master extends Model
{
    public static function bankList(){
        $response = DB::table("bank")
                    ->select(
                        "id",
                        "name"
                    )
                    ->where("status",1)->get();
        return $response;
    }
}

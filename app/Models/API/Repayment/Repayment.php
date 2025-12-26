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

    public static function updateRecord($update_data,$id){
        $userInfo = app('userData');
        $response = DB::table("repayment")
                        ->where("id",$id)
                        ->where("user_id",$userInfo["id"])
                        ->update($update_data);
        return $response;
    }

    public static function list($sort_data){
        $query = DB::table("repayment")
                    ->select(
                        "user.id as payee_id",
                        "user.name as payee",

                        "repayment.id",
                        "repayment.amount",
                        "repayment.pr_fee",
                        "repayment.charges",
                        "repayment.total",

                        "repayment.payment_date",
                        "repayment.distributed_date",

                        "repayment.remarks",
                        "repayment.user_id",

                        "repayment.payment_status",

                        "repayment.source as from_id",
                        "bank.name as from",
                    )
                    ->join("user",'user.id', '=', 'repayment.payee_id')
                    ->join("bank",'bank.id', '=', 'repayment.source')
                    ->where("repayment.status",1);

                    //Filter
                    $query->whereBetween("repayment.payment_date",[$sort_data["start_date"],$sort_data["end_date"]]);

                    //Filter User
                    if(!empty($sort_data["payee"])){
                        $query->where("repayment.payee_id",$sort_data["payee"]);
                    }


        $response = $query->get();
        return $response;
    }
}

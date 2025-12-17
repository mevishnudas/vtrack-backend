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

    public static function list(){
        $response = DB::table("repayment")
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
                    ->where("repayment.status",1)
                    ->get();
        return $response;
    }
}

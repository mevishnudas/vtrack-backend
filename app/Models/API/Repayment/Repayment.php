<?php

namespace App\Models\API\Repayment;

use Illuminate\Database\Eloquent\Model;
use DB;
use Symfony\Component\HttpFoundation\Request;

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

        $userInfo = app('userData');

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
                    ->where("repayment.status",1)
                    ->where("repayment.user_id",$userInfo['id']);

                    //Filter
                    $query->whereBetween("repayment.payment_date",[$sort_data["start_date"],$sort_data["end_date"]]);

                    //Filter User
                    if(!empty($sort_data["payee"])){
                        $query->where("repayment.payee_id",$sort_data["payee"]);
                    }

                    $query->orderBy("repayment.payment_date","asc");


        $response = $query->get();
        return $response;
    }

    public static function emiAdd($insert_data){
        $response = DB::table("repayment_emi")->insert($insert_data);
        return $response;
    }

    public static function emiList($sort_data){
        $userInfo = app('userData');

        $response = DB::table("repayment_emi")
                        ->select(
                            "repayment_emi.id",
                            "repayment_emi.payee as payee_id",
                            "user.name as payee",

                            "repayment_emi.amount",
                            "repayment_emi.emi",
                            "repayment_emi.pr_fee",
                            "repayment_emi.paid",
                            "repayment_emi.duration",

                            "repayment_emi.payment_date",
                            "repayment_emi.distributed_date",

                            "bank.name as source",
                            "bank.id as source_id",

                            "repayment_emi.emi_status",
                            "repayment_emi.remarks"
                        )
                        ->where("repayment_emi.status",1)
                        ->join("user",'user.id', '=', 'repayment_emi.payee')
                        ->join("bank",'bank.id', '=', 'repayment_emi.source')
                        ->where("repayment_emi.user_id",$userInfo['id'])
                        ->where("repayment_emi.emi_status",$sort_data['status'])

                        //Filter by user
                        ->when($sort_data["payee"],fn($q)=>$q->where("repayment_emi.payee",$sort_data["payee"]))

                        //Filter by bank
                        ->when($sort_data["bank"],fn($q)=>$q->where("repayment_emi.source",$sort_data["bank"]))

                        ->get();

        return $response;
    }

    public static function updateEMIStatus($update_data,$id){

        $userInfo = app('userData');
        $response = DB::table("repayment_emi")
                        ->where("id",$id)
                        ->where("user_id",$userInfo['id'])
                        ->update($update_data);
        return $response;
    }

    public static function emiSchedule($id){
        $response = DB::table("repayment_emi_schedule")
                        ->select(
                            "repayment_emi_schedule.id",
                            "repayment_emi_schedule.principle",
                            "repayment_emi_schedule.amount",
                            "repayment_emi_schedule.payment_date",
                            "repayment_emi_schedule.payment_status",
                            "repayment_emi_schedule.emi_id",
                            "repayment_emi_schedule.remarks",
                        )
                        ->where("emi_id",$id)
                        ->where("status",1)
                        ->orderBy('repayment_emi_schedule.principle', 'DESC')
                        ->get();
        return $response;
    }

    public static function emiScheduleCheck($user_id){
        $response = DB::table("repayment_emi_schedule")
                        ->join('repayment_emi','repayment_emi.id','=','repayment_emi_schedule.emi_id')
                        ->where('repayment_emi.user_id',$user_id)
                        ->first();
        return $response;
    }

    public static function emiScheduleAdd($insert_data){
        $response = DB::table("repayment_emi_schedule")
                        ->insert($insert_data);
        return $response;
    }

    public static function emiScheduleUpdate($insert_data,$id){
        $response = DB::table("repayment_emi_schedule")
                        ->where("id",$id)
                        ->update($insert_data);
        return $response;
    }
}

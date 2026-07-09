<?php

namespace App\Http\Controllers\API\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\API\Test\Test;
class TestController extends Controller
{
    public function test(){
        $list = Test::expenseTransactionList(7);

        $data = array();
        foreach($list as $row){

            $paid_amount = "";
            $received_amount = "";
            if($row->to_user_id==1){
                $received_amount = $row->amount;
            }else{
                $paid_amount = $row->amount;
            }
            $data[] = array(
                "date"=>date('d-m-Y H:i A',strtotime($row->date)),
                "remarks"=>$row->remarks,
                "paid"=>$paid_amount,
                "received"=>$received_amount,
            );
        }
        return response($data,200);
    }
}

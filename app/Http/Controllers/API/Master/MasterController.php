<?php

namespace App\Http\Controllers\API\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\API\Master\Master;

class MasterController extends Controller
{
    public function bankList(Request $request){
        $bankList = Master::bankList();
        return response(["status"=>200,"msg"=>"Success","data"=>$bankList],200);
    }

    public function creditCardList(Request $request){
        $bankList = Master::creditCardList();
        return response(["status"=>200,"msg"=>"Success","data"=>$bankList],200);
    }

    public function yearList(Request $request){
        $data = array();
        $data[] = 2023;
        $data[] = 2024;
        $data[] = 2025;
        $data[] = 2026;
        return response(["status"=>200,"msg"=>"Success","data"=>$data],200);
    }

    public function paymentStatus(){

        $data = array();
        $data[] = array(
            "label"=>"Pending",
            "value"=>"PENDING"
        );

        $data[] = array(
            "label"=>"Received",
            "value"=>"RECEIVED"
        );

        $data[] = array(
            "label"=>"Partially Paid",
            "value"=>"PARTIALLY_PAID"
        );

        return response(["status"=>200,"msg"=>"Success","data"=>$data],200);
    }

    public function emiStatus(){
        $data = array();

        $data[] = array(
            "label"=>"Open",
            "value"=>"OPEN"
        );

        $data[] = array(
            "label"=>"Closed",
            "value"=>"CLOSED"
        );

        $data[] = array(
            "label"=>"Pre Closed",
            "value"=>"PRE_CLOSED"
        );

        return response(["status"=>200,"msg"=>"Success","data"=>$data],200);
    }


    public function emiPrincipleStatus(){
        $data = array();

        $data[] = array(
            "label"=>"Pending",
            "value"=>"PENDING"
        );

        $data[] = array(
            "label"=>"Paid",
            "value"=>"PAID"
        );

        return response(["status"=>200,"msg"=>"Success","data"=>$data],200);
    }


}

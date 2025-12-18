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

    public function yearList(Request $request){
        $data = array();
        $data[] = 2023;
        $data[] = 2024;
        $data[] = 2025;
        return response(["status"=>200,"msg"=>"Success","data"=>$data],200);
    }
}

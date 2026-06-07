<?php

namespace App\Http\Controllers\API\Dashboard;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\API\CreditCard\CreditCard;
use App\Http\Controllers\API\CreditCard\CreditCardController;
use App\Models\API\Master\Master;
use App\Models\API\Repayment\Repayment;

class DashboardController extends Controller
{
    public function summary(Request $request){

        $data = array();
        $data["credit_summary"] = self::creditCardSummary();
        $data["repayment_summary"] = self::repaymentSummary();
        $data["emi_summary"] = self::emiSummary();
        $data["account_summary"] = self::accountSummary();

        return response(["msg"=>"Success","data"=>$data],200);
    }

    public function creditCardSummary(){
        $creditCardPaymentSummary = CreditCardController::creditCardBillSummary();
        return $creditCardPaymentSummary;
    }

    public function repaymentSummary(){

        $sort_data = array();
        // This month last date
        $sort_data["start_date"] = Carbon::now()->subMonthNoOverflow()->startOfMonth()->toDateString();
        // Last month first date
        $sort_data["end_date"] = Carbon::now()->endOfMonth()->addDay()->toDateString();

        $repaymentTodayAndTomorrow = Repayment::repaymentByDate($sort_data);

        $current_date = date('Y-m-d');
        $tomorrow_date = date('Y-m-d',strtotime('+ 1 Day'));
        $today = array("total"=>0,"received"=>0,"pending"=>0,"partially"=>0);
        $tomorrow = array("total"=>0,"received"=>0,"pending"=>0,"partially"=>0);
        $this_month = array("total"=>0,"received"=>0,"pending"=>0,"partially"=>0);
        $last_month = array("total"=>0,"received"=>0,"pending"=>0,"partially"=>0);

        $carbon_now = Carbon::now();
        $carbon_last_month = Carbon::now()->subMonthNoOverflow();

        foreach ($repaymentTodayAndTomorrow as $repaymentTodayAndTomorrow_row) {

            #Today
            if($repaymentTodayAndTomorrow_row->payment_date==$current_date){

                $today["total"]+=1;
                switch ($repaymentTodayAndTomorrow_row->payment_status) {
                    case 'RECEIVED':
                        $today["received"]+=1;
                        break;

                    case 'PARTIALLY_PAID':
                        $today["partially"]+=1;
                    default:
                        $today["pending"]+=1;
                        break;
                }

            }

            #Tomorrow
            if($repaymentTodayAndTomorrow_row->payment_date==$tomorrow_date){
                $tomorrow[] = $repaymentTodayAndTomorrow_row;
                $tomorrow["total"]+=1;
                switch ($repaymentTodayAndTomorrow_row->payment_status) {
                    case 'RECEIVED':
                        $tomorrow["received"]+=1;
                        break;

                    case 'PARTIALLY_PAID':
                        $tomorrow["partially"]+=1;
                    default:
                        $tomorrow["pending"]+=1;
                        break;
                }
            }

            #This Month
            $record_date = Carbon::parse($repaymentTodayAndTomorrow_row->payment_date);
            if($record_date->isSameMonth($carbon_now)){

                $this_month["total"] +=1;
                switch ($repaymentTodayAndTomorrow_row->payment_status) {
                    case 'RECEIVED':
                        $this_month["received"]+=1;
                        break;

                    case 'PARTIALLY_PAID':
                        $this_month["partially"]+=1;
                    default:
                        $this_month["pending"]+=1;
                        break;
                }

            }

            #Last Month
            if($record_date->isSameMonth($carbon_last_month))
            {
                $last_month["total"] +=1;
                switch ($repaymentTodayAndTomorrow_row->payment_status) {
                    case 'RECEIVED':
                        $last_month["received"]+=1;
                        break;

                    case 'PARTIALLY_PAID':
                        $last_month["partially"]+=1;
                    default:
                        $last_month["pending"]+=1;
                        break;
                }
            }

        }

        #Save to Data
        $data = array(
            "today"=>array(
                "total"=>$today["total"],
                "received"=>$today["received"],
                "pending"=>$today["pending"],
                "partially"=>$today["partially"]
            ),
            "tomorrow"=>array(
                "total"=>$tomorrow["total"],
                "received"=>$tomorrow["received"],
                "pending"=>$tomorrow["pending"],
                "partially"=>$tomorrow["partially"]
            ),
            "this_month"=>array(
                "total"=>$this_month["total"],
                "received"=>$this_month["received"],
                "pending"=>$this_month["pending"],
                "partially"=>$this_month["partially"]
            ),
            "last_month"=>array(
                "total"=>$last_month["total"],
                "received"=>$last_month["received"],
                "pending"=>$last_month["pending"],
                "partially"=>$last_month["partially"]
            )
        );

        return $data;
    }

    public function emiSummary(){

        $sort_data = array();
        // This month last date
        $sort_data["start_date"] = Carbon::now()->subMonthNoOverflow()->startOfMonth()->toDateString();
        // Last month first date
        $sort_data["end_date"] = Carbon::now()->endOfMonth()->toDateString();

        $response = Repayment::repaymentEMIByDate($sort_data);

        $data = array(
            "this_month"=>array(
                "opened"=>0,
                "closed"=>0,
                "pre_closed"=>0
            ),
            "last_month"=>array(
                "opened"=>0,
                "closed"=>0,
                "pre_closed"=>0
            )
        );

        $carbon_now = Carbon::now();
        $carbon_last_month = Carbon::now()->subMonthNoOverflow();

        #Opened
        foreach ($response["opened"] as $opened) {
            #This Month
            $record_date = Carbon::parse($opened->distributed_date);
            if($record_date->isSameMonth($carbon_now)){
                $data["this_month"]["opened"] +=1;
            }

            #Last Month
            $record_date = Carbon::parse($opened->distributed_date);
            if($record_date->isSameMonth($carbon_last_month)){
                $data["last_month"]["opened"] +=1;
            }
        }

        #Closed
        foreach ($response["closed"] as $closed) {
            #This Month
            $record_date = Carbon::parse($closed->status_change_date);
            if($record_date->isSameMonth($carbon_now)){

                switch ($closed->emi_status) {
                    case 'CLOSED':
                        $data["this_month"]["closed"] +=1;
                        break;

                    case 'PRE_CLOSED':
                        $data["this_month"]["pre_closed"] +=1;
                        break;
                    default:
                        # code...
                        break;
                }

            }

            #Last Month
            $record_date = Carbon::parse($closed->status_change_date);
            if($record_date->isSameMonth($carbon_last_month)){

                switch ($closed->emi_status) {
                    case 'CLOSED':
                        $data["last_month"]["closed"] +=1;
                        break;

                    case 'PRE_CLOSED':
                        $data["last_month"]["pre_closed"] +=1;
                        break;
                    default:
                        # code...
                        break;
                }

            }
        }

        return $data;

    }

    public function accountSummary(){

        $sync_date = date('Y-m-d');
        if (now()->lt(now()->copy()->setTime(6, 0))) {
            // current time is less than 06:00 AM
            $sync_date = date('Y-m-d',strtotime('-1 Day'));
        }

        //Last Month
        $last_month_date = date('Y-m-15',strtotime('-1 Month'));
        // $last_month_date = "2026-04-23"; //remove after may 15
        $summary = Repayment::savedAccountSummary($sync_date,$last_month_date);

        $accounts = array();
        foreach($summary as $summary_row){

            $last_month = true;
            if($summary_row->sync_date == $sync_date){
                $last_month = false;
            }

            if(empty(@$accounts["B".$summary_row->id])){
                //New
                $accounts["B".$summary_row->id] = array(
                    "id"=>$summary_row->id,
                    "name"=>$summary_row->name,
                    "balance"=>$last_month?0:$summary_row->balance,
                    "last_month_balance"=>$last_month?$summary_row->balance:0,
                );

            }
            else{
                //Already Exist
                if($last_month){$accounts["B".$summary_row->id]["last_month_balance"] = $summary_row->balance;}
                else
                { $accounts["B".$summary_row->id]["balance"] = $summary_row->balance;}
            }

        }

        $index = count($summary)-1;
        $last_sync = empty(@$summary[$index]->date)?Null:@$summary[$index]->date;

        $summaryList = array(
            "last_sync"=>$last_sync,
            "accounts"=>array_values($accounts)
        );
        return $summaryList;
    }

}

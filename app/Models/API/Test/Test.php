<?php

namespace App\Models\API\Test;

use Illuminate\Database\Eloquent\Model;
use DB;

class Test extends Model
{
    public static function expenseTransactionList($friend){

        //$userInfo = app('userData');
        $userInfo = array();
        $userInfo["id"] = 1; // Hardcoded for testing
        $query = DB::table("splitwise_transactions")

                    ->join('user as from_user', 'from_user.id', '=', 'splitwise_transactions.from_user')
                    ->join('user as to_user', 'to_user.id', '=', 'splitwise_transactions.to_user')

                    ->select(
                        'to_user.id as to_user_id',
                        'to_user.name as to_user_name',

                        'from_user.id as from_user_id',
                        'from_user.name as from_user_name',

                        'splitwise_transactions.id',
                        'splitwise_transactions.date',
                        'splitwise_transactions.amount',
                        'splitwise_transactions.remarks',

                        'splitwise_transactions.payment_mode',
                        'splitwise_transactions.user_id',
                    );

                    //$query->where('splitwise_transactions.from_user', $friend);
                    $query->where(function ($q) use ($friend, $userInfo) {
                        $q->where(function ($q) use ($friend, $userInfo) {
                                $q->where('splitwise_transactions.from_user', $friend)
                                ->where('splitwise_transactions.to_user', $userInfo['id']);
                            })
                        ->orWhere(function ($q) use ($friend, $userInfo) {
                            $q->where('splitwise_transactions.from_user', $userInfo['id'])
                            ->where('splitwise_transactions.to_user', $friend);
                        });
                    });

        $response = $query
                    //->limit(50)
                    ->orderBy('splitwise_transactions.date', 'desc')
                    ->get();
        return $response;
    }
}

<?php

namespace App\Http\Controllers\API\Cron;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\Repayment\RepaymentController;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CronController extends Controller
{
    public function run(){

        //Run Account Summary at 06:00 AM & 6:00 PM
        $current_time = now()->format('H:i');
        //Log::info("CRON TIME: " . $current_time);
        if ($current_time === '05:30'|| $current_time === '18:30') {
            RepaymentController::accountSummary();
        }

        return response(["msg"=>"Success","data"=>$current_time],200);
    }

    public function databaseBackup(){

        // $database = env('DB_DATABASE');
        // $username = env('DB_USERNAME');
        // $password = env('DB_PASSWORD');
        // $host     = env('DB_HOST');
        // $port     = env('DB_PORT', 3306);

        // $fileName = 'backup-' . date('Y-m-d-H-i-s') . '.sql';

        // $command = sprintf(
        //     'mysqldump --user=%s --password=%s --host=%s --port=%s %s',
        //     \escapeshellarg($username),
        //     \escapeshellarg($password),
        //     \escapeshellarg($host),
        //     \escapeshellarg($port),
        //     \escapeshellarg($database)
        // );

        // return new StreamedResponse(function () use ($command) {
        //     passthru($command);
        // }, 200, [
        //     'Content-Type' => 'application/sql',
        //     'Content-Disposition' => "attachment; filename=\"$fileName\"",
        // ]);

        $database = env('DB_DATABASE');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $host     = env('DB_HOST');
        $port     = env('DB_PORT', 3306);

        $fileName = 'backup-' . date('Y-m-d-H-i-s') . '.sql';
        $filePath = storage_path('app/' . $fileName);

        $command = "mysqldump --user={$username} --password={$password} --host={$host} --port={$port} {$database} > {$filePath}";

        exec($command, $output, $result);

        if ($result !== 0) {
            return response()->json([
                'status' => false,
                'message' => 'Database backup failed'
            ]);
        }

        return response()->download($filePath)->deleteFileAfterSend(true);

    }

}

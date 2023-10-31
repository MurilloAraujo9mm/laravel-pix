<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */

     protected function schedule(Schedule $schedule)
     {
         $schedule->call(function () {
     
             $headers = [
                 'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0L2FwaS92MS9sb2dpbiIsImlhdCI6MTY5ODU5ODUxMiwiZXhwIjoxNjk4NjAyMTEyLCJuYmYiOjE2OTg1OTg1MTIsImp0aSI6IjA3T3c0empBUmdwU2FrWmMiLCJzdWIiOiIxOSIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.mfXzhssW2uKW6qv1UVfpESMFTg_2A5LZ4Z7YkMLikpQ'
             ];
     
             $response = Http::withHeaders($headers)->get('http://localhost:80/api/v1/check-queue');
             $responseArray = json_decode($response->body(), true);  
     
             if (isset($responseArray['message']) && $responseArray['message'] === 'Há mensagens na fila') {
                 $data = $responseArray['next_message_content'];                
                 DB::table('logs_transactions')->insert([
                     'amount' => number_format($data['amount'], 2, '.',''),
                     'key_pix' => $data['key_pix'],
                     'transaction_date' => date('Y-m-d H:i:s', strtotime($data['transaction_date'])),
                     'description' => $data['transaction_type'] ?? 'Unknown',
                     'status' => 'pending',
                     'created_at' => date('Y-m-d H:i:s', strtotime($data['transaction_date'])),
                     'updated_at' => date('Y-m-d H:i:s', strtotime($data['transaction_date'])),
                 ]);
             }
             
         })->everyMinute(); 
     }
     

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

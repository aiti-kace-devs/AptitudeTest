<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $phone;
    protected $message;

    public function __construct($phone, $message)
    {
        $this->phone = $phone;
        $this->message = $message;
    }


    
    public function handle()
    {
        \Log::info('Send SMS job has started.');

        try {
            $apiKey = env('ARKESEL_SMS_API_KEY');
    
            $sender = substr(env('SMS_SENDER_NAME', '1M-CODERS'), 0, 11);
    
            $response = Http::withHeaders([
                'api-key' => $apiKey
            ])->post('https://sms.arkesel.com/api/v2/sms/send', [
                'sender' => $sender,
                'message' => $this->message,
                'recipients' => [$this->phone]
            ]);
    
            \Log::info('SMS Response', ['response' => $response->json()]);
            return $response->json();
        } catch (\Exception $e) {
            \Log::error('SMS Job Failed', ['error' => $e->getMessage()]);
        }
    }
    
    
    

}



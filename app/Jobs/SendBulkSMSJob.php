<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SendBulkSMSJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $message;
    public array $recipients;

    /**
     * Create a new job instance.
     */
    public function __construct(string $message, array $recipients)
    {
        $this->message = $message;
        $this->recipients = $recipients;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $hasPlaceholders = Str::containsAll($this->message, ['{', '}']);

        // Fetch users by their phone numbers
        $users = User::whereIn('mobile_no', $this->recipients)->get();

        foreach ($users as $user) {
            $smsMessage = $hasPlaceholders
                ? $this->replacePlaceholders($this->message, $user->toArray())
                : $this->message;
            
            $this->sendSMS($user->mobile_no, $smsMessage);
        }

    }

    /**
     * Replace placeholders in message using user attributes.
     */
    private function replacePlaceholders(string $template, array $data): string
    {
        foreach ($data as $key => $value) {
            $template = str_replace('{' . $key . '}', $value ?? '', $template);
        }
        return $template;
    }

    /**
     * Send SMS via Arkesel API.
     */
    private function sendSMS(string $phone, string $message): void
    {
        try {
            $apiKey = env('ARKESEL_SMS_API_KEY');
            $sender = substr(env('SMS_SENDER_NAME', 'ApTest'), 0, 11);

            $response = Http::withHeaders([
                'api-key' => $apiKey,
            ])->post('https://sms.arkesel.com/api/v2/sms/send', [
                'sender' => $sender,
                'message' => $message,
                'recipients' => [$phone],
            ]);

            Log::info("SMS sent to {$phone}", [
                'response' => $response->json()
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to send SMS to {$phone}", [
                'error' => $e->getMessage(),
            ]);
        }
    }
}

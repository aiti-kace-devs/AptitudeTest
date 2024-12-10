<?php

namespace App\Jobs;

use App\Mail\ExamLoginCredentials;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendExamLoginCredentialsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $std;
    public $plainPassword;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    public function __construct(User $std, string $plainPassword)
    {
        $this->std = $std;
        $this->plainPassword = $plainPassword;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $deadline = (new Carbon($this->std->created_at))->addDays(2);
        Mail::to($this->std->email)->send(new ExamLoginCredentials($this->std, $this->plainPassword, $deadline));

        //     Log::info('Handling the job, sending email to ' . $this->std->email);

        // Mail::to($this->std->email)->send(new ExamLoginCredentials($this->std, $this->plainPassword));

        // Log::info('Email sent to ' . $this->std->email);


    }
}

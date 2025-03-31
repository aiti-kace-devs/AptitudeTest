<?php

namespace App\Jobs;

use App\Mail\ConfirmationSuccessful;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\UserAdmission;
use App\Models\User;
use App\Models\CourseSession;
use App\Helpers\GoogleSheets;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;


class AdmitStudentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    public $admission;

    public function __construct(UserAdmission $admission)
    {
        $this->admission = $admission;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $user = User::where('userId', $this->admission->user_id)->first();

        $session = CourseSession::findOrFail($this->admission->session);

        if (!$user || !$session) {
            return;
        }
        Mail::to($user->email)->bcc(env('MAIL_FROM_ADDRESS', 'no-reply@gi-kace.gov.gh'))->queue(new ConfirmationSuccessful($user->name, $session->name, $session->course_time));
        // GoogleSheets::updateGoogleSheets($this->admission->user_id, [
        //     "confirmed" => true,
        //     "session" => $session->session,
        // ]);
    }
}

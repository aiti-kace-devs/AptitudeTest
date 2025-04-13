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
use App\Helpers\SmsHelper;
use App\Models\Course;
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

    public $admission, $course, $session, $student;


    public function __construct(UserAdmission $admission)
    {
        $this->admission = $admission;
        $this->student = User::where('userId', $this->admission->user_id)->first();
        $this->course = Course::findOrFail($this->admission->course_id);
        $this->session = CourseSession::findOrFail($this->admission->session);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        if (!$this->student || !$this->course || !$this->session) {
            return;
        }

        $this->sendConfirmationEmail();
    }

    private function sendConfirmationEmail()
    {
        Mail::to($this->student->email)->bcc(env('MAIL_FROM_ADDRESS', 'no-reply@example.com'))
            ->send(new ConfirmationSuccessful(
                $this->student->name,
                $this->session
            ));

        $smsContent = SmsHelper::getTemplate(AFTER_ADMISSION_CONFIRMATION_SMS, [
            'name' => $this->student->name,
            'course' => $this->course->programme->title,
        ]) ?? '';;
        $details['message'] = $smsContent;
        $details['phonenumber'] = $this->student->mobile_no;

        SendSMSAfterRegistrationJob::dispatch($details);
    }
}

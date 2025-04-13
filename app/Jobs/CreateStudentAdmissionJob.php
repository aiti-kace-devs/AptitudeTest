<?php

namespace App\Jobs;

use App\Helpers\SmsHelper;
use App\Mail\StudentAdmitted;
use App\Models\Course;
use App\Models\User;
use App\Models\UserAdmission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class CreateStudentAdmissionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public User $student)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (!$this->student) return;

        $course = Course::find($this->student->registered_course);
        if (!$course) return;

        $existingAdmission = UserAdmission::where('user_id', $this->student->userId)->first();
        if ($existingAdmission) {
            if (!$existingAdmission->email_sent) {
                $this->sendAdmissionEmail();
                $existingAdmission->update(['email_sent' => now()]);
            }
            return;
        }

        UserAdmission::create([
            'user_id' => $this->student->userId,
            'course_id' => $course->id,
            'email_sent' => now(),
        ]);

        $this->sendAdmissionEmail();
    }


    private function sendAdmissionEmail()
    {
        Mail::to($this->student->email)->bcc(env('MAIL_FROM_ADDRESS', 'no-reply@example.com'))
            ->send(new StudentAdmitted(
                $this->student
            ));

        $smsContent = SmsHelper::getTemplate(AFTER_ADMISSION_SMS, [
            'name' => $this->student->name,
        ]) ?? '';;
        $details['message'] = $smsContent;
        $details['phonenumber'] = $this->student->mobile_no;

        SendSMSAfterRegistrationJob::dispatch($details);
    }
}

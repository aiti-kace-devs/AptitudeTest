<?php

namespace App\Jobs;

use App\Events\UserRegistered;
use App\Helpers\GoogleSheets;
use App\Models\Oex_exam_master;
use App\Models\User;
use App\Models\user_exam;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


class AddNewStudentsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $students;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($students)
    {
        $this->students = $students;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $errors = [];

        foreach ($this->students as $student) {
            $validator = Validator::make($student, [
                'name' => 'required',
                'email' => 'required|email',
                'mobile_no' => 'required',
                'gender' => 'required',
                'exam' => 'required_if:exam_name,null|exists:oex_exam_masters,id',
                'registered_course' => 'required|exists:courses,id',
                'userId' => 'required',
                'password' => 'sometimes',
                'exam_name' => 'sometimes',
            ]);

            if ($validator->fails()) {
                $errors[] = "Error creating student with email " . $student['email'];
                $errors[] = $validator->errors()->all();
                break;
            }

            $plainPassword = $student['password'] ?? str()->random(8);

            // Exam validation
            if (!empty($student['exam_name'])) {
                $exam = null;
                if (strtolower($student['exam_name']) == 'random') {
                    $exam = Oex_exam_master::inRandomOrder()->first();
                } else {
                    $exam = Oex_exam_master::where('title', $student['exam_name'])->first();
                }
                if ($exam == null) {
                    abort(422, 'Exam not found');
                }
                $student['exam'] = $exam->id;
            }

            // Check for existing user
            $existingUser = User::where('email', $student['email'])->first();
            $std = null;

            if ($existingUser == null) {
                // Create a new student
                $std = new User();
                $std->name = $student['name'];
                $std->email = $student['email'];
                $std->mobile_no = $student['mobile_no'];
                $std->exam = $student['exam'];
                $std->userId = $student['userId'];
                $std->password = Hash::make($plainPassword);
                $std->registered_course = $student['registered_course'];
                $std->gender = $student['gender'];
                $std->status = 1;
                $std->save();
            }

            // Create or update user_exam
            user_exam::firstOrCreate(
                [
                    'user_id' => $existingUser ? $existingUser->id : $std->id,
                    'exam_id' => $student['exam'],
                ],
                [
                    'user_id' => $existingUser ? $existingUser->id : $std->id,
                    'exam_id' => $student['exam'],
                    'std_status' => 1,
                    'exam_joined' => 0,
                ]
            );

            if ($existingUser == null) {
                // $userId = $std->userId;
                // GoogleSheets::updateGoogleSheets($userId, ["registered" => true, "result" => "N/A"]);
                event(new UserRegistered($std, $plainPassword));
                $details['message'] = "Hi " . $student['name'] . ", \nYour information has been successfully registered. Check your email for instructions on how to take the aptitude test. Goodluck";
                $details['phonenumber'] = $student['mobile_no'];

                SendSMSAfterRegistrationJob::dispatch($details);
            }
        }

        if (!empty($errors)) {
            Log::error($errors);
        }
    }
}

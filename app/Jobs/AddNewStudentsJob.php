<?php

namespace App\Jobs;

use App\Events\UserRegistered;
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
                'exam' => 'required_if:exam_name,null|exists:oex_exam_masters,id',
                'password' => 'sometimes',
                'exam_name' => 'sometimes',
            ]);

            if ($validator->fails()) {
                $errors[] = ['status' => 'false', 'message' => $validator->errors()->all()];
            }

            $plainPassword = $student['password'] ?? str()->random(8);

            // Exam validation
            if (!empty($student['exam_name'])) {
                $exam = Oex_exam_master::where('title', $student['exam_name'])->first();
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
                $std->password = Hash::make($plainPassword);
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

            // Trigger event for newly registered users
            if ($existingUser == null) {
                event(new UserRegistered($std, $plainPassword));

                // Call any additional methods like updating Google Sheets
                $this->updateGoogleSheets('d027038b-3a87-4f3f-be8c-9002851e8880', [false]);
            }
        }

        if (!empty($errors)) {
            // Handle errors, possibly log or notify the admin
            // Log errors or do something meaningful here
        }
    }

    protected function updateGoogleSheets($sheetId, array $data)
    {
        // Implement your logic to update Google Sheets here
    }
}

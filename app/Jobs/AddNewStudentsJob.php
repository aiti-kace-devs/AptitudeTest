<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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
        $successMessages = [];

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
                $errors[] = [
                    'status' => 'false',
                    'message' => $validator->errors()->all(),
                ];
                continue; // Skip to the next student if validation fails
            }

            $plainPassword = !empty($student['password']) ? $student['password'] : str()->random(8);

            // Check and retrieve exam ID if exam_name is provided
            if (!empty($student['exam_name'])) {
                $exam = Oex_exam_master::where('title', $student['exam_name'])->first();
                if ($exam == null) {
                    abort(422, 'Exam not found for student: ' . $student['name']);
                }
                $student['exam'] = $exam->id;
            }

            // Check for existing user
            $existingUser = User::where('email', $student['email'])->first();
            $std = null;

            if ($existingUser == null) {
                // Create new user
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
                    'std_status' => 1,
                    'exam_joined' => 0,
                ]
            );

            // Fire the registration event for new users
            if ($existingUser == null) {
                event(new UserRegistered($std, $plainPassword));
            }

            $successMessages[] = [
                'status' => 'true',
                'message' => 'Student ' . $student['name'] . ' added successfully',
            ];
        }
    }
}

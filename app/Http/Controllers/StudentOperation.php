<?php

namespace App\Http\Controllers;

use App\Mail\StudentAdmitted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Oex_student;
use App\Models\Oex_exam_master;
use App\Models\Oex_question_master;
use App\Models\Oex_result;
use App\Models\User;
use App\Models\CourseSession;
use App\Models\Course;
use App\Models\UserAdmission;
use App\Models\user_exam;
use Illuminate\Support\Carbon;
use App\Helpers\GoogleSheets;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Jobs\AdmitStudentJob;

class StudentOperation extends Controller
{
    //student dashboard
    public function dashboard()
    {
        $data['portal_exams'] = user_exam::select(['user_exams.*', 'users.name', 'oex_exam_masters.*', 'oex_categories.name as category_name'])
            ->selectRaw('(SELECT count(id) from oex_question_masters where exam_id = oex_exam_masters.id) as question_count', [])
            ->join('users', 'users.id', '=', 'user_exams.user_id')
            ->join('oex_exam_masters', 'user_exams.exam_id', '=', 'oex_exam_masters.id')
            ->orderBy('user_exams.exam_id', 'desc')
            ->join('oex_categories', 'oex_exam_masters.category', '=', 'oex_categories.id')
            ->where('user_exams.user_id', Session::get('id'))
            ->where('user_exams.std_status', '1')
            ->get()
            ->toArray();

        // $data['portal_exams'] = Oex_exam_master::select(['oex_exam_masters.*', 'oex_categories.name as cat_name'])
        //     ->join('oex_categories', 'oex_exam_masters.category', '=', 'oex_categories.id')
        //     ->orderBy('id', 'desc')->where('oex_exam_masters.status', '1')->get()->toArray();
        return view('student.dashboard', $data);
    }

    //Exam page
    public function exam()
    {
        $student_info = user_exam::select(['user_exams.*', 'users.name', 'oex_exam_masters.title', 'oex_exam_masters.exam_date', 'users.created_at as registered'])
            ->join('users', 'users.id', '=', 'user_exams.user_id')
            ->join('oex_exam_masters', 'user_exams.exam_id', '=', 'oex_exam_masters.id')
            ->orderBy('user_exams.exam_id', 'desc')
            ->where('user_exams.user_id', Session::get('id'))
            ->where('user_exams.std_status', '1')
            ->get()
            ->toArray();


        return view('student.exam', ['student_info' => $student_info]);
    }

    //join exam page
    public function join_exam($id)
    {
        $questionSets = Oex_question_master::select('exam_id')
            ->distinct()
            ->pluck('exam_id');
        $randomExamId = $questionSets->random();
        $question = Oex_question_master::where('exam_id', $randomExamId)
            ->inRandomOrder()
            ->get();

        // $question = Oex_question_master::where('exam_id', $id)->inRandomOrder()->get();
        $user_exam = user_exam::where('exam_id', $id)->where('user_id', Session::get('id'))->get()->first();

        if ($user_exam && $user_exam->submitted) {
            return redirect(url('student/exam'))->with([
                'flash' => 'Unable to take exam. Test already submitted',
                'key' => 'error',
            ]);
        }

        $exam = Oex_exam_master::where('id', $id)->get()->first();
        $now = Carbon::now();

        if ($now->isAfter(new Carbon($exam->exam_date))) {
            return redirect(url('student/exam'))->with([
                'flash' => 'Unable to take exam. Exam deadline was ' . $exam->exam_date,
                'key' => 'error',
            ]);
        }

        // 48 hours to finish exam
        $userCreatedAt = new Carbon(Auth::user()->created_at);
        $userCreatedAtPlusTwoDays = $userCreatedAt->addDays(2);


        if ($userCreatedAtPlusTwoDays->isBefore($now)) {
            return redirect(url('student/exam'))->with([
                'flash' => 'Unable to take exam. Time to take exams has elapsed',
                'key' => 'error',
            ]);
        }


        $usedTime = 0;
        if ($user_exam && $user_exam->started) {
            $start = new Carbon($user_exam->started);

            $usedTime = $now->diffInMinutes($start);
        }
        if ($usedTime > $exam->exam_duration) {
            return redirect(url('student/exam'))->with([
                'flash' => 'Unable to take exam. Exam duration time has elapsed. ' . $usedTime . ' mins has passed since user started exams.',
                'key' => 'error',
            ]);
        }
        // dd($question->pluck("id"));
        return view('student.join_exam', ['question' => $question, 'exam' => $exam, 'usedTime' => $usedTime]);
    }

    // start exam
    public function start_exam($id)
    {
        $user_exam = user_exam::where('exam_id', $id)->where('user_id', Session::get('id'))->get()->first();
        $arr = ['status' => 'true', 'message' => 'started successfully'];
        if (!$user_exam->started) {
            user_exam::updateOrCreate(
                [
                    'user_id' => Session::get('id'),
                    'exam_id' => $id,
                ],
                ['started' => Carbon::now()->toDateTimeString()],
            );
        }

        return json_encode($arr);
    }

    //On submit
    public function submit_questions(Request $request)
    {
        $std_info = user_exam::where('user_id', Session::get('id'))
            ->where('exam_id', $request->exam_id)
            ->get()
            ->first();

        if ($std_info && $std_info->submitted) {
            $res = Oex_result::where('exam_id', $request->exam_id)
                ->where('user_id', Session::get('id'))
                ->get()
                ->first();
            $yes_ans = $res->yes_ans;
            $total = $res->yes_ans + $res->no_ans;
            $percentage = round(($yes_ans / $total) * 100);

            return redirect(url('student/exam'))->with([
                'flash' => "Test already submitted on this exam. Submission Date: {$std_info->submitted} .Result: {$percentage}% ({$yes_ans}/{$total})",
                'key' => 'info',
            ]);
        }

        $yes_ans = 0;
        $no_ans = 0;
        $data = $request->all();
        $result = [];
        for ($i = 1; $i <= $request->index; $i++) {
            if (isset($data['question' . $i])) {
                $q = Oex_question_master::where('id', $data['question' . $i])
                    ->get()
                    ->first();

                if ($q->ans == $data['ans' . $i]) {
                    $result[$data['question' . $i]] = 'YES';
                    $yes_ans++;
                } else {
                    $result[$data['question' . $i]] = 'NO';
                    $no_ans++;
                }
            }
        }

        $std_info->exam_joined = 1;
        $std_info->submitted = Carbon::now()->toDateTimeString();
        $std_info->update();

        $user = User::where('id', Session::get('id'))->first();
        $userId = $user->userId;

        $res = new Oex_result();
        $res->exam_id = $request->exam_id;
        $res->user_id = Session::get('id');
        $res->yes_ans = $yes_ans;
        $res->no_ans = $no_ans;
        $res->result_json = json_encode($result);
        $total = $yes_ans + $no_ans;
        $percentage = round(($yes_ans / $total) * 100);
        echo $res->save();
        $storedResult = Oex_result::where('user_id', $user->id)->where('exam_id', $request->exam_id)->first();
        GoogleSheets::updateGoogleSheets($userId, ["result" => $storedResult->yes_ans]);

        return redirect(url('student/exam'))->with([
            'flash' => "Test submitted successfully. Result: {$percentage}%  {$yes_ans}/{$total}",
            'key' => 'success',
        ]);
    }

    //Applying for exam
    public function apply_exam($id)
    {
        $checkuser = user_exam::where('user_id', Session::get('id'))->where('exam_id', $id)->get()->first();

        if ($checkuser) {
            $arr = ['status' => 'false', 'message' => 'Already applied, see your exam section'];
        } else {
            $exam_user = new user_exam();

            $exam_user->user_id = Session::get('id');
            $exam_user->exam_id = $id;
            $exam_user->std_status = 1;
            $exam_user->exam_joined = 0;

            $exam_user->save();

            $arr = ['status' => 'true', 'message' => 'applied successfully', 'reload' => url('student/dashboard')];
        }

        echo json_encode($arr);
    }

    //View Result
    public function view_result($id)
    {
        $data['result_info'] = Oex_result::where('exam_id', $id)->where('user_id', Session::get('id'))->get()->first();

        $data['student_info'] = User::where('id', Session::get('id'))->get()->first();

        $data['exam_info'] = Oex_exam_master::where('id', $id)->get()->first();

        return view('student.view_result', $data);
    }

    //View answer
    public function view_answer($id)
    {
        $data['question'] = Oex_question_master::where('exam_id', $id)->get()->toArray();

        return view('student.view_amswer', $data);
    }

    public function reset_exam($exam_id, $user_id)
    {

        $user = User::findOrFail($user_id);
        $user->created_at = Carbon::now()->toDateTimeString();
        $user->updated_at = Carbon::now()->toDateTimeString();
        $user->save();

        user_exam::updateOrCreate(
            [
                'user_id' => $user_id,
                'exam_id' => $exam_id,
            ],
            ['started' => null, 'submitted' => null, 'exam_joined' => 0, 'std_status' => 1],
        );

        Oex_result::where('user_id', $user_id)->where('exam_id', $exam_id)->delete();

        return redirect(url('admin/manage_students'))->with([
            'flash' => 'Exam reset successfully',
            'key' => 'success',
        ]);
    }

    public function select_session_view($user_id)
    {
        $admission = UserAdmission::where('user_id', $user_id)->first();
        if ($admission->confirmed) {
            return view('student.session-select.index', [
                "confirmed" => true,
                "session" => CourseSession::where('id', $admission->session)->first(),
            ]);
        }
        $courseDetails = Course::find($admission->course_id);
        $sessions = CourseSession::where('course_id', $courseDetails->id)->get();
        // $sessions->each(function($s){
        //     $used = UserAdmission::where('session', $s->id)->whereNotNull('confirmed')->count();

        // });
        $user = User::select('id', 'name', 'userId')->where('userId', $user_id)->first();
        return view('student.session-select.index', [
            "user" => $user,
            "sessions" => $sessions,
            "course" => $courseDetails,
            "confirmed" => false
        ]);
    }

    public function confirm_session($user_id, Request $request)
    {

        try {
            $data = $request->validate([
                'session_id' => 'required|exists:course_sessions,id'
            ]);

            $admission = UserAdmission::where('user_id', $user_id)->first();
            $courseDetails = Course::find($admission->course_id);
            $session = CourseSession::where('course_id', $courseDetails->id)
                ->where('id', $data['session_id'])
                ->first();

            if (!$session) {
                return redirect(url('student/select-session' . $data['user_id']))->with([
                    'flash' => 'Unable to confirm session. Try again later',
                    'key' => 'error',
                ]);
            }

            $slotLeft = $session->slotLeft();

            if ($slotLeft < 1) {
                return redirect(url('student/select-session' . $data['user_id']))->with([
                    'flash' => 'Unable to confirm session. No slots available',
                    'key' => 'error',
                ]);
            }

            $admission->confirmed = now();
            $admission->session = $session->id;
            $admission->email_sent = now();
            $admission->location = $courseDetails->location;
            $admission->save();

            AdmitStudentJob::dispatch($admission);
            return redirect(url('student/select-session/' . $user_id))->with([
                'flash' => 'Confirmation successful',
                'key' => 'success',
            ]);
        } catch (\Exception $e) {
            return redirect(url('student/select-session/' . $user_id))->with([
                'flash' => 'Unable to confirm session. No slots available. Refresh page and try again later',
                'key' => 'error',
            ]);
        }
    }

    public function admit_student(Request $request)
    {

        try {
            $students = $request->get('students');
            if ($students) {
                foreach ($students as $student) {
                    $course_name = $student['course'] ?? null;
                    $location = $student['location'] ?? null;
                    $user_id = $student['user_id'] ?? null;

                    if (!$course_name || !$location || !$user_id) {
                        break;
                    }
                    $course = Course::where('course_name', $course_name)->where('location', $location)->first();
                    if (!$course) {
                        break;
                    }

                    $oldAdmission = UserAdmission::where('user_id', $user_id)->first();
                    $user = User::where('userId', $user_id)->first();
                    $url = url('student/select-session/' . $user_id);

                    if ($oldAdmission && !$oldAdmission->email_sent) {
                        Mail::to($user->email)->queue(new StudentAdmitted(name: $user->name, course: $course_name, location: $location, url: $url));
                        $oldAdmission->email_sent = now();
                        $oldAdmission->save();
                        break;
                    }


                    $admission = new UserAdmission();
                    $admission->user_id = $user_id;
                    $admission->course_id = $course->id;
                    $admission->email_sent = now();
                    $admission->save();

                    Mail::to($user->email)->queue(new StudentAdmitted(name: $user->name, course: $course_name, location: $location, url: $url));
                }
            }

            return ["message" => "success"];
        } catch (\Exception $e) {
            // return redirect(url('student/select-session/' . $user_id))->with([
            //     'flash' => 'Unable to confirm session. No slots available. Refresh page and try again later',
            //     'key' => 'error',
            // ]);
            abort(503);
        }
    }
}

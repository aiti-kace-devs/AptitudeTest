<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Models\Oex_student;
use App\Models\Oex_exam_master;
use App\Models\Oex_question_master;
use App\Models\Oex_result;
use App\Models\User;
use App\Models\user_exam;
use Illuminate\Support\Carbon;

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
        $student_info = user_exam::select(['user_exams.*', 'users.name', 'oex_exam_masters.title', 'oex_exam_masters.exam_date'])
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
        $question = Oex_question_master::where('exam_id', $id)->inRandomOrder()->get();
        $user_exam = user_exam::where('exam_id', $id)->where('user_id', Session::get('id'))->get()->first();
        if ($user_exam && $user_exam->submitted) {
            return redirect(url('student/exam'))->with([
                'flash' => 'Unable to take exam. Test already submitted',
                'key' => 'error',
            ]);
        }

        $exam = Oex_exam_master::where('id', $id)->get()->first();
        $usedTime = 0;
        if ($user_exam && $user_exam->started) {
            $start = new Carbon($user_exam->started);
            $now = Carbon::now();
            $usedTime = $now->diffInMinutes($start);
        }
        if ($usedTime > $exam->exam_duration) {
            return redirect(url('student/exam'))->with([
                'flash' => 'Unable to take exam. Time elased',
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
            $percentage = ($yes_ans / $total) * 100;

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

        $res = new Oex_result();
        $res->exam_id = $request->exam_id;
        $res->user_id = Session::get('id');
        $res->yes_ans = $yes_ans;
        $res->no_ans = $no_ans;
        $res->result_json = json_encode($result);
        $total = $yes_ans + $no_ans;
        $percentage = ($yes_ans / $total) * 100;
        echo $res->save();

        return redirect(url('student/exam'))->with([
            'flash' => "Test submitted successfully. Result: {$percentage}%  {$yes_ans}/{$total}",
            'key' => 'success',
        ]);
    }

    public function sendResultToFile($userId)
    {
        $user = User::where('userId', $userId)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $result = Oex_result::where('user_id', $user->id)->first();

        if (!$result) {
            return response()->json(['error' => 'Result not found'], 404);
        }

        $data = [
            'sheetIndex' => 1,
            'userId' => $user->userId,
            'data' => [
                'registered' => $user->registered,
                'result' => $result->yes_ans,
            ],
        ];

        $response = Http::post('#', $data);

        if ($response->successful()) {
            return response()->json(['message' => 'Result successfully sent to the file'], 200);
        } else {
            return response()->json(['error' => 'Failed to send result'], $response->status());
        }
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
}

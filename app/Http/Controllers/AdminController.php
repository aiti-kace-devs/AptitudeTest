<?php

namespace App\Http\Controllers;

use App\Events\UserRegistered;
use App\Jobs\AddNewStudentsJob;
use Illuminate\Support\Facades\DB;
use App\Jobs\ProcessStudentRegistrationJob;
use App\Jobs\UpdateSheetWithGhanaCardDetails;
use App\Jobs\AdmitStudentJob;
use App\Models\Attendance;
use App\Models\CourseSession;
use Illuminate\Http\Request;
use App\Models\Oex_category;
use App\Models\Oex_exam_master;
use App\Models\Oex_student;
use App\Models\Oex_portal;
use App\Models\User;
use App\Models\Oex_question_master;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\user_exam;
use App\Models\Admin;
use App\Models\Oex_result;
use App\Models\UserAdmission;
use App\Mail\ExamLoginCredentials;
use App\Mail\StudentAdmitted;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

use App\Helpers\GoogleSheets;
use App\Models\Course;
use App\Models\Branch;
use App\Models\Centre;
use App\Models\Programme;
use App\Helpers\Common as CommonHelper;

use Carbon\Carbon;

class AdminController extends Controller
{
    use CommonHelper;
    // admin dashboard
    public function index()
    {
        $user_count = User::get()->count();
        $exam_count = Oex_exam_master::get()->count();
        $admin_count = Admin::get()->count();

        return view('admin.dashboard', ['student' => $user_count, 'exam' => $exam_count, 'admin' => $admin_count]);
    }

    //Exam categories
    public function exam_category()
    {
        $data['category'] = Oex_category::get()->toArray();
        return view('admin.exam_category', $data);
    }

    //Adding new exam categories
    public function add_new_category(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            $arr = ['status' => 'false', 'message' => $validator->errors()->all()];
        } else {
            $cat = new Oex_category();
            $cat->name = $request->name;
            $cat->status = 1;
            $cat->save();
            $arr = ['status' => 'true', 'message' => 'Success', 'reload' => url('admin/exam_category')];
        }
        echo json_encode($arr);
    }

    //Deleting the categories
    public function delete_category($id)
    {
        $cat = Oex_category::where('id', $id)->get()->first();
        $cat->delete();
        return redirect(url('admin/exam_category'));
    }

    //Editing the categories
    public function edit_category($id)
    {
        $category = Oex_category::where('id', $id)->get()->first();
        return view('admin.edit_category', ['category' => $category]);
    }

    //Editing the categories
    public function edit_new_category(Request $request)
    {
        $cat = Oex_category::where('id', $request->id)
            ->get()
            ->first();
        $cat->name = $request->name;
        $cat->update();
        echo json_encode(['status' => 'true', 'message' => 'updated successfully', 'reload' => url('admin/exam_category')]);
    }

    //Editing categories status
    public function category_status($id)
    {
        $cat = Oex_category::where('id', $id)->get()->first();

        if ($cat->status == 1) {
            $status = 0;
        } else {
            $status = 1;
        }

        $cat1 = Oex_category::where('id', $id)->get()->first();
        $cat1->status = $status;
        $cat1->update();
    }




    //Editing branch status
    public function branch_status($id)
    {
        $branch = Branch::where('id', $id)->get()->first();

        if ($branch->status == 1) {
            $status = 0;
        } else {
            $status = 1;
        }

        $branch1 = Branch::where('id', $id)->get()->first();
        $branch1->status = $status;
        $branch1->update();
    }



    //Editing centre status
    public function centre_status($id)
    {
        $centre = Centre::where('id', $id)->get()->first();

        if ($centre->status == 1) {
            $status = 0;
        } else {
            $status = 1;
        }

        $centre1 = Centre::where('id', $id)->get()->first();
        $centre1->status = $status;
        $centre1->update();
    }




    //Editing programme status
    public function programme_status($id)
    {
        $programme = Programme::where('id', $id)->get()->first();

        if ($programme->status == 1) {
            $status = 0;
        } else {
            $status = 1;
        }

        $programme1 = Programme::where('id', $id)->get()->first();
        $programme1->status = $status;
        $programme1->update();
    }




    //Editing course status
    public function course_status($id)
    {
        $course = Course::where('id', $id)->get()->first();

        if ($course->status == 1) {
            $status = 0;
        } else {
            $status = 1;
        }

        $course1 = Course::where('id', $id)->get()->first();
        $course1->status = $status;
        $course1->update();
    }





    //Manage exam page
    public function manage_exam()
    {
        $data['category'] = Oex_category::where('status', '1')->get()->toArray();
        $data['exams'] = Oex_exam_master::select(['oex_exam_masters.*', 'oex_categories.name as cat_name'])
            ->join('oex_categories', 'oex_exam_masters.category', '=', 'oex_categories.id')
            ->get()
            ->toArray();
        return view('admin.manage_exam', $data);
    }

    //Adding new exam page
    public function add_new_exam(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'exam_date' => 'required',
            'exam_category' => 'required',
            'passmark' => 'required',
            'exam_duration' => 'required',
        ]);

        if ($validator->fails()) {
            $arr = ['status' => 'false', 'message' => $validator->errors()->all()];
        } else {
            $exam = new Oex_exam_master();
            $exam->title = $request->title;
            $exam->exam_date = $request->exam_date;
            $exam->exam_duration = $request->exam_duration;
            $exam->category = $request->exam_category;
            $exam->passmark = $request->passmark;
            $exam->status = 1;
            $exam->save();

            $arr = ['status' => 'true', 'message' => 'exam added successfully', 'reload' => url('admin/manage_exam')];
        }

        echo json_encode($arr);
    }

    //editing exam status
    public function exam_status($id)
    {
        $exam = Oex_exam_master::where('id', $id)->get()->first();

        if ($exam->status == 1) {
            $status = 0;
        } else {
            $status = 1;
        }

        $exam1 = Oex_exam_master::where('id', $id)->get()->first();
        $exam1->status = $status;
        $exam1->update();
    }

    //Deleting exam status
    public function delete_exam($id)
    {
        $exam1 = Oex_exam_master::where('id', $id)->get()->first();
        $exam1->delete();
        return redirect(url('admin/manage_exam'));
    }

    //Edit Exam
    public function edit_exam($id)
    {
        $data['category'] = Oex_category::where('status', '1')->get()->toArray();
        $data['exam'] = Oex_exam_master::where('id', $id)->get()->first();

        return view('admin.edit_exam', $data);
    }

    //Editing Exam
    public function edit_exam_sub(Request $request)
    {
        $exam = Oex_exam_master::where('id', $request->id)
            ->get()
            ->first();
        $exam->title = $request->title;
        $exam->exam_date = $request->exam_date;
        $exam->category = $request->exam_category;
        $exam->passmark = $request->passmark;
        $exam->exam_duration = $request->exam_duration;

        $exam->update();

        echo json_encode(['status' => 'true', 'message' => 'Successfully updated', 'reload' => url('admin/manage_exam')]);
    }

    //Manage students
    public function manage_students()
    {
        $data['exam'] = Oex_exam_master::all();

        $data['exams'] = Oex_exam_master::where('status', '1')->get()->toArray();

        $data['students'] = user_exam::with('result')
            // select(['user_exams.*', 'users.name', 'oex_exam_masters.title as ex_name', 'oex_exam_masters.exam_date', 'oex_exam_masters.passmark'])
            ->join('users', 'users.id', '=', 'user_exams.user_id')
            ->join('oex_exam_masters', 'user_exams.exam_id', '=', 'oex_exam_masters.id')
            ->orderBy('user_exams.exam_id', 'desc')
            // ->leftJoin('oex_results', 'user_exams.id', '=', 'oex_results.exam_id')
            ->get();
        // ->toArray();
        return view('admin.manage_students', $data);
    }

    public function add_new_students(Request $request)
    {
        $data = $request->input('students') !== null ? $request->input('students') : [$request->all()];

        AddNewStudentsJob::dispatch($data);

        echo json_encode(['status' => 'true', 'message' => 'Successfully updated', 'reload' => url('admin/manage_students')]);
    }

    //Add new students
    // public function add_new_students_arr(Request $request)
    // {
    //     $students = $request->input('students') ? $request->input('students') : [$request->all()];

    //     foreach ($students as $student) {
    //         $validator = Validator::make($student, [
    //             'name' => 'required',
    //             'email' => 'required|email',
    //             'mobile_no' => 'required',
    //             'exam' => 'required_if:exam_name,null|exists:oex_exam_masters,id',
    //             'password' => 'sometimes',
    //             'exam_name' => 'sometimes',
    //         ]);

    //         if ($validator->fails()) {
    //             $arr = ['status' => 'false', 'message' => $validator->errors()->all()];
    //         } else {
    //             AddNewStudentsJob::dispatch($student);
    //         }
    //     }

    //     $arr = ['status' => 'true', 'message' => 'student added successfully', 'reload' => url('admin/manage_students')];
    //     echo json_encode($arr);
    // }

    //Editing student status
    public function student_status($id)
    {
        $std = user_exam::where('id', $id)->get()->first();

        if ($std->std_status == 1) {
            $status = 0;
        } else {
            $status = 1;
        }

        $std1 = user_exam::where('id', $id)->get()->first();
        $std1->std_status = $status;
        $std1->update();
    }

    //Deleting students
    public function delete_students($id)
    {
        $std = user_exam::where('id', $id)->get()->first();
        $std->delete();
        return redirect('admin/manage_students');
    }

    //Editing students
    public function edit_students_final(Request $request)
    {
        $std = User::where('id', $request->id)
            ->get()
            ->first();
        $std->name = $request->name;
        $std->email = $request->email;
        $std->mobile_no = $request->mobile_no;

        if ($std->exam != $request->exam) {
            user_exam::create([
                'user_id' => $std->id,
                'exam_id' => $request->exam,
                'std_status' => 1,
                'exam_joined' => 0,
            ]);
        }

        $std->exam = $request->exam;

        if ($request->password != '') {
            $std->password = $request->password;
        }

        $std->update();
        echo json_encode(['status' => 'true', 'message' => 'Successfully updated', 'reload' => url('admin/manage_students')]);
    }

    //Registered student page
    public function registered_students()
    {
        $data['users'] = User::select('users.*', 'user_admission.id as admitted', 'courses.course_name', 'course_sessions.name as session_name', 'user_admission.session as session_id', 'courses.id as course_id')->leftJoin('user_admission', 'users.userId', '=', 'user_admission.user_id')->leftJoin('courses', 'user_admission.course_id', '=', 'courses.id')->leftJoin('course_sessions', 'user_admission.session', '=', 'course_sessions.id')->get();
        $courses = Course::all();
        $sessions = CourseSession::all();
        $data['courses'] = $courses;
        $data['sessions'] = $sessions;

        return view('admin.registered_students', $data);
    }

    //Deleting students egistered
    public function delete_registered_students($id)
    {
        $std = User::where('id', $id)->get()->first();
        $std->delete();
        return redirect('admin/registered_students');
    }

    //addning questions
    public function add_questions($id)
    {
        $data['questions'] = Oex_question_master::where('exam_id', $id)->get()->toArray();
        return view('admin.add_questions', $data);
    }

    //adding new questions
    public function add_new_question(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question' => 'required',
            'option_1' => 'required',
            'option_2' => 'required',
            'option_3' => 'required',
            'option_4' => 'required',
            'ans' => 'required',
        ]);

        if ($validator->fails()) {
            $arr = ['status' => 'flase', 'message' => $validator->errors()->all()];
        } else {
            $q = new Oex_question_master();
            $q->exam_id = $request->exam_id;
            $q->questions = $request->question;

            if ($request->ans == 'option_1') {
                $q->ans = $request->option_1;
            } elseif ($request->ans == 'option_2') {
                $q->ans = $request->option_2;
            } elseif ($request->ans == 'option_3') {
                $q->ans = $request->option_3;
            } else {
                $q->ans = $request->option_4;
            }

            $q->status = 1;
            $q->options = json_encode(['option1' => $request->option_1, 'option2' => $request->option_2, 'option3' => $request->option_3, 'option4' => $request->option_4]);

            $q->save();

            $arr = ['status' => 'true', 'message' => 'successfully added', 'reload' => url('admin/add_questions/' . $request->exam_id)];
        }

        echo json_encode($arr);
    }

    //Edit question status
    public function question_status($id)
    {
        $p = Oex_question_master::where('id', $id)->get()->first();

        if ($p->status == 1) {
            $status = 0;
        } else {
            $status = 1;
        }

        $p1 = Oex_question_master::where('id', $id)->get()->first();
        $p1->status = $status;
        $p1->update();
    }

    //Delete questions
    public function delete_question($id)
    {
        $q = Oex_question_master::where('id', $id)->get()->first();
        $exam_id = $q->exam_id;
        $q->delete();

        return redirect(url('admin/add_questions/' . $exam_id));
    }

    //update questions
    public function update_question($id)
    {
        $data['q'] = Oex_question_master::where('id', $id)->get()->toArray();

        return view('admin.update_question', $data);
    }

    //Edit question
    public function edit_question_inner(Request $request)
    {
        $q = Oex_question_master::where('id', $request->id)
            ->get()
            ->first();

        $q->questions = $request->question;

        if ($request->ans == 'option_1') {
            $q->ans = $request->option_1;
        } elseif ($request->ans == 'option_2') {
            $q->ans = $request->option_2;
        } elseif ($request->ans == 'option_3') {
            $q->ans = $request->option_3;
        } else {
            $q->ans = $request->option_4;
        }

        $q->options = json_encode(['option1' => $request->option_1, 'option2' => $request->option_2, 'option3' => $request->option_3, 'option4' => $request->option_4]);

        $q->update();

        echo json_encode(['status' => 'true', 'message' => 'successfully updated', 'reload' => url('admin/add_questions/' . $q->exam_id)]);
    }

    public function admin_view_result($id)
    {
        $std_exam = user_exam::where('user_id', $id)->first();

        $data['result_info'] = Oex_result::where('exam_id', $std_exam->exam_id)
            ->where('user_id', $id)
            ->first();

        $data['student_info'] = User::where('id', $id)->first();

        $data['exam_info'] = Oex_exam_master::where('id', $std_exam->exam_id)->first();

        return view('admin.admin_view_result', $data);
    }

    public function generate_qrcode_page()
    {
        $courses = Course::distinct('course_name')->get()->all();

        return view('admin.qr-generator', [
            // "locations" => $locations,
            'courses' => $courses,
        ]);
    }

    public function scan_qrcode_page()
    {
        $courses = Course::all();

        return view('admin.qr-scanner', [
            // "locations" => $locations,
            'courses' => $courses,
        ]);
    }

    public function verifyDetails(Request $request)
    {
        $courses = Course::all();

        $students = collect();
        $selectedCourse = null;

        if ($request->has('course_id')) {
            $selectedCourse = Course::find($request->input('course_id'));

            if ($selectedCourse) {
                $students = UserAdmission::where('course_id', $selectedCourse->id)
                    ->join('users', 'user_admission.user_id', '=', 'users.userId')
                    ->select('users.*')
                    ->get();
            }
        }

        return view('admin.verify_student_details', compact('courses', 'students', 'selectedCourse'));
    }

    public function verifyStudent($id)
    {
        $student = User::find($id);

        // match with ghana card format
        $correctFormat = preg_match('/GHA-[0-9]{9}-[0-9]{1}$/', $student->ghcard);
        if ($student && $correctFormat || $student && $student->ghcard && $student->card_type !== 'ghcard') {
            $adminId = Auth::id();
            $student->verification_date = now();
            $student->verified_by = $adminId;
            $student->save();
            $student->verified_by_name = Admin::find($student->verified_by)->name;

            UpdateSheetWithGhanaCardDetails::dispatch($student);

            return response()->json([
                'success' => true,
                'message' => 'Verification successsful',
                'student' => $student,
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Unable to verify. Card Number format is wrong',
        ]);
    }

    public function verification_page(Request $request)
    {
        $allCourses = Course::all();
        $students = [];

        $selectedCourse = $request->input('course_id');

        if (isset($selectedCourse)) {
            $students = UserAdmission::select('users.*', 'user_admission.created_at as admission_created', 'user_admission.updated_at as admission_updated', \DB::raw('(select admins.name from admins where admins.id = users.verified_by) as verified_by_name'))
                ->join('users', 'users.userId', 'user_admission.user_id')

                ->where('course_id', $selectedCourse)
                ->get();
            $selectedCourse = Course::find($selectedCourse);
        }

        return view('admin.verification', [
            'courses' => $allCourses,
            'students' => $students,
            'selectedCourse' => $selectedCourse,
        ]);
    }

    public function viewAttendanceByDate(Request $request)
    {
        $courses = Course::all();
        $attendance = collect();
        $selectedCourse = null;
        $selectedDate = null;

        if ($request->has('course_id') && $request->has('date')) {
            $selectedCourse = Course::find($request->input('course_id'));
            $selectedDate = $request->input('date');

            if ($selectedCourse && $selectedDate) {
                $attendance = Attendance::select('attendances.*', 'users.name', 'users.email')
                    ->join('users', 'users.userId', '=', 'attendances.user_id')
                    ->where('attendances.course_id', $selectedCourse->id)
                    ->whereDate('attendances.date', '=', $selectedDate)
                    ->get();
            }
        }

        return view('admin.view_attendance', [
            'courses' => $courses,
            'attendance' => $attendance,
            'selectedCourse' => $selectedCourse,
            'selectedDate' => $selectedDate,
        ]);
    }

    public function admit_student(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'session_id' => 'required|exists:course_sessions,id',
            'user_id' => 'required|exists:users,userId',
            'change' => 'sometimes',
        ]);

        $course = Course::find($validated['course_id']);
        $session = CourseSession::find($validated['session_id']);
        if ($session->course_id != $course->id) {
            return redirect()
                ->back()
                ->with([
                    'flash' => 'Session not valid for selected course',
                    'key' => 'error',
                ]);
        }

        $user_id = $validated['user_id'];

        $change = $validated['change'] == 'true';

        try {
            $oldAdmission = UserAdmission::where('user_id', $user_id)->first();
            $user = User::where('userId', $user_id)->first();
            $url = url('student/select-session/' . $user_id);
            $message = 'Student admitted successfully';

            if ($oldAdmission && !$oldAdmission->email_sent) {
                Mail::to($user->email)->queue(new StudentAdmitted(name: $user->name, course: $course_name, location: $location, url: $url));
                $oldAdmission->email_sent = now();
                $oldAdmission->save();
            }

            if ($oldAdmission && $change) {
                $oldAdmission->course_id = $course->id;
                $oldAdmission->session = $session->id;
                $oldAdmission->save();
                $message = 'Student admission changed successfully';
            }

            if (!$oldAdmission) {
                $admission = new UserAdmission();
                $admission->user_id = $user_id;
                $admission->course_id = $course->id;
                $admission->email_sent = now();
                $admission->session = $session->id;
                $admission->confirmed = now();
                $admission->location = $course->location;
                $admission->save();

                Mail::to($user->email)
                    ->bcc(env('MAIL_FROM_ADDRESS', 'no-reply@gi-kace.gov.gh'))
                    ->queue(new StudentAdmitted(name: $user->name, course: $course->course_name, location: $course->location, url: $url));

                AdmitStudentJob::dispatch($admission);
            }
            return redirect()
                ->back()
                ->with([
                    'flash' => $message,
                    'key' => 'success',
                ]);
        } catch (\Exception $e) {
            return redirect(url('student/select-session/' . $user_id))->with([
                'flash' => 'Unable to confirm session. No slots available. Refresh page and try again later',
                'key' => 'error',
            ]);
        }
    }

    public function reset_verify($userId)
    {
        $u = User::findOrFail($userId);
        if ($u && !$u->verification_date) {
            $u->ghcard = null;
            $u->card_type = null;
            $u->updated_at = $u->created_at;
        }

        $u->contact = null;
        $u->gender = null;
        $u->network_type = null;
        $u->save();

        return redirect()
            ->back()
            ->with([
                'flash' => 'Student reset successfully',
                'key' => 'success',
            ]);
    }

    public function getReportView()
    {
        $courses = Course::all();

        // find students that have attendance for the selected dates

        $data = [
            'courses' => $courses,
            'attendanceData' => [],
            'startDate' => now()->toDateString(),
            'endDate' => now()->toDateString(),
            'dates_array' => [],
            'report_type' => null,
            'dates' => '',
            'selectedCourse' => [],
            'selectedDailyOption' => "no",
            'virtualQuery' => false,
            'virtual_week' => []
        ];

        return view('admin.reports', $data);
    }

    public function generateReport(Request $request)
    {
        $validated = $request->validate([
            'report_type' => 'required|in:student_summary,course_summary',
            'dates' => 'required',
            'course_id' => 'sometimes|array',
            'daily' => 'sometimes|in:yes,no',
            'course_id.*' => 'numeric|min:0',
            'virtual_week' => 'sometimes|array',
            'virtual_week.*' => 'numeric|min:1|max:54',
        ]);

        $startDate = Carbon::parse(explode(' - ', $request->dates)[0]);
        $endDate = Carbon::parse(explode(' - ', $request->dates)[1]);

        $dates = $this->getWeekdays($startDate, $endDate);

        $courses = Course::all();
        $selectedCourse = null;
        $studentAttendanceData = collect();
        $attendanceData = collect();

        $dailyQuery = isset($validated['daily']) && $validated['daily'] == 'yes';

        if ($request->get('report_type') == 'course_summary') {
            // find students that have attendance for the selected dates
            $attendanceData = DB::table('vDailyCourseAttendance', 'v1');
            if ($dailyQuery) {
                $attendanceData = $attendanceData->whereRaw('DATE(attendance_date) BETWEEN ? AND ?', [$startDate, $endDate]);
            }

            $attendanceData = $attendanceData->whereRaw('DATE(attendance_date) BETWEEN ? AND ?', [$startDate, $endDate])
                ->select('v1.*')
                ->selectRaw('(SELECT AVG(v2.total) from `vDailyCourseAttendance` v2 where v2.course_id = v1.course_id AND DATE(attendance_date) BETWEEN ? AND ? group by v1.course_id ) as average', [$startDate, $endDate])
                ->selectRaw('(SELECT SUM(v2.total) from `vDailyCourseAttendance` v2 where v2.course_id = v1.course_id AND DATE(attendance_date) BETWEEN ? AND ? group by v1.course_id ) as attendance_total', [$startDate, $endDate])
                ->orderBy('course_id', 'desc')
                ->orderBy('attendance_date')
                ->get()
                ->groupBy(['course_name', 'attendance_date']);
        }

        if ($request->get('report_type') == 'student_summary') {
            $courseId = $validated['course_id'] ?? [0];
            $whereCourseClause = $courseId[0] == 0 ? '' : ' WHERE c.id in ( ' . implode(',', (collect($courseId)->flatten()->all())) . ')';
            // use total attendance when start to date and no daily
            // if ($s)
            $virtualQuery =  isset($validated['virtual_week']) && count($validated['virtual_week']) > 0;

            $whereVirtualClause = $virtualQuery ? " AND WEEK(a.date, 3) IN (" . implode(',', $validated['virtual_week']) . ") " : '';
            $optimizeQuery = "select _ta.total as attendance_total, _ta.user_id,
                u.name as user_name, u.email as email, u.gender as user_gender, u.contact as user_contact, u.network_type as user_network_type, c.course_name, c.location as course_location, c.id " . ($dailyQuery ? ', _da.date as attendance_date' : '') . ($virtualQuery ? ', _va.t as virtual_attendance, (_ta.total - _va.t) as in_person' : '') . " from
                (select count(distinct `a`.`date`) AS `total`,max(`a`.`user_id`) AS `user_id`
                from `attendances` `a`
                where DATE(a.date) between ? AND ?
                group by `a`.`user_id`) as _ta " .
                ($virtualQuery ? "inner join
                (select COUNT(*) as t, MAX(a.user_id) AS user_id FROM `attendances` `a`
                where DATE(a.date) between ? AND ? $whereVirtualClause
                group by `a`.`user_id` ) as _va
                ON _ta.user_id  = _va.user_id " : '') .
                ($dailyQuery ? "inner join
                (select distinct date_format(`a`.`date`,'%Y-%m-%d') AS `date`,
                `a`.`user_id` from `attendances` `a`
                where DATE(a.date) between ? AND ? ) as _da
                ON _ta.user_id  = _da.user_id " : "") . "
                left join users u on u.userId = _ta.user_id
                left join user_admission ua on ua.user_id = _ta.user_id
                inner join courses c on c.id = ua.course_id
                $whereCourseClause order by c.course_name, u.name";
            $dateParams = [$startDate, $endDate];
            $params = $dateParams;
            $groupBy = ['user_id', 'attendance_date'];

            if ($dailyQuery) {
                $params =  array_merge($params, $dateParams);
            }

            if ($virtualQuery) {
                $params =  array_merge($params, $dateParams);
            }

            // if ($courseId[0] !== 'all') {
            //     // $params[] = "(" . implode(',', (collect($courseId)->flatten()->all())) . ") ";
            // }
            // DB::prepareBindings($params);
            // dd($optimizeQuery);
            // DB::bindValues($optimizeQuery, $params);


            $studentAttendanceData = collect(DB::select($optimizeQuery, $params))
                ->groupBy($groupBy);
            // dd($studentAttendanceData);

            $selectedCourse = $courseId[0] == '0' ||  count($courseId) > 1 ? '0' : Course::find($courseId[0]);
        }

        $data = [
            'courses' => $courses,
            'attendanceData' => $attendanceData,
            'studentAttendanceData' => $studentAttendanceData,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'dates_array' => $dates,
            'report_type' => $request->get('report_type'),
            'dates' => $request->get('dates'),
            'selectedCourse' => $selectedCourse ?? '0',
            'selectedDailyOption' => $request->get('daily'),
            'virtualQuery' => $virtualQuery,
            'virtual_week' => $validated['virtual_week'] ?? []

        ];
        // dd($data);
        return view('admin.reports', $data);
    }
}

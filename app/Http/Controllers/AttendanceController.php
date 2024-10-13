<?php

namespace App\Http\Controllers;

use App\Helpers\GoogleSheets;
use App\Models\Course;
use App\Models\UserAdmission;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use ReallySimpleJWT\Token;
use App\Jobs\UpdateAttendanceOnSheetJob;

class AttendanceController extends Controller
{
    public function generateQRCodeData(Request $request)
    {
        $data = $request->validate([
            'course_id' => 'exists:courses,id',
            'date' => 'date|before_or_equal:' . now()->toDateString()
        ]);

        // $course = Course::findOrFail($courseId);
        $course = Course::findOrFail($data['course_id']);


        // $scret should be 12 char and must containe upper,lower,number and specialchar
        $secret = env('JWT_KEY');

        $dataToEncode = json_encode([
            'course_id' => $course->id,
            'location' => $course->location,
            'date' => $data['date'],
        ]);

        $expiration = Carbon::now()->addMinutes(30)->timestamp;

        $issuer = 'attendance_app';
        // dd($secret);

        $token = Token::create($dataToEncode, $secret, $expiration, $issuer);

        return response()->json(["data" => $token]);
        // $loginUrl = url('/login?scanned_data=' . urlencode($token));
        // $qrCode = QrCode::size(300)->generate($loginUrl);

        // return view('attendance.qrcode', compact('qrCode', 'token'));
    }

    public function recordAttendance(Request $request)
    {
        $confirmedAdmissions = UserAdmission::whereNotNull('confirmed')->pluck('user_id')->toArray();

        $scannedToken = $request->input('scanned_data');

        $secret = env('JWT_KEY');

        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to record attendance.');
        }

        if (Token::validate($scannedToken, $secret)) {
            $decodedData = Token::getPayload($scannedToken);
            $decodedUserIdData = json_decode($decodedData['user_id'], true);
            if (is_array($decodedUserIdData) && isset($decodedUserIdData['course_id']) && isset($decodedUserIdData['location']) && isset($decodedUserIdData['date'])) {
                $courseId = $decodedUserIdData['course_id'];
                $location = $decodedUserIdData['location'];
                $date = Carbon::parse($decodedUserIdData['date'])->format('Y-m-d');
            } else {
                $courseId = null;
                $location = null;
            }

            // $courseId = $decodedData['course_id'];
            // $location = $decodedData['location'];
            // $date = Carbon::parse($decodedData['date'])->format('Y-m-d');

            $loggedUserId = Auth::id();

            if (in_array($loggedUserId, $confirmedAdmissions)) {
                $attendanceRecord = Attendance::where('user_id', $loggedUserId)->where('course_id', $courseId)->where('location', $location)->whereDate('date', $date)->first();

                if ($attendanceRecord) {
                    return redirect()->back()->with('message', 'Attendance for today is already recorded.');
                } else {
                    $attendance = new Attendance();
                    $attendance->user_id = $loggedUserId;
                    $attendance->course_id = $courseId;
                    $attendance->location = $location;
                    $attendance->date = $date;
                    $attendance->save();

                    $userId = Auth::user()->userId;
                    GoogleSheets::updateGoogleSheets($userId, ['attendance' => true]);
                    return redirect()->back()->with('message', 'Attendance recorded successfully.');
                }
            } else {
                return redirect()->back()->with('error', 'You are not authorized to record attendance.');
            }
        } else {
            return redirect()->back()->with('error', 'Invalid or expired QR code.');
        }
    }

    public function confirmAttendance(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,userId',
            'course_id' => 'required|exists:courses,id',
            'date' => 'required|date|before_or_equal:' . now()->toDateString(),
        ]);


        $userAdmitted = UserAdmission::where('user_id', $data['user_id'])->whereNotNull('confirmed')->first();
        if (!$userAdmitted) {
            return response()->json(['message' => 'User not admitted. Cannot confirm attendance', 'success' => false]);
        }

        $alreadyConfirmed = Attendance::where('user_id', $data['user_id'])->where('date', $data['date'])->first();
        if ($alreadyConfirmed) {
            return response()->json(['message' => 'Attendance already confirmed.', 'success' => true]);
        }

        $attendance = new Attendance();
        $attendance->user_id = $data['user_id'];
        $attendance->course_id = $data['course_id'];
        $attendance->date = $data['date'];
        $attendance->save();

        UpdateAttendanceOnSheetJob::dispatch($attendance);

        return response()->json(['message' => 'Attendance recorded successfully.', 'success' => true]);
    }

    public function viewAttendance()
    {
        $userId = Auth::user()->userId;
        $attendance = Attendance::select('attendances.*', 'courses.created_at as course_created', 'courses.course_name')
            ->where('user_id', $userId)
            ->join('courses', 'courses.id', 'attendances.course_id')
            ->get();

        return view('student.attendance', compact('attendance'));
    }
}

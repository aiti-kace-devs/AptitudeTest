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
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AttendanceController extends Controller
{
    public function generateQRCodeData($courseId)
    {
        $course = Course::find($courseId);
        if (!$course) {
            throw new \Exception('Course not found.');
        }

        // $scret should be 12 char and must containe upper,lower,number and specialchar
        $secret = env('APP_KEY');

        $data = json_encode([
            'course_id' => $course->id,
            'location' => $course->location,
            'date' => Carbon::now()->format('Y-m-d'),
        ]);

        $expiration = Carbon::now()->addHours(2)->timestamp;

        $issuer = 'attendance_app';
        // dd($secret);

        $token = Token::create($data, $secret, $expiration, $issuer);

        return $token;
        // $loginUrl = url('/login?scanned_data=' . urlencode($token));
        // $qrCode = QrCode::size(300)->generate($loginUrl);

        // return view('attendance.qrcode', compact('qrCode', 'token'));
    }

    public function recordAttendance(Request $request)
    {
        $confirmedAdmissions = UserAdmission::whereNotNull('confirmed')->pluck('user_id')->toArray();

        $scannedToken = $request->input('scanned_data');

        $secret = env('APP_KEY');

        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to record attendance.');
        }

        if (Token::validate($scannedToken, $secret)) {
            $decodedString = Token::getPayload($scannedToken);
            $decodedData = json_decode($decodedString, true);

            $courseId = $decodedData['course_id'];
            $location = $decodedData['location'];
            $date = Carbon::parse($decodedData['date'])->format('Y-m-d');

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

    public function viewAttendance()
    {
        $userId = Auth::id();
        $attendanceRecords = Attendance::where('user_id', $userId)->get();

        return view('dashboard.attendance', compact('attendanceRecords'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Helpers\GoogleSheets;
use App\Models\UserAdmission;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use ReallySimpleJWT\Token;

class AttendanceController extends Controller
{
    public function generateQRCodeData($location, $courseId)
    {
        $secret = env('APP_KEY', 'base64:WVUMYOASkJ4hdLI+sJNfordWS1dH20y70ZqTSBhrCqY=');

        $data = json_encode([
            'course_id' => $courseId,
            'location' => $location,
            'date' => Carbon::now()->format('Y-m-d'),
            // 'expiry' => Carbon::now()->addHours(2)->timestamp
        ]);

        $expiration = Carbon::now()->addHours(2)->timestamp;

        $issuer = 'attendance_app';

        $token = Token::create($data, $secret, $expiration, $issuer);

        return $token;
    }

    public function recordAttendance(Request $request)
    {
        $confirmedAdmissions = UserAdmission::whereNotNull('confirmed')->pluck('user_id')->toArray();

        $scannedToken = $request->input('scanned_data');

        $secret = env('APP_KEY');

        if (Token::validate($scannedToken, $secret)) {
            $decodedString = Token::getPayload($scannedToken);
            $decodedData = json_decode($decodedString, true);

            $courseId = $decodedData['course_id'];
            $location = $decodedData['location'];
            $date = Carbon::parse($decodedData['date'])->format('Y-m-d');

            $userId = Auth::id();

            if (in_array($userId, $confirmedAdmissions)) {
                $attendanceRecord = Attendance::where('userId', $userId)->where('course_id', $courseId)->where('location', $location)->whereDate('date', $date)->first();

                if ($attendanceRecord) {
                    return redirect()->back()->with('message', 'Attendance for today is already recorded.');
                } else {
                    $attendance = new Attendance();
                    $attendance->userId = $userId;
                    $attendance->course_id = $courseId;
                    $attendance->location = $location;
                    $attendance->date = $date;
                    $attendance->attendance_status = 'present';
                    $attendance->save();

                    GoogleSheets::updateGoogleSheets($userId, ["attendance" => true]);
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

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentOperation;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AttendanceController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::redirect('/', '/login');


// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/home', function () {
    return view('home');
})->middleware(['auth'])->name('home');


Route::prefix('admin')->middleware('theme:dashboard')->name('admin.')->group(function () {



    Route::middleware(['auth:admin'])->group(function () {

        Route::get('/dashboard', [AdminController::class, 'index']);
        // Route::get('/exam_category', [AdminController::class, 'exam_category']);
        // Route::get('/delete_category/{id}', [AdminController::class, 'delete_category']);
        // Route::get('/edit_category/{id}', [AdminController::class, 'edit_category']);
        // Route::get('/category_status/{id}', [AdminController::class, 'category_status']);
        // Route::get('/manage_exam', [AdminController::class, 'manage_exam']);
        // Route::get('/exam_status/{id}', [AdminController::class, 'exam_status']);
        // Route::get('/delete_exam/{id}', [AdminController::class, 'delete_exam']);
        // Route::get('/edit_exam/{id}', [AdminController::class, 'edit_exam']);
        // Route::get('/manage_students', [AdminController::class, 'manage_students']);
        // Route::get('/student_status/{id}', [AdminController::class, 'student_status']);
        // Route::get('/delete_students/{id}', [AdminController::class, 'delete_students']);
        // Route::get('/add_questions/{id}', [AdminController::class, 'add_questions']);
        // Route::get('/question_status/{id}', [AdminController::class, 'question_status']);
        // Route::get('/delete_question/{id}', [AdminController::class, 'delete_question']);
        // Route::get('/update_question/{id}', [AdminController::class, 'update_question']);
        // Route::get('/registered_students', [AdminController::class, 'registered_students']);
        // Route::get('/delete_registered_students/{id}', [AdminController::class, 'delete_registered_students']);
        // Route::get('/apply_exam/{id}', [AdminController::class, 'apply_exam']);
        // Route::get('/admin_view_result/{id}', [AdminController::class, 'admin_view_result']);
        // Route::get('/view_answer/{id}', [StudentOperation::class, 'view_answer']);
        // Route::post('/edit_question_inner', [AdminController::class, 'edit_question_inner']);
        // Route::post('/add_new_question', [AdminController::class, 'add_new_question']);
        // Route::post('/edit_students_final', [AdminController::class, 'edit_students_final']);
        // Route::post('/add_new_exam', [AdminController::class, 'add_new_exam']);
        // Route::post('/add_new_category', [AdminController::class, 'add_new_category']);
        // Route::post('/edit_new_category', [AdminController::class, 'edit_new_category']);
        // Route::post('/edit_exam_sub', [AdminController::class, 'edit_exam_sub']);
        // Route::post('/add_new_students', [AdminController::class, 'add_new_students']);
        // Route::get('/reset-exam/{exam_id}/student/{user_id}', [StudentOperation::class, 'reset_exam'])->name('reset-exam');
        Route::get('/generate_qrcode', [AdminController::class, 'generate_qrcode_page']);
        Route::post('/generate_qrcode', [AttendanceController::class, 'generateQRCodeData']);
        Route::get('/scan_qrcode', [AdminController::class, 'scan_qrcode_page']);
        Route::post('/confirm_attendance', [AttendanceController::class, 'confirmAttendance']);
        Route::get('/verification', [AdminController::class, 'verification_page'])->name('verification');
        Route::get('/verify_details', [AdminController::class, 'verifyDetails'])->name('verify-details');
        Route::post('/verify-student/{id}', [AdminController::class, 'verifyStudent'])->name('verify-student');
        Route::get('/view_attendance', [AdminController::class, 'viewAttendanceByDate'])->name('viewAttendanceByDate');


    });
});



/* Student section routes */
Route::prefix('student')->middleware('theme:dashboard')->name('student.')->group(function () {

    Route::get('/select-session/{user_id}', [StudentOperation::class, 'select_session_view']);
    Route::post('/select-session/{user_id}', [StudentOperation::class, 'confirm_session'])->name('select-session');

    Route::middleware(['auth:web'])->group(function () {
        Route::get('/dashboard', [StudentOperation::class, 'dashboard']);

        Route::get('/exam', [StudentOperation::class, 'exam']);
        Route::get('/join_exam/{id}', [StudentOperation::class, 'join_exam']);
        Route::post('/submit_questions', [StudentOperation::class, 'submit_questions']);
        Route::get('/show_result/{id}', [StudentOperation::class, 'show_result']);
        Route::get('/apply_exam/{id}', [StudentOperation::class, 'apply_exam']);
        Route::get('/view_result/{id}', [StudentOperation::class, 'view_result']);
        Route::post('/attendance/record', [AttendanceController::class, 'recordAttendance'])->name('attendance.record');
        Route::get('/attendance', [AttendanceController::class, 'viewAttendance'])->name('attendance.show');
        Route::get('/id-qrcode', [StudentOperation::class, 'get_details_page']);
        Route::get('/scan-qrcode', [StudentOperation::class, 'get_scanner_page']);
        Route::get('/meeting-link', [StudentOperation::class, 'get_meeting_link_page']);
        Route::post('/update-details', [StudentOperation::class, 'updateDetails'])->name('updateDetails');



        // Route::get('/ateendance', [StudentOperation::class, 'view_result']);



        // Route::get('/view_answer/{id}', [StudentOperation::class, 'view_answer']);

        Route::post('/start-exam/{id}', [StudentOperation::class, 'start_exam']);
        Route::get('/mark_attendance', [AttendanceController::class, 'recordAttendance'])->name('mark-attendance');
        Route::get('/logout', [AuthenticatedSessionController::class, 'destroy']);
    });
});




require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';

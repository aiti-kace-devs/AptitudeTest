<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("CREATE VIEW vUserCourseAttendance AS
        SELECT u.userId AS user_id, u.name AS user_name, at.attendance_date, at.total, at.user_id AS attendance_user_id
        FROM users u
        LEFT JOIN (
        SELECT DATE_FORMAT(a.date, '%Y-%m-%d') AS attendance_date,
               COUNT(*) AS total,
               MAX(a.user_id) AS user_id
        FROM attendances a
        GROUP BY a.user_id , attendance_date order by a.user_id, attendance_date asc
        ) AS at ON at.user_id = u.userId");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('views_for_student_attendance');
    }
};

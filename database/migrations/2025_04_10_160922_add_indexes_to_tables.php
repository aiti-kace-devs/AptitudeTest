<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Schema::table('users', function (Blueprint $table) {
        //     // column type changes
        //     $table->unsignedBigInteger('exam')->change();

        //     // index
        //     $table->index('exam');
        //     // $table->unsignedBigInteger('exam')->change();
        //     $table->index('age');
        //     $table->index('userId');
        // });

        // Schema::table('user_admission', function (Blueprint $table) {
        //     // column type changes
        //     $table->unsignedBigInteger('course_id')->change();
        //     $table->unsignedBigInteger('session')->change();

        //     // index
        //     $table->unique('user_id');
        //     $table->index(['user_id', 'course_id']);
        // });

        // Schema::table('courses', function (Blueprint $table) {
        //     // index
        //     $table->index('centre_id');
        //     $table->index('programme_id');
        // });

        // Schema::table('centres', function (Blueprint $table) {
        //     // index
        //     $table->index('branch_id');
        // });

        // Schema::table('user_exams', function (Blueprint $table) {
        //     // column type changes
        //     $table->unsignedBigInteger('user_id')->change();
        //     $table->unsignedBigInteger('exam_id')->change();

        //     // index
        //     $table->index('user_id');
        //     $table->index('exam_id');
        //     $table->index(['exam_id', 'user_id']);
        //     $table->index('exam_joined');
        // });

        Schema::table('oex_results', function (Blueprint $table) {
            // index
            $table->index('exam_id');
            $table->index('user_id');
            $table->index(['exam_id','user_id']);
            $table->index('yes_ans');
            $table->index('no_ans');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       
    }
};

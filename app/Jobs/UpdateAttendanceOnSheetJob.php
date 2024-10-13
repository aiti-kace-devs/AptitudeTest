<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\Course;
use App\Helpers\GoogleSheets;

class UpdateAttendanceOnSheetJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $attendance;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($attendance)
    {
        $this->attendance = $attendance;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $course = Course::find($this->attendance->course_id);

        // TODO: work on the attendance update
        // GoogleSheets::updateGoogleSheets($this->attendance->user_id, [

        // ]);

    }
}

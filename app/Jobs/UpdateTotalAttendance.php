<?php

namespace App\Jobs;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateTotalAttendance implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user_id;
    public $calculate = false;



    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user_id, $reset = false)
    {
        $this->user_id = $user_id;
        $this->calculate = $reset;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        return;
        $user = User::where('userId', $this->user_id)->firstOrFail();
        // 14 October, 2024 as start date
        $startDate = (new Carbon('2024-10-14'))->toDateString();
        $endDate = now()->toDateString();
        if ($this->calculate) { // get the number to increase
            $count = Attendance::where('user_id', $this->user_id)
                ->whereRaw('DATE(date) BETWEEN ? AND ?', [$startDate, $endDate])
                ->distinct('date')
                ->count();
            $user->total_attendance = $count;
            $user->attendance_last_update = now();
            $user->save();
        } else {
            // last update increase
            $startDate = (new Carbon($user->attendance_last_update))->toDateString();
            $count = Attendance::where('user_id', $this->user_id)
                ->whereRaw('DATE(date) BETWEEN ? AND ?', [$startDate, $endDate])
                ->distinct('date')
                ->count();
            User::where('userId', $this->user_id)->increment('total_attendance', $count, [
                'attendance_last_update' => now()
            ]);
        }
    }
}

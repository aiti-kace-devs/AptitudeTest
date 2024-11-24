<?php

namespace App\Console\Commands;

use App\Jobs\UpdateTotalAttendance;
use App\Models\Attendance;
use App\Models\UserAdmission;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SetTotalAttendance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates all user attendance';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        return;
        $this->line('**************************');
        $this->info('Updating attendance');

        $totalToUpdate = Attendance::selectRaw('count(distinct(user_id)) as count')
            ->get()[0]->count;
        $this->info($totalToUpdate . ' student(s) to update');

        $this->withProgressBar($totalToUpdate, function () {
            Attendance::selectRaw('distinct(user_id) as user_id')
                ->orderBy('user_id')
                ->chunk(200, function ($userIds) {
                    $startDate = (new Carbon('2024-10-14'))->toDateString();
                    $endDate = now()->toDateString();
                    foreach ($userIds as $user) {
                        UpdateTotalAttendance::dispatch($user->user_id, true);
                        // pusging
                    }
                });
        });
    }
}

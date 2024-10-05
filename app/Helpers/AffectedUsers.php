<?php
namespace App\Helpers;

use App\Models\Oex_result;
use App\Models\User;
use Carbon\Carbon;

$startTime = Carbon::yesterday()->setTime(19, 50); // Yesterday 7:50 PM
$endTime = Carbon::today()->setTime(13, 8); // Today 1:08 PM
$usersToUpdate = User::whereBetween('created_at', [$startTime, $endTime])->get();

foreach ($usersToUpdate as $user) {
    $existingUser = User::where('email', $user->email)->first();

    if ($existingUser === null) {
        $result = Oex_result::where('user_id', $user->id)->first();

        $dataToUpdate = [
            'registered' => true,
            'result' => $result ? $result->yes_ans : 'N/A',
        ];
        dd($result);
        GoogleSheets::updateGoogleSheets($user->userId, $dataToUpdate);
    }
}



<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleSheets
{
    public static function updateGoogleSheets($userId, array $data)
    {

        $apiEndpoint = env('SheetUpdateURL');
        $data = [
            'sheetIndex' => 0,
            'userId' => $userId,
            'data' => $data,
        ];

        $response = Http::post($apiEndpoint, $data);

        if ($response->successful()) {
            return response()->json(['status' => 'success', 'message' => 'Result sent successfully.']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Failed to send result.'], $response->status());
        }
    }
}

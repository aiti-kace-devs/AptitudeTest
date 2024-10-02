<?php
namespace App\Traits;

use Illuminate\Support\Facades\Http;

trait UpdateGoogleSheets
{
    public function updateGoogleSheets($userId, array $data)
    {
        $apiEndpoint = env('SheetUpdateURL');
        $data = [
            'sheetIndex' => 1,
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

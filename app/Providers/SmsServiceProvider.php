<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Http;
use App\Jobs\SendSmsJob;

class SmsServiceProvider extends ServiceProvider
{





    public static function sendSmsOnRegistration($form, $userObject, $smsMessage)
    {
        $phoneNumber = null;
    
        // Decode the JSON fields
        $fields = json_decode($form->fields, true);
        if (empty($fields) || !is_array($fields)) {
            return response()->json(['error' => 'Invalid form template format'], 500);
        }
    
        // Get user form values
        $formValues = $userObject->form_values;
    
        // Extract phone number
        foreach ($fields as $field) {
            if ($field['type'] === 'phoneNumber' && isset($formValues[$field['title']])) {
                $phoneNumber = $formValues[$field['title']];
                // \Log::info('Extracted Phone Number', ['phoneNumber' => $phoneNumber]);
            }
        }
    
        // Validate phone number format
        if (!$phoneNumber || !preg_match('/^(\+233|0)[245789][0-9]{8}$/', $phoneNumber)) {
            return response()->json([
                'error' => 'Invalid phone number format',
                'phoneNumber' => $phoneNumber
            ], 422);
        }
    
        // Ensure SMS message is not null before replacing placeholders
        if (!$smsMessage) {
            return response()->json(['error' => 'SMS template is empty'], 500);
        }
    
        foreach ($fields as $field) {
            $placeholder = '{{' . $field['title'] . '}}';
            if (isset($formValues[$field['title']])) {
                $smsMessage = str_replace($placeholder, $formValues[$field['title']], $smsMessage);
            }
        }
    
        if (empty($smsMessage)) {
            return response()->json(['error' => 'Generated SMS message is empty'], 500);
        }



        //Dispatch SMS asynchronously
        dispatch(new SendSmsJob($phoneNumber, $smsMessage));
    
        
    
        return response()->json([
            'message' => 'SMS sent successfully',
            'smsMessage' => $smsMessage,
            'phoneNumber' => $phoneNumber
        ], 201);
    }
    
}

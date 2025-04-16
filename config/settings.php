<?php

return [
    'exam_deadline_after_registration' => env('EXAM_DEADLINE_AFTER_REGISTRATION', 7),
    'allow_course_change' => env('ALLOW_COURSE_CHANGE', false),
    'allow_session_change' => env('ALLOW_SESSION_CHANGE', true),
    'send_email_registration' => env('SEND_EMAIL_REGISTRATION', true),
    'send_sms_registration' => env('SEND_SMS_REGISTRATION', false),
    'send_email_exam_submission' => env('SEND_EMAIL_EXAM_SUBMISSION', true),
    'send_sms_exam_submission' => env('SEND_SMS_EXAM_SUBMISSION', false),
    'send_email_admission_creation' => env('SEND_EMAIL_ADMISSION_CREATION', true),
    'send_sms_admission_creation' => env('SEND_SMS_ADMISSION_CREATION', true),
    'send_email_admission_confirmation' => env('SEND_EMAIL_ADMISSION_CONFIRMATION', true),
    'send_sms_admission_confirmation' => env('SEND_SMS_ADMISSION_CONFIRMATION', true),
];

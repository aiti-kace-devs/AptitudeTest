@component('mail::message')
# Welcome, {{ $name }}

We are excited to have you. Here are your exam login details:

- **Email:** {{ $email }}
- **Password:** {{ $password }}

You can log in and start your exam preparation by clicking the button below:

@component('mail::button', ['url' => $examUrl])
Start Your Exam
@endcomponent

Thanks,
{{ config('app.name') }}
@endcomponent

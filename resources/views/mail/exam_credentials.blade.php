@component('mail::message')
# Welcome, {{ $name }} !

We are excited to have you. Here are your exam login details:
@component('mail::panel')
- **Email:** {{ $email }}
- **Password:** {{ $password }}
@endcomponent

You can log in and start your exam preparation by clicking the button below:

@component('mail::button', ['url' => $examUrl])
Start Your Exam
@endcomponent

@component('mail::panel')

You have <b>48 hours</b> to complete your examination. Your deadline for exam submission is {{$deadline}}
If you are having trouble with the button copy and paste this URL in a browser: <a href="{{$examUrl}}">{{$examUrl}}</a>
@endcomponent

Thanks,
{{ config('app.name') }}
@endcomponent

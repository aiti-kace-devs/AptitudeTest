@component('mail::message')
# Welcome, {{ $name }}

We are excited to have you. Here are your exam login details:

- **Email:** {{ $email }}
- **Password:** {{ $password }}

Please log in and start your exam preparation!

Thanks,
{{ config('app.name') }}
@endcomponent

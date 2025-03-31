<x-mail::message>
# Hello, {{$name}}

This is to confirm that you have successfully enrolled for {{$session}} 
Time is {{$sessionTime}}
<br>
@include('mail.contact-info')

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>

<x-mail::message>
# Dear {{$name}},

Congratulations on your selection as one of the shortlisted participants for <b>{{$course->course_name}}</b>.

Kindly take note of the following details:

Start Date: {{$course->created_at}}.

Training Duration: {{$course->location}}. <br>
Venue: {{$course->location}}. <br>
Required Resource: Laptop. <br>


@include('mail.contact-info')

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>

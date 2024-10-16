<x-mail::message>
# Dear {{$name}},

Congratulations on your selection as one of the shortlisted participants for <b>{{$course}}</b>.
This is a final confirmation phase to ensure your availability for the training.
Kindly take note of the following details:

Start Date: {{$data['startDate']}}.

Training Duration: {{$data['duration']}}. <br>
Venue: {{$data['venue']}}. <br>
Required Resource: Laptop. <br>

Kindly select a session that fits your schedule by clicking on the button below

<x-mail::button :url="$url">
Select Session
</x-mail::button>

Please join the WhatsApp Group for {{$course}} for quick information, by clicking the button below:

<x-mail::button :url="$data['whatsapp']" color="success">
Join WhatsApp Group
</x-mail::button>

<x-mail::panel>
Note: Only applicants who have selected their sessions will move to the next stage.
</x-mail::panel>

@include('mail.contact-info')

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>

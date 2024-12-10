<x-mail::message>
# Dear {{$data['course']}} Participant,

Kindly join using the new link below

<a href="{{$data['link']}}">{{$data['link']}}</a>

or click the button below
<x-mail::button :url="$data['link']">
Join Meeting
</x-mail::button>

@if ($data['whatsapp'])
Please join the WhatsApp Group for {{$data['course']}} by clicking the button below
<x-mail::button :url="$data['whatsapp']" color="success">
Join WhatsApp
</x-mail::button>

If button is not working copy this: <a href="{{$data['whatsapp']}}">{{$data['whatsapp']}}</a>

@endif

This link supersedes any other link shared previously.


Thank you.<br>
{{ config('app.name') }}
</x-mail::message>

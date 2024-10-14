<x-mail::message>
# Dear {{$data['course']}} Participant,

Kindly join using the new link below

<a href="{{$data['link']}}">{{$data['link']}}</a>

<x-mail::button :url="$data['link']">
Join Meeting
</x-mail::button>

This link supersedes any other link shared previously.


Thank you.<br>
{{ config('app.name') }}
</x-mail::message>

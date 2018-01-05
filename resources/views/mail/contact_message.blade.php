@component('mail::message')
# Website message

@component('mail::panel')
**From:** {{ $name }}<br>
**Email:** {{ $email }}
@endcomponent


{{ $message_body }}

@foreach($message_notes as $field => $value)
**{{ $field }}** {{ $value }}<br>
@endforeach

{{--@component('mail::button', ['url' => $url])--}}
    {{--View on Site--}}
{{--@endcomponent--}}


@endcomponent
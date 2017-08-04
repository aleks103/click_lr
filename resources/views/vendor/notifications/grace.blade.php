@component('mail::message')
{{-- Greeting --}}
@if (! empty($greeting))
# {!! $greeting !!}
@endif

{{-- Intro Lines --}}
@foreach ($introLines as $line)
{!! $line !!}<br>
@endforeach

{{-- Action Button --}}
@isset($actionText)
@component('mail::button', ['url' => $actionUrl, 'color' => 'blue'])
{{ $actionText }}
@endcomponent
@endisset

{{-- Outro Lines --}}
@foreach ($outroLines as $line)
{!! $line !!}<br>
@endforeach

<!-- Salutation -->
@if (! empty($salutation))
{{ $salutation }}
@else
Regards,<br>The {{ config('app.name') }} Team.
@endif

<!-- Subcopy -->
@isset($actionText)
@component('mail::subcopy')
If you’re having trouble clicking the "{{ $actionText }}" button, copy and paste the URL below
into your web browser: [{{ $actionUrl }}]({{ $actionUrl }})
@endcomponent
@endisset
@endcomponent
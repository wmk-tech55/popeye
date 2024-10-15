@component('mail::layout')
{{-- Header --}}
{{-- Header --}}
@slot('header')
@component('mail::header', ['url' => route('home')])
{{-- {{ config('app.name') }} --}}
{{ getSettings()->name }}
@endcomponent
@endslot

{{-- Body --}}
<div align ="{{ getDirection() === 'rtl' ? 'right' : 'left' }}">
{{ Illuminate\Mail\Markdown::parse($slot) }}
</div>

{{-- Subcopy --}}
@isset($subcopy)
@slot('subcopy')
@component('mail::subcopy')
{{ $subcopy }}
@endcomponent
@endslot
@endisset

{{-- Footer --}}
@slot('footer')
@component('mail::footer')
© {{ date('Y') }} {{ getSettings()->name }}. @lang('All rights reserved.')
@endcomponent
@endslot
@endcomponent

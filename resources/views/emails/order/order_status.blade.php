@component('mail::message')

{{-- @component('mail::promotion')
 
<img src="{{ asset('/assets/img/logo-2.png') }}" alt="{{ getSettings()->name }}">
@endcomponent --}}

# @lang($subject)

@lang($message)
 
@lang('notifications.thanks'),<br>
{{ getSettings()->name }}
@endcomponent
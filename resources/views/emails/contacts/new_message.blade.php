@component('mail::message')

@component('mail::promotion')
    <img src="{{ asset('/assets/img/logo.png') }}" alt="{{ getSettings()->name }}">
@endcomponent

# @lang($subject)

@lang($message)

@component('mail::button', ['url' => $review_link])
    @lang('notifications.review')
@endcomponent

@lang('notifications.thanks'),<br>
{{ getSettings()->name }}
@endcomponent
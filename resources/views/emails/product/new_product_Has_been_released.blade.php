@component('mail::message')
 

# @lang($subject)

@lang($message)

@component('mail::button', ['url' => $review_link])
    @lang('notifications.review')
@endcomponent

@lang('notifications.thanks'),<br>
{{ getSettings()->name }}
@endcomponent
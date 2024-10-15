@component('mail::message')

 

# @lang($subject)

@lang($message)

@component('mail::button', ['url' => $review_link])
    @lang('notifications.rate')
@endcomponent

@lang('notifications.thanks'),<br>
{{ getSettings()->name }}
@endcomponent
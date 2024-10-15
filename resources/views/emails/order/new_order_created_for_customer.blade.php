@component('mail::message')
 

# @lang($subject)

@lang($message)

{{-- @component('mail::button', ['url' => $review_link ,'color'=>"btn-gold-border"])
    @lang('notifications.review')
@endcomponent --}}

@lang('notifications.thanks'),<br>
{{ getSettings()->name }}
@endcomponent
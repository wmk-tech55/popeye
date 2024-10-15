@component('mail::message')
    @lang($subject)
    <br />
    <h1 style="text-align: center">@lang($message)</h1>
    <hr />
    @lang('notifications.thanks'),<br>
    {{ getSettings()->name_by_lang }}
@endcomponent

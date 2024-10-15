@component('mail::message')
#{{ $title }}
<br />
<h1 style="text-align: center">{{ $message }}</h1>
<hr />
@lang('notifications.thanks'),<br>
{{ getSettings()->name_by_lang }}
@endcomponent

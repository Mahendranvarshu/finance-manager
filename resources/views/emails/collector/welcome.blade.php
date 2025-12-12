@component('mail::message')
# Welcome {{ $collector->name }}!

Your account has been successfully created.

**Username:** {{ $collector->username }}

We are excited to have you on board.  
Feel free to contact us anytime.

Thanks,<br>
{{ config('app.name') }}
@endcomponent

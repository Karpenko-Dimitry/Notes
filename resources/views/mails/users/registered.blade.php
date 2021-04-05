@component('mail::message')
# Thank you for registering, {{$user->name}}

You have registered in Notes Application

@component('mail::button', ['url' => Request::root()])
Let's go
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent

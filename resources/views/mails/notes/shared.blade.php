@component('mail::message')
# Somebody shared with you

User {{$user->name}} has shared with you note "{{$note->title}}"
@component('mail::button', ['url' => Request::root() . '/notes/' . $note->uid])
Show note
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent

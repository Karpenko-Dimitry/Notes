@extends('layout.base')

@section('content')
    <div class="col-md-8 blog-main mt-3">
        <h2 class="text-center">{{__('title.delete_note', ['note' => $note->title])}}</h2>
        {!! Form::open(['action' => ['\App\Http\Controllers\NoteController@destroy', $note->uid], 'method' => 'delete']) !!}
            <div class="d-flex justify-content-center mt-3">
                {!! Form::submit(__('common.yes'), ['class' => 'btn btn-secondary btn-md mr-3']) !!}
                {!! link_to(url_locale("/notes/$note->uid"), __('common.no'), ['class' => 'btn btn-secondary btn-md']) !!}
            </div>
        {!! Form::close() !!}
    </div>
@endsection

@section('side-bar')
    @include('partials.side-bar')
@endsection

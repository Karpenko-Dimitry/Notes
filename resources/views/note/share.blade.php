@extends('layout.base')

@section('jumbotron')
    <div class="col-md-8 blog-main">
        <h3 class="pb-4 mb-4 font-italic border-bottom">
            {{__('title.share_note', ['note' => $note->title])}}
        </h3>
    </div>
    {!! Form::open(['action' => ['\App\Http\Controllers\User\ShareController@store', $note->id], 'method' => 'post']) !!}
        <div class="form-row">
            <div class="form-group col-md-6">
                {!! Form::text('email', null, ['class' => 'form-control', 'id' => 'inputEmail4', 'placeholder' => __('common.email')]) !!}

                @if ($errors->first('email'))
                    <p class="text-danger">{{$errors->first('email')}}</p>
                @endif
                @if (Session::has('message'))
                    <p class="text-success">{{Session::get('message')}}</p>
                @endif
            </div>
        </div>
        {!! Form::submit(__('common.send'), ['class' => 'btn btn-primary']) !!}
        {!! link_to('/user/'.Auth::id().'/notes', __('common.back')) !!}
    {!! Form::close() !!}
@endsection

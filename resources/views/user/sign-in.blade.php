@extends('layout.base')

@section('jumbotron')
    <div class="container d-flex justify-content-center">
        {!! Form::open(['url' => url_locale('/sign-in'), 'method' => 'post', 'class' => 'form-signin page']) !!}
            <img class="mb-4" src={{asset('/assets/img/note.png')}} alt="" width="72" height="72" />
            <h1 class="h3 mb-3 font-weight-normal">{{__('title.signin_title')}}</h1>

            {!! Form::email('email', null, ['class' => 'form-control', 'id' => 'inputEmail4', 'placeholder' => __('common.email')]) !!}
            @error('email')
                <p class="text-danger">{{$errors->first('email')}}</p>
            @enderror
            {!! Form::password('password', ['class' => 'form-control', 'id' => 'inputPassword4', 'placeholder' => __('common.password')]) !!}
            @error('password')
                <p class="text-danger">{{$errors->first('password')}}</p>
            @enderror
            @if (Session::has('message'))
                <p class="text-danger"> {{Session::get('message')}}</p>
            @endif

            {!! Form::submit(__('common.signin'), ['class' => 'btn btn-md btn-secondary btn-block mt-3 mr-0']) !!}
            {!! link_to(url_locale('/login/oauth?state=web'), __('common.via_google'), ['class' => 'mt-3']) !!}
            <p class="mt-5 mb-3 text-muted">&copy; 2020-2021</p>
        {!! Form::close() !!}
    </div>
@endsection

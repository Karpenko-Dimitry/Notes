@extends('layout.base')

@section('jumbotron')
    <div class="container d-flex justify-content-center">

        {!! Form::open(['url' => url_locale('/users'), 'method' => 'post', 'class' => 'form-signin page']) !!}
            <img class="mb-4" src={{asset('/assets/img/note.png')}} alt="" width="72" height="72" />
            <h1 class="h3 mb-3 font-weight-normal">{{__('title.signup_title')}}</h1>

            {!! Form::text('name', null, ['class' => 'form-control', 'id' => 'inputEmail4', 'placeholder' => __('common.name')]) !!}
            @error('name')
                <p class="text-danger"> {{$errors->first('name')}} </p>
            @enderror

            {!! Form::email('email', null, ['class' => 'form-control', 'id' => 'inputEmail4', 'placeholder' => __('common.email')]) !!}
            @error('email')
                <p class="text-danger"> {{$errors->first('email')}} </p>
            @enderror

            {!! Form::password('password', ['class' => 'form-control mt-3', 'id' => 'inputPassword4', 'placeholder' => __('common.password')]) !!}
            {!! Form::password('password_confirmation', ['class' => 'form-control mt-3', 'id' => 'inputPassword4', 'placeholder' => __('common.pas_conf')]) !!}
            @error('password')
                <p class="text-danger"> {{$errors->first('password')}} </p>
            @enderror

            {!! Form::submit(__('common.signup'), ['class' => 'btn btn-md btn-secondary btn-block mt-3 mr-']) !!}
            {!! link_to(url_locale('/login/oauth?state=web'), __('common.via_google'), ['class' => 'mt-3']) !!}
            <p class="mt-5 mb-3 text-muted">&copy; 2020-2021</p>
        {!! Form::close() !!}
    </div>
@endsection

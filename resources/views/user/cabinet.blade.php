@extends('layout.base')

@section('jumbotron')
    <main role="main" class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative justify-content-center">
                    <div class="col-md-auto d-lg-block d-flex justify-content-center">
                        <div class="avatar-place bd-placeholder-img border" style='background-image: url("{{$user->avatar ? \Illuminate\Support\Facades\Storage::url($user->avatar): asset('/assets/img/no-image.png')}}")'>

                        </div>
                    </div>
                    <div class="col-md p-4 d-flex flex-column position-static">
                        {!! Form::open(['url' => "/user/$user->id/avatar", 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'avatar-store-form']) !!}
                            {!! Form::file('user_file', ['id' =>'avatar_image', 'hidden']) !!}
                            <strong class="d-inline-block mb-2 text-primary">
                                {!! Form::label('avatar_image', __('common.change_avatar'), ['class' => 'href']) !!}
                            </strong>
                            @error('user_file')
                                <p class="text-danger">{{$errors->first('user_file')}}</p>
                            @enderror
                        {!! Form::close() !!}

                        <h3 class="mb-0">
                            {{__('title.cabinet', ['user' => $user->name])}}
                        </h3>
                        <div class="mb-1 text-muted">
                            {{__('common.created_at', ['date' => $user->created_at])}}
                        </div>
                        <p class="card-text mb-auto">
                            {{__('common.user_card_intro')}}
                        </p>
                        <div class="d-flex justify-content-center mt-3">
                            <a href="/" class="btn btn-secondary btn-md ">
                                {{__('common.home')}}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>
@endsection

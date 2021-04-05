<div class="nav-scroller py-1 mb-2 pt-0">
    <nav class="navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand" href="{{url_locale('/')}}">ALL Notes</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample02" aria-controls="navbarsExample02" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse d-flex justify-content-end" id="navbarsExample02">
           <ul class="navbar-nav">
               <div class="dropdown mr-3">
                   <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                       {{__('common.' . App::getLocale())}}
                   </a>
                   <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                       @foreach($languages as $lang)
                           <a class="dropdown-item" href="{{url_locale(null, [], $lang)}}">{{__("common.$lang")}}</a>
                       @endforeach
                   </div>
               </div>
                @auth
                    <li class="nav-item active">
                        <a class="nav-link nav-btn text-center" href="{{url_locale('/user/'.Auth::id().'/notes')}}">{{auth_user()->name}}</a>
                    </li>
                    <li class="nav-item">
                        {!! Form::open(['url' => url_locale('log-out'), 'method' => 'delete']) !!}
                            {!! Form::submit(__('common.logout'), ['class' => 'nav-link nav-btn']) !!}
                        {!! Form::close() !!}
                    </li>
                @endauth
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{url_locale('/sign-up')}}">{{__('common.signup')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{url_locale('/sign-in')}}">{{__('common.signin')}}</a>
                    </li>
                @endguest
            </ul>
            {!! Form::open(['url' => url_locale('/notes'), 'method' => 'get', 'class' => 'form-inline my-2 my-md-0  d-flex justify-content-center']) !!}
                {!! Form::submit(__('common.search'), ['class' => 'nav-link nav-btn']) !!}
                {!! Form::text('query', null, ['class' => 'form-control']) !!}
            {!! Form::close() !!}
        </div>
    </nav>
</div>

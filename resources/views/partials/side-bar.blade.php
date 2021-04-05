
<aside class="col-lg-4 blog-sidebar">
    @auth
        <div class="p-4 mb-3 bg-light rounded">
            <h4 class="font-italic">
                <a href="/user/{{Auth::id()}}/cabinet" className="ml-0">
                    <i class="fas fa-home btn btn-secondary btn-sm"></i>
                </a>
                {{__('title.cabinet', ['user' => ''])}}
            </h4>
        </div>
        <div class="p-4 mb-3 bg-light rounded">
            <h4 class="font-italic">
                {{__('title.add_title')}}
                <a href="{{url_locale('/notes/create')}}" class="ml-3">
                    <i class="fa fa-plus btn btn-secondary btn-sm"></i>
                </a>
            </h4>
        </div>
    @endauth
    @guest
        <div class="p-4 mb-3 bg-light rounded">
            <h4 class="font-italic">{{__('title.log_title')}}</h4>
            {!! Form::open(['url' => '/sign-in', 'method' => 'post']) !!}
                <div class="form-row">
                    <div class="form-group col-md-12 mb-0">
                        {!! Form::label('inputEmail4', __('common.email')) !!}
                        {!! Form::email('email', null, ['class' => 'form-control', 'id' => 'inputEmail4']) !!}
                    </div>
                    @error('email')
                        <p class="text-danger col-md-12">{{$errors->first('email')}}</p>
                    @enderror
                    <div class="form-group col-md-12 mt-3 mb-0">
                        {!! Form::label('inputPassword4', __('common.password')) !!}
                        {!! Form::password('password', ['class' => 'form-control', 'id' => 'inputPassword4']) !!}
                    </div>
                    @error('password')
                        <p class="text-danger col-md-12">{{$errors->first('password')}}</p>
                    @enderror

                    @if(Session::has('message'))
                        <p class="text-danger col-md-12">{{Session::get('message')}}</p>
                    @endif

                </div>

                {!! Form::submit(__('common.signin'), ['class' => 'btn btn-secondary btn-sm mt-3']) !!}

                {!! link_to('/sign-up', __('common.signup'), ['class' => 'btn btn-secondary btn-sm mt-3']) !!}
                {!! link_to('/login/oauth?state=web', __('common.via_google'), ['class' => 'btn btn-primary btn-sm mt-3']) !!}

            {!! Form::close() !!}
        </div>
    @endguest
    @isset($categoriesList)
        <div class="p-4 mb-3 bg-light rounded">
            <h4 class="font-italic">{{__('title.range_title')}}</h4>
            {!! Form::open(['url' => '/' . request()->path(), 'method' => 'get', 'class' => 'd-flex flex-wrap category-range']) !!}
                @foreach ($categoriesList as $category)
                    <div class="w-30 mr-3">
                        {!! Form::checkbox('category[]', $category->id, (in_array($category->id, $filter['category']) ? 'checked' : ''),['class' => 'category-checkbox', 'id' => 'category'.$category->id]) !!}
                        {!! Form::label('category'.$category->id, $category->name) !!}
                    </div>
                @endforeach
            {!! Form::close() !!}
        </div>
    @endisset
</aside><!-- /.blog-sidebar -->

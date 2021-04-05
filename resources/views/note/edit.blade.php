@extends('layout.base')

@section('jumbotron')
    <div class="card text-center">
        <div class="card-header ">
            <ul class="nav nav-tabs card-header-tabs">
                @foreach($languages as $lang)
                    @if ($note->hasTranslation($lang))
                        <li class="nav-item ">
                            <a class="nav-link language-card" id="{{$lang}}" href="#">{{__('common.' . $lang)}}</a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
        <div class="card-body">
            {!! Form::model($note, ['action' => ['\App\Http\Controllers\NoteController@update', $note->uid], 'method' => 'put', 'files' => true]) !!}
                @foreach($languages as $lang)
                    @if ($note->hasTranslation($lang))
                        <div class="language-body" id="{{$lang}}_body">
                            <h2>{{__('title.edit_note')}} ({{__('common.' . $lang)}})</h2>
                            <div class="input-group mt-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">{{__('common.title')}}</span>
                                </div>
                                {!! Form::text($lang . '_title', $note->title, ['class' => 'form-control']) !!}
                            </div>
                            <p class="text-danger">
                                {{$errors->first($lang . '_title') ? $errors->first($lang . '_title') : ''}}
                            </p>
                            <div class="input-group mt-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">{{__('common.text')}}</span>
                                </div>
                                {!! Form::textarea($lang . '_content', $note->content, ['class' => 'form-control']) !!}
                            </div>
                            <p class="text-danger">
                                {{$errors->first($lang . '_content') ? $errors->first($lang . '_content') : ''}}
                            </p>
                        </div>
                    @endif
                @endforeach
            <div class="d-flex flex-wrap">
                <p class="font-italic mr-3 mb-0">{{__('common.choose_cat')}}</p>
                @foreach ($categoriesList as $category)
                    <div class="mr-3">
                        {!! Form::checkbox('category[]', $category->id, (in_array($category->id, $noteCategories) ? 'checked' : ''), ['id' => 'category'.$category->id]) !!}
                        {!! Form::label('category'.$category->id, $category->name, ['class' => 'mb-0']) !!}
                    </div>
                @endforeach
            </div>
            <p class="text-danger">
                {{$errors->first('category') ? $errors->first('category') : ''}}
            </p>
            <div class="form-group mt-3 text-left">
                {!! Form::label('userFile', __('common.upload'), ['class' => 'btn btn-secondary btn-sm']) !!}
                {!! Form::file('user_file[]', ['id' => 'userFile', 'hidden', 'class' => 'form-control-file']) !!}
                <ul class="files-list">
                </ul>
            </div>
            <div class="form-group form-check mt-3 text-left">
                {!! Form::checkbox('public', '1', ($note->public ? 'checked' : '0'), ['class' => 'form-check-input']) !!}
                {!! Form::label('exampleCheck1', __('common.m_public'), ['class' => 'form-check-label']) !!}
            </div>
            <div class="form-group form-check mt-3 text-left">
                {!! Form::label('tags', 'Hash Tag', ['class' => 'form-check-label']) !!}
                {!! Form::select('tags', $tags, $noteTags, ['class' => 'form-check-input chosen-select', 'id' => 'hashtag']) !!}
            </div>
            <div class="d-flex justify-content-center mt-3">
                {!! Form::submit(__('common.edit_btn'), ['class' => 'btn btn-secondary btn-lg']) !!}
            </div>
            {!! Form::close() !!}
        </div>
@endsection

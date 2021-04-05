@extends('layout.base')

@section('content')
    <div class="col-md-8 blog-main">
        <h3 class="pb-4 mb-4 font-italic border-bottom">
            {{trans_choice('title.user_notes_title', 1, ['user' => $note->user->name])}}
        </h3>
        <div class="blog-post">
            <h2 class="blog-post-title">
                {{$note->title}}
                @if(!$note->public)
                    <span style="color: red">({{__('common.private_label')}})</span>
                @endif
            </h2>
            <p class="blog-post-meta">
                {{__('common.created_at')}} {{\Carbon\Carbon::parse($note->created_at)->format('F j, Y')}} <br> {{__('common.by')}} {{$note->user->name}}
                @can('update', $note)
                    <a href="{{url_locale("/notes/$note->uid/share")}}">{{__('common.share')}}, </a>
                    <a href="{{url_locale("/notes/$note->uid/edit")}}">{{__('common.edit')}},</a>
                @endcan

                @can('delete', $note)
                    <a href="{{url_locale("/notes/$note->uid/delete")}}">{{__('common.delete')}}</a>
                @endcan
                <br> {{__('common.category_short')}}
                @foreach ($note->categories as $category)
                    <a href="{{url_locale("/category/$category->id/notes")}}">#{{$category->name}}</a>
                @endforeach
            </p>
            <p>
                {!! $note->getParsedContent() !!}
            </p>
            <p>
                @foreach ($note->tags as $tag)
                    <a href="/tag/{{$tag->id}}/notes">#{{$tag->name}}</a>
                @endforeach
            </p>
            @if(count($note->files) > 0)
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{__('common.download')}}
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            @foreach($note->files as $key => $file)
                                <a class="dropdown-item" href="{{Storage::url($file->path)}}" download>
                                    {{__('common.download_file')}} №{{$key+1}}
                                </a>
                            @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('side-bar')
    @include('partials.side-bar')
@endsection

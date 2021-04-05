<?php
/** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Note[] $notes */
?>
@if(count($notes))
    @foreach($notes as $note)
        <div class="blog-post">
            <h2 class="blog-post-title">
                {{$note->title}}
                @if(!$note->public)
                    <sup class="private-label">({{__('common.private_label')}})</sup>
                @endif
            </h2>
            <p class="blog-post-meta">
                {{__('common.created_at')}} {{\Carbon\Carbon::parse($note->created_at)->format('F j, Y')}} {{__('common.by')}} {{$note->user->name}}
                @can ('update', $note)
                    <a href="{{url_locale("/notes/$note->uid/share")}}">{{__('common.share')}}, </a>
                    <a href="{{url_locale("/notes/$note->uid/edit")}}">{{__('common.edit')}},</a>
                @endcan
                @can ('delete', $note)
                    <a href="{{url_locale("/notes/$note->uid/delete")}}">{{__('common.delete')}}</a>
                @endcan
                <br> {{__('common.category_short')}}
                @foreach ($note->categories as $category)
                    <a href="{{url_locale("/notes?category%5B%5D=$category->id")}}">{{$category->name}}</a>
                @endforeach
            </p>
            <p>
                {{Str::limit($note->content, 150)}}...

            </p>
            <p>
                @foreach ($note->tags as $tag)
                    <a href="/notes?tag={{$tag->name}}">#{{$tag->name}}</a>
                @endforeach
            </p>
            <p>
                <a class="btn btn-secondary btn-sm" href="{{url_locale("/notes/$note->uid")}}">{{__('common.details')}}</a>
            </p>
            <hr>
        </div>
    @endforeach
@else
    <h2 class="pb-4 mb-4 font-italic text-center">
        {{__('common.no_notes')}}
    </h2>

@endif

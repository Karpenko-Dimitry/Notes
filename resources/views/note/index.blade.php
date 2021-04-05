@extends('layout.base')

@section('content')
    <div class="col-lg-8 blog-main">
        <div class="container">
            <div class="row border-bottom mb-1 pb-1">
                <h3 class="col-md-7 font-italic mb-0">
                    {{$title}}
                </h3>
                @include('partials.range-notes')
            </div>
        </div>
        <div class="container">
            @if (!$filter['grid_notes'])
                @include('partials.notes-grid-list')
            @else
                @include('partials.notes-grid-block')
            @endif
        </div>
        <nav class="pagination">
            {{$notes->appends($filter)->links('partials.pagination')}}
        </nav>
    </div>
@endsection

@section('side-bar')
    @include('partials.side-bar')
@endsection

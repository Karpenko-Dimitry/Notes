@extends('layout.base')

@section('content')
    <div class="col-lg-8 blog-main">
        <h3 class="pb-4 mb-4 font-italic border-bottom">
            Notes by the category: "{{$category->name}}".
        </h3>
        <div class="container">
            @include('partials.notes-grid-block');
        </div>
        <nav class="pagination">
            <div class="col-md-8">
                <form class="per-page-form form-row justify-content-between align-items-end" action="/user/{{Auth::id()}}/notes" method="get">
                    <div class="form-group col-md-3 mb-0">
                        <label for="per-page-select">Per page</label>
                        <select id="per-page-select" class="form-control per-page-select" name="per_page">
                            @foreach([5, 10, 15, 20] as $page)
                                <option {{ $page == $perPage ? 'selected' : '' }} value={{ $page }}>
                                    {{ $page }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3 mb-0">
                        <label for="range-shared">Shared</label>
                        <select id="range-shared" class="form-control range-shared" name="shared">
                            @if ($shared)
                                <option value="0">All</option>
                                <option value="1" selected>Shared</option>
                            @else
                                <option value="0" selected>All</option>
                                <option value="1">Shared</option>
                            @endif
                        </select>
                    </div>
                </form>
            </div>
            {{$notes->links('partials.pagination')}}
        </nav>
    </div>
@endsection

@section('side-bar')
    @include('partials.side-bar')
@endsection

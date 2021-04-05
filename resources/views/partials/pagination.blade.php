@if ($paginator->hasPages())
    <div class="pagination-list">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <div class="pagination-item disabled"><span>«</span></div>
        @else
            <div ><a class="pagination-item" href="{{ $paginator->previousPageUrl() }}" rel="prev">«</a></div>
        @endif

        @if($paginator->currentPage() > 3)
            <div ><a class="pagination-item hidden-xs" href="{{ $paginator->url(1) }}">1</a></div>
        @endif
        @if($paginator->currentPage() > 4)
            <div><span>...</span></div>
        @endif
        @foreach(range(1, $paginator->lastPage()) as $i)
            @if($i >= $paginator->currentPage() - 1 && $i <= $paginator->currentPage() + 1)
                @if ($i == $paginator->currentPage())
                    <div class="pagination-item active"><span>{{ $i }}</span></div>
                @else
                    <div ><a class="pagination-item" href="{{ $paginator->url($i) }}">{{ $i }}</a></div>
                @endif
            @endif
        @endforeach
        @if($paginator->currentPage() < $paginator->lastPage() - 3)
            <li><span>...</span></li>
        @endif
        @if($paginator->currentPage() < $paginator->lastPage() - 2)
            <div ><a class="pagination-item hidden-xs" href="{{ $paginator->url($paginator->lastPage()) }}">{{ $paginator->lastPage() }}</a></div>
        @endif

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <div ><a class="pagination-item" href="{{ $paginator->nextPageUrl() }}" rel="next">»</a></div>
        @else
            <div class="pagination-item disabled"><span>»</span></div>
        @endif
    </div>
@endif

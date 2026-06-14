@if ($paginator->hasPages())
    <div class="admin-panel-pagination">
        @if ($paginator->onFirstPage())
            <span class="admin-panel-pagination-arrow is-disabled" aria-hidden="true">‹</span>
        @else
            <a
                href="{{ $paginator->previousPageUrl() }}"
                class="admin-panel-pagination-arrow"
                aria-label="Poprzednia strona"
            >‹</a>
        @endif

        <div class="admin-panel-pagination-scroll" tabindex="0">
            @for ($page = 1; $page <= $paginator->lastPage(); $page++)
                @if ($page === $paginator->currentPage())
                    <span class="admin-panel-pagination-btn is-current">{{ $page }}</span>
                @else
                    <a href="{{ $paginator->url($page) }}" class="admin-panel-pagination-btn">{{ $page }}</a>
                @endif
            @endfor
        </div>

        @if ($paginator->hasMorePages())
            <a
                href="{{ $paginator->nextPageUrl() }}"
                class="admin-panel-pagination-arrow"
                aria-label="Następna strona"
            >›</a>
        @else
            <span class="admin-panel-pagination-arrow is-disabled" aria-hidden="true">›</span>
        @endif
    </div>
@endif

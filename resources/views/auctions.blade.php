@extends('layout')

@push('styles')
    @vite(['resources/css/auctions.css', 'resources/js/auctions/category-picker.js'])
@endpush

@section('content')
    <section id="filters">
        <div id="filters-left">
            <form method="GET" action="{{ route('auctions.index') }}" id="sortby">
                @if (request()->filled('q'))
                    <input type="hidden" name="q" value="{{ request('q') }}">
                @endif
                @if (request()->filled('price_min'))
                    <input type="hidden" name="price_min" value="{{ request('price_min') }}">
                @endif
                @if (request()->filled('price_max'))
                    <input type="hidden" name="price_max" value="{{ request('price_max') }}">
                @endif
                @if ($selectedCategoryId ?? null)
                    <input type="hidden" name="category" value="{{ $selectedCategoryId }}">
                @endif
                <div>Sortuj</div>
                <select name="sort" onchange="this.form.submit()">
                    <option value="newest" @selected(request('sort', 'newest') === 'newest')>Najnowsze</option>
                    <option value="oldest" @selected(request('sort') === 'oldest')>Najstarsze</option>
                </select>
            </form>
            <div id="category-filter">
                <div>Kategoria</div>
                <div
                    id="category-picker"
                    class="category-picker"
                    data-categories='@json($categoryTree)'
                    data-selected-category="{{ $selectedCategoryId ?? '' }}"
                >
                    <form method="GET" action="{{ route('auctions.index') }}" id="category-filter-form">
                        @if (request()->filled('q'))
                            <input type="hidden" name="q" value="{{ request('q') }}">
                        @endif
                        @if (request()->filled('price_min'))
                            <input type="hidden" name="price_min" value="{{ request('price_min') }}">
                        @endif
                        @if (request()->filled('price_max'))
                            <input type="hidden" name="price_max" value="{{ request('price_max') }}">
                        @endif
                        @if (request()->filled('sort'))
                            <input type="hidden" name="sort" value="{{ request('sort') }}">
                        @endif
                        @if ($selectedCategoryId ?? null)
                            <input type="hidden" name="category" value="{{ $selectedCategoryId }}">
                        @endif
                    </form>
                    <div class="category-picker__trigger">
                        {{ $selectedCategoryName ?? 'Wszystkie kategorie' }}
                    </div>
                    <div class="category-picker__panel">
                        <div class="category-picker__columns"></div>
                    </div>
                </div>
            </div>
        </div>
        <form method="GET" action="{{ route('auctions.index') }}" id="price">
            @if (request()->filled('q'))
                <input type="hidden" name="q" value="{{ request('q') }}">
            @endif
            @if (request()->filled('sort'))
                <input type="hidden" name="sort" value="{{ request('sort') }}">
            @endif
            @if ($selectedCategoryId ?? null)
                <input type="hidden" name="category" value="{{ $selectedCategoryId }}">
            @endif
            <div>Cena</div>
            <div id="price-inputs">
                <input
                    type="number"
                    name="price_min"
                    placeholder="od"
                    min="0"
                    step="1"
                    value="{{ request('price_min') }}"
                    onfocus="this.dataset.initialValue = this.value"
                    onblur="if (this.dataset.initialValue !== this.value) this.form.submit()"
                    onkeydown="if (event.key === 'Enter') { event.preventDefault(); this.form.submit(); }"
                >
                <input
                    type="number"
                    name="price_max"
                    placeholder="do"
                    min="0"
                    step="1"
                    value="{{ request('price_max') }}"
                    onfocus="this.dataset.initialValue = this.value"
                    onblur="if (this.dataset.initialValue !== this.value) this.form.submit()"
                    onkeydown="if (event.key === 'Enter') { event.preventDefault(); this.form.submit(); }"
                >
            </div>
        </form>
    </section>
    <section id="searched-auctions">
        @forelse($auctions as $auction)
            <a href="{{ route('auctions.show', $auction) }}" class="searched-auction-card">
                <img
                    src="{{ $auction->image?->file_url ?? asset('assets/placeholder.png') }}"
                    class="searched-auction-img"
                    alt="{{ $auction->name }}"
                    style="object-fit: cover;"
                />
                <div class="content">
                    <div class="title-price">
                        <div class="title">{{ $auction->name }}</div>
                        <div class="price">{{ number_format($auction->price, 0, ',', ' ') }} zł</div>
                    </div>
                    <div class="description">
                        {{ Str::limit(Str::words($auction->description, 30, ''), 200, '...') }}
                    </div>
                    <div class="date-location">
                        <div class="date">{{ $auction->createdAt->format('d.m.Y') }}</div>
                        <div class="date">{{ $auction->location }}</div>
                    </div>
                </div>
            </a>
        @empty
            <p>Brak aukcji.</p>
        @endforelse
    </section>
    @if ($auctions->hasPages())
        <div id="pagination">
            @for ($page = 1; $page <= $auctions->lastPage(); $page++)
                @if ($page === $auctions->currentPage())
                    <span class="pagination-btn is-current">{{ $page }}</span>
                @else
                    <a href="{{ $auctions->url($page) }}" class="pagination-btn">{{ $page }}</a>
                @endif
            @endfor
        </div>
    @endif
@endsection

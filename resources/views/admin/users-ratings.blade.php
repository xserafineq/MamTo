@extends('layout')

@push('styles')
    @vite(['resources/css/admin-panel.css'])
@endpush

@section('content')
    <section class="admin-panel">
        <h1>Polecenia użytkownika</h1>

        <p class="admin-panel-desc">
            {{ $user->firstName }} {{ $user->lastName }}
            @if($recommendationPercent !== null)
                · {{ $recommendationPercent }}% oceniających poleca ({{ $ratings->count() }} {{ $ratings->count() === 1 ? 'ocena' : ($ratings->count() < 5 ? 'oceny' : 'ocen') }})
            @else
                · Brak poleceń
            @endif
        </p>

        @include('admin.partials.nav')

        @if (session('success'))
            <div class="alert alert-success py-2 small" role="alert">{{ session('success') }}</div>
        @endif

        <p class="admin-panel-back-link-wrap">
            <a href="{{ route('admin.users.index', $returnQuery) }}" class="admin-panel-back-link">← Wróć do listy użytkowników</a>
        </p>

        <div class="admin-panel-ratings-list">
            @forelse($ratings as $rating)
                <article class="admin-panel-rating-card">
                    <div class="admin-panel-rating-card__info">
                        <div class="admin-panel-rating-card__reviewer">
                            @if($rating->reviewer)
                                {{ $rating->reviewer->firstName }} {{ $rating->reviewer->lastName }}
                                <span class="admin-panel-rating-card__email">{{ $rating->reviewer->email }}</span>
                            @else
                                <span class="text-muted">Usunięty użytkownik</span>
                            @endif
                        </div>
                        <span class="admin-panel-rating-card__badge admin-panel-rating-card__badge--{{ $rating->rating === 1 ? 'positive' : 'negative' }}">
                            {{ $rating->rating === 1 ? 'Poleca' : 'Nie poleca' }}
                        </span>
                    </div>
                    <button
                        type="button"
                        class="btn btn-outline-danger btn-sm admin-panel-rating-delete-btn"
                        data-bs-toggle="modal"
                        data-bs-target="#deleteRatingModal"
                        data-reviewer-name="{{ $rating->reviewer ? $rating->reviewer->firstName . ' ' . $rating->reviewer->lastName : 'tego użytkownika' }}"
                        data-delete-url="{{ route('admin.users.ratings.destroy', array_merge(['user' => $user, 'rating' => $rating], $returnQuery)) }}"
                    >
                        Usuń
                    </button>
                </article>
            @empty
                <p class="admin-panel-admins-empty">Ten użytkownik nie ma jeszcze żadnych poleceń.</p>
            @endforelse
        </div>
    </section>

    <div class="modal fade" id="deleteRatingModal" tabindex="-1" aria-labelledby="deleteRatingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="deleteRatingForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    @foreach($returnQuery as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteRatingModalLabel">Usuń polecenie</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Zamknij"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-0" id="deleteRatingModalText"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Anuluj</button>
                        <button type="submit" class="btn btn-danger">Usuń polecenie</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/admin-user-ratings.js'])
@endpush

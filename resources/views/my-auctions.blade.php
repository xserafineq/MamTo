@extends('layout')

@push('styles')
    @vite(['resources/css/my-auctions.css'])
@endpush

@section('content')
    <section class="my-auctions-page">
        <h1>Moje ogłoszenia</h1>

        <div class="my-auctions-stats">
            <span class="my-auctions-stats__active">Aktywne: {{ $activeCount }}</span>
            <span class="my-auctions-stats__closed">Zamknięte: {{ $closedCount }}</span>
            @if($pendingCount > 0)
                <span class="my-auctions-stats__pending">Do akceptacji: {{ $pendingCount }}</span>
            @endif
        </div>

        <a href="{{ route('auctions.create') }}" class="btn btn-primary my-auctions-new-btn">Nowa aukcja</a>

        @if (session('success') && ! session('job_pending_approval'))
            <div class="alert alert-success py-2 small" role="alert">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger py-2 small" role="alert">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @forelse($auctions as $auction)
            <article class="my-auctions-card {{ $auction->status !== 'aktywna' ? 'my-auctions-card--closed' : '' }}">
                @if($auction->status === 'aktywna' || auth()->user()?->isAdmin || (auth()->check() && (int) auth()->id() === (int) $auction->userId))
                    <a href="{{ route('auctions.show', $auction) }}" class="my-auctions-card__link">
                @else
                    <div class="my-auctions-card__link">
                @endif
                    <img
                        src="{{ $auction->image?->file_url ?? asset('assets/placeholder.png') }}"
                        alt="{{ $auction->name }}"
                    >
                    <div class="my-auctions-card__info">
                        <div class="my-auctions-card__title">{{ $auction->name }}</div>
                        <div class="my-auctions-card__price">{{ number_format($auction->price, 0, ',', ' ') }} zł</div>
                        <div class="my-auctions-card__meta">
                            {{ $auction->location }} · {{ $auction->createdAt->format('d.m.Y') }}
                        </div>
                        @if($auction->status !== 'aktywna')
                            <span class="my-auctions-card__badge">Zamknięte</span>
                        @elseif(!$auction->approved)
                            <span class="my-auctions-card__badge my-auctions-card__badge--pending">Oczekuje na akceptację</span>
                        @endif
                    </div>
                @if($auction->status === 'aktywna' || auth()->user()?->isAdmin || (auth()->check() && (int) auth()->id() === (int) $auction->userId))
                    </a>
                @else
                    </div>
                @endif

                @if($auction->status === 'aktywna')
                    <div class="my-auctions-card__actions">
                        <a href="{{ route('auctions.edit', $auction) }}" class="btn btn-outline-primary btn-sm">Edytuj</a>
                        <button
                            type="button"
                            class="btn btn-outline-danger btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#closeModal{{ $auction->id }}"
                        >
                            Zamknij
                        </button>
                    </div>
                @endif
            </article>

            @if($auction->status === 'aktywna')
                <div class="modal fade" id="closeModal{{ $auction->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Zamknij ogłoszenie</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Zamknij"></button>
                            </div>
                            <div class="modal-body">
                                Czy na pewno chcesz zamknąć ogłoszenie <strong>{{ $auction->name }}</strong>?
                                Tej operacji <strong>nie można cofnąć</strong>.
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
                                <form action="{{ route('auctions.close', $auction) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-danger">Tak, zamknij ogłoszenie</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @empty
            <p>Nie masz jeszcze żadnych ogłoszeń.</p>
            <a href="{{ route('auctions.create') }}" class="btn btn-primary mt-3">Utwórz aukcję</a>
        @endforelse
    </section>

    @if (session('job_pending_approval'))
        <div class="modal fade" id="jobPendingModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ogłoszenie o pracę</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Zamknij"></button>
                    </div>
                    <div class="modal-body">
                        Ogłoszenie czeka na akceptację administratora.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    @if (session('job_pending_approval'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const modal = document.getElementById('jobPendingModal');
                if (modal) {
                    bootstrap.Modal.getOrCreateInstance(modal).show();
                }
            });
        </script>
    @endif
@endpush

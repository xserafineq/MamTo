@extends('layout')

@push('styles')
    @vite(['resources/css/messages.css'])
@endpush

@section('content')
    <section id="chat-page">
        <a href="{{ route('chats.index', $isArchived ? ['tab' => 'archived'] : []) }}" class="chat-back-link">← Wróć do wiadomości</a>

        <article class="chat-header-card">
            <img
                class="chat-header-card__image"
                src="{{ $chat->auction?->image?->file_url ?? asset('assets/placeholder.png') }}"
                alt="{{ $chat->auction?->name ?? 'Aukcja' }}"
            />
            <div class="chat-header-card__info">
                <h1 class="chat-header-card__title">
                    @if($chat->auction)
                        <a href="{{ route('auctions.show', $chat->auction) }}" class="auction-title-link">
                            {{ $chat->auction->name }}
                        </a>
                        @include('partials.own-auction-badge', ['auction' => $chat->auction])
                    @else
                        Usunięta aukcja
                    @endif
                </h1>
                <p class="chat-header-card__meta">
                    Rozmowa z
                    <a href="{{ route('users.show', $otherParticipant) }}" class="chat-header-card__participant-link">
                        {{ $otherParticipant->firstName }} {{ $otherParticipant->lastName }}
                    </a>
                </p>
                @if($chat->auction)
                    <a href="{{ route('auctions.show', $chat->auction) }}" class="chat-header-card__auction-link">
                        Zobacz aukcję
                    </a>
                @endif
            </div>
        </article>

        @if($isArchived)
            <div class="alert alert-warning mb-0" role="alert">
                Ten chat jest zarchiwizowany. Dostępny jest tylko podgląd wiadomości.
            </div>
        @endif

        <div class="chat-thread">
            @forelse($messages as $message)
                <div @class([
                    'chat-message',
                    'chat-message--own' => (int) $message->senderId === (int) auth()->id(),
                ])>
                    <div class="chat-message__bubble">{{ trim($message->text) }}</div>
                    <time class="chat-message__time">{{ $message->sentAt->format('d/m/Y H:i') }}</time>
                </div>
            @empty
                <p class="chat-thread__empty">Brak wiadomości. Napisz pierwszą wiadomość poniżej.</p>
            @endforelse
        </div>

        @if(! $isArchived)
            <form method="POST" action="{{ route('chats.messages.store', $chat) }}" class="chat-form">
                @csrf
                <textarea
                    name="text"
                    class="chat-form__input @error('text') chat-form__input--error @enderror"
                    rows="4"
                    placeholder="Napisz wiadomość..."
                    required
                >{{ old('text') }}</textarea>
                @error('text')
                    <p class="chat-form__error">{{ $message }}</p>
                @enderror
                <button type="submit" class="chat-form__submit">Wyślij</button>
            </form>
        @endif
    </section>
@endsection

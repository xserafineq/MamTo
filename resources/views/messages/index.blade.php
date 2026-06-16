@extends('layout')

@push('styles')
    @vite(['resources/css/messages.css'])
@endpush

@section('content')
    <section id="messages-page">
        <h1 id="messages-title">Wiadomości</h1>
        <div class="messages-tabs">
            <a href="{{ route('chats.index') }}" class="messages-tab {{ !$isArchiveTab ? 'messages-tab--active' : '' }}">Aktywne</a>
            <a href="{{ route('chats.index', ['tab' => 'archived']) }}" class="messages-tab {{ $isArchiveTab ? 'messages-tab--active' : '' }}">Zarchiwizowane</a>
        </div>
        <p id="messages-subtitle">
            Masz
            <span class="messages-subtitle__highlight">{{ $newMessagesCount }}</span>
            @if($newMessagesCount === 1)
                nową wiadomość
            @elseif($newMessagesCount >= 2 && $newMessagesCount <= 4)
                nowe wiadomości
            @else
                nowych wiadomości
            @endif
        </p>

        <div id="messages-list">
            @forelse($chats as $chat)
                <article class="message-card">
                    <img
                        class="message-card__image"
                        src="{{ $chat->auction?->image?->file_url ?? asset('assets/placeholder.png') }}"
                        alt="{{ $chat->auction?->name ?? 'Aukcja' }}"
                    />
                    <div class="message-card__content">
                        <h2 class="message-card__title">
                            @if($chat->auction)
                                <a href="{{ route('auctions.show', $chat->auction) }}" class="auction-title-link">
                                    {{ $chat->auction->name }}
                                </a>
                                @include('partials.own-auction-badge', ['auction' => $chat->auction])
                            @else
                                Usunięta aukcja
                            @endif
                        </h2>
                        <p class="message-card__meta">
                            Chat z
                            <a href="{{ route('users.show', $chat->otherParticipant) }}" class="message-card__participant-link">
                                {{ $chat->otherParticipant->firstName }} {{ $chat->otherParticipant->lastName }}
                            </a>,
                            @if($chat->lastMessage)
                                {{ $chat->lastMessage->sentAt->format('d/m/Y H:i') }}
                            @else
                                brak wiadomości
                            @endif
                        </p>
                        <div class="message-card__actions">
                            @if($isArchiveTab)
                                <a href="{{ route('chats.show', $chat) }}" class="message-btn message-btn--read">podgląd</a>
                            @else
                                @if($chat->isUnread)
                                    <a href="{{ route('chats.show', $chat) }}" class="message-btn message-btn--primary">zobacz</a>
                                @else
                                    <a href="{{ route('chats.show', $chat) }}" class="message-btn message-btn--read">odczytane</a>
                                @endif
                            @endif
                            @if($isArchiveTab)
                                <form method="POST" action="{{ route('chats.unarchive', $chat) }}">
                                    @csrf
                                    <button type="submit" class="message-btn message-btn--restore">przywróć</button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('chats.archive', $chat) }}">
                                    @csrf
                                    <button type="submit" class="message-btn message-btn--archive">archiwizuj</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </article>
            @empty
                <p class="messages-empty">Brak wiadomości. Napisz do sprzedawcy z poziomu strony aukcji.</p>
            @endforelse
        </div>
    </section>
@endsection

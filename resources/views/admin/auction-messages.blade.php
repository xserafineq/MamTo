@extends('layout')

@push('styles')
    @vite(['resources/css/admin-panel.css'])
@endpush

@section('content')
    <section class="admin-panel">
        <h1>Wiadomości aukcji</h1>

        <p class="admin-panel-desc">
            {{ $auction->name }}
            · {{ $messagesCount }} {{ $messagesCount === 1 ? 'wiadomość' : ($messagesCount < 5 ? 'wiadomości' : 'wiadomości') }}
            · {{ $chats->count() }} {{ $chats->count() === 1 ? 'czat' : ($chats->count() < 5 ? 'czaty' : 'czatów') }}
        </p>

        @include('admin.partials.nav')

        @if (session('success'))
            <div class="alert alert-success py-2 small" role="alert">{{ session('success') }}</div>
        @endif

        <p class="admin-panel-back-link-wrap">
            <a href="{{ route('admin.auctions.index', $returnQuery) }}" class="admin-panel-back-link">← Wróć do listy aukcji</a>
        </p>

        <div class="admin-panel-chats-list">
            @forelse($chats as $chat)
                @php
                    $chatTitle = ($chat->buyer?->firstName ?? 'Kupujący') . ' ↔ ' . ($chat->seller?->firstName ?? 'Sprzedawca');
                @endphp
                <article class="admin-panel-chat-card {{ $chat->archived ? 'admin-panel-chat-card--archived' : '' }}">
                    <div class="admin-panel-chat-card__info">
                        <div class="admin-panel-chat-card__participants">
                            <span>
                                <strong>Kupujący:</strong>
                                @if($chat->buyer)
                                    {{ $chat->buyer->firstName }} {{ $chat->buyer->lastName }}
                                    <span class="admin-panel-chat-card__email">{{ $chat->buyer->email }}</span>
                                @else
                                    <span class="text-muted">Usunięty użytkownik</span>
                                @endif
                            </span>
                            <span class="admin-panel-chat-card__separator">·</span>
                            <span>
                                <strong>Sprzedawca:</strong>
                                @if($chat->seller)
                                    {{ $chat->seller->firstName }} {{ $chat->seller->lastName }}
                                    <span class="admin-panel-chat-card__email">{{ $chat->seller->email }}</span>
                                @else
                                    <span class="text-muted">Usunięty użytkownik</span>
                                @endif
                            </span>
                        </div>
                        <span class="admin-panel-chat-card__count">
                            {{ $chat->messages->count() }} {{ $chat->messages->count() === 1 ? 'wiadomość' : ($chat->messages->count() < 5 ? 'wiadomości' : 'wiadomości') }}
                        </span>
                        @if($chat->archived)
                            <span class="admin-panel-chat-card__badge">Zarchiwizowana</span>
                        @endif
                    </div>

                    <div class="admin-panel-chat-card__actions">
                        <button
                            type="button"
                            class="btn btn-outline-primary btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#chatPreviewModal"
                            data-chat-id="{{ $chat->id }}"
                            data-chat-title="{{ $chatTitle }}"
                            {{ $chat->messages->isEmpty() ? 'disabled' : '' }}
                        >
                            Podgląd
                        </button>

                        @if($chat->archived)
                            <form
                                method="POST"
                                action="{{ route('admin.auctions.chats.unarchive', array_merge(['auction' => $auction, 'chat' => $chat], $returnQuery)) }}"
                            >
                                @csrf
                                <button type="submit" class="btn btn-outline-secondary btn-sm">Przywróć</button>
                            </form>
                        @else
                            <form
                                method="POST"
                                action="{{ route('admin.auctions.chats.archive', array_merge(['auction' => $auction, 'chat' => $chat], $returnQuery)) }}"
                            >
                                @csrf
                                <button type="submit" class="btn btn-outline-secondary btn-sm">Archiwizuj</button>
                            </form>
                        @endif

                        <button
                            type="button"
                            class="btn btn-outline-danger btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#deleteChatModal"
                            data-chat-title="{{ $chatTitle }}"
                            data-delete-url="{{ route('admin.auctions.chats.destroy', array_merge(['auction' => $auction, 'chat' => $chat], $returnQuery)) }}"
                        >
                            Usuń
                        </button>
                    </div>
                </article>
            @empty
                <p class="admin-panel-admins-empty">Ta aukcja nie ma jeszcze żadnych rozmów.</p>
            @endforelse
        </div>
    </section>

    @foreach($chats as $chat)
        <div id="chat-preview-{{ $chat->id }}" class="d-none">
            <div class="admin-panel-chat-modal__participants">
                <div>
                    <strong>Kupujący:</strong>
                    @if($chat->buyer)
                        {{ $chat->buyer->firstName }} {{ $chat->buyer->lastName }}
                        <span class="admin-panel-chat__email">{{ $chat->buyer->email }}</span>
                    @else
                        <span class="text-muted">Usunięty użytkownik</span>
                    @endif
                </div>
                <div>
                    <strong>Sprzedawca:</strong>
                    @if($chat->seller)
                        {{ $chat->seller->firstName }} {{ $chat->seller->lastName }}
                        <span class="admin-panel-chat__email">{{ $chat->seller->email }}</span>
                    @else
                        <span class="text-muted">Usunięty użytkownik</span>
                    @endif
                </div>
            </div>

            <div class="admin-panel-chat-modal__thread">
                @forelse($chat->messages as $message)
                    <article class="admin-panel-chat-modal__message">
                        <div class="admin-panel-message-card__meta">
                            @if($message->sender)
                                <span class="admin-panel-message-card__sender">
                                    {{ $message->sender->firstName }} {{ $message->sender->lastName }}
                                </span>
                            @else
                                <span class="text-muted">Usunięty użytkownik</span>
                            @endif
                            <span class="admin-panel-message-card__date">
                                {{ $message->sentAt?->format('d.m.Y H:i') }}
                            </span>
                        </div>
                        <p class="admin-panel-message-card__text">{{ $message->text }}</p>
                    </article>
                @empty
                    <p class="admin-panel-admins-empty mb-0">Brak wiadomości w tym czacie.</p>
                @endforelse
            </div>
        </div>
    @endforeach

    <div class="modal fade" id="chatPreviewModal" tabindex="-1" aria-labelledby="chatPreviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="chatPreviewModalLabel">Podgląd konwersacji</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Zamknij"></button>
                </div>
                <div class="modal-body" id="chatPreviewModalBody"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Zamknij</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteChatModal" tabindex="-1" aria-labelledby="deleteChatModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="deleteChatForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    @foreach($returnQuery as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteChatModalLabel">Usuń rozmowę</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Zamknij"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-0" id="deleteChatModalText"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Anuluj</button>
                        <button type="submit" class="btn btn-danger">Usuń rozmowę</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/admin-auction-messages.js'])
@endpush

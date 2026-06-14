@extends('layout')

@push('styles')
    @vite(['resources/css/admin-panel.css'])
@endpush

@section('content')
    <section class="admin-panel">
        <h1>Użytkownicy</h1>

        <p class="admin-panel-desc">
            Zarządzaj kontami użytkowników w systemie.
            Razem: {{ $totalUsers }} · Administratorzy: {{ $adminCount }} · Użytkownicy: {{ $regularCount }}
        </p>

        @include('admin.partials.nav')

        @if (session('success'))
            <div class="alert alert-success py-2 small" role="alert">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger py-2 small" role="alert">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="GET" action="{{ route('admin.users.index') }}" class="admin-panel-users-search">
            <input
                type="search"
                name="q"
                class="form-control"
                placeholder="Szukaj po imieniu, nazwisku, e-mailu lub telefonie…"
                value="{{ request('q') }}"
            >
            <button type="submit" class="btn btn-primary">Szukaj</button>
            @if (request('q'))
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Wyczyść</a>
            @endif
        </form>

        <div class="admin-panel-admins">
            @forelse($users as $user)
                <article class="admin-panel-admin-card">
                    <img
                        class="admin-panel-admin-avatar"
                        src="{{ asset('assets/seller.png') }}"
                        alt="{{ $user->firstName }} {{ $user->lastName }}"
                    >

                    <div class="admin-panel-admin-content">
                        <div class="admin-panel-admin-header">
                            <div>
                                <div class="admin-panel-admin-name">
                                    {{ $user->firstName }} {{ $user->lastName }}
                                    @if((int) $user->id === (int) auth()->id())
                                        <span class="admin-panel-user-you">(Ty)</span>
                                    @endif
                                </div>
                                @if($user->isMainAdmin)
                                    <span class="admin-panel-user-badge admin-panel-user-badge--main">Główny administrator</span>
                                @elseif($user->isAdmin)
                                    <span class="admin-panel-user-badge admin-panel-user-badge--admin">Administrator</span>
                                @else
                                    <span class="admin-panel-user-badge">Użytkownik</span>
                                @endif
                            </div>
                        </div>

                        <div class="admin-panel-admin-details">
                            <div class="admin-panel-admin-detail">
                                <span class="admin-panel-admin-label">E-mail</span>
                                <span>{{ $user->email }}</span>
                            </div>
                            <div class="admin-panel-admin-detail">
                                <span class="admin-panel-admin-label">Telefon</span>
                                <span>
                                    @php
                                        $phoneDigits = preg_replace('/\D/', '', $user->phoneNumber);
                                    @endphp
                                    @if(strlen($phoneDigits) === 9)
                                        {{ substr($phoneDigits, 0, 3) }} {{ substr($phoneDigits, 3, 3) }} {{ substr($phoneDigits, 6, 3) }}
                                    @else
                                        {{ $user->phoneNumber }}
                                    @endif
                                </span>
                            </div>
                            <div class="admin-panel-admin-detail">
                                <span class="admin-panel-admin-label">Dołączył</span>
                                <span>{{ $user->joinedAt?->format('d.m.Y') ?? '—' }}</span>
                            </div>
                            <div class="admin-panel-admin-detail">
                                <span class="admin-panel-admin-label">Ostatnio online</span>
                                <span>{{ $user->lastOnline?->format('d.m.Y H:i') ?? '—' }}</span>
                            </div>
                            <div class="admin-panel-admin-detail">
                                <span class="admin-panel-admin-label">Ogłoszenia</span>
                                <span>{{ $user->auctions_count }}</span>
                            </div>
                        </div>

                        <div class="admin-panel-user-actions">
                            @php
                                $canEditUser = (! $user->isMainAdmin || (int) $user->id === (int) auth()->id())
                                    && (! $user->isAdmin || $isMainAdmin || (int) $user->id === (int) auth()->id());
                            @endphp

                            @if($canEditUser)
                                <a
                                    href="{{ route('admin.users.edit', array_merge(['user' => $user], request()->only('q', 'page'))) }}"
                                    class="btn btn-outline-primary btn-sm"
                                >
                                    Edytuj
                                </a>
                            @endif

                            @if($isMainAdmin && ! $user->isMainAdmin && (int) $user->id !== (int) auth()->id())
                                <div class="admin-panel-admin-permissions">
                                    <div class="admin-panel-admin-permissions-title">Uprawnienia</div>
                                    <form
                                        class="admin-panel-admin-permissions-form"
                                        method="POST"
                                        action="{{ route('admin.users.permissions.update', $user) }}"
                                    >
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="q" value="{{ request('q') }}">
                                        <input type="hidden" name="page" value="{{ request('page') }}">

                                        <select name="isAdmin" class="form-select admin-panel-admin-permissions-select" required>
                                            <option value="1" @selected($user->isAdmin)>Administrator</option>
                                            <option value="0" @selected(! $user->isAdmin)>Użytkownik</option>
                                        </select>

                                        <button type="submit" class="btn btn-primary btn-sm">
                                            Zapisz
                                        </button>
                                    </form>
                                </div>
                            @endif

                            @if(
                                ! $user->isMainAdmin
                                && (int) $user->id !== (int) auth()->id()
                                && (! $user->isAdmin || $isMainAdmin)
                            )
                                <form
                                    method="POST"
                                    action="{{ route('admin.users.destroy', $user) }}"
                                    onsubmit="return confirm('Czy na pewno chcesz usunąć użytkownika {{ $user->firstName }} {{ $user->lastName }}? Tej operacji nie można cofnąć.');"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="q" value="{{ request('q') }}">
                                    <input type="hidden" name="page" value="{{ request('page') }}">
                                    <button type="submit" class="btn btn-danger btn-sm">Usuń użytkownika</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </article>
            @empty
                <p class="admin-panel-admins-empty">Nie znaleziono użytkowników.</p>
            @endforelse
        </div>

        @if ($users->hasPages())
            <div class="admin-panel-pagination">
                @for ($page = 1; $page <= $users->lastPage(); $page++)
                    @if ($page === $users->currentPage())
                        <span class="admin-panel-pagination-btn is-current">{{ $page }}</span>
                    @else
                        <a href="{{ $users->url($page) }}" class="admin-panel-pagination-btn">{{ $page }}</a>
                    @endif
                @endfor
            </div>
        @endif
    </section>
@endsection

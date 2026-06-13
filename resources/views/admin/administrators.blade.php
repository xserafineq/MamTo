@extends('layout')

@push('styles')
    @vite(['resources/css/admin-panel.css'])
@endpush

@section('content')
    <section class="admin-panel">
        <h1>Inni administratorzy</h1>

        <p class="admin-panel-desc">
            Lista pozostałych administratorów z dostępem do panelu.
            @if($isMainAdmin)
                Jako główny administrator możesz zmieniać ich uprawnienia.
            @endif
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

        <div class="admin-panel-admins">
            @forelse($administrators as $admin)
                <article class="admin-panel-admin-card">
                    <img
                        class="admin-panel-admin-avatar"
                        src="{{ asset('assets/seller.png') }}"
                        alt="{{ $admin->firstName }} {{ $admin->lastName }}"
                    >

                    <div class="admin-panel-admin-content">
                        <div class="admin-panel-admin-header">
                            <div>
                                <div class="admin-panel-admin-name">
                                    {{ $admin->firstName }} {{ $admin->lastName }}
                                </div>
                                <div class="admin-panel-admin-role">Administrator</div>
                            </div>
                        </div>

                        <div class="admin-panel-admin-details">
                            <div class="admin-panel-admin-detail">
                                <span class="admin-panel-admin-label">E-mail</span>
                                <span>{{ $admin->email }}</span>
                            </div>
                            <div class="admin-panel-admin-detail">
                                <span class="admin-panel-admin-label">Telefon</span>
                                <span>
                                    @php($phoneDigits = preg_replace('/\D/', '', $admin->phoneNumber))
                                    @if(strlen($phoneDigits) === 9)
                                        {{ substr($phoneDigits, 0, 3) }} {{ substr($phoneDigits, 3, 3) }} {{ substr($phoneDigits, 6, 3) }}
                                    @else
                                        {{ $admin->phoneNumber }}
                                    @endif
                                </span>
                            </div>
                            <div class="admin-panel-admin-detail">
                                <span class="admin-panel-admin-label">Dołączył</span>
                                <span>{{ $admin->joinedAt?->format('d.m.Y') ?? '—' }}</span>
                            </div>
                            <div class="admin-panel-admin-detail">
                                <span class="admin-panel-admin-label">Ostatnio online</span>
                                <span>{{ $admin->lastOnline?->format('d.m.Y H:i') ?? '—' }}</span>
                            </div>
                        </div>

                        @if($isMainAdmin)
                            <div class="admin-panel-admin-permissions">
                                <div class="admin-panel-admin-permissions-title">Zmień uprawnienia</div>

                                <form
                                    class="admin-panel-admin-permissions-form"
                                    method="POST"
                                    action="{{ route('admin.administrators.permissions.update', $admin) }}"
                                >
                                    @csrf
                                    @method('PUT')

                                    <select name="isAdmin" class="form-select admin-panel-admin-permissions-select" required>
                                        <option value="1" @selected($admin->isAdmin)>Administrator</option>
                                        <option value="0" @selected(!$admin->isAdmin)>Użytkownik</option>
                                    </select>

                                    <button type="submit" class="btn btn-primary admin-panel-admin-permissions-btn">
                                        Zapisz uprawnienia
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </article>
            @empty
                <p class="admin-panel-admins-empty">Brak innych administratorów w systemie.</p>
            @endforelse
        </div>
    </section>
@endsection

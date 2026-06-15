@extends('layout')

@push('styles')
    @vite(['resources/css/admin-panel.css'])
@endpush

@section('content')
    <section class="admin-panel">
        <h1>Edycja użytkownika</h1>

        <p class="admin-panel-desc">
            {{ $user->firstName }} {{ $user->lastName }} · {{ $user->email }}
        </p>

        @include('admin.partials.nav')

        @if ($errors->any())
            <div class="alert alert-danger py-2 small" role="alert">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="admin-panel-user-edit-card">
            <form method="POST" action="{{ route('admin.users.update', $user) }}" novalidate>
                @csrf
                @method('PUT')

                @foreach ($returnQuery as $key => $value)
                    @if($value !== null && $value !== '')
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endif
                @endforeach

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="firstName" class="form-label">Imię</label>
                        <input
                            type="text"
                            name="firstName"
                            id="firstName"
                            class="form-control @error('firstName') is-invalid @enderror"
                            value="{{ old('firstName', $user->firstName) }}"
                            maxlength="100"
                            required
                        >
                        @error('firstName')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="lastName" class="form-label">Nazwisko</label>
                        <input
                            type="text"
                            name="lastName"
                            id="lastName"
                            class="form-control @error('lastName') is-invalid @enderror"
                            value="{{ old('lastName', $user->lastName) }}"
                            maxlength="100"
                            required
                        >
                        @error('lastName')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label">E-mail</label>
                        <input
                            type="email"
                            name="email"
                            id="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $user->email) }}"
                            maxlength="200"
                            required
                        >
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="phoneNumber" class="form-label">Numer telefonu</label>
                        <input
                            type="text"
                            name="phoneNumber"
                            id="phoneNumber"
                            class="form-control @error('phoneNumber') is-invalid @enderror"
                            value="{{ old('phoneNumber', $user->phoneNumber) }}"
                            placeholder="+48123456789"
                            maxlength="12"
                            inputmode="tel"
                            required
                        >
                        @error('phoneNumber')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="admin-panel-user-edit-actions">
                    <button type="submit" class="btn btn-primary">Zapisz zmiany</button>
                    <a
                        href="{{ route('admin.users.index', $returnQuery) }}"
                        class="btn btn-outline-secondary"
                    >
                        Anuluj
                    </a>
                </div>
            </form>
        </div>
    </section>
@endsection

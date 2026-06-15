@extends('layout')

@push('styles')
    @vite(['resources/css/profile.css'])
@endpush

@push('scripts')
    @vite(['resources/js/profile.js'])
@endpush

@section('content')
    <section class="profile-page">
        <h1>Profil użytkownika</h1>

        @if (session('success'))
            <div class="alert alert-success py-2 small" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="profile-grid">
            <div class="profile-card">
                <h2 class="profile-card__title">Dane osobowe</h2>

            <form id="profile-form" method="POST" action="{{ route('profile.update') }}" novalidate>
                @csrf
                @method('PUT')

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
                </div>

                <div class="mt-3">
                    <label for="email" class="form-label">Adres e-mail</label>
                    <input
                        type="email"
                        id="email"
                        class="form-control"
                        value="{{ $user->email }}"
                        maxlength="200"
                        disabled
                    >
                </div>

                <div class="mt-3">
                    <label for="phoneNumber" class="form-label">Numer telefonu</label>
                    <input
                        type="tel"
                        name="phoneNumber"
                        id="phoneNumber"
                        class="form-control @error('phoneNumber') is-invalid @enderror"
                        value="{{ old('phoneNumber', $user->phoneNumber) }}"
                        placeholder="+48123456789"
                        maxlength="12"
                        required
                    >
                    @error('phoneNumber')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary mt-4">Zapisz dane</button>
            </form>
            </div>

            <div class="profile-card">
                <h2 class="profile-card__title">Zmiana hasła</h2>

            <form id="password-form" method="POST" action="{{ route('profile.password.update') }}" novalidate>
                @csrf
                @method('PUT')

                <div class="mt-0">
                    <label for="current_password" class="form-label">Obecne hasło</label>
                    <input
                        type="password"
                        name="current_password"
                        id="current_password"
                        class="form-control @error('current_password') is-invalid @enderror"
                        required
                    >
                    @error('current_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="password" class="form-label">Nowe hasło</label>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="form-control @error('password') is-invalid @enderror"
                        minlength="8"
                        required
                    >
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="password_confirmation" class="form-label">Potwierdź nowe hasło</label>
                    <input
                        type="password"
                        name="password_confirmation"
                        id="password_confirmation"
                        class="form-control @error('password_confirmation') is-invalid @enderror"
                        minlength="8"
                        required
                    >
                    @error('password_confirmation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary mt-4">Zmień hasło</button>
            </form>
            </div>
        </div>
    </section>
@endsection

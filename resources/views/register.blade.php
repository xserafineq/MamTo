<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MamTo - Rejestracja</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/forms.css', 'resources/js/auth/register.js'])
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-5">
            <div class="card login-card p-4 p-md-5">
                <div class="text-center mb-4">
                    <img src="{{ asset('assets/logo.png') }}" alt="MamTo" class="img-fluid mx-auto d-block mb-3" style="max-width: 220px;">
                    <p class="text-muted small">Utwórz nowe konto, aby korzystać z serwisu</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger py-2 small" role="alert">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form id="register-form" action="{{ route('register') }}" method="POST" novalidate>
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="firstName" class="form-label text-secondary fw-semibold small">Imię</label>
                            <input
                                type="text"
                                name="firstName"
                                id="firstName"
                                class="form-control form-control-lg @error('firstName') is-invalid @enderror"
                                placeholder="Jan"
                                value="{{ old('firstName') }}"
                                maxlength="100"
                                required
                                autofocus
                            >
                            @error('firstName')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="lastName" class="form-label text-secondary fw-semibold small">Nazwisko</label>
                            <input
                                type="text"
                                name="lastName"
                                id="lastName"
                                class="form-control form-control-lg @error('lastName') is-invalid @enderror"
                                placeholder="Kowalski"
                                value="{{ old('lastName') }}"
                                maxlength="100"
                                required
                            >
                            @error('lastName')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label text-secondary fw-semibold small">Adres e-mail</label>
                        <input
                            type="email"
                            name="email"
                            id="email"
                            class="form-control form-control-lg @error('email') is-invalid @enderror"
                            placeholder="nazwa@przyklad.com"
                            value="{{ old('email') }}"
                            maxlength="200"
                            required
                        >
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="phoneNumber" class="form-label text-secondary fw-semibold small">Numer telefonu</label>
                        <input
                            type="tel"
                            name="phoneNumber"
                            id="phoneNumber"
                            class="form-control form-control-lg @error('phoneNumber') is-invalid @enderror"
                            placeholder="123456789"
                            value="{{ old('phoneNumber') }}"
                            pattern="[0-9]{9}"
                            maxlength="9"
                            required
                        >
                        @error('phoneNumber')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label text-secondary fw-semibold small">Hasło</label>
                        <input
                            type="password"
                            name="password"
                            id="password"
                            class="form-control form-control-lg @error('password') is-invalid @enderror"
                            placeholder="••••••••"
                            minlength="8"
                            required
                        >
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label text-secondary fw-semibold small">Potwierdź hasło</label>
                        <input
                            type="password"
                            name="password_confirmation"
                            id="password_confirmation"
                            class="form-control form-control-lg @error('password_confirmation') is-invalid @enderror"
                            placeholder="••••••••"
                            minlength="8"
                            required
                        >
                        @error('password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100 btn-lg mb-3">Zarejestruj się</button>
                </form>

                <div class="text-center">
                    <p class="text-muted small mb-2">Masz już konto? <a href="{{ route('login') }}" class="text-decoration-none fw-semibold">Zaloguj się</a></p>
                    <a href="/" class="text-decoration-none text-muted small">Powrót do strony głównej</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

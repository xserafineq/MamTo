<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MamTo - Logowanie</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/forms.css', 'resources/js/auth/login.js'])
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card login-card p-4 p-md-5">
                <div class="text-center mb-4">
                    <img src="{{ asset('assets/logo.png') }}" alt="MamTo" class="img-fluid mx-auto d-block mb-3" style="max-width: 220px;">
                    <p class="text-muted small">Zaloguj się do swojego konta</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger py-2 small" role="alert">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form id="login-form" action="{{ route('login') }}" method="POST" novalidate>
                    @csrf
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
                            autofocus
                        >
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="password" class="form-label text-secondary fw-semibold small">Hasło</label>
                        <input
                            type="password"
                            name="password"
                            id="password"
                            class="form-control form-control-lg @error('password') is-invalid @enderror"
                            placeholder="••••••••"
                            minlength="8"
                            maxlength="255"
                            required
                        >
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary w-100 btn-lg mb-3">Zaloguj się</button>
                </form>
                <div class="text-center">
                    <p class="text-muted small mb-2">Nie masz konta? <a href="{{ route('register') }}" class="text-decoration-none fw-semibold">Zarejestruj się</a></p>
                    <a href="/" class="text-decoration-none text-muted small">Powrót do strony głównej</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

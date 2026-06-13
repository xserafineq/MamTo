<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
      <title>Mamto.pl</title>
     @vite(['resources/css/app.css'])
     @stack('styles')
  </head>
  <body>
    <nav>
        <div id="logo">
            <a href="/">
                <img src="{{ asset('assets/logo.png') }}" alt="logo"/>
            </a>
        </div>
        <form method="GET" action="{{ route('auctions.index') }}" id="search-box">
            @if (request()->filled('price_min'))
                <input type="hidden" name="price_min" value="{{ request('price_min') }}">
            @endif
            @if (request()->filled('price_max'))
                <input type="hidden" name="price_max" value="{{ request('price_max') }}">
            @endif
            @if (request()->filled('sort'))
                <input type="hidden" name="sort" value="{{ request('sort') }}">
            @endif
            @if (request()->filled('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif
            <input
                id="search"
                type="text"
                name="q"
                placeholder="szukaj"
                value="{{ request('q') }}"
                onfocus="this.dataset.initialValue = this.value"
                onblur="if (this.dataset.initialValue !== this.value) this.form.submit()"
                onkeydown="if (event.key === 'Enter') { event.preventDefault(); this.form.submit(); }"
            >
            <button type="button" id="filter-btn"><img src="{{ asset('assets/fi-ss-filter.png')}}" alt=""></button>
        </form>
        <ul id="menu">
            <li><img src="{{ asset('assets/fi-bs-heart.png') }}" alt="favourites"/></li>
            <li>
                <a href="{{ auth()->check() ? route('chats.index') : route('login') }}">
                    <img src="{{ asset('assets/messages.png') }}" alt="messages"/>
                </a>
            </li>
            <li><img src="{{ asset('assets/fi-sr-user.png') }}" alt="user-profile"/></li>
        </ul>
    </nav>
    <main>
        @yield('content')
    </main>
  <img id="footer-waves" src="{{asset('assets/footer-waves.svg')}}" alt="waves"/>
  <footer>
        Mateusz Serafin, Przemysław Sulowski
  </footer>
  @stack('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>

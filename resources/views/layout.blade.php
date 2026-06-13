<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
      <title>Mamto.pl</title>
     @vite(['resources/css/app.css'])
     @stack('styles')
  </head>
  <body>
    <nav>
        <div id="logo">
            <img src="{{ asset('assets/logo.png') }}" alt="logo"/>
        </div>
        <div id="search-box">
            <input id="search" type="text" placeholder="szukaj">
            <button id="filter-btn"><img src="{{ asset('assets/fi-ss-filter.png')}}" alt=""></button>
        </div>
        <ul id="menu">
            <li><img src="{{ asset('assets/fi-bs-heart.png') }}" alt="favourites"/></li>
            <li><img src="{{ asset('assets/messages.png') }}" alt="messages"/></li>
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
  </body>
</html>

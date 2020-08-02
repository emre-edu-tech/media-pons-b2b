<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Media Pons B2B App Demo') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <script src="{{ asset('js/app.js') }}" defer></script>
    
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    
                    <ul class="navbar-nav mr-auto">
                        @auth
                            <li class="nav-item">
                                <router-link class="nav-link" to="/home">Home</router-link>
                            </li>
                            @can('isAdmin')
                                <li class="nav-item">
                                    <router-link class="nav-link" to="/users">Benutzerlist
                                        <span class="sr-only">(current)</span>
                                    </router-link>
                                </li>
                                <li class="nav-item">
                                    <router-link class="nav-link" to="/dealers">Anwendungsliste</router-link>
                                </li>
                            @endcan
                            <li class="nav-item">
                                <router-link class="nav-link" to="/categories">Produkte</router-link>
                            </li>
                            <li class="nav-item">
                                <router-link class="nav-link" to="/cart">Warenkorb</router-link>
                            </li>
                            <li class="nav-item">
                                <router-link class="nav-link" to="/dealer-chat">Händlerchat</router-link>
                            </li>
                            <li class="nav-item">
                                <router-link class="nav-link" to="/profile">Profil</router-link>
                            </li>
                        @else
                        <li class="nav-item">
                            <a class="nav-link" href="/">Willkommen</a>
                        </li>
                        @endauth
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Benutzer Login</a>
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <router-view :user="{{ Auth::user() }}"></router-view>
            <vue-progress-bar></vue-progress-bar>
        </main>

        <footer class="py-5 bg-dark">
            <div class="container">
                <p class="m-0 text-center text-white">Copyright © Media Pons B2B Application 2020</p>
            </div>
            <!-- /.container -->
        </footer>
    </div>
    <!-- Scripts -->
    @auth
        <script>
            window.user = @json(auth()->user())
        </script>
    @endauth
</body>
</html>

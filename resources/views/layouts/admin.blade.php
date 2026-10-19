<!DOCTYPE html>
<html lang="es-MX">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Tema claro/oscuro: fijar antes de pintar para evitar parpadeo. --}}
    <script>
        (function () {
            try {
                var stored = localStorage.getItem('jr-theme');
                var prefersLight = window.matchMedia &&
                    window.matchMedia('(prefers-color-scheme: light)').matches;
                document.documentElement.setAttribute('data-theme', stored || (prefersLight ? 'light' : 'dark'));
            } catch (e) {
                document.documentElement.setAttribute('data-theme', 'dark');
            }
        })();
    </script>

    <title>@yield('title', 'Panel') — JReyes Ópticos</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,600;1,9..144,600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/theme.css', 'resources/js/app.js'])
</head>

<body class="jr-admin">
    @auth
        <header class="jr-admin-topbar">
            <div class="container d-flex align-items-center justify-content-between">
                <a href="{{ route('admin.dashboard') }}" class="jr-admin-topbar__brand d-flex align-items-center gap-2">
                    <img src="{{ asset('img/logo-jreyes.png') }}" alt="JReyes Ópticos">
                    <span class="jr-admin-topbar__tag">Panel</span>
                </a>

                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="jr-theme-toggle" data-theme-toggle
                        aria-pressed="false" aria-label="Cambiar a tema claro" title="Cambiar a tema claro">
                        <i class="fa-solid fa-sun icon-sun" aria-hidden="true"></i>
                        <i class="fa-solid fa-moon icon-moon" aria-hidden="true"></i>
                    </button>

                    <span class="jr-admin-topbar__user d-none d-sm-inline">{{ auth()->user()->name }}</span>

                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-jr-outline">
                            <i class="fa-solid fa-right-from-bracket me-1" aria-hidden="true"></i> Salir
                        </button>
                    </form>
                </div>
            </div>
        </header>
    @endauth

    <main class="jr-admin-main">
        <div class="container">
            @yield('content')
        </div>
    </main>
</body>

</html>

<!DOCTYPE html>
<html lang="es-MX">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Tema claro/oscuro: fijar antes de pintar para evitar parpadeo.
         Usa la elección guardada; si no hay, sigue la preferencia del sistema. --}}
    <script>
        (function () {
            try {
                var stored = localStorage.getItem('jr-theme');
                var prefersLight = window.matchMedia &&
                    window.matchMedia('(prefers-color-scheme: light)').matches;
                var theme = stored || (prefersLight ? 'light' : 'dark');
                document.documentElement.setAttribute('data-theme', theme);
            } catch (e) {
                document.documentElement.setAttribute('data-theme', 'dark');
            }
        })();
    </script>

    <title>@yield('title', 'JReyes Ópticos') — JReyes Ópticos</title>
    <meta name="description" content="@yield('meta_description', 'Óptica JReyes: lentes graduados, progresivos Varilux, tratamientos Crizal y lentes de contacto. Mejora lo que ves.')">

    {{-- Tipografías --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,600;1,9..144,600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Vite: theme.css + app.js --}}
    @vite(['resources/css/theme.css', 'resources/js/app.js'])
</head>

<body>
    @include('partials.navbar')

    <main id="contenido">
        @yield('content')
    </main>

    @include('partials.footer')
</body>

</html>
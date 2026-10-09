<!DOCTYPE html>
<html lang="es-MX">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="robots" content="noindex">

    <title>JReyes Ópticos — Visítanos</title>
    <meta name="description" content="Escanea el código y visita JReyes Ópticos: lentes graduados, progresivos Varilux y lentes de contacto.">

    {{-- Tipografías --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,600;1,9..144,600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Solo el tema (sin app.js: esta página no necesita navbar ni el configurador) --}}
    @vite(['resources/css/theme.css'])
</head>

<body class="jr-share-body">
    <main class="jr-share">
        <div class="jr-share__panel jr-reveal is-visible">

            {{-- Logo --}}
            <img class="jr-share__logo" src="{{ asset('img/logo-jreyes.png') }}" alt="JReyes Ópticos">

            {{-- Hero (mismo mensaje que la portada) --}}
            <h1 class="jr-share__title">
                <em>Personaliza</em><br>tus lentes en un solo click
            </h1>
            <!-- <p class="jr-share__lead">
                Lentes graduados a la medida: monofocales, bifocales, progresivos y lentes de contacto.
                Arma tus lentes y conoce el precio al instante.
            </p> -->

            {{-- Código QR --}}
            <div class="jr-share__qr-wrap">
                <div id="jr-qr" class="jr-share__qr" data-qr-url="{{ $urlSitio }}" aria-live="polite">
                    {{-- El QR se dibuja aquí con JS; mientras tanto, un enlace de respaldo --}}
                    <a href="{{ $urlSitio }}">{{ $urlSitio }}</a>
                </div>
                <p class="jr-share__qr-hint"><i class="fa-solid fa-mobile-screen me-1"></i> Escanea para visitarnos</p>
            </div>

            {{-- Botón directo (para quien ya está en el teléfono) --}}
            <a href="{{ $urlSitio }}" class="btn btn-jr-primary btn-lg jr-share__cta">
                <i class="fa-solid fa-glasses me-2"></i> Ir al sitio
            </a>

            {{-- Redes sociales con texto --}}
            <div class="jr-share__social">
                @if (!empty($social['whatsapp']) && $social['whatsapp'] !== '#')
                <a href="{{ $social['whatsapp'] }}" target="_blank" rel="noopener" class="jr-share__social-btn jr-share__social-btn--wa">
                    <i class="fa-brands fa-whatsapp"></i> <span>WhatsApp</span>
                </a>
                @endif
                @if (!empty($social['instagram']) && $social['instagram'] !== '#')
                <a href="{{ $social['instagram'] }}" target="_blank" rel="noopener" class="jr-share__social-btn jr-share__social-btn--ig">
                    <i class="fa-brands fa-instagram"></i> <span>Instagram</span>
                </a>
                @endif
                @if (!empty($social['facebook']) && $social['facebook'] !== '#')
                <a href="{{ $social['facebook'] }}" target="_blank" rel="noopener" class="jr-share__social-btn jr-share__social-btn--fb">
                    <i class="fa-brands fa-facebook-f"></i> <span>Facebook</span>
                </a>
                @endif
                @if (!empty($social['tiktok']) && $social['tiktok'] !== '#')
                <a href="{{ $social['tiktok'] }}" target="_blank" rel="noopener" class="jr-share__social-btn jr-share__social-btn--tt">
                    <i class="fa-brands fa-tiktok"></i> <span>TikTok</span>
                </a>
                @endif
            </div>

        </div>
    </main>

    {{-- Font Awesome para los iconos de redes (el tema ya lo importa vía CSS,
         pero esta página standalone necesita el CSS del tema, que lo trae). --}}

    {{-- Librería QR vendida + script que la dibuja (sin dependencias externas) --}}
    <script src="{{ asset('js/qrcode.min.js') }}"></script>
    <script src="{{ asset('js/share.js') }}"></script>
</body>

</html>
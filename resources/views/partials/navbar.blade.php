<a href="#contenido" class="visually-hidden-focusable btn btn-jr-gold position-absolute top-0 start-0 m-2" style="z-index:1090">Saltar al contenido</a>

<nav class="jr-navbar navbar navbar-expand-lg sticky-top py-2" aria-label="Navegación principal">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
            <img src="{{ asset('img/logo-jreyes.png') }}" alt="JReyes Ópticos">
        </a>

        {{-- Botón que abre el offcanvas en móvil --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#jrNav"
            aria-controls="jrNav" aria-label="Abrir menú">
            <i class="fa-solid fa-bars text-light"></i>
        </button>

        {{-- Offcanvas en móvil; se comporta como nav normal en ≥lg --}}
        <div class="offcanvas offcanvas-end jr-offcanvas" tabindex="-1" id="jrNav" aria-labelledby="jrNavLabel">
            <div class="offcanvas-header">
                <a class="navbar-brand" href="{{ route('home') }}" id="jrNavLabel">
                    <img src="{{ asset('img/logo-jreyes.png') }}" alt="JReyes Ópticos" style="height:40px">
                </a>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center gap-lg-0 gap-2">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('lentes') ? 'active' : '' }}" href="{{ route('lentes') }}">Lentes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('marcas') ? 'active' : '' }}" href="{{ route('marcas') }}">Marcas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('lentes-contacto') ? 'active' : '' }}" href="{{ route('lentes-contacto') }}">Lentes de contacto</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('paquetes') ? 'active' : '' }}" href="{{ route('paquetes') }}">Paquetes</a>
                    </li>
                    <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
                        <a class="btn btn-jr-primary w-100" href="{{ route('contacto') }}">
                            <i class="fa-solid fa-calendar-check me-1"></i> Agenda tu cita
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
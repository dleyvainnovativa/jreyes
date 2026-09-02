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
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('empresa') ? 'active' : '' }}" href="{{ route('empresa') }}">Conócenos</a>
                    </li>

                    {{-- Lentes con submenú: tipos de mica + lentes de contacto --}}
                    <li class="nav-item dropdown jr-dropdown">
                        <a class="nav-link dropdown-toggle {{ request()->routeIs('lentes') || request()->routeIs('lentes-contacto') ? 'active' : '' }}"
                            href="{{ route('lentes') }}" id="jrLentesMenu" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Lentes
                        </a>
                        <ul class="dropdown-menu jr-dropdown-menu" aria-labelledby="jrLentesMenu">
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('lentes') && request('tipo','monofocal')==='monofocal' ? 'active' : '' }}"
                                    href="{{ route('lentes', ['tipo' => 'monofocal']) }}">
                                    <i class="fa-solid fa-circle me-2"></i> Monofocales
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('lentes') && request('tipo')==='bifocal' ? 'active' : '' }}"
                                    href="{{ route('lentes', ['tipo' => 'bifocal']) }}">
                                    <i class="fa-solid fa-circle-half-stroke me-2"></i> Bifocales
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('lentes') && request('tipo')==='progresiva' ? 'active' : '' }}"
                                    href="{{ route('lentes', ['tipo' => 'progresiva']) }}">
                                    <i class="fa-solid fa-layer-group me-2"></i> Progresivos
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('lentes-contacto') ? 'active' : '' }}"
                                    href="{{ route('lentes-contacto') }}">
                                    <i class="fa-solid fa-eye me-2"></i> Lentes de contacto
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('paquetes') ? 'active' : '' }}" href="{{ route('paquetes') }}">Paquetes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('programas') ? 'active' : '' }}" href="{{ route('programas') }}">Programas</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

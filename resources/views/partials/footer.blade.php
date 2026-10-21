<footer class="jr-footer jr-section-sm mt-0">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <img src="{{ asset('img/logo-jreyes.png') }}" alt="JReyes Ópticos" class="jr-footer__logo mb-3">
                <p class="mb-3" style="max-width:32ch">Mejora lo que ves. Lentes graduados, progresivos de alta gama y lentes de contacto con la mejor asesoría.</p>
                <div class="d-flex gap-2 flex-wrap">
                    @foreach ($ajustes->redes() as $red)
                        @continue(empty($red->url) || $red->url === '#')
                        <a href="{{ $red->url }}" target="_blank" rel="noopener"
                            class="jr-social-btn" style="--c: {{ $red->color }}"
                            aria-label="{{ ucfirst($red->plataforma) }}">
                            <i class="{{ $red->icono }}"></i>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="col-6 col-lg-2">
                <h5 class="mb-3">Catálogo</h5>
                <ul class="list-unstyled d-flex flex-column gap-2">
                    <li><a href="{{ route('lentes') }}">Lentes graduados</a></li>
                    <li><a href="{{ route('marcas') }}">Varilux y Crizal</a></li>
                    <li><a href="{{ route('lentes-contacto') }}">Lentes de contacto</a></li>
                    <li><a href="{{ route('paquetes') }}">Paquetes</a></li>
                    <li><a href="{{ route('empresa') }}">Empresa</a></li>
                    <li><a href="{{ route('contacto.empresas') }}">Para empresas</a></li>
                </ul>
            </div>

            <div class="col-6 col-lg-3">
                <h5 class="mb-3">Contacto</h5>
                <ul class="list-unstyled d-flex flex-column gap-2">
                    @if ($ajustes->ubicacion())
                        <li><i class="fa-solid fa-location-dot me-2 jr-text-gold"></i> {{ $ajustes->ubicacion() }}</li>
                    @endif
                    @if ($ajustes->telefono)
                        <li><i class="fa-solid fa-phone me-2 jr-text-gold"></i> {{ $ajustes->telefono }}</li>
                    @endif
                    @if ($ajustes->correo)
                        <li><i class="fa-solid fa-envelope me-2 jr-text-gold"></i> {{ $ajustes->correo }}</li>
                    @endif
                </ul>
            </div>

            <div class="col-lg-3">
                <h5 class="mb-3">Horario</h5>
                <ul class="list-unstyled d-flex flex-column gap-2">
                    @forelse ($ajustes->horario() as $fila)
                        <li>{{ $fila['etiqueta'] ?? '' }}@if (!empty($fila['horario'])): {{ $fila['horario'] }}@endif</li>
                    @empty
                        <li>Lunes a viernes: 9:00 – 19:00</li>
                    @endforelse
                </ul>
                <a href="{{ route('contacto') }}" class="btn btn-jr-gold btn-sm mt-2">Agenda tu examen</a>
            </div>
        </div>

        <hr class="jr-divider my-4">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 small">
            <span>© {{ date('Y') }} JReyes Ópticos. Todos los derechos reservados.</span>
            <span class="jr-text-muted">Precios en pesos mexicanos (MXN). Sujetos a cambio sin previo aviso.</span>
        </div>
    </div>
</footer>

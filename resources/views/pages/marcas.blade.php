@extends('layouts.app')

@section('title', 'Marcas: Varilux y Crizal')
@section('meta_description', 'Progresivos Varilux y tratamientos Crizal. Compara protección UV, filtro de luz azul, resistencia a rayas y más.')

@section('content')

{{-- Encabezado --}}
<section class="jr-section-sm jr-bg-2">
    <div class="container">
        <p class="jr-eyebrow mb-2">Marcas de confianza</p>
        <h1 class="mb-2" style="font-size:clamp(2rem,5vw,3.2rem)">Varilux y Crizal</h1>
        <p class="jr-text-muted mb-0" style="max-width:60ch">Lo mejor en progresivos y tratamientos, respaldado por Essilor. Aquí puedes ver qué hace especial a cada uno y comparar sus características.</p>
    </div>
</section>

{{-- ===================== VARILUX ===================== --}}
<section class="jr-section">
    <div class="container">
        <div class="row align-items-center g-4 mb-5">
            <div class="col-lg-6">
                <p class="jr-eyebrow mb-2">Progresivos</p>
                <h2 class="mb-3" style="font-size:clamp(1.8rem,4vw,2.6rem)">Varilux, el progresivo n.º 1 del mundo</h2>
                <p class="jr-text-muted mb-4">Cada diseño Varilux ofrece una transición fluida entre lejos, intermedio y cerca. Cuanto más avanzado el diseño, más amplias las zonas nítidas y más natural la adaptación.</p>
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="jr-feature">
                            <i class="fa-solid fa-glasses mb-2"></i>
                            <h3 class="h6 mb-1">Visión sin líneas</h3>
                            <p class="jr-text-muted small mb-0">Transición continua entre todas las distancias.</p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="jr-feature">
                            <i class="fa-solid fa-bolt mb-2"></i>
                            <h3 class="h6 mb-1">Adaptación rápida</h3>
                            <p class="jr-text-muted small mb-0">Menos zonas difusas a los lados.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="jr-brand-hero jr-reveal">
                    <img src="{{ asset('img/varilux-physio.jpg') }}" alt="Progresivos Varilux Physio">
                </div>
            </div>
        </div>

        {{-- Line-up Varilux --}}
        <div class="row g-3">
            @foreach ($varilux as $d)
            <div class="col-6 col-lg-3 jr-reveal">
                <div class="jr-card h-100">
                    <div class="jr-card__media">
                        <img src="{{ asset($d->imagen ?? 'img/varilux-comfort.jpg') }}" alt="{{ $d->nombre }} ({{ $d->material }})" loading="lazy">
                    </div>
                    <div class="jr-card__body">
                        <span class="jr-tag mb-2 d-inline-block">{{ $d->material }}</span>
                        <h3 class="h6 mb-1">{{ $d->nombre }}</h3>
                        <p class="jr-card__price mb-0 small">${{ number_format($d->precio, 0) }} MXN</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===================== CRIZAL ===================== --}}
<section class="jr-section jr-bg-2" id="tratamientos">
    <div class="container">
        <div class="row align-items-center g-4 mb-5 flex-lg-row-reverse">
            <div class="col-lg-6">
                <p class="jr-eyebrow mb-2">Tratamientos</p>
                <h2 class="mb-3" style="font-size:clamp(1.8rem,4vw,2.6rem)">Crizal: protección de pies a cabeza para tus lentes</h2>
                <p class="jr-text-muted mb-4">Los tratamientos Crizal combinan capas antirreflejantes, antirrayas, repelentes al agua y filtros de luz azul y UV. Elige el nivel de protección ideal para tu día a día.</p>
                <a href="{{ route('lentes') }}" class="btn btn-jr-gold">Arma tus lentes con Crizal</a>
            </div>
            <div class="col-lg-6">
                <div class="jr-brand-hero jr-reveal">
                    <img src="{{ asset('img/crizal-comparativa.jpg') }}" alt="Tabla comparativa de la línea Crizal">
                </div>
            </div>
        </div>

        {{-- Tabla comparativa de tratamientos --}}
        <p class="jr-eyebrow mb-2">Comparativa</p>
        <h3 class="mb-4" style="font-size:clamp(1.4rem,3vw,2rem)">¿Qué protege cada tratamiento?</h3>

        <div class="table-responsive">
            <table class="jr-compare">
                <thead>
                    <tr>
                        <th scope="col" style="text-align:left">Tratamiento</th>
                        <th scope="col">Protección UV</th>
                        <th scope="col">Luz azul</th>
                        <th scope="col">Reflejos</th>
                        <th scope="col">Rayas</th>
                        <th scope="col">Manchas</th>
                        <th scope="col">Agua</th>
                        <th scope="col">Precio</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($comparativa as $t)
                    <tr>
                        <th scope="row">
                            {{ $t->nombre }}
                            @if ($t->premium)<i class="fa-solid fa-star jr-text-gold ms-1" title="Premium" style="font-size:.7rem"></i>@endif
                        </th>
                        @foreach ($t->puntuaciones() as $valor)
                        <td>
                            <span class="jr-score" role="img" aria-label="{{ $valor }} de 3">
                                @for ($i = 1; $i <= 3; $i++)
                                    <span class="{{ $i <= $valor ? 'on' . ($t->premium ? ' gold' : '') : '' }}"></span>
                            @endfor
                            </span>
                        </td>
                        @endforeach
                        <td class="is-price">${{ number_format($t->precio, 0) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <p class="jr-text-muted small mt-3 mb-0">Escala de 0 a 3 según el nivel de protección de cada tratamiento. Precios de referencia en MXN.</p>
    </div>
</section>

@endsection
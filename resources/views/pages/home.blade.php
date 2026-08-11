@extends('layouts.app')

@section('title', 'Inicio')
@section('meta_description', 'JReyes Ópticos: mejora lo que ves. Lentes graduados, progresivos Varilux, tratamientos Crizal y lentes de contacto.')

@section('content')

{{-- ===================== HERO ===================== --}}
<section class="jr-hero jr-section">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-6">
                <p class="jr-eyebrow mb-3">Óptica · Veracruz</p>
                <h1 class="jr-hero__title mb-4">
                    Mejora <em>lo que ves</em>,<br>sin gastar de más.
                </h1>
                <p class="jr-hero__lead mb-4">
                    Lentes graduados a la medida, progresivos de alta gama y lentes de contacto.
                    Arma tus lentes y conoce el precio al instante.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('lentes') }}" class="btn btn-jr-primary btn-lg">
                        <i class="fa-solid fa-glasses me-2"></i> Arma tus lentes
                    </a>
                    <a href="{{ route('contacto') }}" class="btn btn-jr-outline btn-lg">
                        Agenda tu examen
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="jr-hero__media jr-reveal">
                    <img src="{{ asset('img/logo-jreyes.png') }}"
                        alt="Lentes progresivos Varilux Liberty 360°" loading="eager">
                    <!-- <span class="jr-hero__badge"><i class="fa-solid fa-gift me-1"></i> Armazón gratis en tus Varilux</span> -->
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===================== BARRA DE CONFIANZA ===================== --}}
<section class="jr-trust py-3">
    <div class="container">
        <div class="row text-center g-3 justify-content-between">
            <div class="col-6 col-md-3 jr-trust__item"><i class="fa-solid fa-eye me-2"></i> Examen profesional</div>
            <div class="col-6 col-md-3 jr-trust__item"><i class="fa-solid fa-award me-2"></i> Marcas originales</div>
            <div class="col-6 col-md-3 jr-trust__item"><i class="fa-solid fa-tags me-2"></i> Precios claros</div>
            <div class="col-6 col-md-3 jr-trust__item"><i class="fa-solid fa-hand-holding-heart me-2"></i> Asesoría personal</div>
        </div>
    </div>
</section>

{{-- ===================== MISIÓN Y VISIÓN ===================== --}}
<section class="jr-section">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-6 jr-reveal">
                <div class="jr-feature h-100">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <i class="fa-solid fa-bullseye"></i>
                        <h2 class="h3 mb-0">Misión</h2>
                    </div>
                    <p class="jr-text-muted mb-0">Ofrecer un servicio integral mediante la combinación de valores, calidad de atención y asesoría, de manera que satisfagan las necesidades y expectativas de nuestros clientes, produciendo un impacto positivo en sus vidas y crecimiento personal.</p>
                </div>
            </div>
            <div class="col-lg-6 jr-reveal">
                <div class="jr-feature h-100">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <i class="fa-solid fa-eye"></i>
                        <h2 class="h3 mb-0">Visión</h2>
                    </div>
                    <p class="jr-text-muted mb-0">Ser la empresa de vanguardia que provea la más alta calidad en sus líneas de productos y la adecuada atención visual de cada uno de nuestros clientes a lo largo de sus vidas.</p>
                </div>
            </div>
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('empresa') }}" class="btn btn-jr-outline">Conoce más sobre nosotros</a>
        </div>
    </div>
</section>

{{-- ===================== CATEGORÍAS ===================== --}}
<section class="jr-section">
    <div class="container">
        <div class="row align-items-end mb-4 g-3">
            <div class="col-md-8">
                <p class="jr-eyebrow mb-2">Nuestro catálogo</p>
                <h2 class="mb-0" style="font-size:clamp(1.9rem,4vw,2.8rem)">Encuentra el lente perfecto para tu vista</h2>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ route('lentes') }}" class="btn btn-jr-outline">Ver todo el catálogo</a>
            </div>
        </div>

        <div class="row g-4">
            @foreach ($lensTypes as $tipo)
            <div class="col-md-4 jr-reveal">
                <a href="{{ route('lentes') }}#{{ $tipo->slug }}" class="jr-cat text-decoration-none jr-bg-2 h-100">
                    <div class="jr-cat__body">
                        <div class="jr-cat__icon"><i class="{{ $tipo->icono ?? 'fa-solid fa-glasses' }}"></i></div>
                        <h3 class="jr-cat__title mb-2">{{ $tipo->nombre }}</h3>
                        <p class="jr-text-muted mb-3">{{ $tipo->resumen }}</p>
                        <span class="jr-text-gold fw-semibold">
                            Desde {{ '$' . number_format($tipo->designs->min('precio'), 0) }} MXN
                            <i class="fa-solid fa-arrow-right-long ms-1"></i>
                        </span>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===================== PROGRESIVOS PREMIUM (VARILUX) ===================== --}}
<section class="jr-section jr-bg-2">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-5">
                <p class="jr-eyebrow mb-2">Progresivos de alta gama</p>
                <h2 class="mb-3" style="font-size:clamp(1.9rem,4vw,2.6rem)">La diferencia se llama Varilux</h2>
                <p class="jr-text-muted mb-4">
                    Los progresivos Varilux amplían las zonas de visión nítida y reducen las zonas
                    difusas. Ves de lejos, en intermedio y de cerca con una transición natural, sin líneas.
                </p>
                <a href="{{ route('marcas') }}" class="btn btn-jr-gold">Conoce las marcas</a>
            </div>
            <div class="col-lg-7">
                <div class="row g-3">
                    @foreach ($premium as $d)
                    <div class="col-sm-4 jr-reveal">
                        <div class="jr-card">
                            <div class="jr-card__media">
                                <img src="{{ asset($d->imagen ?? 'img/varilux-comfort.jpg') }}" alt="{{ $d->nombre }}" loading="lazy">
                            </div>
                            <div class="jr-card__body">
                                <span class="jr-badge-premium mb-2"><i class="fa-solid fa-star"></i> Premium</span>
                                <h3 class="jr-card__title">{{ $d->nombre }}</h3>
                                <p class="jr-card__price mb-0">Desde ${{ number_format($d->precio, 0) }} MXN</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===================== TRATAMIENTOS (CRIZAL) ===================== --}}
<section class="jr-section">
    <div class="container">
        <div class="row align-items-center g-4 flex-lg-row-reverse">
            <div class="col-lg-5">
                <p class="jr-eyebrow mb-2">Protección para tus lentes</p>
                <h2 class="mb-3" style="font-size:clamp(1.9rem,4vw,2.6rem)">Crizal: a ver bien en todo momento</h2>
                <p class="jr-text-muted mb-4">
                    Los tratamientos Crizal reducen reflejos, resisten rayaduras, repelen agua y polvo
                    y filtran la luz azul dañina. Elige la capa que mejor cuida tu vista.
                </p>
                <a href="{{ route('marcas') }}#tratamientos" class="btn btn-jr-outline">Comparar tratamientos</a>
            </div>
            <div class="col-lg-7">
                <div class="jr-brand-hero jr-reveal">
                    <img src="{{ asset('img/crizal-decidete.jpg') }}" alt="Comparación con y sin tratamiento Crizal Sapphire 360°">
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===================== CTA FINAL ===================== --}}
<section class="jr-section-sm">
    <div class="container">
        <div class="jr-surface p-4 p-lg-5 text-center jr-reveal" style="border-radius:var(--jr-radius-lg);border:1px solid var(--jr-line)">
            <h2 class="mb-3" style="font-size:clamp(1.7rem,3.5vw,2.4rem)">¿Listo para ver mejor?</h2>
            <p class="jr-text-muted mb-4 mx-auto" style="max-width:48ch">Agenda tu examen de la vista o cotiza tus lentes. Te asesoramos para elegir la mejor opción para ti.</p>
            <div class="d-flex flex-wrap gap-3 justify-content-center">
                <a href="{{ route('contacto') }}" class="btn btn-jr-primary btn-lg">Agenda tu cita</a>
                <a href="{{ route('lentes') }}" class="btn btn-jr-outline btn-lg">Arma tus lentes</a>
            </div>
        </div>
    </div>
</section>

@endsection
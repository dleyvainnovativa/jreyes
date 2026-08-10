@extends('layouts.app')

@section('title', 'Lentes graduados')
@section('meta_description', 'Arma tus lentes graduados: elige tipo, diseño y tratamiento, y conoce el precio al instante. Monofocal, bifocal y progresivo.')

@section('content')

{{-- Encabezado --}}
<section class="jr-section-sm jr-bg-2">
    <div class="container">
        <p class="jr-eyebrow mb-2">Catálogo</p>
        <h1 class="mb-2" style="font-size:clamp(2rem,5vw,3.2rem)">Lentes graduados</h1>
        <p class="jr-text-muted mb-0" style="max-width:56ch">Elige el tipo de mica que necesitas, su diseño y el tratamiento. El precio se calcula al momento.</p>
    </div>
</section>

{{-- ===================== CONFIGURADOR (elemento firma) ===================== --}}
<section class="jr-section">
    <div class="container">
        <div class="row g-4 g-lg-5">
            <div class="col-lg-7">
                <div id="jr-configurator" class="jr-config">
                    {{-- Paso 1 --}}
                    <div class="jr-config__step">
                        <div class="jr-config__label mb-3"><span class="jr-config__num">1</span> Tipo de lente</div>
                        <div class="jr-chips" data-step="tipo"></div>
                    </div>
                    {{-- Paso 2 --}}
                    <div class="jr-config__step">
                        <div class="jr-config__label mb-3"><span class="jr-config__num">2</span> Diseño y material</div>
                        <div class="jr-chips" data-step="diseno"></div>
                        <p class="jr-text-muted mb-0 mt-2 small" data-diseno-empty>Primero elige un tipo de lente.</p>
                    </div>
                    {{-- Paso 3 --}}
                    <div class="jr-config__step">
                        <div class="jr-config__label mb-3"><span class="jr-config__num">3</span> Tratamiento Antireflejante <span class="jr-text-muted fw-normal small">(opcional)</span></div>
                        <div class="jr-chips" data-step="tratamiento"></div>
                    </div>
                    {{-- Paso 4 --}}
                    <div class="jr-config__step">
                        <div class="jr-config__label mb-3"><span class="jr-config__num">4</span> Tratamiento Fotocromático <span class="jr-text-muted fw-normal small">(opcional)</span></div>
                        <div class="jr-chips" data-step="extra"></div>
                    </div>
                    {{-- Total --}}
                    <div class="jr-config__total d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div>
                            <p class="jr-config__hint mb-1">Precio estimado</p>
                            <div class="jr-config__price" data-total>$0</div>
                            <p class="jr-config__hint mt-2 mb-0" data-breakdown>Elige el tipo y el diseño para ver tu precio.</p>
                        </div>
                        <a href="#" class="btn btn-jr-primary" data-wa-config target="_blank" rel="noopener">
                            <i class="fa-brands fa-whatsapp me-1"></i> Pedir por WhatsApp
                        </a>
                    </div>
                </div>
                <p class="jr-text-muted small mt-3 mb-0">
                    <i class="fa-solid fa-circle-info me-1 jr-text-gold"></i>
                    El precio es de referencia y corresponde a las micas. El examen y el armazón se cotizan en tienda; en tus Varilux el armazón de línea básica va incluido.
                </p>
            </div>

            {{-- Resumen en vivo de la configuración --}}
            <div class="col-lg-5">
                <div class="jr-card h-100" id="jr-config-summary">
                    <div class="jr-card__media" style="aspect-ratio:auto">
                        <img data-summary-img src="{{ asset('img/logo-jreyes.png') }}"
                             alt="Resumen de tu configuración"
                             style="object-fit:contain;background:#fff;transition:opacity .2s ease">
                    </div>
                    <div class="jr-card__body">
                        {{-- Estado inicial: guía. Se oculta al empezar a elegir. --}}
                        <div data-summary-empty>
                            <h3 class="jr-card__title">¿Por qué elegir un buen progresivo?</h3>
                            <p class="jr-text-muted mb-0">Un diseño premium amplía el pasillo de visión y reduce las zonas difusas a los lados, para que te adaptes más rápido y veas nítido en todas las distancias.</p>
                        </div>
                        {{-- Resumen dinámico --}}
                        <div data-summary-detail class="d-none">
                            <h3 class="jr-card__title mb-3">Tu configuración</h3>
                            <div data-summary-list class="d-flex flex-column gap-3"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===================== DETALLE POR TIPO ===================== --}}
@foreach ($lensTypes as $tipo)
<section class="jr-section-sm {{ $loop->even ? 'jr-bg-2' : '' }}" id="{{ $tipo->slug }}">
    <div class="container">
        <div class="row mb-4">
            <div class="col-lg-8">
                <p class="jr-eyebrow mb-2"><i class="{{ $tipo->icono }}"></i> {{ $tipo->nombre }}</p>
                <h2 class="mb-2" style="font-size:clamp(1.6rem,3.5vw,2.2rem)">{{ $tipo->resumen }}</h2>
                <p class="jr-text-muted mb-0">{{ $tipo->descripcion }}</p>
            </div>
        </div>

        <div class="row g-3">
            @foreach ($tipo->designs as $d)
                <div class="col-md-6 col-lg-4 jr-reveal">
                    <div class="jr-package h-100 {{ $d->premium ? 'is-featured' : '' }}">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h3 class="h5 mb-1">{{ $d->nombre }}</h3>
                                <span class="jr-tag">{{ $d->material }}</span>
                                @if ($d->indice)<span class="jr-tag">Índice {{ $d->indice }}</span>@endif
                            </div>
                            @if ($d->premium)<span class="jr-badge-premium"><i class="fa-solid fa-star"></i> Premium</span>@endif
                        </div>
                        <p class="jr-text-muted small mb-3">{{ $d->descripcion }}</p>
                        <div class="jr-package__price">${{ number_format($d->precio, 0) }} <span class="fs-6 jr-text-muted">MXN</span></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endforeach

{{-- Datos del configurador para el cliente --}}
<script type="application/json" id="jr-config-data">{!! json_encode($configData, JSON_UNESCAPED_UNICODE) !!}</script>

@endsection
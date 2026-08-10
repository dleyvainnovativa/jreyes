@extends('layouts.app')

@section('title', 'Lentes de contacto')
@section('meta_description', 'Catálogo de lentes de contacto: Alcon, Johnson & Johnson, Bausch & Lomb e Hidrosoft. Esféricos, tóricos, multifocales y de color.')

@section('content')

{{-- Encabezado --}}
<section class="jr-section-sm jr-bg-2">
    <div class="container">
        <p class="jr-eyebrow mb-2">Catálogo</p>
        <h1 class="mb-2" style="font-size:clamp(2rem,5vw,3.2rem)">Lentes de contacto</h1>
        <p class="jr-text-muted mb-0" style="max-width:60ch">Las mejores marcas para cada necesidad. Filtra por marca o por tipo para encontrar los tuyos.</p>
    </div>
</section>

<section class="jr-section">
    <div class="container" id="jr-contact-catalog">

        {{-- Filtros --}}
        <div class="mb-4">
            <p class="jr-eyebrow mb-2">Marca</p>
            <div class="jr-filter-bar mb-3">
                <button class="jr-filter active" data-filter-type="brand" data-filter="all">Todas</button>
                @foreach ($brands as $brand)
                <button class="jr-filter" data-filter-type="brand" data-filter="{{ $brand->slug }}">{{ $brand->nombre }}</button>
                @endforeach
            </div>

            <p class="jr-eyebrow mb-2">Tipo</p>
            <div class="jr-filter-bar">
                <button class="jr-filter active" data-filter-type="tipo" data-filter="all">Todos</button>
                <button class="jr-filter" data-filter-type="tipo" data-filter="Esférico">Esférico</button>
                <button class="jr-filter" data-filter-type="tipo" data-filter="Tórico">Tórico</button>
                <button class="jr-filter" data-filter-type="tipo" data-filter="Multifocal">Multifocal</button>
                <button class="jr-filter" data-filter-type="tipo" data-filter="Color">Color</button>
            </div>
        </div>

        <hr class="jr-divider my-4">

        {{-- Listado por marca --}}
        @foreach ($brands as $brand)
        <div class="mb-5" data-brand-group>
            <h2 class="h4 mb-3">{{ $brand->nombre }}</h2>
            <div class="row g-3">
                @foreach ($brand->lenses as $lens)
                @php
                $waMsg = "¡Hola! Me interesan estos lentes de contacto:\n"
                . "• {$lens->nombre}\n"
                . ($lens->tipo ? "• Tipo: {$lens->tipo}\n" : '')
                . ($lens->reemplazo ? "• Reemplazo: {$lens->reemplazo}\n" : '')
                . "• Precio: $" . number_format($lens->precio, 0) . " MXN";
                $waHref = 'https://wa.me/' . preg_replace('/\D/', '', $whatsapp ?? '') . '?text=' . rawurlencode($waMsg);
                $img = $lens->imagen ? asset($lens->imagen) : $logo;
                $panelId = 'cl-panel-' . $lens->id;
                @endphp
                <div class="col-md-6 col-lg-4"
                    data-cl-row
                    data-brand="{{ $brand->slug }}"
                    data-tipo="{{ $lens->tipo }}">
                    <div class="jr-cl-item h-100">
                        <!-- <div class="jr-cl-item"> -->
                        <button type="button" class="jr-cl-head" data-cl-toggle
                            aria-expanded="false" aria-controls="{{ $panelId }}">
                            <div class="text-start">
                                <div class="jr-cl-row__name">{{ $lens->nombre }}</div>
                                <div class="jr-cl-row__meta">
                                    @if ($lens->tipo)<span class="jr-tag">{{ $lens->tipo }}</span>@endif
                                    @if ($lens->reemplazo)<span class="jr-tag">{{ $lens->reemplazo }}</span>@endif
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="jr-cl-row__price">${{ number_format($lens->precio, 0) }}</span>
                                <i class="fa-solid fa-chevron-down jr-cl-caret" aria-hidden="true"></i>
                            </div>
                        </button>
                        <div class="jr-cl-panel" id="{{ $panelId }}" hidden>
                            <div class="jr-cl-panel__media">
                                <img src="{{ $img }}" alt="{{ $lens->nombre }}" loading="lazy">
                            </div>
                            <a href="{{ $waHref }}" target="_blank" rel="noopener" class="btn btn-jr-gold btn-sm w-100">
                                <i class="fa-brands fa-whatsapp me-1"></i> Obtener por WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach

        {{-- Estado vacío --}}
        <div class="text-center py-5 d-none" data-empty>
            <i class="fa-solid fa-magnifying-glass jr-text-muted mb-3" style="font-size:2rem"></i>
            <p class="jr-text-muted mb-0">No hay lentes con esos filtros. Prueba con otra combinación.</p>
        </div>

        <p class="jr-text-muted small mt-2 mb-0">Precios de referencia en MXN. Consulta disponibilidad y graduación en tienda.</p>
    </div>
</section>

@endsection
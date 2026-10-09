@extends('layouts.app')

@section('title', 'Lentes ' . $tipoNombre)
@section('meta_description', 'Arma tus lentes ' . strtolower($tipoNombre) . ': elige nivel, material y tratamiento, y conoce el precio al instante.')

@section('content')

{{-- Encabezado --}}
<section class="jr-section-sm jr-bg-2">
    <div class="container">
        <p class="jr-eyebrow mb-2">Catálogo · {{ $tipoNombre }}</p>
        <h1 class="mb-2" style="font-size:clamp(2rem,5vw,3.2rem)">Arma tus lentes {{ strtolower($tipoNombre) }}</h1>
        <p class="jr-text-muted mb-3" style="max-width:56ch">Elige el armazón, el nivel de la mica, el material y el tratamiento. El precio se calcula al momento.</p>

        {{-- Cambiar de tipo sin salir de la página --}}
        <div class="jr-type-switch" role="tablist" aria-label="Tipo de lente">
            @foreach ($tipos as $slug => $label)
                <a href="{{ route('lentes', ['tipo' => $slug]) }}"
                   class="jr-type-switch__btn {{ $slug === $tipoActual ? 'is-active' : '' }}"
                   role="tab" aria-selected="{{ $slug === $tipoActual ? 'true' : 'false' }}">{{ $label }}</a>
            @endforeach
            <a href="{{ route('lentes-contacto') }}" class="jr-type-switch__btn jr-type-switch__btn--alt">
                Lentes de contacto <i class="fa-solid fa-arrow-right-long ms-1"></i>
            </a>
        </div>
    </div>
</section>

{{-- ===================== CONFIGURADOR ===================== --}}
<section class="jr-section">
    <div class="container">
        <div class="row g-4 g-lg-5">
            <div class="col-lg-7">
                {{-- Los pasos se generan dinámicamente desde el árbol (configurator.js) --}}
                <div id="jr-configurator" class="jr-config" data-tipo="{{ $tipoActual }}">
                    {{-- Paso 1 — Armazón (obligatorio) --}}
                    <div class="jr-config__step">
                        <div class="jr-config__label mb-3"><span class="jr-config__num">1</span> Armazón</div>
                        <div class="jr-frame-grid" data-step="armazon" role="listbox" aria-label="Elige un armazón"></div>
                        <p class="jr-text-muted mb-0 mt-2 small">Elige el modelo que más te guste. El armazón va incluido.</p>
                    </div>

                    {{-- Pasos dinámicos del árbol (nivel → material/diseño → tratamiento) --}}
                    <div data-dynamic-steps></div>

                    {{-- Total --}}
                    <div class="jr-config__total d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div>
                            <p class="jr-config__hint mb-1">Precio estimado</p>
                            <div class="jr-config__price" data-total>$0</div>
                            <p class="jr-config__hint mt-2 mb-0" data-breakdown>Elige un armazón para comenzar.</p>
                        </div>
                        <a href="#" class="btn btn-jr-primary disabled" data-wa-config target="_blank" rel="noopener" aria-disabled="true">
                            <i class="fa-brands fa-whatsapp me-1"></i> Pedir por WhatsApp
                        </a>
                    </div>
                </div>
                <p class="jr-text-muted small mt-3 mb-0">
                    <i class="fa-solid fa-circle-info me-1 jr-text-gold"></i>
                    El precio es de referencia y corresponde a las micas. Algunas opciones se cotizan en tienda. El examen se realiza en sucursal.
                </p>
            </div>

            {{-- Resumen en vivo con galería de imágenes --}}
            <div class="col-lg-5">
                <div class="jr-card h-100" id="jr-config-summary">
                    {{-- Galería: una imagen por cada selección con imagen propia --}}
                    <div class="jr-summary-gallery" data-summary-gallery>
                        <div class="jr-summary-gallery__placeholder" data-summary-placeholder>
                            <img src="{{ asset('img/logo-jreyes.png') }}" alt="JReyes Ópticos">
                        </div>
                    </div>
                    <div class="jr-card__body">
                        <div data-summary-empty>
                            <h3 class="jr-card__title">Tu selección aparecerá aquí</h3>
                            <p class="jr-text-muted mb-0">A medida que elijas armazón, nivel, material y tratamiento, verás cada opción con su imagen y precio.</p>
                        </div>
                        <div data-summary-detail class="d-none">
                            <h3 class="jr-card__title mb-3">Tu configuración</h3>
                            <div data-summary-list class="d-flex flex-column gap-3"></div>
                        </div>
                    </div>

                    {{-- ============ GRADUACIÓN (opcional) ============ --}}
                    <div class="jr-grad" id="jr-grad">
                        <div class="jr-grad__head">
                            <h3 class="jr-card__title mb-1">Tu graduación <span class="jr-text-muted fw-normal small">(opcional)</span></h3>
                            <p class="jr-text-muted small mb-0">Si tienes tu receta a la mano, captúrala y la enviamos junto con tu pedido. Déjala en blanco si no la tienes.</p>
                        </div>

                        {{-- Vista TABLA (escritorio) --}}
                        <div class="jr-grad__table-wrap" aria-hidden="false">
                            <table class="jr-grad-table">
                                <thead>
                                    <tr>
                                        <th scope="col"><span class="visually-hidden">Ojo</span></th>
                                        <th scope="col">Esfera</th>
                                        <th scope="col">Cilindro</th>
                                        <th scope="col">Eje</th>
                                        <th scope="col">ADD</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row" class="jr-grad-table__eye">OD <span class="jr-text-muted fw-normal">der.</span></th>
                                        <td><input type="text" inputmode="decimal" data-grad="od-esfera" class="jr-grad-input" aria-label="Esfera ojo derecho" placeholder="0.00"></td>
                                        <td><input type="text" inputmode="decimal" data-grad="od-cilindro" class="jr-grad-input" aria-label="Cilindro ojo derecho" placeholder="0.00"></td>
                                        <td><input type="text" inputmode="numeric" data-grad="od-eje" class="jr-grad-input" aria-label="Eje ojo derecho" placeholder="0–180"></td>
                                        <td><input type="text" inputmode="decimal" data-grad="od-add" class="jr-grad-input" aria-label="ADD ojo derecho" placeholder="0.00"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="jr-grad-table__eye">OI <span class="jr-text-muted fw-normal">izq.</span></th>
                                        <td><input type="text" inputmode="decimal" data-grad="oi-esfera" class="jr-grad-input" aria-label="Esfera ojo izquierdo" placeholder="0.00"></td>
                                        <td><input type="text" inputmode="decimal" data-grad="oi-cilindro" class="jr-grad-input" aria-label="Cilindro ojo izquierdo" placeholder="0.00"></td>
                                        <td><input type="text" inputmode="numeric" data-grad="oi-eje" class="jr-grad-input" aria-label="Eje ojo izquierdo" placeholder="0–180"></td>
                                        <td><input type="text" inputmode="decimal" data-grad="oi-add" class="jr-grad-input" aria-label="ADD ojo izquierdo" placeholder="0.00"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        {{-- Vista TARJETAS (móvil) --}}
                        <div class="jr-grad__cards">
                            <div class="jr-grad-eye">
                                <p class="jr-grad-eye__title">Ojo derecho <span class="jr-text-muted fw-normal">(OD)</span></p>
                                <div class="jr-grad-eye__fields">
                                    <label class="jr-grad-field"><span>Esfera</span><input type="text" inputmode="decimal" data-grad-m="od-esfera" placeholder="0.00"></label>
                                    <label class="jr-grad-field"><span>Cilindro</span><input type="text" inputmode="decimal" data-grad-m="od-cilindro" placeholder="0.00"></label>
                                    <label class="jr-grad-field"><span>Eje</span><input type="text" inputmode="numeric" data-grad-m="od-eje" placeholder="0–180"></label>
                                    <label class="jr-grad-field"><span>ADD</span><input type="text" inputmode="decimal" data-grad-m="od-add" placeholder="0.00"></label>
                                </div>
                            </div>
                            <div class="jr-grad-eye">
                                <p class="jr-grad-eye__title">Ojo izquierdo <span class="jr-text-muted fw-normal">(OI)</span></p>
                                <div class="jr-grad-eye__fields">
                                    <label class="jr-grad-field"><span>Esfera</span><input type="text" inputmode="decimal" data-grad-m="oi-esfera" placeholder="0.00"></label>
                                    <label class="jr-grad-field"><span>Cilindro</span><input type="text" inputmode="decimal" data-grad-m="oi-cilindro" placeholder="0.00"></label>
                                    <label class="jr-grad-field"><span>Eje</span><input type="text" inputmode="numeric" data-grad-m="oi-eje" placeholder="0–180"></label>
                                    <label class="jr-grad-field"><span>ADD</span><input type="text" inputmode="decimal" data-grad-m="oi-add" placeholder="0.00"></label>
                                </div>
                            </div>
                        </div>

                        {{-- DIP (una sola medida) --}}
                        <div class="jr-grad__dip">
                            <label class="jr-grad-field jr-grad-field--inline">
                                <span>DIP <span class="jr-text-muted fw-normal">(distancia interpupilar)</span></span>
                                <input type="text" inputmode="decimal" data-grad="dip" class="jr-grad-input" placeholder="mm" aria-label="Distancia interpupilar">
                            </label>
                        </div>

                        <button type="button" class="jr-grad__clear" data-grad-clear>
                            <i class="fa-solid fa-eraser me-1"></i> Borrar graduación
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Datos del configurador para el cliente --}}
<script type="application/json" id="jr-config-data">{!! json_encode($configData, JSON_UNESCAPED_UNICODE) !!}</script>

@endsection

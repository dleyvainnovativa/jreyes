@extends('layouts.app')

@section('title', 'Paquetes de lentes completos')
@section('meta_description', 'Paquetes de lentes completos: armazón más micas con tratamiento a precio cerrado. Monofocal, bifocal y progresivo.')

@section('content')

{{-- Encabezado --}}
<section class="jr-section-sm jr-bg-2">
    <div class="container">
        <p class="jr-eyebrow mb-2">Precio cerrado</p>
        <h1 class="mb-2" style="font-size:clamp(2rem,5vw,3.2rem)">Lentes completos</h1>
        <p class="jr-text-muted mb-0" style="max-width:60ch">Armazón más micas con tratamiento, todo en un solo precio. Ideal si buscas la opción más sencilla y económica.</p>
    </div>
</section>

<section class="jr-section">
    <div class="container">
        @foreach ($packages as $nombre => $grupo)
            <div class="mb-5">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <h2 class="h3 mb-0">{{ $nombre }}</h2>
                    <span class="jr-tag">{{ $grupo->count() }} opciones</span>
                </div>
                <div class="row g-3">
                    @foreach ($grupo as $paquete)
                        <div class="col-md-6 col-lg-3 jr-reveal">
                            <div class="jr-package h-100 {{ $paquete->destacado ? 'is-featured' : '' }}">
                                @if ($paquete->destacado)
                                    <span class="jr-badge-premium mb-2"><i class="fa-solid fa-fire"></i> Más pedido</span>
                                @endif
                                <h3 class="h6 mb-1">{{ $paquete->tratamiento }}</h3>
                                <p class="jr-text-muted small mb-3">{{ $paquete->incluye }}</p>
                                <div class="jr-package__price mb-3">${{ number_format($paquete->precio, 0) }} <span class="fs-6 jr-text-muted">MXN</span></div>
                                @php
                                    $waMsg = "¡Hola! Me interesa el paquete de lentes completos:\n"
                                        . "• {$paquete->nombre} — {$paquete->tratamiento}\n"
                                        . "• Precio: $" . number_format($paquete->precio, 0) . " MXN";
                                    $waHref = 'https://wa.me/' . preg_replace('/\D/', '', $whatsapp ?? '') . '?text=' . rawurlencode($waMsg);
                                @endphp
                                <a href="{{ $waHref }}" target="_blank" rel="noopener" class="btn btn-jr-outline btn-sm w-100">
                                    <i class="fa-brands fa-whatsapp me-1"></i> Lo quiero
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        <div class="jr-surface p-4 p-lg-5 text-center mt-4" style="border-radius:var(--jr-radius-lg);border:1px solid var(--jr-line)">
            <h2 class="mb-2" style="font-size:clamp(1.5rem,3vw,2rem)">¿Necesitas algo más específico?</h2>
            <p class="jr-text-muted mb-4 mx-auto" style="max-width:46ch">Si buscas progresivos de alta gama o un tratamiento premium, arma tus lentes a la medida.</p>
            <a href="{{ route('lentes') }}" class="btn btn-jr-primary btn-lg">Arma tus lentes</a>
        </div>
    </div>
</section>

@endsection
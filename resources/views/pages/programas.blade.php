@extends('layouts.app')

@section('title', 'Programas')
@section('meta_description', 'Conoce JReyes Ópticos: misión, visión, tecnología de diagnóstico, calidad de armazones, nuestro proceso y programas de salud visual.')

@section('content')


{{-- ===================== PROGRAMAS ===================== --}}
<section class="jr-section jr-bg-2" id="alianzas">
    <div class="container">
        <p class="jr-eyebrow mb-2">Alianzas estratégicas</p>
        <h2 class="mb-2" style="font-size:clamp(1.8rem,4vw,2.6rem)">Programas de salud visual</h2>
        <p class="jr-text-muted mb-4" style="max-width:60ch">Colaboramos con empresas e instituciones para acercar la salud visual a más personas.</p>
        <div class="row g-4">
            <div class="col-lg-6 jr-reveal">
                <div class="jr-card h-100">
                    <div class="jr-card__body">
                        <span class="jr-badge-premium mb-3"><i class="fa-solid fa-building"></i> Empresas</span>
                        <h3 class="jr-card__title">Programa de Salud Visual Empresarial</h3>
                        <p class="jr-text-muted">Llevamos el diagnóstico visual a tu empresa, con expediente por trabajador y atención de calidad. Cuidar la vista de tu equipo mejora la productividad y reduce riesgos de accidente.</p>
                        <ul class="list-unstyled jr-text-muted mb-0 d-flex flex-column gap-2">
                            <li><i class="fa-solid fa-check jr-text-gold me-2"></i> Diagnóstico para tus colaboradores</li>
                            <li><i class="fa-solid fa-check jr-text-gold me-2"></i> Expediente individual</li>
                            <li><i class="fa-solid fa-check jr-text-gold me-2"></i> Mayor rendimiento y seguridad</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 jr-reveal">
                <div class="jr-card h-100">
                    <div class="jr-card__body">
                        <span class="jr-badge-premium mb-3"><i class="fa-solid fa-hand-holding-heart"></i> Social</span>
                        <h3 class="jr-card__title">Programa de Subsidio de Lentes</h3>
                        <p class="jr-text-muted">Gracias a alianzas estratégicas, subsidiamos las micas monofocales o bifocales para que más personas accedan a lentes completos pagando únicamente el armazón.</p>
                        <ul class="list-unstyled jr-text-muted mb-0 d-flex flex-column gap-2">
                            <li><i class="fa-solid fa-check jr-text-gold me-2"></i> Micas mono/bifocales subsidiadas</li>
                            <li><i class="fa-solid fa-check jr-text-gold me-2"></i> Pagas solo el armazón</li>
                            <li><i class="fa-solid fa-check jr-text-gold me-2"></i> Salud visual para la comunidad</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <p class="jr-text-muted small mt-3 mb-0">¿Tu empresa o institución quiere participar? <a href="{{ route('contacto') }}" class="jr-text-gold">Contáctanos</a> para conocer los detalles.</p>
    </div>
</section>

{{-- ===================== REFERENCIAS COMERCIALES ===================== --}}
<section class="jr-section">
    <div class="container">
        <p class="jr-eyebrow mb-2">Confían en nosotros</p>
        <h2 class="mb-4" style="font-size:clamp(1.6rem,3.5vw,2.2rem)">Referencias comerciales</h2>
        <div class="row g-3">
            @foreach (['DACSA del Mercado', 'Transportadora San José', 'Transportadora ATM', 'Colegio Jean Piaget', 'Restaurant Quick Lunch', 'Pastelerías Lulú'] as $ref)
            <div class="col-6 col-md-4 col-lg-2 jr-reveal">
                <div class="jr-feature h-100 text-center d-flex flex-column justify-content-center" style="min-height:100px">
                    <i class="fa-solid fa-handshake jr-text-gold mb-2"></i>
                    <span class="small fw-semibold">{{ $ref }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===================== CTA ===================== --}}
<section class="jr-section-sm">
    <div class="container">
        <div class="jr-surface p-4 p-lg-5 text-center jr-reveal" style="border-radius:var(--jr-radius-lg);border:1px solid var(--jr-line)">
            <h2 class="mb-3" style="font-size:clamp(1.7rem,3.5vw,2.4rem)">¿Nos visitas?</h2>
            <p class="jr-text-muted mb-4 mx-auto" style="max-width:48ch">Agenda tu examen de la vista o cotiza tus lentes. Con gusto te asesoramos.</p>
            <div class="d-flex flex-wrap gap-3 justify-content-center">
                <a href="{{ route('contacto') }}" class="btn btn-jr-primary btn-lg">Contáctanos</a>
                <a href="{{ route('lentes') }}" class="btn btn-jr-outline btn-lg">Arma tus lentes</a>
            </div>
        </div>
    </div>
</section>

@endsection
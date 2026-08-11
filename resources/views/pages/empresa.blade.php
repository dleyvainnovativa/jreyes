@extends('layouts.app')

@section('title', 'Empresa')
@section('meta_description', 'Conoce JReyes Ópticos: misión, visión, tecnología de diagnóstico, calidad de armazones, nuestro proceso y programas de salud visual.')

@section('content')

{{-- ===================== HERO ===================== --}}
<section class="jr-section-sm jr-bg-2">
    <div class="container">
        <p class="jr-eyebrow mb-2">Conócenos</p>
        <h1 class="mb-2" style="font-size:clamp(2rem,5vw,3.2rem)">Salud visual con calidad y cercanía</h1>
        <p class="jr-text-muted mb-0" style="max-width:60ch">Somos una óptica veracruzana comprometida con la vista de cada cliente: diagnóstico profesional, armazones de calidad y lentes a la medida, con asesoría en cada paso.</p>
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
    </div>
</section>

{{-- ===================== TECNOLOGÍA / DIAGNÓSTICO ===================== --}}
<section class="jr-section jr-bg-2">
    <div class="container">
        <p class="jr-eyebrow mb-2">Tecnología</p>
        <h2 class="mb-4" style="font-size:clamp(1.8rem,4vw,2.6rem)">Contamos con equipo para el diagnóstico de la vista</h2>
        <div class="row g-3">
            <div class="col-md-4 jr-reveal">
                <div class="jr-feature h-100">
                    <i class="fa-solid fa-briefcase-medical mb-2"></i>
                    <h3 class="h5 mb-1">Caja de pruebas</h3>
                    <p class="jr-text-muted small mb-0">Determinación precisa de la graduación para cada paciente.</p>
                </div>
            </div>
            <div class="col-md-4 jr-reveal">
                <div class="jr-feature h-100">
                    <i class="fa-solid fa-glasses mb-2"></i>
                    <h3 class="h5 mb-1">Lensómetro</h3>
                    <p class="jr-text-muted small mb-0">Medición exacta de micas y verificación de tus lentes actuales.</p>
                </div>
            </div>
            <div class="col-md-4 jr-reveal">
                <div class="jr-feature h-100">
                    <i class="fa-solid fa-eye-low-vision mb-2"></i>
                    <h3 class="h5 mb-1">Diagnóstico completo</h3>
                    <p class="jr-text-muted small mb-0">Evaluación del estado de salud visual y ficha de diagnóstico.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===================== CALIDAD DE ARMAZONES ===================== --}}
<section class="jr-section">
    <div class="container">
        <div class="row g-4 align-items-start">
            <div class="col-lg-5">
                <p class="jr-eyebrow mb-2">Calidad</p>
                <h2 class="mb-3" style="font-size:clamp(1.8rem,4vw,2.6rem)">Armazones para cada estilo y presupuesto</h2>
                <p class="jr-text-muted mb-0">Trabajamos con materiales metálicos y de pasta seleccionados por su resistencia, ligereza y comodidad.</p>
            </div>
            <div class="col-lg-7">
                <div class="row g-3">
                    <div class="col-sm-6 jr-reveal">
                        <div class="jr-feature h-100">
                            <h3 class="h6 mb-2"><i class="fa-solid fa-gem me-2 jr-text-gold"></i> Metálicos</h3>
                            <ul class="list-unstyled jr-text-muted mb-0 d-flex flex-column gap-1">
                                <li>Alpaca</li>
                                <li>Acero monel</li>
                                <li>Acero inoxidable</li>
                                <li>Aluminio</li>
                                <li>Titanio</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-6 jr-reveal">
                        <div class="jr-feature h-100">
                            <h3 class="h6 mb-2"><i class="fa-solid fa-layer-group me-2 jr-text-gold"></i> Pasta</h3>
                            <ul class="list-unstyled jr-text-muted mb-0 d-flex flex-column gap-1">
                                <li>Celulosa de algodón</li>
                                <li>Poliamida</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===================== MECÁNICA OPERATIVA (TIMELINE) ===================== --}}
<section class="jr-section jr-bg-2">
    <div class="container">
        <p class="jr-eyebrow mb-2">Nuestro proceso</p>
        <h2 class="mb-4" style="font-size:clamp(1.8rem,4vw,2.6rem)">Así trabajamos, paso a paso</h2>
        <div class="jr-timeline">
            <div class="jr-timeline__item jr-reveal">
                <div class="jr-timeline__dot">1</div>
                <div class="jr-timeline__body">
                    <h3 class="h6 mb-1">Diagnóstico</h3>
                    <p class="jr-text-muted small mb-0">Evaluación de tu salud visual. <span class="jr-text-gold">~5 min</span></p>
                </div>
            </div>
            <div class="jr-timeline__item jr-reveal">
                <div class="jr-timeline__dot">2</div>
                <div class="jr-timeline__body">
                    <h3 class="h6 mb-1">Ficha de diagnóstico</h3>
                    <p class="jr-text-muted small mb-0">Registramos graduación y datos. <span class="jr-text-gold">~3 min</span></p>
                </div>
            </div>
            <div class="jr-timeline__item jr-reveal">
                <div class="jr-timeline__dot">3</div>
                <div class="jr-timeline__body">
                    <h3 class="h6 mb-1">Selección de armazón</h3>
                    <p class="jr-text-muted small mb-0">Eliges el modelo que más te gusta. <span class="jr-text-gold">~5 min</span></p>
                </div>
            </div>
            <div class="jr-timeline__item jr-reveal">
                <div class="jr-timeline__dot">4</div>
                <div class="jr-timeline__body">
                    <h3 class="h6 mb-1">Elaboración del pedido</h3>
                    <p class="jr-text-muted small mb-0">Preparamos tu orden. <span class="jr-text-gold">~3 min</span></p>
                </div>
            </div>
            <div class="jr-timeline__item jr-reveal">
                <div class="jr-timeline__dot">5</div>
                <div class="jr-timeline__body">
                    <h3 class="h6 mb-1">Elaboración de anteojos</h3>
                    <p class="jr-text-muted small mb-0">Fabricación de tus lentes. <span class="jr-text-gold">1 a 7 días</span></p>
                </div>
            </div>
            <div class="jr-timeline__item jr-reveal">
                <div class="jr-timeline__dot">6</div>
                <div class="jr-timeline__body">
                    <h3 class="h6 mb-1">Entrega</h3>
                    <p class="jr-text-muted small mb-0">Recibes tus anteojos completos. <span class="jr-text-gold">~5 min</span></p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===================== BENEFICIOS ===================== --}}
<section class="jr-section">
    <div class="container">
        <p class="jr-eyebrow mb-2">Beneficios</p>
        <h2 class="mb-4" style="font-size:clamp(1.8rem,4vw,2.6rem)">Por qué elegirnos</h2>
        <div class="row g-3">
            <div class="col-md-6 col-lg-3 jr-reveal">
                <div class="jr-feature h-100">
                    <i class="fa-solid fa-stethoscope mb-2"></i>
                    <h3 class="h6 mb-1">Diagnóstico profesional</h3>
                    <p class="jr-text-muted small mb-0">Atención con equipo especializado.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 jr-reveal">
                <div class="jr-feature h-100">
                    <i class="fa-solid fa-folder-open mb-2"></i>
                    <h3 class="h6 mb-1">Expediente por paciente</h3>
                    <p class="jr-text-muted small mb-0">Historial visual siempre a la mano.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 jr-reveal">
                <div class="jr-feature h-100">
                    <i class="fa-solid fa-glasses mb-2"></i>
                    <h3 class="h6 mb-1">Lentes completos</h3>
                    <p class="jr-text-muted small mb-0">Armazón y micas en un solo lugar.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 jr-reveal">
                <div class="jr-feature h-100">
                    <i class="fa-solid fa-medal mb-2"></i>
                    <h3 class="h6 mb-1">Servicio de calidad</h3>
                    <p class="jr-text-muted small mb-0">Asesoría cercana de principio a fin.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===================== PROGRAMAS ===================== --}}
<section class="jr-section jr-bg-2">
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
                <a href="{{ route('contacto') }}" class="btn btn-jr-primary btn-lg">Agenda tu cita</a>
                <a href="{{ route('lentes') }}" class="btn btn-jr-outline btn-lg">Arma tus lentes</a>
            </div>
        </div>
    </div>
</section>

@endsection
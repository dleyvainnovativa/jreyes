@extends('layouts.app')

@section('title', 'Contacto y citas')
@section('meta_description', 'Agenda tu examen de la vista o solicita una cotización en JReyes Ópticos. Estamos en Veracruz.')

@section('content')

{{-- Encabezado --}}
<section class="jr-section-sm jr-bg-2">
    <div class="container">
        <p class="jr-eyebrow mb-2">Estamos para ayudarte</p>
        <h1 class="mb-2" style="font-size:clamp(2rem,5vw,3.2rem)">Contáctanos</h1>
        <p class="jr-text-muted mb-0" style="max-width:58ch">Déjanos tus datos y el motivo de tu visita. Te contactamos para confirmar tu examen de la vista o tu cotización.</p>
    </div>
</section>

<section class="jr-section">
    <div class="container">
        <div class="row g-4">
            {{-- Formulario --}}
            <div class="col-lg-7">
                @if (session('exito'))
                <div class="jr-alert-success mb-4" role="status">
                    <i class="fa-solid fa-circle-check me-2"></i>{{ session('exito') }}
                </div>
                @endif

                <form action="{{ route('contacto.store') }}" method="POST" novalidate>
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="nombre" class="form-label">Nombre completo *</label>
                            <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}"
                                class="form-control @error('nombre') is-invalid @enderror" required>
                            @error('nombre')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="telefono" class="form-label">Teléfono *</label>
                            <input type="tel" id="telefono" name="telefono" value="{{ old('telefono') }}"
                                class="form-control @error('telefono') is-invalid @enderror" required>
                            @error('telefono')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="correo" class="form-label">Correo electrónico</label>
                            <input type="email" id="correo" name="correo" value="{{ old('correo') }}"
                                class="form-control @error('correo') is-invalid @enderror">
                            @error('correo')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="motivo" class="form-label">Motivo de la visita</label>
                            <select id="motivo" name="motivo" class="form-select @error('motivo') is-invalid @enderror">
                                <option value="">Selecciona una opción</option>
                                @foreach (['Examen de la vista', 'Cotización de lentes', 'Lentes de contacto', 'Ajuste o reparación', 'Otro'] as $op)
                                <option value="{{ $op }}" @selected(old('motivo')===$op)>{{ $op }}</option>
                                @endforeach
                            </select>
                            @error('motivo')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="fecha_preferida" class="form-label">Fecha preferida</label>
                            <input type="date" id="fecha_preferida" name="fecha_preferida" value="{{ old('fecha_preferida') }}"
                                min="{{ date('Y-m-d') }}"
                                class="form-control @error('fecha_preferida') is-invalid @enderror">
                            @error('fecha_preferida')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label for="mensaje" class="form-label">Mensaje (opcional)</label>
                            <textarea id="mensaje" name="mensaje" rows="4"
                                class="form-control @error('mensaje') is-invalid @enderror"
                                placeholder="Cuéntanos qué necesitas…">{{ old('mensaje') }}</textarea>
                            @error('mensaje')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-jr-primary btn-lg">
                                <i class="fa-solid fa-paper-plane me-2"></i> Enviar solicitud
                            </button>
                            <p class="jr-text-muted small mt-2 mb-0">* Campos obligatorios. Te contactaremos para confirmar.</p>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Información de la tienda --}}
            <div class="col-lg-5">
                <div class="jr-surface p-4" style="border-radius:var(--jr-radius-lg);border:1px solid var(--jr-line)">
                    <h2 class="h4 mb-4">Visítanos</h2>
                    <ul class="list-unstyled d-flex flex-column gap-3 mb-4">
                        <li class="d-flex gap-3">
                            <i class="fa-solid fa-location-dot jr-text-gold mt-1"></i>
                            <div><strong>Dirección</strong><br><span class="jr-text-muted">Veracruz, Veracruz, México</span></div>
                        </li>
                        <li class="d-flex gap-3">
                            <i class="fa-solid fa-phone jr-text-gold mt-1"></i>
                            <div><strong>Teléfono</strong><br><span class="jr-text-muted">(229) 000 0000</span></div>
                        </li>
                        <li class="d-flex gap-3">
                            <i class="fa-brands fa-whatsapp jr-text-gold mt-1"></i>
                            <div><strong>WhatsApp</strong><br><span class="jr-text-muted">(229) 000 0000</span></div>
                        </li>
                        <li class="d-flex gap-3">
                            <i class="fa-solid fa-envelope jr-text-gold mt-1"></i>
                            <div><strong>Correo</strong><br><span class="jr-text-muted">hola@jreyesopticos.com</span></div>
                        </li>
                    </ul>

                    <hr class="jr-divider mb-4">

                    <h3 class="h5 mb-3">Horario</h3>
                    <ul class="list-unstyled d-flex flex-column gap-2 jr-text-muted mb-0">
                        <li class="d-flex justify-content-between"><span>Lunes a viernes</span><span>9:00 – 19:00</span></li>
                        <li class="d-flex justify-content-between"><span>Sábado</span><span>9:00 – 15:00</span></li>
                        <li class="d-flex justify-content-between"><span>Domingo</span><span>Cerrado</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
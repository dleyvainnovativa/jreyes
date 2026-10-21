@extends('layouts.app')

@section('title', 'Contacto para empresas')
@section('meta_description', 'Convenios y cotizaciones corporativas para empresas en JReyes Ópticos. Salud visual para tu equipo.')

@section('content')

{{-- Encabezado --}}
<section class="jr-section-sm jr-bg-2">
    <div class="container">
        <p class="jr-eyebrow mb-2">Para empresas</p>
        <h1 class="mb-2" style="font-size:clamp(2rem,5vw,3.2rem)">Salud visual para tu equipo</h1>
        <p class="jr-text-muted mb-0" style="max-width:60ch">¿Buscas un convenio o una cotización para tu empresa? Déjanos los datos y te contactamos con una propuesta a la medida.</p>
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

                <form action="{{ route('contacto.empresas.store') }}" method="POST" novalidate>
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="empresa" class="form-label">Empresa *</label>
                            <input type="text" id="empresa" name="empresa" value="{{ old('empresa') }}"
                                class="form-control @error('empresa') is-invalid @enderror" required>
                            @error('empresa')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="nombre_contacto" class="form-label">Nombre de contacto *</label>
                            <input type="text" id="nombre_contacto" name="nombre_contacto" value="{{ old('nombre_contacto') }}"
                                class="form-control @error('nombre_contacto') is-invalid @enderror" required>
                            @error('nombre_contacto')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="puesto" class="form-label">Puesto</label>
                            <input type="text" id="puesto" name="puesto" value="{{ old('puesto') }}"
                                class="form-control @error('puesto') is-invalid @enderror">
                            @error('puesto')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
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
                            <label for="num_empleados" class="form-label">Número de empleados</label>
                            <select id="num_empleados" name="num_empleados" class="form-select @error('num_empleados') is-invalid @enderror">
                                <option value="">Selecciona un rango</option>
                                @foreach (['1–10', '11–50', '51–200', 'Más de 200'] as $op)
                                <option value="{{ $op }}" @selected(old('num_empleados')===$op)>{{ $op }}</option>
                                @endforeach
                            </select>
                            @error('num_empleados')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label for="mensaje" class="form-label">Mensaje (opcional)</label>
                            <textarea id="mensaje" name="mensaje" rows="4"
                                class="form-control @error('mensaje') is-invalid @enderror"
                                placeholder="Cuéntanos qué necesita tu empresa…">{{ old('mensaje') }}</textarea>
                            @error('mensaje')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-jr-primary btn-lg">
                                <i class="fa-solid fa-paper-plane me-2"></i> Enviar solicitud
                            </button>
                            <p class="jr-text-muted small mt-2 mb-0">* Campos obligatorios. Te contactaremos con una propuesta.</p>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Información --}}
            <div class="col-lg-5">
                <div class="jr-surface p-4" style="border-radius:var(--jr-radius-lg);border:1px solid var(--jr-line)">
                    <h2 class="h4 mb-3">Convenios empresariales</h2>
                    <ul class="list-unstyled d-flex flex-column gap-3 jr-text-muted mb-4">
                        <li class="d-flex gap-3"><i class="fa-solid fa-users jr-text-gold mt-1"></i><div>Precios preferenciales para tu equipo.</div></li>
                        <li class="d-flex gap-3"><i class="fa-solid fa-calendar-check jr-text-gold mt-1"></i><div>Jornadas de examen de la vista en sitio.</div></li>
                        <li class="d-flex gap-3"><i class="fa-solid fa-file-invoice jr-text-gold mt-1"></i><div>Facturación y esquemas a la medida.</div></li>
                    </ul>
                    <hr class="jr-divider mb-4">
                    <p class="mb-2">¿Buscas atención personal?</p>
                    <a href="{{ route('contacto') }}" class="btn btn-jr-outline">Ir al contacto general</a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

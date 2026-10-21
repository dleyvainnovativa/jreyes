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

                <form action="{{ route('contacto.store') }}" method="POST" novalidate data-contact-form>
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

                        {{-- Preguntas adicionales --}}
                        <div class="col-12"><hr class="jr-divider my-1"></div>

                        <div class="col-md-6">
                            <label class="form-label d-block">¿Conoces tu graduación?</label>
                            <div class="d-flex gap-4 pt-1">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="conoce_graduacion" id="cg-si" value="1"
                                        {{ old('conoce_graduacion') === '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="cg-si">Sí</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="conoce_graduacion" id="cg-no" value="0"
                                        {{ old('conoce_graduacion', '0') === '0' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="cg-no">No</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="ultimo_examen" class="form-label">¿Hace cuánto hiciste tu examen de la vista?</label>
                            <input type="text" id="ultimo_examen" name="ultimo_examen" value="{{ old('ultimo_examen') }}"
                                class="form-control" placeholder="p. ej. hace 1 año">
                        </div>

                        <div class="col-md-6">
                            <label for="antiguedad_armazon" class="form-label">¿Hace cuánto tienes tu armazón actual?</label>
                            <input type="text" id="antiguedad_armazon" name="antiguedad_armazon" value="{{ old('antiguedad_armazon') }}"
                                class="form-control" placeholder="p. ej. 2 años">
                        </div>

                        {{-- Graduación: se muestra solo si respondió «Sí» --}}
                        <div class="col-12 jr-grad-contact" data-grad-contact @if (old('conoce_graduacion') !== '1') hidden @endif>
                            <div class="jr-surface p-3" style="border-radius:var(--jr-radius-lg);border:1px solid var(--jr-line)">
                                <p class="jr-eyebrow mb-3">Tu graduación <span class="jr-text-muted fw-normal text-lowercase">(opcional)</span></p>

                                <div class="jr-grad-eye">
                                    <p class="jr-grad-eye__title">Ojo derecho <span class="jr-text-muted fw-normal">(OD)</span></p>
                                    <div class="jr-grad-eye__fields">
                                        <label class="jr-grad-field"><span>Esfera</span><div class="jr-signed"><button type="button" class="jr-sign-btn" data-grad-sign tabindex="-1" aria-label="Cambiar signo (+/−)">±</button><input type="text" inputmode="decimal" name="grad[od_esfera]" value="{{ old('grad.od_esfera') }}" placeholder="0.00"></div></label>
                                        <label class="jr-grad-field"><span>Cilindro</span><div class="jr-signed"><button type="button" class="jr-sign-btn" data-grad-sign tabindex="-1" aria-label="Cambiar signo (+/−)">±</button><input type="text" inputmode="decimal" name="grad[od_cilindro]" value="{{ old('grad.od_cilindro') }}" placeholder="0.00"></div></label>
                                        <label class="jr-grad-field"><span>Eje</span><input type="text" inputmode="numeric" name="grad[od_eje]" value="{{ old('grad.od_eje') }}" placeholder="0–180"></label>
                                        <label class="jr-grad-field"><span>ADD</span><input type="text" inputmode="decimal" name="grad[od_add]" value="{{ old('grad.od_add') }}" placeholder="0.00"></label>
                                    </div>
                                </div>

                                <div class="jr-grad-eye">
                                    <p class="jr-grad-eye__title">Ojo izquierdo <span class="jr-text-muted fw-normal">(OI)</span></p>
                                    <div class="jr-grad-eye__fields">
                                        <label class="jr-grad-field"><span>Esfera</span><div class="jr-signed"><button type="button" class="jr-sign-btn" data-grad-sign tabindex="-1" aria-label="Cambiar signo (+/−)">±</button><input type="text" inputmode="decimal" name="grad[oi_esfera]" value="{{ old('grad.oi_esfera') }}" placeholder="0.00"></div></label>
                                        <label class="jr-grad-field"><span>Cilindro</span><div class="jr-signed"><button type="button" class="jr-sign-btn" data-grad-sign tabindex="-1" aria-label="Cambiar signo (+/−)">±</button><input type="text" inputmode="decimal" name="grad[oi_cilindro]" value="{{ old('grad.oi_cilindro') }}" placeholder="0.00"></div></label>
                                        <label class="jr-grad-field"><span>Eje</span><input type="text" inputmode="numeric" name="grad[oi_eje]" value="{{ old('grad.oi_eje') }}" placeholder="0–180"></label>
                                        <label class="jr-grad-field"><span>ADD</span><input type="text" inputmode="decimal" name="grad[oi_add]" value="{{ old('grad.oi_add') }}" placeholder="0.00"></label>
                                    </div>
                                </div>

                                <div class="jr-grad__dip mt-2">
                                    <label class="jr-grad-field jr-grad-field--inline">
                                        <span>DIP <span class="jr-text-muted fw-normal">(distancia interpupilar)</span></span>
                                        <input type="text" inputmode="decimal" name="grad[dip]" value="{{ old('grad.dip') }}" placeholder="mm">
                                    </label>
                                </div>
                            </div>
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
                        @if ($ajustes->ubicacion())
                        <li class="d-flex gap-3">
                            <i class="fa-solid fa-location-dot jr-text-gold mt-1"></i>
                            <div><strong>Dirección</strong><br><span class="jr-text-muted">{{ $ajustes->ubicacion() }}</span></div>
                        </li>
                        @endif
                        @if ($ajustes->telefono)
                        <li class="d-flex gap-3">
                            <i class="fa-solid fa-phone jr-text-gold mt-1"></i>
                            <div><strong>Teléfono</strong><br><span class="jr-text-muted">{{ $ajustes->telefono }}</span></div>
                        </li>
                        @endif
                        @if ($ajustes->whatsapp())
                        <li class="d-flex gap-3">
                            <i class="fa-brands fa-whatsapp jr-text-gold mt-1"></i>
                            <div><strong>WhatsApp</strong><br>
                                <a class="jr-text-muted" href="{{ $ajustes->whatsappLink() }}" target="_blank" rel="noopener">Escríbenos por WhatsApp</a>
                            </div>
                        </li>
                        @endif
                        @if ($ajustes->correo)
                        <li class="d-flex gap-3">
                            <i class="fa-solid fa-envelope jr-text-gold mt-1"></i>
                            <div><strong>Correo</strong><br><span class="jr-text-muted">{{ $ajustes->correo }}</span></div>
                        </li>
                        @endif
                    </ul>

                    <hr class="jr-divider mb-4">

                    <h3 class="h5 mb-3">Horario</h3>
                    <ul class="list-unstyled d-flex flex-column gap-2 jr-text-muted mb-4">
                        @forelse ($ajustes->horario() as $fila)
                        <li class="d-flex justify-content-between"><span>{{ $fila['etiqueta'] ?? '' }}</span><span>{{ $fila['horario'] ?? '' }}</span></li>
                        @empty
                        <li class="d-flex justify-content-between"><span>Lunes a viernes</span><span>9:00 – 19:00</span></li>
                        @endforelse
                    </ul>

                    <hr class="jr-divider mb-4">

                    <p class="mb-2"><i class="fa-solid fa-building jr-text-gold me-2"></i> ¿Representas a una empresa?</p>
                    <a href="{{ route('contacto.empresas') }}" class="btn btn-jr-outline">Contacto para empresas</a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
@extends('layouts.admin')

@section('title', 'Ajustes')

@section('content')
    <div class="jr-admin-head d-flex flex-wrap align-items-end justify-content-between gap-2">
        <div>
            <p class="jr-eyebrow mb-1">Panel · Ajustes</p>
            <h1 class="jr-admin-title mb-0">Ajustes del sitio</h1>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-jr-outline">
            <i class="fa-solid fa-arrow-left me-1" aria-hidden="true"></i> Panel
        </a>
    </div>

    @if (session('exito'))
        <div class="jr-admin-flash" role="status">
            <i class="fa-solid fa-circle-check me-1" aria-hidden="true"></i> {{ session('exito') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="jr-login__error mt-3" role="alert">
            <i class="fa-solid fa-circle-exclamation me-1" aria-hidden="true"></i> {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.ajustes.update') }}" novalidate class="mt-3">
        @csrf
        @method('PUT')

        {{-- ===== Contacto y ubicación ===== --}}
        <div class="jr-edit-card mb-4">
            <h2 class="h5 mb-3">Contacto y ubicación</h2>
            <div class="row g-3">
                <div class="col-12 col-lg-4">
                    <label for="whatsapp_number" class="form-label">WhatsApp</label>
                    <input type="text" id="whatsapp_number" name="whatsapp_number" class="form-control"
                        value="{{ old('whatsapp_number', $valores['whatsapp_number'] ?? '') }}"
                        placeholder="5212711234567">
                    <small class="jr-text-dim">Con código de país, sin «+» ni espacios.</small>
                </div>
                <div class="col-12 col-lg-4">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="text" id="telefono" name="telefono" class="form-control"
                        value="{{ old('telefono', $valores['telefono'] ?? '') }}" placeholder="(271) 000 0000">
                </div>
                <div class="col-12 col-lg-4">
                    <label for="correo" class="form-label">Correo</label>
                    <input type="email" id="correo" name="correo" class="form-control"
                        value="{{ old('correo', $valores['correo'] ?? '') }}" placeholder="hola@jreyesopticos.com">
                </div>
                <div class="col-12">
                    <label for="direccion" class="form-label">Dirección</label>
                    <input type="text" id="direccion" name="direccion" class="form-control"
                        value="{{ old('direccion', $valores['direccion'] ?? '') }}" placeholder="Calle y número, colonia">
                </div>
                <div class="col-6">
                    <label for="ciudad" class="form-label">Ciudad</label>
                    <input type="text" id="ciudad" name="ciudad" class="form-control"
                        value="{{ old('ciudad', $valores['ciudad'] ?? '') }}" placeholder="Córdoba">
                </div>
                <div class="col-6">
                    <label for="estado" class="form-label">Estado</label>
                    <input type="text" id="estado" name="estado" class="form-control"
                        value="{{ old('estado', $valores['estado'] ?? '') }}" placeholder="Veracruz">
                </div>
            </div>
        </div>

        {{-- ===== Horario ===== --}}
        <div class="jr-edit-card mb-4">
            <h2 class="h5 mb-1">Horario</h2>
            <p class="jr-text-dim small mb-3">Agrega renglones; deja uno vacío para quitarlo.</p>
            @php($filasHorario = array_merge($horario, [[], [], []]))
            <div class="d-flex flex-column gap-2">
                @foreach ($filasHorario as $i => $fila)
                    <div class="row g-2 align-items-center">
                        <div class="col-5 col-lg-4">
                            <input type="text" name="horario[{{ $i }}][etiqueta]" class="form-control"
                                value="{{ $fila['etiqueta'] ?? '' }}" placeholder="Lunes a viernes">
                        </div>
                        <div class="col-7 col-lg-4">
                            <input type="text" name="horario[{{ $i }}][horario]" class="form-control"
                                value="{{ $fila['horario'] ?? '' }}" placeholder="9:00 – 19:00 (o «Cerrado»)">
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ===== Redes sociales ===== --}}
        <div class="jr-edit-card mb-4">
            <h2 class="h5 mb-1">Redes sociales</h2>
            <p class="jr-text-dim small mb-3">Elige la plataforma (pone el ícono), su color y la URL. Marca «Eliminar» para quitarla. Las filas en blanco de abajo agregan nuevas.</p>

            <div class="d-flex flex-column gap-3">
                @foreach ($redes as $r)
                    @include('admin.ajustes._red', ['key' => $r->id, 'red' => $r, 'plataformas' => $plataformas])
                @endforeach

                {{-- Filas nuevas --}}
                @include('admin.ajustes._red', ['key' => 'new-1', 'red' => null, 'plataformas' => $plataformas])
                @include('admin.ajustes._red', ['key' => 'new-2', 'red' => null, 'plataformas' => $plataformas])
            </div>
        </div>

        <div class="jr-edit-actions">
            <button type="submit" class="btn btn-jr-primary">Guardar ajustes</button>
        </div>
    </form>
@endsection

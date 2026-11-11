@extends('layouts.admin')

@section('title', 'Nuevo nodo')

@section('content')
    <div class="jr-admin-head">
        <p class="jr-eyebrow mb-1">Panel · Catálogo · {{ $tipoNombre }}</p>
        <h1 class="jr-admin-title mb-0">Nuevo nodo</h1>
        <p class="jr-text-muted mb-0 mt-1">
            @if ($parent)
                Se creará dentro de <strong>{{ $parent->nombre }}</strong>.
            @else
                Se creará como <strong>nodo raíz</strong> de {{ $tipoNombre }}.
            @endif
            La imagen se agrega después, al editar el nodo.
        </p>
    </div>

    @if ($errors->any())
        <div class="jr-login__error" role="alert">
            <i class="fa-solid fa-circle-exclamation me-1" aria-hidden="true"></i>
            {{ $errors->first() }}
        </div>
    @endif

    <div class="jr-edit-card mt-3">
        <form method="POST" action="{{ route('admin.catalogo.store') }}" novalidate>
            @csrf
            {{-- El tipo lo fija la pestaña / el padre; no se cambia aquí. --}}
            <input type="hidden" name="tipo" value="{{ $tipo }}">

            <div class="row g-3">
                <div class="col-12 col-lg-8">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" id="nombre" name="nombre" class="form-control"
                        value="{{ old('nombre') }}" required autofocus>
                </div>

                <div class="col-12 col-lg-4">
                    <label for="kind" class="form-label">Tipo de nodo</label>
                    <select id="kind" name="kind" class="form-select" required>
                        @foreach ($kinds as $valor => $etiqueta)
                            <option value="{{ $valor }}"
                                {{ old('kind', $kindSugerido) === $valor ? 'selected' : '' }}>
                                {{ $etiqueta }}
                            </option>
                        @endforeach
                    </select>
                    <small class="jr-text-dim">Define la etiqueta del paso en el configurador.</small>
                </div>

                <div class="col-12 col-lg-4">
                    <label for="precio" class="form-label">Precio (MXN)</label>
                    <input type="number" id="precio" name="precio" class="form-control"
                        step="0.01" min="0" value="{{ old('precio', '0.00') }}" required>
                    <small class="jr-text-dim">0 = «Precio en tienda» (no suma en el configurador).</small>
                </div>

                <div class="col-12 col-lg-4">
                    <label for="orden" class="form-label">Orden</label>
                    <input type="number" id="orden" name="orden" class="form-control"
                        min="0" step="1" value="{{ old('orden', $ordenSugerido) }}" required>
                </div>

                <div class="col-12 col-lg-4">
                    <label for="slug" class="form-label">Slug (opcional)</label>
                    <input type="text" id="slug" name="slug" class="form-control"
                        value="{{ old('slug') }}" placeholder="se genera del nombre">
                    <small class="jr-text-dim">Déjalo vacío para autogenerarlo.</small>
                </div>

                <div class="col-12">
                    <label for="parent_id" class="form-label">Padre (ubicación en el árbol)</label>
                    <select id="parent_id" name="parent_id" class="form-select">
                        <option value="">(Raíz de {{ $tipoNombre }})</option>
                        @foreach ($opcionesPadre as $op)
                            <option value="{{ $op['id'] }}"
                                {{ (int) old('parent_id', $parent?->id) === $op['id'] ? 'selected' : '' }}>
                                {{ $op['label'] }}
                            </option>
                        @endforeach
                    </select>
                    <small class="jr-text-dim">El nodo hereda el tipo ({{ $tipoNombre }}) de su padre.</small>
                </div>

                <div class="col-12">
                    <label for="descripcion" class="form-label">Descripción (opcional)</label>
                    <input type="text" id="descripcion" name="descripcion" class="form-control"
                        maxlength="255" value="{{ old('descripcion') }}">
                </div>

                <div class="col-12 d-flex flex-wrap gap-4">
                    <div class="form-check">
                        <input type="checkbox" id="activo" name="activo" value="1" class="form-check-input"
                            {{ old('activo', true) ? 'checked' : '' }}>
                        <label for="activo" class="form-check-label">Visible en el sitio</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" id="es_extra" name="es_extra" value="1" class="form-check-input"
                            {{ old('es_extra') ? 'checked' : '' }}>
                        <label for="es_extra" class="form-check-label">Es un extra aditivo</label>
                    </div>
                </div>
            </div>

            <div class="jr-edit-actions mt-4">
                <a href="{{ route('admin.catalogo', ['tipo' => $tipo]) }}" class="btn btn-jr-outline">Cancelar</a>
                <button type="submit" class="btn btn-jr-primary">Crear nodo</button>
            </div>
        </form>
    </div>
@endsection

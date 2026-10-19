@extends('layouts.admin')

@section('title', 'Editar nodo')

@section('content')
    <div class="jr-admin-head">
        <p class="jr-eyebrow mb-1">Panel · Catálogo · {{ $tipoNombre }}</p>
        <h1 class="jr-admin-title mb-0">Editar: {{ $node->nombre }}</h1>
        <p class="jr-text-muted mb-0 mt-1">
            Tipo <strong>{{ $node->kind }}</strong>
            @if ($padre) · dentro de <strong>{{ $padre->nombre }}</strong> @else · nodo raíz @endif
            @if ($numHijos) · {{ $numHijos }} {{ $numHijos === 1 ? 'hijo' : 'hijos' }} @endif
        </p>
    </div>

    @if ($errors->any())
        <div class="jr-login__error" role="alert">
            <i class="fa-solid fa-circle-exclamation me-1" aria-hidden="true"></i>
            {{ $errors->first() }}
        </div>
    @endif

    <div class="jr-edit-card mt-3">
        <form method="POST" action="{{ route('admin.catalogo.update', $node) }}" enctype="multipart/form-data" novalidate>
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-12 col-lg-8">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" id="nombre" name="nombre" class="form-control"
                        value="{{ old('nombre', $node->nombre) }}" required>
                </div>

                <div class="col-12 col-lg-4">
                    <label for="precio" class="form-label">Precio (MXN)</label>
                    <input type="number" id="precio" name="precio" class="form-control"
                        step="0.01" min="0" value="{{ old('precio', number_format((float) $node->precio, 2, '.', '')) }}" required>
                    <small class="jr-text-dim">0 = «Precio en tienda» (no suma en el configurador).</small>
                </div>

                <div class="col-12 col-lg-8">
                    <label for="slug" class="form-label">Slug</label>
                    <input type="text" id="slug" name="slug" class="form-control"
                        value="{{ old('slug', $node->slug) }}" required>
                    <small class="jr-text-dim">Identificador único; se normaliza automáticamente.</small>
                </div>

                <div class="col-12 col-lg-4">
                    <label for="orden" class="form-label">Orden</label>
                    <input type="number" id="orden" name="orden" class="form-control"
                        min="0" step="1" value="{{ old('orden', $node->orden) }}" required>
                </div>

                <div class="col-12">
                    <label for="parent_id" class="form-label">Padre (ubicación en el árbol)</label>
                    <select id="parent_id" name="parent_id" class="form-select">
                        <option value="">(Raíz de {{ $tipoNombre }})</option>
                        @foreach ($opcionesPadre as $op)
                            <option value="{{ $op['id'] }}"
                                {{ (int) old('parent_id', $node->parent_id) === $op['id'] ? 'selected' : '' }}>
                                {{ $op['label'] }}
                            </option>
                        @endforeach
                    </select>
                    <small class="jr-text-dim">
                        Mueve el nodo (y su subárbol) a otro padre del mismo tipo.
                        No aparecen el nodo ni sus descendientes, para evitar ciclos.
                    </small>
                </div>

                <div class="col-12">
                    <label for="descripcion" class="form-label">Descripción (opcional)</label>
                    <input type="text" id="descripcion" name="descripcion" class="form-control"
                        maxlength="255" value="{{ old('descripcion', $node->descripcion) }}">
                </div>

                <div class="col-12 d-flex flex-wrap gap-4">
                    <div class="form-check">
                        <input type="checkbox" id="activo" name="activo" value="1" class="form-check-input"
                            {{ old('activo', $node->activo) ? 'checked' : '' }}>
                        <label for="activo" class="form-check-label">Visible en el sitio</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" id="es_extra" name="es_extra" value="1" class="form-check-input"
                            {{ old('es_extra', $node->es_extra) ? 'checked' : '' }}>
                        <label for="es_extra" class="form-check-label">Es un extra aditivo</label>
                    </div>
                </div>

                <div class="col-12">
                    <label for="imagen" class="form-label">Imagen (opcional)</label>

                    @if ($node->imagen)
                        <div class="jr-edit-img mb-2">
                            <img src="{{ asset($node->imagen) }}" alt="{{ $node->nombre }}">
                            <span class="jr-text-dim">{{ $node->imagen }}</span>
                        </div>
                    @else
                        <p class="jr-text-dim mb-2">Este nodo no tiene imagen.</p>
                    @endif

                    <input type="file" id="imagen" name="imagen" class="form-control"
                        accept="image/jpeg,image/png,image/webp">
                    <small class="jr-text-dim">
                        JPG, PNG o WebP · máx. 3 MB · se recomienda 16:9 · se guarda como
                        <code>img/catalog/{{ $node->slug }}.&lt;ext&gt;</code>
                    </small>

                    @if ($node->imagen)
                        <div class="form-check mt-2">
                            <input type="checkbox" id="quitar_imagen" name="quitar_imagen" value="1" class="form-check-input">
                            <label for="quitar_imagen" class="form-check-label">Quitar la imagen actual</label>
                        </div>
                    @endif
                </div>
            </div>

            <div class="jr-edit-actions mt-4">
                <a href="{{ route('admin.catalogo', ['tipo' => $node->tipo]) }}" class="btn btn-jr-outline">Cancelar</a>
                <button type="submit" class="btn btn-jr-primary">Guardar cambios</button>
            </div>
        </form>
    </div>
@endsection

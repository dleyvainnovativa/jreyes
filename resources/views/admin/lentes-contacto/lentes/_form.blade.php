{{-- Formulario de lente. Espera $brand, $lente, $tipos, $reemplazos. --}}
@php($editando = $lente->exists)

@if ($errors->any())
    <div class="jr-login__error mb-3" role="alert">
        <i class="fa-solid fa-circle-exclamation me-1" aria-hidden="true"></i> {{ $errors->first() }}
    </div>
@endif

<div class="jr-edit-card">
    <form method="POST"
        action="{{ $editando ? route('admin.lentes-contacto.lentes.update', [$brand, $lente]) : route('admin.lentes-contacto.lentes.store', $brand) }}"
        enctype="multipart/form-data" novalidate>
        @csrf
        @if ($editando) @method('PUT') @endif

        <div class="row g-3">
            <div class="col-12 col-lg-6">
                <label for="nombre" class="form-label">Nombre del lente</label>
                <input type="text" id="nombre" name="nombre" class="form-control"
                    value="{{ old('nombre', $lente->nombre) }}" required>
            </div>
            <div class="col-6 col-lg-3">
                <label for="precio" class="form-label">Precio (MXN)</label>
                <input type="number" id="precio" name="precio" class="form-control" step="0.01" min="0"
                    value="{{ old('precio', number_format((float) $lente->precio, 2, '.', '')) }}">
            </div>
            <div class="col-6 col-lg-3">
                <label for="orden" class="form-label">Orden</label>
                <input type="number" id="orden" name="orden" class="form-control" min="0" step="1"
                    value="{{ old('orden', $lente->orden) }}">
            </div>

            <div class="col-6 col-lg-4">
                <label for="tipo" class="form-label">Tipo</label>
                <select id="tipo" name="tipo" class="form-select">
                    <option value="">—</option>
                    @foreach ($tipos as $op)
                        <option value="{{ $op }}" {{ old('tipo', $lente->tipo) === $op ? 'selected' : '' }}>{{ $op }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-lg-4">
                <label for="reemplazo" class="form-label">Reemplazo</label>
                <select id="reemplazo" name="reemplazo" class="form-select">
                    <option value="">—</option>
                    @foreach ($reemplazos as $op)
                        <option value="{{ $op }}" {{ old('reemplazo', $lente->reemplazo) === $op ? 'selected' : '' }}>{{ $op }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-lg-4 d-flex align-items-end">
                <div class="form-check">
                    <input type="checkbox" id="activo" name="activo" value="1" class="form-check-input"
                        {{ old('activo', $lente->activo ?? true) ? 'checked' : '' }}>
                    <label for="activo" class="form-check-label">Visible en el sitio</label>
                </div>
            </div>

            <div class="col-12">
                <label for="imagen" class="form-label">Imagen (opcional)</label>
                @if ($editando && $lente->imagen)
                    <div class="jr-edit-img mb-2">
                        <img src="{{ asset($lente->imagen) }}" alt="{{ $lente->nombre }}">
                        <span class="jr-text-dim">{{ $lente->imagen }}</span>
                    </div>
                @endif
                <input type="file" id="imagen" name="imagen" class="form-control" accept="image/jpeg,image/png,image/webp">
                <small class="jr-text-dim">JPG, PNG o WebP · máx. 3 MB.</small>
                @if ($editando && $lente->imagen)
                    <div class="form-check mt-2">
                        <input type="checkbox" id="quitar_imagen" name="quitar_imagen" value="1" class="form-check-input">
                        <label for="quitar_imagen" class="form-check-label">Quitar la imagen actual</label>
                    </div>
                @endif
            </div>
        </div>

        <div class="jr-edit-actions mt-4">
            <a href="{{ route('admin.lentes-contacto.lentes', $brand) }}" class="btn btn-jr-outline">Cancelar</a>
            <button type="submit" class="btn btn-jr-primary">{{ $editando ? 'Guardar cambios' : 'Agregar lente' }}</button>
        </div>
    </form>
</div>

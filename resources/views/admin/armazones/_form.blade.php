{{-- Formulario compartido de armazón (alta/edición). Espera $frame. --}}
@php($editando = $frame->exists)

@if ($errors->any())
    <div class="jr-login__error mb-3" role="alert">
        <i class="fa-solid fa-circle-exclamation me-1" aria-hidden="true"></i>
        {{ $errors->first() }}
    </div>
@endif

<div class="jr-edit-card">
    <form method="POST"
        action="{{ $editando ? route('admin.armazones.update', $frame) : route('admin.armazones.store') }}"
        enctype="multipart/form-data" novalidate>
        @csrf
        @if ($editando) @method('PUT') @endif

        <div class="row g-3">
            <div class="col-6 col-lg-3">
                <label for="numero" class="form-label">Número</label>
                <input type="number" id="numero" name="numero" class="form-control" min="1" step="1"
                    value="{{ old('numero', $frame->numero) }}">
            </div>

            <div class="col-6 col-lg-3">
                <label for="orden" class="form-label">Orden</label>
                <input type="number" id="orden" name="orden" class="form-control" min="0" step="1"
                    value="{{ old('orden', $frame->orden) }}">
            </div>

            <div class="col-12 col-lg-6">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" id="nombre" name="nombre" class="form-control"
                    value="{{ old('nombre', $frame->nombre) }}" required>
            </div>

            <div class="col-12 col-lg-4">
                <label for="precio" class="form-label">Precio (MXN)</label>
                <input type="number" id="precio" name="precio" class="form-control" step="0.01" min="0"
                    value="{{ old('precio', number_format((float) $frame->precio, 2, '.', '')) }}">
                <small class="jr-text-dim">0 = incluido / sin costo extra.</small>
            </div>

            <div class="col-12">
                <label for="imagen" class="form-label">Imagen {{ $editando ? '(opcional al editar)' : '' }}</label>
                @if ($editando && $frame->imagen)
                    <div class="jr-edit-img mb-2">
                        <img src="{{ asset($frame->imagen) }}" alt="{{ $frame->nombre }}">
                        <span class="jr-text-dim">{{ $frame->imagen }}</span>
                    </div>
                @endif
                <input type="file" id="imagen" name="imagen" class="form-control"
                    accept="image/jpeg,image/png,image/webp" {{ $editando ? '' : 'required' }}>
                <small class="jr-text-dim">JPG, PNG o WebP · máx. 3 MB · se recomienda 16:9.</small>
            </div>

            <div class="col-12">
                <div class="form-check">
                    <input type="checkbox" id="activo" name="activo" value="1" class="form-check-input"
                        {{ old('activo', $frame->activo ?? true) ? 'checked' : '' }}>
                    <label for="activo" class="form-check-label">Visible en el sitio</label>
                </div>
            </div>
        </div>

        <div class="jr-edit-actions mt-4">
            <a href="{{ route('admin.armazones') }}" class="btn btn-jr-outline">Cancelar</a>
            <button type="submit" class="btn btn-jr-primary">
                {{ $editando ? 'Guardar cambios' : 'Agregar armazón' }}
            </button>
        </div>
    </form>
</div>

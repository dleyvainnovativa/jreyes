{{-- Formulario de marca de lentes de contacto. Espera $brand. --}}
@php($editando = $brand->exists)

@if ($errors->any())
    <div class="jr-login__error mb-3" role="alert">
        <i class="fa-solid fa-circle-exclamation me-1" aria-hidden="true"></i> {{ $errors->first() }}
    </div>
@endif

<div class="jr-edit-card">
    <form method="POST"
        action="{{ $editando ? route('admin.lentes-contacto.update', $brand) : route('admin.lentes-contacto.store') }}"
        novalidate>
        @csrf
        @if ($editando) @method('PUT') @endif

        <div class="row g-3">
            <div class="col-12 col-lg-6">
                <label for="nombre" class="form-label">Nombre de la marca</label>
                <input type="text" id="nombre" name="nombre" class="form-control"
                    value="{{ old('nombre', $brand->nombre) }}" required>
            </div>
            <div class="col-6 col-lg-4">
                <label for="slug" class="form-label">Slug (opcional)</label>
                <input type="text" id="slug" name="slug" class="form-control"
                    value="{{ old('slug', $brand->slug) }}" placeholder="se genera solo">
            </div>
            <div class="col-6 col-lg-2">
                <label for="orden" class="form-label">Orden</label>
                <input type="number" id="orden" name="orden" class="form-control" min="0" step="1"
                    value="{{ old('orden', $brand->orden) }}">
            </div>
        </div>

        <div class="jr-edit-actions mt-4">
            <a href="{{ route('admin.lentes-contacto') }}" class="btn btn-jr-outline">Cancelar</a>
            <button type="submit" class="btn btn-jr-primary">{{ $editando ? 'Guardar cambios' : 'Agregar marca' }}</button>
        </div>
    </form>
</div>

{{-- Formulario de paquete. Espera $paquete. --}}
@php($editando = $paquete->exists)

@if ($errors->any())
    <div class="jr-login__error mb-3" role="alert">
        <i class="fa-solid fa-circle-exclamation me-1" aria-hidden="true"></i> {{ $errors->first() }}
    </div>
@endif

<div class="jr-edit-card">
    <form method="POST"
        action="{{ $editando ? route('admin.paquetes.update', $paquete) : route('admin.paquetes.store') }}"
        novalidate>
        @csrf
        @if ($editando) @method('PUT') @endif

        <div class="row g-3">
            <div class="col-12 col-lg-5">
                <label for="nombre" class="form-label">Grupo</label>
                <input type="text" id="nombre" name="nombre" class="form-control" list="grupos"
                    value="{{ old('nombre', $paquete->nombre) }}" placeholder="Monofocal" required>
                <datalist id="grupos">
                    <option value="Monofocal"></option>
                    <option value="Bifocal"></option>
                    <option value="Progresivas"></option>
                </datalist>
                <small class="jr-text-dim">Encabezado bajo el que se agrupa en el sitio.</small>
            </div>
            <div class="col-12 col-lg-7">
                <label for="tratamiento" class="form-label">Tratamiento</label>
                <input type="text" id="tratamiento" name="tratamiento" class="form-control"
                    value="{{ old('tratamiento', $paquete->tratamiento) }}" placeholder="CR-39 Blanco / A.R. Básico / Blueray" required>
            </div>

            <div class="col-6 col-lg-3">
                <label for="precio" class="form-label">Precio (MXN)</label>
                <input type="number" id="precio" name="precio" class="form-control" step="0.01" min="0"
                    value="{{ old('precio', number_format((float) $paquete->precio, 2, '.', '')) }}">
            </div>
            <div class="col-6 col-lg-3">
                <label for="orden" class="form-label">Orden</label>
                <input type="number" id="orden" name="orden" class="form-control" min="0" step="1"
                    value="{{ old('orden', $paquete->orden) }}">
            </div>

            <div class="col-12">
                <label for="incluye" class="form-label">Incluye (opcional)</label>
                <textarea id="incluye" name="incluye" rows="2" class="form-control"
                    placeholder="Armazón + micas con tratamiento + estuche">{{ old('incluye', $paquete->incluye) }}</textarea>
            </div>

            <div class="col-12 d-flex flex-wrap gap-4">
                <div class="form-check">
                    <input type="checkbox" id="activo" name="activo" value="1" class="form-check-input"
                        {{ old('activo', $paquete->activo ?? true) ? 'checked' : '' }}>
                    <label for="activo" class="form-check-label">Visible en el sitio</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" id="destacado" name="destacado" value="1" class="form-check-input"
                        {{ old('destacado', $paquete->destacado) ? 'checked' : '' }}>
                    <label for="destacado" class="form-check-label">Destacado («Más pedido»)</label>
                </div>
            </div>
        </div>

        <div class="jr-edit-actions mt-4">
            <a href="{{ route('admin.paquetes') }}" class="btn btn-jr-outline">Cancelar</a>
            <button type="submit" class="btn btn-jr-primary">{{ $editando ? 'Guardar cambios' : 'Agregar paquete' }}</button>
        </div>
    </form>
</div>

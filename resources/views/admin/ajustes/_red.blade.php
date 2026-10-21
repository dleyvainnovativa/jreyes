{{-- Una fila de red social. Espera $key, $red (SocialLink|null), $plataformas. --}}
@php($p = $key)
<div class="jr-red-row">
    @if ($red)
        <input type="hidden" name="redes[{{ $p }}][id]" value="{{ $red->id }}">
    @endif

    <div class="row g-2 align-items-end">
        <div class="col-6 col-lg-3">
            <label class="form-label small mb-1">Plataforma</label>
            <select name="redes[{{ $p }}][plataforma]" class="form-select">
                @foreach ($plataformas as $slug => $info)
                    <option value="{{ $slug }}" {{ $red && $red->plataforma === $slug ? 'selected' : '' }}>{{ $info[0] }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-6 col-lg-4">
            <label class="form-label small mb-1">URL</label>
            <input type="text" name="redes[{{ $p }}][url]" class="form-control"
                value="{{ $red->url ?? '' }}" placeholder="https://...">
        </div>
        <div class="col-4 col-lg-2">
            <label class="form-label small mb-1">Color</label>
            <input type="color" name="redes[{{ $p }}][color]" class="form-control form-control-color"
                value="{{ $red->color ?? '#888888' }}">
        </div>
        <div class="col-4 col-lg-1">
            <label class="form-label small mb-1">Orden</label>
            <input type="number" name="redes[{{ $p }}][orden]" class="form-control" min="0" step="1"
                value="{{ $red->orden ?? 0 }}">
        </div>
        <div class="col-4 col-lg-2 d-flex flex-column gap-1 pt-lg-4">
            <div class="form-check m-0">
                <input type="checkbox" name="redes[{{ $p }}][activo]" value="1" class="form-check-input"
                    {{ ! $red || $red->activo ? 'checked' : '' }}>
                <label class="form-check-label small">Visible</label>
            </div>
            @if ($red)
                <div class="form-check m-0">
                    <input type="checkbox" name="redes[{{ $p }}][eliminar]" value="1" class="form-check-input">
                    <label class="form-check-label small jr-text-red">Eliminar</label>
                </div>
            @endif
        </div>
        <div class="col-12">
            <label class="form-label small mb-1">Ícono personalizado <span class="jr-text-dim">(solo si eliges «Otro»)</span></label>
            <input type="text" name="redes[{{ $p }}][icono]" class="form-control"
                value="{{ $red && $red->plataforma === 'otro' ? $red->icono : '' }}" placeholder="fa-brands fa-pinterest">
        </div>
    </div>
</div>

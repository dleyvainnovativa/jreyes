@extends('layouts.admin')

@section('title', 'Lentes · ' . $brand->nombre)

@section('content')
    <div class="jr-admin-head d-flex flex-wrap align-items-end justify-content-between gap-2">
        <div>
            <p class="jr-eyebrow mb-1">Panel · Lentes de contacto</p>
            <h1 class="jr-admin-title mb-0">{{ $brand->nombre }}</h1>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.lentes-contacto') }}" class="btn btn-sm btn-jr-outline">
                <i class="fa-solid fa-arrow-left me-1" aria-hidden="true"></i> Marcas
            </a>
            <a href="{{ route('admin.lentes-contacto.lentes.create', $brand) }}" class="btn btn-sm btn-jr-primary">
                <i class="fa-solid fa-plus me-1" aria-hidden="true"></i> Nuevo lente
            </a>
        </div>
    </div>

    @if (session('exito'))
        <div class="jr-admin-flash" role="status">
            <i class="fa-solid fa-circle-check me-1" aria-hidden="true"></i> {{ session('exito') }}
        </div>
    @endif

    <div class="jr-armazones-grid mt-3">
        @forelse ($lentes as $lente)
            <div class="jr-armazon-card {{ $lente->activo ? '' : 'is-hidden' }}">
                <div class="jr-armazon-card__media">
                    <img src="{{ asset($lente->imagen ?: 'img/logo-jreyes.png') }}" alt="{{ $lente->nombre }}" loading="lazy">
                </div>
                <div class="jr-armazon-card__body">
                    <strong>{{ $lente->nombre }}</strong>
                    <div class="jr-text-muted small mb-1">
                        {{ $lente->tipo ?: '—' }} · {{ $lente->reemplazo ?: '—' }}
                    </div>
                    <div class="jr-text-muted small mb-2">
                        @if ((float) $lente->precio > 0) ${{ number_format($lente->precio, 2) }} @else Sin precio @endif
                        @unless ($lente->activo) · <span class="jr-text-dim">Oculto</span> @endunless
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.lentes-contacto.lentes.edit', [$brand, $lente]) }}" class="btn btn-sm btn-jr-outline">
                            <i class="fa-solid fa-pen" aria-hidden="true"></i> Editar
                        </a>
                        <form method="POST" action="{{ route('admin.lentes-contacto.lentes.destroy', [$brand, $lente]) }}" class="m-0"
                            onsubmit="return confirm('¿Eliminar el lente «{{ $lente->nombre }}»?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-jr-outline jr-btn-danger">
                                <i class="fa-solid fa-trash" aria-hidden="true"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <p class="jr-text-muted">Esta marca aún no tiene lentes. Agrega el primero.</p>
        @endforelse
    </div>
@endsection

@extends('layouts.admin')

@section('title', 'Armazones')

@section('content')
    <div class="jr-admin-head d-flex flex-wrap align-items-end justify-content-between gap-2">
        <div>
            <p class="jr-eyebrow mb-1">Panel · Armazones</p>
            <h1 class="jr-admin-title mb-0">Armazones</h1>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-jr-outline">
                <i class="fa-solid fa-arrow-left me-1" aria-hidden="true"></i> Panel
            </a>
            <a href="{{ route('admin.armazones.create') }}" class="btn btn-sm btn-jr-primary">
                <i class="fa-solid fa-plus me-1" aria-hidden="true"></i> Nuevo armazón
            </a>
        </div>
    </div>

    @if (session('exito'))
        <div class="jr-admin-flash" role="status">
            <i class="fa-solid fa-circle-check me-1" aria-hidden="true"></i> {{ session('exito') }}
        </div>
    @endif

    <div class="jr-armazones-grid mt-3">
        @forelse ($frames as $frame)
            <div class="jr-armazon-card {{ $frame->activo ? '' : 'is-hidden' }}">
                <div class="jr-armazon-card__media">
                    <img src="{{ asset($frame->imagen ?: 'img/logo-jreyes.png') }}" alt="{{ $frame->nombre }}" loading="lazy">
                </div>
                <div class="jr-armazon-card__body">
                    <div class="d-flex align-items-center justify-content-between gap-2">
                        <strong>{{ $frame->nombre }}</strong>
                        <span class="jr-text-dim small">#{{ $frame->numero }}</span>
                    </div>
                    <div class="jr-text-muted small mb-2">
                        @if ((float) $frame->precio > 0)
                            ${{ number_format($frame->precio, 2) }}
                        @else
                            Incluido
                        @endif
                        @unless ($frame->activo) · <span class="jr-text-dim">Oculto</span> @endunless
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.armazones.edit', $frame) }}" class="btn btn-sm btn-jr-outline">
                            <i class="fa-solid fa-pen" aria-hidden="true"></i> Editar
                        </a>
                        <form method="POST" action="{{ route('admin.armazones.destroy', $frame) }}" class="m-0"
                            onsubmit="return confirm('¿Eliminar el armazón «{{ $frame->nombre }}»? Esta acción no se puede deshacer.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-jr-outline jr-btn-danger">
                                <i class="fa-solid fa-trash" aria-hidden="true"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <p class="jr-text-muted">Aún no hay armazones. Agrega el primero.</p>
        @endforelse
    </div>
@endsection

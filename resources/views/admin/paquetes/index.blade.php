@extends('layouts.admin')

@section('title', 'Paquetes')

@section('content')
    <div class="jr-admin-head d-flex flex-wrap align-items-end justify-content-between gap-2">
        <div>
            <p class="jr-eyebrow mb-1">Panel · Paquetes</p>
            <h1 class="jr-admin-title mb-0">Lentes completos</h1>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-jr-outline">
                <i class="fa-solid fa-arrow-left me-1" aria-hidden="true"></i> Panel
            </a>
            <a href="{{ route('admin.paquetes.create') }}" class="btn btn-sm btn-jr-primary">
                <i class="fa-solid fa-plus me-1" aria-hidden="true"></i> Nuevo paquete
            </a>
        </div>
    </div>

    @if (session('exito'))
        <div class="jr-admin-flash" role="status">
            <i class="fa-solid fa-circle-check me-1" aria-hidden="true"></i> {{ session('exito') }}
        </div>
    @endif

    @forelse ($packages as $nombre => $grupo)
        <h2 class="h5 mt-4 mb-2">{{ $nombre }} <span class="jr-text-dim small">({{ $grupo->count() }})</span></h2>
        <div class="jr-tree">
            @foreach ($grupo as $paquete)
                <div class="jr-tree__row {{ $paquete->activo ? '' : 'is-hidden' }}">
                    <div class="jr-tree__main">
                        <span class="jr-tree__name">{{ $paquete->tratamiento }}</span>
                        @if ($paquete->destacado)<span class="jr-tree__badge jr-tree__badge--extra">Destacado</span>@endif
                        @unless ($paquete->activo)<span class="jr-tree__badge jr-tree__badge--off">Oculto</span>@endunless
                    </div>
                    <div class="jr-tree__side">
                        <span class="jr-tree__price">${{ number_format($paquete->precio, 0) }}</span>
                        <a href="{{ route('admin.paquetes.edit', $paquete) }}" class="btn btn-sm btn-jr-outline">
                            <i class="fa-solid fa-pen" aria-hidden="true"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.paquetes.destroy', $paquete) }}" class="m-0"
                            onsubmit="return confirm('¿Eliminar el paquete «{{ $paquete->nombre }} · {{ $paquete->tratamiento }}»?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-jr-outline jr-btn-danger">
                                <i class="fa-solid fa-trash" aria-hidden="true"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @empty
        <p class="jr-text-muted mt-3">Aún no hay paquetes. Agrega el primero.</p>
    @endforelse
@endsection

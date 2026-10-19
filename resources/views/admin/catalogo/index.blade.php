@extends('layouts.admin')

@section('title', 'Catálogo')

@section('content')
    <div class="jr-admin-head d-flex flex-wrap align-items-end justify-content-between gap-2">
        <div>
            <p class="jr-eyebrow mb-1">Panel · Catálogo</p>
            <h1 class="jr-admin-title mb-0">Árbol de lentes</h1>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-jr-outline">
            <i class="fa-solid fa-arrow-left me-1" aria-hidden="true"></i> Panel
        </a>
    </div>

    @if (session('exito'))
        <div class="jr-admin-flash" role="status">
            <i class="fa-solid fa-circle-check me-1" aria-hidden="true"></i> {{ session('exito') }}
        </div>
    @endif

    {{-- Pestañas por tipo --}}
    <div class="jr-tree-tabs mt-3">
        @foreach ($tipos as $slug => $label)
            <a href="{{ route('admin.catalogo', ['tipo' => $slug]) }}"
                class="jr-tree-tab {{ $tipoActual === $slug ? 'is-active' : '' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="jr-tree mt-3">
        @forelse ($raices as $raiz)
            @include('admin.catalogo._node', ['node' => $raiz, 'depth' => 0])
        @empty
            <p class="jr-text-muted">No hay nodos para este tipo.</p>
        @endforelse
    </div>
@endsection

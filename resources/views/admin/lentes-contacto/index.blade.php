@extends('layouts.admin')

@section('title', 'Lentes de contacto')

@section('content')
    <div class="jr-admin-head d-flex flex-wrap align-items-end justify-content-between gap-2">
        <div>
            <p class="jr-eyebrow mb-1">Panel · Lentes de contacto</p>
            <h1 class="jr-admin-title mb-0">Marcas</h1>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-jr-outline">
                <i class="fa-solid fa-arrow-left me-1" aria-hidden="true"></i> Panel
            </a>
            <a href="{{ route('admin.lentes-contacto.create') }}" class="btn btn-sm btn-jr-primary">
                <i class="fa-solid fa-plus me-1" aria-hidden="true"></i> Nueva marca
            </a>
        </div>
    </div>

    @if (session('exito'))
        <div class="jr-admin-flash" role="status">
            <i class="fa-solid fa-circle-check me-1" aria-hidden="true"></i> {{ session('exito') }}
        </div>
    @endif

    <div class="jr-tree mt-3">
        @forelse ($brands as $brand)
            <div class="jr-tree__row">
                <div class="jr-tree__main">
                    <span class="jr-tree__name">{{ $brand->nombre }}</span>
                    <span class="jr-tree__kind">{{ $brand->lenses_count }} {{ $brand->lenses_count === 1 ? 'lente' : 'lentes' }}</span>
                </div>
                <div class="jr-tree__side">
                    <a href="{{ route('admin.lentes-contacto.lentes', $brand) }}" class="btn btn-sm btn-jr-primary">
                        <i class="fa-solid fa-eye" aria-hidden="true"></i> <span class="d-none d-sm-inline">Ver lentes</span>
                    </a>
                    <a href="{{ route('admin.lentes-contacto.edit', $brand) }}" class="btn btn-sm btn-jr-outline">
                        <i class="fa-solid fa-pen" aria-hidden="true"></i>
                    </a>
                    <form method="POST" action="{{ route('admin.lentes-contacto.destroy', $brand) }}" class="m-0"
                        onsubmit="return confirm('¿Eliminar la marca «{{ $brand->nombre }}» y TODOS sus lentes?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-jr-outline jr-btn-danger">
                            <i class="fa-solid fa-trash" aria-hidden="true"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <p class="jr-text-muted p-3 mb-0">Aún no hay marcas. Agrega la primera.</p>
        @endforelse
    </div>
@endsection

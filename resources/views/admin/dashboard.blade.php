@extends('layouts.admin')

@section('title', 'Panel')

@section('content')
    <div class="jr-admin-head">
        <p class="jr-eyebrow mb-1">Panel de administración</p>
        <h1 class="jr-admin-title">Catálogo</h1>
        <p class="jr-text-muted mb-0">Resumen rápido. El editor del árbol de lentes llega en el siguiente paso.</p>
    </div>

    <div class="row g-3 mt-1">
        <div class="col-6 col-lg-3">
            <div class="jr-stat">
                <div class="jr-stat__num">{{ $stats['nodos'] }}</div>
                <div class="jr-stat__label">Nodos en el árbol</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="jr-stat">
                <div class="jr-stat__num">{{ $stats['tratamientos'] }}</div>
                <div class="jr-stat__label">Tratamientos (hojas)</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="jr-stat">
                <div class="jr-stat__num jr-text-red">{{ $stats['sinPrecio'] }}</div>
                <div class="jr-stat__label">Sin precio</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="jr-stat">
                <div class="jr-stat__num">{{ $stats['raices'] }}</div>
                <div class="jr-stat__label">Tipos de lente</div>
            </div>
        </div>
    </div>

    <div class="mt-4 d-flex flex-wrap gap-2">
        <a href="{{ route('admin.catalogo') }}" class="btn btn-jr-primary">
            <i class="fa-solid fa-sitemap me-1" aria-hidden="true"></i> Editar árbol de lentes
        </a>
    </div>
@endsection

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
        <a href="{{ route('admin.armazones') }}" class="btn btn-jr-outline">
            <i class="fa-solid fa-glasses me-1" aria-hidden="true"></i> Administrar armazones
        </a>
        <a href="{{ route('admin.lentes-contacto') }}" class="btn btn-jr-outline">
            <i class="fa-solid fa-eye me-1" aria-hidden="true"></i> Lentes de contacto
        </a>
        <a href="{{ route('admin.paquetes') }}" class="btn btn-jr-outline">
            <i class="fa-solid fa-box me-1" aria-hidden="true"></i> Paquetes
        </a>
        <a href="{{ route('admin.ajustes') }}" class="btn btn-jr-outline">
            <i class="fa-solid fa-gear me-1" aria-hidden="true"></i> Ajustes del sitio
        </a>
    </div>
@endsection

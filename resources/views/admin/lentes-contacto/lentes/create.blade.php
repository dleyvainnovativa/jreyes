@extends('layouts.admin')

@section('title', 'Nuevo lente')

@section('content')
    <div class="jr-admin-head">
        <p class="jr-eyebrow mb-1">Panel · Lentes de contacto · {{ $brand->nombre }}</p>
        <h1 class="jr-admin-title mb-0">Nuevo lente</h1>
    </div>
    <div class="mt-3">@include('admin.lentes-contacto.lentes._form')</div>
@endsection

@extends('layouts.admin')

@section('title', 'Editar marca')

@section('content')
    <div class="jr-admin-head">
        <p class="jr-eyebrow mb-1">Panel · Lentes de contacto</p>
        <h1 class="jr-admin-title mb-0">Editar marca: {{ $brand->nombre }}</h1>
    </div>
    <div class="mt-3">@include('admin.lentes-contacto._form')</div>
@endsection

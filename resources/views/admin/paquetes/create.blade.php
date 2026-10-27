@extends('layouts.admin')

@section('title', 'Nuevo paquete')

@section('content')
    <div class="jr-admin-head">
        <p class="jr-eyebrow mb-1">Panel · Paquetes</p>
        <h1 class="jr-admin-title mb-0">Nuevo paquete</h1>
    </div>
    <div class="mt-3">@include('admin.paquetes._form')</div>
@endsection

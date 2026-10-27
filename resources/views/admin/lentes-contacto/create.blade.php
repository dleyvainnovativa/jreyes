@extends('layouts.admin')

@section('title', 'Nueva marca')

@section('content')
    <div class="jr-admin-head">
        <p class="jr-eyebrow mb-1">Panel · Lentes de contacto</p>
        <h1 class="jr-admin-title mb-0">Nueva marca</h1>
    </div>
    <div class="mt-3">@include('admin.lentes-contacto._form')</div>
@endsection

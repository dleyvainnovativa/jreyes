@extends('layouts.admin')

@section('title', 'Nuevo armazón')

@section('content')
    <div class="jr-admin-head">
        <p class="jr-eyebrow mb-1">Panel · Armazones</p>
        <h1 class="jr-admin-title mb-0">Nuevo armazón</h1>
    </div>

    <div class="mt-3">
        @include('admin.armazones._form')
    </div>
@endsection

@extends('layouts.admin')

@section('title', 'Acceso')

@section('content')
    <div class="jr-login">
        {{-- Cambio de tema (arriba a la derecha; no hay barra superior sin sesión) --}}
        <button type="button" class="jr-theme-toggle jr-login__theme" data-theme-toggle
            aria-pressed="false" aria-label="Cambiar a tema claro" title="Cambiar a tema claro">
            <i class="fa-solid fa-sun icon-sun" aria-hidden="true"></i>
            <i class="fa-solid fa-moon icon-moon" aria-hidden="true"></i>
        </button>

        <div class="jr-login__card">
            <div class="jr-login__head">
                <img src="{{ asset('img/logo-jreyes.png') }}" alt="JReyes Ópticos" class="jr-login__logo">
                <p class="jr-eyebrow mb-1">Panel de administración</p>
                <h1 class="jr-login__title">Inicia sesión</h1>
            </div>

            @if ($errors->any())
                <div class="jr-login__error" role="alert">
                    <i class="fa-solid fa-circle-exclamation me-1" aria-hidden="true"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.store') }}" novalidate>
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Correo</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                        class="form-control" autocomplete="username" required autofocus>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" id="password" name="password"
                        class="form-control" autocomplete="current-password" required>
                </div>

                <div class="form-check mb-4">
                    <input type="checkbox" id="remember" name="remember" value="1" class="form-check-input">
                    <label for="remember" class="form-check-label">Mantener sesión iniciada</label>
                </div>

                <button type="submit" class="btn btn-jr-primary w-100">Entrar</button>
            </form>
        </div>

        <a href="{{ route('home') }}" class="jr-login__back">
            <i class="fa-solid fa-arrow-left me-1" aria-hidden="true"></i> Volver al sitio
        </a>
    </div>
@endsection

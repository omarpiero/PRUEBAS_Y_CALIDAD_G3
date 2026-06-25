@extends('layouts.app')

@section('title', 'Iniciar sesión')

@push('styles')
<style>
    .login-page {
        min-height: calc(100vh - 76px);
        padding: 148px clamp(18px, 5vw, 76px) 78px;
        display: grid;
        place-items: start center;
        background:
            linear-gradient(100deg, rgba(234, 247, 255, 0.3), rgba(223, 243, 255, 0.16)),
            url("{{ asset('img/login.png') }}") center / cover no-repeat;
        font-family: "Poppins", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    }

    .login-card {
        width: min(480px, 100%);
        padding: 34px;
        border: 1px solid rgba(21, 101, 142, 0.16);
        border-radius: 8px;
        background: rgba(248, 252, 255, 0.94);
        box-shadow: 0 18px 46px rgba(7, 89, 133, 0.18);
        backdrop-filter: blur(4px);
    }

    .login-title {
        margin: 0 0 8px;
        color: #0b2538;
        font-size: 34px;
        font-weight: 600;
        line-height: 1.1;
    }

    .login-copy {
        margin: 0 0 28px;
        color: #587082;
        font-size: 15px;
        line-height: 1.65;
    }

    .login-field {
        margin-bottom: 17px;
    }

    .login-label {
        display: block;
        margin-bottom: 7px;
        color: #343833;
        font-size: 14px;
        font-weight: 800;
    }

    .login-control {
        width: 100%;
        min-height: 48px;
        padding: 13px 14px;
        border: 1px solid rgba(21, 101, 142, 0.16);
        border-radius: 8px;
        color: #0b2538;
        background: #fff;
        font-size: 14px;
        outline: none;
        transition: border-color 0.18s ease, box-shadow 0.18s ease;
    }

    .login-control:focus {
        border-color: #0284c7;
        box-shadow: 0 0 0 4px rgba(2, 132, 199, 0.12);
    }

    .login-control.is-invalid {
        border-color: #ef4444;
    }

    .login-error {
        display: block;
        margin-top: 6px;
        color: #ef4444;
        font-size: 13px;
        line-height: 1.2;
    }

    .login-options {
        margin: 4px 0 24px;
        color: #587082;
        font-size: 13px;
    }

    .login-check {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
    }

    .login-check input {
        width: 16px;
        height: 16px;
        accent-color: #0284c7;
    }

    .login-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }

    .login-button,
    .login-back {
        min-height: 48px;
        padding: 12px 20px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 800;
        line-height: 1;
        text-decoration: none;
    }

    .login-button {
        flex: 1 1 210px;
        border: 1px solid transparent;
        color: #fff;
        background: #0284c7;
        box-shadow: 0 16px 34px rgba(2, 132, 199, 0.2);
        transition: background-color 0.18s ease, transform 0.18s ease, box-shadow 0.18s ease;
    }

    .login-button:hover {
        color: #fff;
        background: #075985;
        transform: translateY(-2px);
        box-shadow: 0 20px 42px rgba(2, 132, 199, 0.24);
    }

    .login-back {
        flex: 0 1 140px;
        border: 1px solid rgba(2, 132, 199, 0.28);
        color: #075985;
        background: rgba(255, 255, 255, 0.58);
    }

    .login-back:hover {
        color: #075985;
        background: #dff3ff;
    }

    .login-status {
        margin-bottom: 18px;
        padding: 12px 14px;
        border: 1px solid rgba(2, 132, 199, 0.18);
        border-radius: 8px;
        color: #075985;
        background: #dff3ff;
        font-size: 14px;
    }

    @media (max-width: 700px) {
        .login-page {
            padding-top: 244px;
        }

        .login-card {
            padding: 26px;
        }

        .login-title {
            font-size: 28px;
        }

        .login-actions {
            align-items: stretch;
            flex-direction: column;
        }

        .login-button,
        .login-back {
            width: 100%;
            flex-basis: auto;
        }
    }
</style>
@endpush

@section('content')
<main class="login-page">
    <section class="login-card" aria-labelledby="login-title">
        <h1 class="login-title" id="login-title">Iniciar sesión</h1>
        <p class="login-copy">Ingresa con tu cuenta para acceder al panel o continuar navegando.</p>

        @if (session('status'))
            <div class="login-status">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('login.store') }}" novalidate>
            @csrf

            <div class="login-field">
                <label class="login-label" for="email">Correo electrónico</label>
                <input
                    class="login-control @error('email') is-invalid @enderror"
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    autocomplete="email"
                    required
                    autofocus>
                @error('email')
                    <span class="login-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="login-field">
                <label class="login-label" for="password">Contraseña</label>
                <input
                    class="login-control @error('password') is-invalid @enderror"
                    id="password"
                    type="password"
                    name="password"
                    autocomplete="current-password"
                    required>
                @error('password')
                    <span class="login-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="login-options">
                <label class="login-check">
                    <input type="checkbox" name="remember" value="1">
                    Recordarme
                </label>
            </div>

            <div class="login-actions">
                <button class="login-button" type="submit">Iniciar sesión</button>
                <a class="login-back" href="{{ route('inicio') }}">Volver</a>
            </div>
        </form>

        <div style="margin-top:22px;padding-top:18px;border-top:1px solid rgba(21,101,142,.12);text-align:center;font-size:13.5px;color:#587082;">
            ¿No tienes cuenta?
            <a href="{{ route('register') }}" style="color:#0284c7;font-weight:700;text-decoration:none;margin-left:4px;">
                Regístrate gratis →
            </a>
        </div>
    </section>
</main>
@endsection

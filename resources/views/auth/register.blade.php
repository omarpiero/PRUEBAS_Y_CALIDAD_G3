@extends('layouts.app')

@section('title', 'Crear cuenta')

@push('styles')
<style>
    .login-page {
        min-height: calc(100vh - 76px);
        padding: 100px clamp(18px, 5vw, 76px) 78px;
        display: grid;
        place-items: start center;
        background:
            linear-gradient(100deg, rgba(234, 247, 255, 0.3), rgba(223, 243, 255, 0.16)),
            url("{{ asset('img/login.png') }}") center / cover no-repeat;
        font-family: "Poppins", system-ui, sans-serif;
    }
    .login-card {
        width: min(520px, 100%);
        padding: 36px;
        border: 1px solid rgba(21, 101, 142, 0.16);
        border-radius: 12px;
        background: rgba(248, 252, 255, 0.96);
        box-shadow: 0 18px 46px rgba(7, 89, 133, 0.18);
        backdrop-filter: blur(4px);
    }
    .login-title { margin: 0 0 6px; color: #0b2538; font-size: 30px; font-weight: 700; line-height: 1.15; }
    .login-copy  { margin: 0 0 26px; color: #587082; font-size: 14px; line-height: 1.65; }

    .reg-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0 16px;
    }
    .reg-full { grid-column: span 2; }

    .login-field    { margin-bottom: 14px; }
    .login-label    { display: block; margin-bottom: 6px; color: #343833; font-size: 13px; font-weight: 700; }
    .login-control  {
        width: 100%; min-height: 46px; padding: 11px 14px;
        border: 1px solid rgba(21, 101, 142, 0.18); border-radius: 8px;
        color: #0b2538; background: #fff; font-size: 14px; font-family: inherit;
        outline: none; transition: border-color .18s, box-shadow .18s;
    }
    .login-control:focus      { border-color: #0284c7; box-shadow: 0 0 0 4px rgba(2,132,199,.12); }
    .login-control.is-invalid { border-color: #ef4444; }
    .login-error { display: block; margin-top: 5px; color: #ef4444; font-size: 12px; }

    .pass-hint { font-size: 11.5px; color: #94a3b8; margin-top: 4px; }

    .login-divider {
        display: flex; align-items: center; gap: 12px;
        margin: 20px 0 16px; color: #94a3b8; font-size: 12px;
    }
    .login-divider::before,
    .login-divider::after { content: ''; flex: 1; height: 1px; background: #e2e8f0; }

    .login-actions { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 20px; }
    .login-button {
        flex: 1; min-height: 48px; padding: 12px 20px;
        border: none; border-radius: 8px; cursor: pointer;
        font-size: 14px; font-weight: 700; font-family: inherit;
        color: #fff; background: #0284c7;
        box-shadow: 0 14px 30px rgba(2,132,199,.22);
        transition: background .18s, transform .15s;
    }
    .login-button:hover  { background: #075985; transform: translateY(-2px); }
    .login-button:disabled { opacity: .6; cursor: not-allowed; transform: none; }

    .login-back {
        min-height: 48px; padding: 12px 20px;
        border: 1px solid rgba(2,132,199,.28); border-radius: 8px;
        color: #075985; background: rgba(255,255,255,.6);
        font-size: 14px; font-weight: 700; text-decoration: none;
        display: inline-flex; align-items: center; transition: background .18s;
    }
    .login-back:hover { background: #dff3ff; }

    .reg-footer { margin-top: 20px; text-align: center; font-size: 13px; color: #587082; }
    .reg-footer a { color: #0284c7; font-weight: 600; text-decoration: none; }
    .reg-footer a:hover { text-decoration: underline; }

    @media (max-width: 560px) {
        .reg-grid { grid-template-columns: 1fr; }
        .reg-full  { grid-column: span 1; }
        .login-page { padding-top: 120px; }
    }
</style>
@endpush

@section('content')
<main class="login-page">
    <section class="login-card">
        <h1 class="login-title">Crear cuenta</h1>
        <p class="login-copy">Regístrate gratis y accede a todos los cursos de JM y JS Alimentos.</p>

        @if ($errors->any())
            <div style="background:#fee2e2;border:1px solid #fecaca;border-radius:8px;padding:12px 14px;margin-bottom:18px;color:#dc2626;font-size:13px;">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('register.store') }}" novalidate>
            @csrf

            <div class="reg-grid">
                {{-- Nombre --}}
                <div class="login-field reg-full">
                    <label class="login-label" for="name">Nombre completo</label>
                    <input class="login-control @error('name') is-invalid @enderror"
                           id="name" name="name" type="text"
                           placeholder="Ej. Giancarlo Guerreros Córdova"
                           value="{{ old('name') }}" required autofocus>
                    @error('name')<span class="login-error">{{ $message }}</span>@enderror
                </div>

                {{-- Email --}}
                <div class="login-field reg-full">
                    <label class="login-label" for="email">Correo electrónico</label>
                    <input class="login-control @error('email') is-invalid @enderror"
                           id="email" name="email" type="email"
                           placeholder="correo@empresa.com"
                           value="{{ old('email') }}" required>
                    @error('email')<span class="login-error">{{ $message }}</span>@enderror
                </div>

                {{-- DNI --}}
                <div class="login-field">
                    <label class="login-label" for="dni">DNI / RUC <span style="font-weight:400;color:#94a3b8">(opcional)</span></label>
                    <input class="login-control @error('dni') is-invalid @enderror"
                           id="dni" name="dni" type="text"
                           placeholder="71993692"
                           value="{{ old('dni') }}">
                    @error('dni')<span class="login-error">{{ $message }}</span>@enderror
                </div>

                {{-- Teléfono --}}
                <div class="login-field">
                    <label class="login-label" for="phone">Teléfono <span style="font-weight:400;color:#94a3b8">(opcional)</span></label>
                    <input class="login-control @error('phone') is-invalid @enderror"
                           id="phone" name="phone" type="tel"
                           placeholder="+51 987 654 321"
                           value="{{ old('phone') }}">
                    @error('phone')<span class="login-error">{{ $message }}</span>@enderror
                </div>

                {{-- Contraseña --}}
                <div class="login-field">
                    <label class="login-label" for="password">Contraseña</label>
                    <input class="login-control @error('password') is-invalid @enderror"
                           id="password" name="password" type="password"
                           placeholder="Mínimo 8 caracteres" required>
                    @error('password')<span class="login-error">{{ $message }}</span>@enderror
                </div>

                {{-- Confirmar --}}
                <div class="login-field">
                    <label class="login-label" for="password_confirmation">Confirmar contraseña</label>
                    <input class="login-control"
                           id="password_confirmation" name="password_confirmation"
                           type="password" placeholder="Repite tu contraseña" required>
                </div>
            </div>

            <div class="login-actions">
                <button class="login-button" type="submit">Crear mi cuenta</button>
                <a class="login-back" href="{{ route('login') }}">Ya tengo cuenta</a>
            </div>
        </form>

        <p class="reg-footer">
            Al registrarte aceptas nuestros
            <a href="{{ route('inicio') }}">términos de uso</a>.
        </p>
    </section>
</main>
@endsection

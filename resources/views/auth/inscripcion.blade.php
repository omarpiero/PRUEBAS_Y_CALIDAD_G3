@extends('layouts.app')

@section('title', 'Formulario de Inscripción')

@push('styles')
<style>
    .signup-page {
        min-height: calc(100vh - 76px);
        padding: 148px clamp(18px, 5vw, 76px) 78px;
        display: grid;
        place-items: start center;
        background:
            linear-gradient(100deg, rgba(234, 247, 255, 0.28), rgba(223, 243, 255, 0.14)),
            url("{{ asset('img/login.png') }}") center / cover no-repeat;
        font-family: "Poppins", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    }

    .signup-card {
        width: min(520px, 100%);
        padding: 34px;
        border: 1px solid rgba(21, 101, 142, 0.16);
        border-radius: 8px;
        background: rgba(248, 252, 255, 0.94);
        box-shadow: 0 18px 46px rgba(7, 89, 133, 0.18);
        backdrop-filter: blur(4px);
    }

    .signup-title {
        margin: 0 0 8px;
        color: #0b2538;
        font-size: 34px;
        font-weight: 600;
        line-height: 1.1;
    }

    .signup-copy {
        margin: 0 0 28px;
        color: #587082;
        font-size: 15px;
        line-height: 1.65;
    }

    .signup-field {
        margin-bottom: 17px;
    }

    .signup-label {
        display: block;
        margin-bottom: 7px;
        color: #343833;
        font-size: 14px;
        font-weight: 800;
    }

    .signup-control {
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

    .signup-control::placeholder {
        color: #6b7280;
    }

    .signup-control:focus {
        border-color: #0284c7;
        box-shadow: 0 0 0 4px rgba(2, 132, 199, 0.12);
    }

    .signup-control.is-invalid {
        padding-right: 38px;
        border-color: #ef4444;
    }

    .signup-input-wrap {
        position: relative;
    }

    .signup-error-icon {
        position: absolute;
        top: 50%;
        right: 12px;
        width: 18px;
        height: 18px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #ef4444;
        border-radius: 50%;
        color: #ef4444;
        font-size: 12px;
        font-weight: 600;
        line-height: 1;
        transform: translateY(-50%);
    }

    .signup-error {
        display: block;
        margin-top: 6px;
        color: #ef4444;
        font-size: 13px;
        line-height: 1.2;
    }

    .signup-actions {
        margin-top: 26px;
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }

    .signup-button,
    .signup-back {
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

    .signup-button {
        flex: 1 1 210px;
        border: 1px solid transparent;
        color: #fff;
        background: #0284c7;
        box-shadow: 0 16px 34px rgba(2, 132, 199, 0.2);
        transition: background-color 0.18s ease, transform 0.18s ease, box-shadow 0.18s ease;
    }

    .signup-button:hover {
        color: #fff;
        background: #075985;
        transform: translateY(-2px);
        box-shadow: 0 20px 42px rgba(2, 132, 199, 0.24);
    }

    .signup-back {
        flex: 0 1 140px;
        border: 1px solid rgba(2, 132, 199, 0.28);
        color: #075985;
        background: rgba(255, 255, 255, 0.58);
    }

    .signup-back:hover {
        color: #075985;
        background: #dff3ff;
    }

    .signup-status {
        margin-bottom: 18px;
        padding: 12px 14px;
        border: 1px solid rgba(2, 132, 199, 0.18);
        border-radius: 8px;
        color: #075985;
        background: #dff3ff;
        font-size: 14px;
    }

    @media (max-width: 700px) {
        .signup-page {
            padding-top: 244px;
        }

        .signup-card {
            padding: 26px;
        }

        .signup-title {
            font-size: 28px;
        }

        .signup-actions {
            flex-direction: column;
        }

        .signup-button,
        .signup-back {
            width: 100%;
            flex-basis: auto;
        }
    }
</style>
@endpush

@section('content')
<main class="signup-page">
    <section class="signup-card" aria-labelledby="signup-title">
        <h1 class="signup-title" id="signup-title">Formulario de Inscripción</h1>
        <p class="signup-copy">Complete los datos para asegurar su cupo.</p>

        @if (session('status'))
            <div class="signup-status">{{ session('status') }}</div>
        @endif

        <form method="GET" action="{{ route('checkout') }}" novalidate>
            <input type="hidden" name="password" value="password">

            <div class="signup-field">
                <label class="signup-label" for="name">Nombre Completo</label>
                <input
                    class="signup-control"
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name', 'Carlos Ruiz') }}"
                    autocomplete="name">
            </div>

            <div class="signup-field">
                <label class="signup-label" for="email">Correo Electrónico</label>
                <div class="signup-input-wrap">
                    <input
                        class="signup-control {{ $errors->has('email') || old('email') === null ? 'is-invalid' : '' }}"
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email', 'carlos.ruiz@') }}"
                        autocomplete="email"
                        required>
                    @if ($errors->has('email') || old('email') === null)
                        <span class="signup-error-icon" aria-hidden="true">!</span>
                    @endif
                </div>
                @if ($errors->has('email') || old('email') === null)
                    <span class="signup-error">Campo obligatorio</span>
                @endif
            </div>

            <div class="signup-field">
                <label class="signup-label" for="document">DNI/RUC</label>
                <input
                    class="signup-control"
                    id="document"
                    type="text"
                    name="document"
                    value="{{ old('document') }}"
                    placeholder="Ingrese su documento"
                    inputmode="numeric"
                    autocomplete="off">
            </div>

            <div class="signup-actions">
                <button class="signup-button" type="submit">Continuar al Pago</button>
                <a class="signup-back" href="{{ route('inicio') }}">Volver</a>
            </div>
        </form>
    </section>
</main>
@endsection

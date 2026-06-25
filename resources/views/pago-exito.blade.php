@extends('layouts.app')

@section('title', 'Pago Exitoso')

@section('content')
<div style="
    min-height: calc(100vh - 76px);
    display: flex; align-items: center; justify-content: center;
    padding: 40px 24px;
    background: linear-gradient(135deg, #eff6ff, #f0f9ff);
    font-family: 'Poppins', sans-serif;
">
    <div style="
        max-width: 520px; width: 100%;
        background: #fff;
        border: 1px solid #bfdbfe;
        border-radius: 20px;
        padding: 48px 40px;
        text-align: center;
        box-shadow: 0 20px 60px rgba(2,132,199,.12);
    ">
        {{-- Ícono éxito --}}
        <div style="
            width: 80px; height: 80px; border-radius: 50%;
            background: linear-gradient(135deg, #22c55e, #16a34a);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 24px; box-shadow: 0 12px 30px rgba(34,197,94,.3);
        ">
            <svg width="38" height="38" fill="none" stroke="white" stroke-width="2.5" viewBox="0 0 24 24">
                <polyline points="20 6 9 17 4 12"/>
            </svg>
        </div>

        <h1 style="font-size: 26px; font-weight: 800; color: #0f172a; margin-bottom: 10px;">
            ¡Pago realizado con éxito!
        </h1>
        <p style="color: #64748b; font-size: 15px; line-height: 1.7; margin-bottom: 8px;">
            @if (session('paid_count'))
                {{ session('paid_count') }} {{ session('paid_count') == 1 ? 'curso ha sido' : 'cursos han sido' }} agregados a tu cuenta.
            @else
                Tus cursos han sido agregados a tu cuenta.
            @endif
        </p>
        <p style="color: #94a3b8; font-size: 13px; margin-bottom: 36px;">
            Recibirás un correo de confirmación con los detalles de tu inscripción.
        </p>

        <div style="display:flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
            <a href="{{ route('mi-cuenta') }}" style="
                display: inline-flex; align-items: center; gap: 6px;
                background: #1e40af; color: #fff;
                padding: 13px 26px; border-radius: 10px;
                font-size: 14px; font-weight: 700; text-decoration: none;
                transition: background .18s;
            ">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                </svg>
                Ver Mi Cuenta
            </a>
            <a href="{{ route('cursos') }}" style="
                display: inline-flex; align-items: center; gap: 6px;
                background: #eff6ff; color: #1e40af;
                border: 1.5px solid #bfdbfe;
                padding: 13px 26px; border-radius: 10px;
                font-size: 14px; font-weight: 700; text-decoration: none;
            ">
                Explorar más cursos
            </a>
        </div>
    </div>
</div>
@endsection

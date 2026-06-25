@extends('layouts.admin')

@section('title', 'Gestionar Cupones')

@section('content')
<div class="page-header" style="margin-bottom:20px;display:flex;justify-content:space-between;align-items:center;">
    <div>
        <h1>Gesti&oacute;n de Cupones</h1>
        <p>Crea, edita, desactiva y administra cupones de descuento para el checkout de la plataforma.</p>
    </div>
    <div>
        <a href="{{ route('admin.coupons.create') }}" class="btn-primary" style="text-decoration:none;">
            + Crear Nuevo Cup&oacute;n
        </a>
    </div>
</div>

@if (session('success'))
    <div style="background:#dcfce7;border:1px solid #bbf7d0;border-radius:8px;padding:12px 16px;color:#15803d;font-size:13px;margin-bottom:20px;">
        ✓ {{ session('success') }}
    </div>
@endif

{{-- Coupons Table --}}
<div class="card" style="padding:0;">
    <div class="card-body" style="padding:0;">
        @if ($coupons->isEmpty())
            <div style="padding:40px;text-align:center;color:var(--slate-400);">No hay cupones creados aún.</div>
        @else
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>C&oacute;digo</th>
                        <th>Tipo Descuento</th>
                        <th>Valor</th>
                        <th>Vigencia</th>
                        <th>L&iacute;mite de Uso</th>
                        <th>Veces Usado</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($coupons as $coupon)
                        <tr>
                            <td><strong style="color:#fff;font-size:15px;font-family:monospace;letter-spacing:1px;">{{ $coupon->code }}</strong></td>
                            <td>{{ $coupon->type === 'porcentaje' ? 'Porcentaje (%)' : 'Monto Fijo (S/)' }}</td>
                            <td>
                                <strong>
                                    {{ $coupon->type === 'porcentaje' ? (int)$coupon->value . '%' : 'S/ ' . number_format($coupon->value, 2) }}
                                </strong>
                            </td>
                            <td style="font-size:12.5px;">
                                <div><span style="color:var(--slate-400);">Desde:</span> {{ $coupon->start_date->format('d/m/Y') }}</div>
                                <div style="margin-top:2px;"><span style="color:var(--slate-400);">Hasta:</span> {{ $coupon->end_date->format('d/m/Y') }}</div>
                            </td>
                            <td>{{ $coupon->usage_limit ?? 'Ilimitado' }}</td>
                            <td>{{ $coupon->times_used }}</td>
                            <td>
                                @if ($coupon->is_valid)
                                    <span style="font-size:10px;padding:2px 8px;border-radius:10px;background:#22c55e;color:#fff;font-weight:600;">VÁLIDO</span>
                                @else
                                    <span style="font-size:10px;padding:2px 8px;border-radius:10px;background:#ef4444;color:#fff;font-weight:600;">INACTIVO / EXPIRADO</span>
                                @endif
                            </td>
                            <td>
                                <div style="display:flex;gap:8px;">
                                    <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn-primary" style="padding:4px 8px;font-size:11.5px;text-decoration:none;">
                                        Editar
                                    </a>
                                    
                                    <form method="POST" action="{{ route('admin.coupons.destroy', $coupon) }}" style="display:inline;" onsubmit="return confirm('¿Seguro de eliminar este cupón?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="background:none;border:1px solid var(--danger);padding:4px 8px;border-radius:6px;cursor:pointer;color:var(--danger);font-size:11.5px;">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div style="padding:20px;">
                {{ $coupons->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

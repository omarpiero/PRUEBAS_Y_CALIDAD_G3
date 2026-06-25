@extends('layouts.admin')

@section('title', 'Detalle de Factura #' . $sale->id)

@section('content')
<div style="margin-bottom:20px;">
    <a href="{{ route('admin.sales.index') }}" style="color:#38bdf8;text-decoration:none;font-size:13px;font-weight:600;display:inline-flex;align-items:center;gap:6px;">
        ← Volver a Ventas
    </a>
</div>

<div class="page-header" style="margin-bottom:24px;">
    <div>
        <h1>Factura #{{ $sale->id }}</h1>
        <p>Detalle de la transacci&oacute;n y cursos comprados.</p>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 320px;gap:24px;align-items:start;">
    {{-- Invoice breakdown --}}
    <div style="display:flex;flex-direction:column;gap:24px;">
        <div class="card" style="padding:0;">
            <div class="card-head" style="padding:18px 24px;border-bottom:1px solid rgba(255,255,255,0.08);">
                <h3 style="color:#fff;font-size:16px;">Ítems de Compra</h3>
            </div>
            <div class="card-body" style="padding:0;">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Curso</th>
                            <th style="text-align:right;">Precio</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sale->items as $item)
                            <tr>
                                <td>
                                    <strong style="color:#fff;">{{ $item->course->name ?? 'Curso eliminado' }}</strong>
                                    <div style="font-size:11px;color:var(--slate-400);margin-top:2px;">Nivel: {{ ucfirst($item->course->level ?? '—') }}</div>
                                </td>
                                <td style="text-align:right;font-weight:600;">S/ {{ number_format($item->price, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
                {{-- Totals breakdown --}}
                <div style="padding:24px;background:rgba(255,255,255,0.01);border-top:1px solid rgba(255,255,255,0.06);display:flex;flex-direction:column;align-items:flex-end;gap:8px;font-size:13.5px;">
                    <div style="width:280px;display:flex;justify-content:space-between;color:var(--slate-400);">
                        <span>Subtotal</span>
                        <span>S/ {{ number_format($sale->subtotal, 2) }}</span>
                    </div>
                    @if ($sale->discount > 0)
                        <div style="width:280px;display:flex;justify-content:space-between;color:#22c55e;">
                            <span>Descuento ({{ $sale->coupon->code ?? 'Cupón' }})</span>
                            <span>-S/ {{ number_format($sale->discount, 2) }}</span>
                        </div>
                    @endif
                    <div style="width:280px;height:1px;background:rgba(255,255,255,0.08);margin:4px 0;"></div>
                    <div style="width:280px;display:flex;justify-content:space-between;font-size:18px;font-weight:800;color:#fff;">
                        <span>Total Pagado</span>
                        <span>S/ {{ number_format($sale->total, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Details sidebar --}}
    <div style="display:flex;flex-direction:column;gap:24px;">
        
        {{-- Client info --}}
        <div class="card" style="padding:20px;">
            <h3 style="margin-bottom:14px;color:#fff;font-size:14px;border-bottom:1px solid rgba(255,255,255,0.08);padding-bottom:8px;">Cliente</h3>
            <div style="display:flex;flex-direction:column;gap:8px;font-size:12.5px;">
                <div><strong>{{ $sale->user->name }}</strong></div>
                <div style="color:var(--slate-400);">{{ $sale->user->email }}</div>
                <div>DNI: {{ $sale->user->dni ?? 'No registrado' }}</div>
            </div>
        </div>

        {{-- Payment info --}}
        <div class="card" style="padding:20px;">
            <h3 style="margin-bottom:14px;color:#fff;font-size:14px;border-bottom:1px solid rgba(255,255,255,0.08);padding-bottom:8px;">Datos de Transacción</h3>
            <div style="display:flex;flex-direction:column;gap:10px;font-size:12.5px;">
                <div>
                    <span style="color:var(--slate-400);display:block;font-size:10px;text-transform:uppercase;font-weight:700;">Método de Pago</span>
                    <span style="font-weight:600;color:#fff;">{{ ucfirst($sale->payment_method) }}</span>
                </div>
                <div>
                    <span style="color:var(--slate-400);display:block;font-size:10px;text-transform:uppercase;font-weight:700;">Estado del Pago</span>
                    <span style="font-weight:600;color:#fff;">{{ ucfirst($sale->payment_status) }}</span>
                </div>
                <div>
                    <span style="color:var(--slate-400);display:block;font-size:10px;text-transform:uppercase;font-weight:700;">Fecha de Pago</span>
                    <span>{{ $sale->paid_at ? $sale->paid_at->format('d/m/Y H:i') : '—' }}</span>
                </div>
                @if ($sale->stripe_payment_id)
                    <div>
                        <span style="color:var(--slate-400);display:block;font-size:10px;text-transform:uppercase;font-weight:700;">Stripe Payment ID</span>
                        <span style="font-family:monospace;font-size:11.5px;">{{ $sale->stripe_payment_id }}</span>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection

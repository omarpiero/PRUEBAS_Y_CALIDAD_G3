@extends('layouts.admin')

@section('title', 'Gestionar Ventas')

@section('content')
<div class="page-header" style="margin-bottom:20px;">
    <div>
        <h1>Historial de Ventas</h1>
        <p>Consulta las transacciones de compra de cursos, cupones aplicados e ingresos totales de la plataforma.</p>
    </div>
</div>

{{-- Filters --}}
<div class="card" style="margin-bottom:20px;">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.sales.index') }}" style="display:flex;gap:16px;align-items:flex-end;flex-wrap:wrap;">
            <div style="flex:1;min-width:240px;">
                <label for="search" style="font-size:11.5px;margin-bottom:4px;display:block;">B&uacute;squeda</label>
                <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="ID de venta o nombre/email de comprador..." style="padding:8px 12px;font-size:13px;width:100%;">
            </div>
            
            <div style="width:180px;">
                <label for="status" style="font-size:11.5px;margin-bottom:4px;display:block;">Estado de Pago</label>
                <select id="status" name="status" style="padding:8px 12px;font-size:13px;width:100%;">
                    <option value="">Todos</option>
                    <option value="pendiente" {{ request('status') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="pagado" {{ request('status') == 'pagado' ? 'selected' : '' }}>Pagado</option>
                    <option value="fallido" {{ request('status') == 'fallido' ? 'selected' : '' }}>Fallido</option>
                    <option value="reembolsado" {{ request('status') == 'reembolsado' ? 'selected' : '' }}>Reembolsado</option>
                </select>
            </div>

            <button type="submit" class="btn-primary" style="padding:10px 20px;">Filtrar</button>
            <a href="{{ route('admin.sales.index') }}" class="btn-secondary" style="padding:10px 20px;text-decoration:none;">Limpiar</a>
        </form>
    </div>
</div>

{{-- Sales Table --}}
<div class="card" style="padding:0;">
    <div class="card-body" style="padding:0;">
        @if ($sales->isEmpty())
            <div style="padding:40px;text-align:center;color:var(--slate-400);">No se encontraron ventas registradas.</div>
        @else
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Venta</th>
                        <th>Comprador</th>
                        <th>Subtotal</th>
                        <th>Descuento</th>
                        <th>Total</th>
                        <th>Fecha / Hora</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sales as $sale)
                        <tr>
                            <td><strong>#{{ $sale->id }}</strong></td>
                            <td>
                                <strong style="color:#fff;">{{ $sale->user->name }}</strong>
                                <div style="font-size:11px;color:var(--slate-400);margin-top:2px;">{{ $sale->user->email }}</div>
                            </td>
                            <td>S/ {{ number_format($sale->subtotal, 2) }}</td>
                            <td style="color:#22c55e;">
                                @if ($sale->discount > 0)
                                    -S/ {{ number_format($sale->discount, 2) }}
                                    @if ($sale->coupon)
                                        <div style="font-size:10px;color:#38bdf8;margin-top:2px;">Cupón: {{ $sale->coupon->code }}</div>
                                    @endif
                                @else
                                    —
                                @endif
                            </td>
                            <td><strong>S/ {{ number_format($sale->total, 2) }}</strong></td>
                            <td>{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <span style="font-size:10px;padding:2px 8px;border-radius:10px;background:rgba(255,255,255,0.06);">
                                    {{ ucfirst($sale->payment_status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.sales.show', $sale) }}" class="btn-primary" style="padding:6px 12px;font-size:12px;text-decoration:none;">
                                    Detalle Factura
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div style="padding:20px;">
                {{ $sales->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

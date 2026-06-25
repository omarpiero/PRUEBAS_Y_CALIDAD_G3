@extends('layouts.admin')

@section('title', 'Perfil del Estudiante: ' . $student->name)

@section('content')
<div style="margin-bottom:20px;">
    <a href="{{ route('admin.students.index') }}" style="color:#38bdf8;text-decoration:none;font-size:13px;font-weight:600;display:inline-flex;align-items:center;gap:6px;">
        ← Volver a Estudiantes
    </a>
</div>

<div class="page-header" style="margin-bottom:24px;">
    <div>
        <h1>Detalle del Alumno</h1>
        <p>Gesti&oacute;n acad&eacute;mica e historial de compras de <strong>{{ $student->name }}</strong>.</p>
    </div>
</div>

@if (session('success'))
    <div style="background:#dcfce7;border:1px solid #bbf7d0;border-radius:8px;padding:12px 16px;color:#15803d;font-size:13px;margin-bottom:20px;">
        ✓ {{ session('success') }}
    </div>
@endif

<div style="display:grid;grid-template-columns:300px 1fr;gap:24px;align-items:start;">
    {{-- Sidebar: Info --}}
    <div class="card" style="padding:24px;">
        <h3 style="margin-bottom:18px;color:#fff;font-size:15px;border-bottom:1px solid rgba(255,255,255,0.08);padding-bottom:8px;">Datos Personales</h3>
        
        <div style="display:flex;flex-direction:column;gap:12px;font-size:13px;">
            <div>
                <span style="color:var(--slate-400);display:block;font-size:11px;text-transform:uppercase;font-weight:700;">Nombre Completo</span>
                <span style="color:#fff;font-weight:600;">{{ $student->name }}</span>
            </div>
            <div>
                <span style="color:var(--slate-400);display:block;font-size:11px;text-transform:uppercase;font-weight:700;">Correo Electrónico</span>
                <span>{{ $student->email }}</span>
            </div>
            <div>
                <span style="color:var(--slate-400);display:block;font-size:11px;text-transform:uppercase;font-weight:700;">DNI / RUC</span>
                <span>{{ $student->dni ?? 'No registrado' }}</span>
            </div>
            <div>
                <span style="color:var(--slate-400);display:block;font-size:11px;text-transform:uppercase;font-weight:700;">Teléfono</span>
                <span>{{ $student->phone ?? 'No registrado' }}</span>
            </div>
            <div>
                <span style="color:var(--slate-400);display:block;font-size:11px;text-transform:uppercase;font-weight:700;">Fecha de Registro</span>
                <span>{{ $student->created_at->format('d/m/Y H:i') }}</span>
            </div>
        </div>
    </div>

    {{-- Main Area: Enrollments and Sales --}}
    <div style="display:flex;flex-direction:column;gap:24px;">
        
        {{-- Enrollments --}}
        <div class="card" style="padding:0;">
            <div class="card-head" style="padding:18px 24px;border-bottom:1px solid rgba(255,255,255,0.08);">
                <h3 style="color:#fff;font-size:16px;">Matr&iacute;culas y Cursos</h3>
            </div>
            <div class="card-body" style="padding:20px;">
                @if ($student->enrollments->isEmpty())
                    <p style="color:var(--slate-400);text-align:center;">El estudiante no est&aacute; inscrito en ningún curso.</p>
                @else
                    <div style="display:flex;flex-direction:column;gap:20px;">
                        @foreach ($student->enrollments as $e)
                            <div style="background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.06);border-radius:12px;padding:20px;display:flex;justify-content:between;align-items:center;flex-wrap:wrap;gap:20px;">
                                <div style="flex:1;min-width:260px;">
                                    <h4 style="color:#fff;font-size:15px;margin-bottom:6px;">{{ $e->course->name }}</h4>
                                    
                                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:10px;">
                                        <span style="font-size:11px;padding:2px 10px;border-radius:12px;background:rgba(255,255,255,0.06);font-weight:600;">
                                            Estado: {{ ucfirst($e->status) }}
                                        </span>
                                        <span style="font-size:11px;color:var(--slate-400);">
                                            Inscrito: {{ $e->enrolled_at ? $e->enrolled_at->format('d/m/Y') : $e->created_at->format('d/m/Y') }}
                                        </span>
                                    </div>

                                    {{-- Progress bar --}}
                                    <div>
                                        <div style="display:flex;justify-content:space-between;font-size:11px;color:var(--slate-400);margin-bottom:4px;">
                                            <span>Avance del Temario</span>
                                            <span style="color:#38bdf8;font-weight:700;">{{ (int)$e->progress }}%</span>
                                        </div>
                                        <div style="height:6px;background:#1e293b;border-radius:10px;overflow:hidden;">
                                            <div style="width:{{ (int)$e->progress }}%;height:100%;background:linear-gradient(90deg, #38bdf8, #0ea5e9);border-radius:10px;"></div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Academic Controls --}}
                                <div style="display:flex;gap:10px;flex-wrap:wrap;">
                                    @if ($e->status === 'suspendido')
                                        <form method="POST" action="{{ route('admin.students.reactivate', [$student, $e->course]) }}">
                                            @csrf
                                            <button type="submit" class="btn-primary" style="background:#22c55e;border-color:#22c55e;padding:8px 14px;font-size:12.5px;">
                                                Reactivar Acceso
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.students.suspend', [$student, $e->course]) }}" onsubmit="return confirm('¿Seguro de suspender el acceso a este curso para el alumno?')">
                                            @csrf
                                            <button type="submit" class="btn-secondary" style="background:#dc2626;border-color:#dc2626;color:#fff;padding:8px 14px;font-size:12.5px;">
                                                Suspender Acceso
                                            </button>
                                        </form>
                                    @endif

                                    <form method="POST" action="{{ route('admin.students.reset', [$student, $e->course]) }}" onsubmit="return confirm('¿ATENCIÓN! Estás a punto de borrar todo el progreso obtenido en este curso por el alumno (lecciones completadas). Esta acción no se puede deshacer. ¿Deseas continuar?')">
                                        @csrf
                                        <button type="submit" class="btn-secondary" style="padding:8px 14px;font-size:12.5px;">
                                            Reiniciar Progreso
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Purchase History --}}
        <div class="card" style="padding:0;">
            <div class="card-head" style="padding:18px 24px;border-bottom:1px solid rgba(255,255,255,0.08);">
                <h3 style="color:#fff;font-size:16px;">Historial de Ventas / Compras</h3>
            </div>
            <div class="card-body" style="padding:0;">
                @if ($student->sales->isEmpty())
                    <p style="color:var(--slate-400);text-align:center;padding:24px;">No se registran compras para este estudiante.</p>
                @else
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID Venta</th>
                                <th>Fecha</th>
                                <th>Subtotal</th>
                                <th>Descuento</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($student->sales as $sale)
                                <tr>
                                    <td><strong>#{{ $sale->id }}</strong></td>
                                    <td>{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                                    <td>S/ {{ number_format($sale->subtotal, 2) }}</td>
                                    <td style="color:#22c55e;">-S/ {{ number_format($sale->discount, 2) }}</td>
                                    <td><strong>S/ {{ number_format($sale->total, 2) }}</strong></td>
                                    <td>
                                        <span style="font-size:10px;padding:2px 8px;border-radius:10px;background:rgba(255,255,255,0.06);">
                                            {{ ucfirst($sale->payment_status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.sales.show', $sale) }}" class="btn-primary" style="padding:4px 8px;font-size:11.5px;text-decoration:none;">
                                            Ver Factura
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection

@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

{{-- Welcome banner --}}
<div class="welcome-banner">
    <div class="welcome-text">
        <h2>Bienvenido, {{ \Illuminate\Support\Str::words(auth()->user()->name, 2, '') }} 👋</h2>
        <p>Panel de administración · Analítica y Gestión Académica</p>
    </div>
    <div class="welcome-logo">
        @if (file_exists(public_path('img/logo-jmjs.png')))
            <img src="{{ asset('img/logo-jmjs.png') }}" alt="Logo">
        @else
            <svg width="40" height="40" fill="none" stroke="white" stroke-width="1.5" viewBox="0 0 24 24">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
            </svg>
        @endif
    </div>
</div>

{{-- Stat cards --}}
<div class="stats-grid">
    {{-- 1. Ingresos Históricos --}}
    <div class="stat-card blue">
        <div class="stat-icon blue">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <line x1="12" y1="1" x2="12" y2="23"/>
                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
            </svg>
        </div>
        <div class="stat-body">
            <div class="stat-value">S/ {{ number_format($stats['sales_total_historic'], 2) }}</div>
            <div class="stat-label">Ingresos Totales</div>
            <span class="stat-trend up">
                S/ {{ number_format($stats['sales_this_month'], 2) }} este mes
            </span>
        </div>
    </div>

    {{-- 2. Ticket Promedio --}}
    <div class="stat-card orange">
        <div class="stat-icon orange">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                <line x1="3" y1="6" x2="21" y2="6"/>
                <path d="M16 10a4 4 0 0 1-8 0"/>
            </svg>
        </div>
        <div class="stat-body">
            <div class="stat-value">S/ {{ number_format($stats['average_ticket'], 2) }}</div>
            <div class="stat-label">Ticket Promedio</div>
            <span class="stat-trend flat">Por venta pagada</span>
        </div>
    </div>

    {{-- 3. Estudiantes Totales --}}
    <div class="stat-card green">
        <div class="stat-icon green">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
        </div>
        <div class="stat-body">
            <div class="stat-value">{{ $stats['total_students'] }}</div>
            <div class="stat-label">Estudiantes Inscritos</div>
            <span class="stat-trend {{ $stats['new_students_month'] > 0 ? 'up' : 'flat' }}">
                +{{ $stats['new_students_month'] }} nuevos este mes
            </span>
        </div>
    </div>

    {{-- 4. Tasa de Finalización --}}
    <div class="stat-card sky">
        <div class="stat-icon sky">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                <path d="M6 12v5c0 2 2.667 3 6 3s6-1 6-3v-5"/>
            </svg>
        </div>
        <div class="stat-body">
            <div class="stat-value">{{ number_format($stats['completion_rate'], 1) }}%</div>
            <div class="stat-label">Tasa de Finalización</div>
            <span class="stat-trend flat">Matrículas completadas</span>
        </div>
    </div>

    {{-- 5. Cursos en Plataforma --}}
    <div class="stat-card blue">
        <div class="stat-icon blue">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
            </svg>
        </div>
        <div class="stat-body">
            <div class="stat-value">{{ $stats['total_courses'] }}</div>
            <div class="stat-label">Cursos Creados</div>
            <span class="stat-trend flat">{{ $stats['active_courses'] }} act. / {{ $stats['inactive_courses'] }} borrad.</span>
        </div>
    </div>

    {{-- 6. Curso Estrella --}}
    <div class="stat-card orange">
        <div class="stat-icon orange">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
            </svg>
        </div>
        <div class="stat-body">
            <div class="stat-value" style="font-size: 15px; font-weight: 700; line-height: 1.3; margin-top: 4px;">
                {{ \Illuminate\Support\Str::limit($stats['best_selling_course'], 24) }}
            </div>
            <div class="stat-label">Curso Estrella</div>
            <span class="stat-trend up">{{ $stats['best_selling_sales'] }} ventas</span>
        </div>
    </div>
</div>

{{-- Charts Grid --}}
<div class="charts-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 20px; margin-bottom: 26px;">
    {{-- Ventas Mensuales --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/>
                    <polyline points="17 6 23 6 23 12"/>
                </svg>
                Ventas Mensuales (S/)
            </div>
        </div>
        <div class="card-body">
            <div style="height: 250px; position: relative;">
                <canvas id="salesChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Inscripciones Mensuales --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="3" y="3" width="18" height="18" rx="2"/><line x1="21" y1="12" x2="3" y2="12"/>
                </svg>
                Inscripciones Mensuales
            </div>
        </div>
        <div class="card-body">
            <div style="height: 250px; position: relative;">
                <canvas id="enrollmentsChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Top Cursos Vendidos --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/><path d="M2 12h20"/>
                </svg>
                Top 5 Cursos
            </div>
        </div>
        <div class="card-body">
            <div style="height: 250px; position: relative;">
                <canvas id="topCoursesChart"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- Content grid --}}
<div class="content-grid">

    {{-- Usuarios recientes --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
                Usuarios recientes
            </div>
            <a href="{{ route('admin.users') }}" class="card-link">
                Ver todos
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <polyline points="9 18 15 12 9 6"/>
                </svg>
            </a>
        </div>
        @if ($stats['recent_users']->isEmpty())
            <div class="card-body" style="color:var(--gray-400);font-size:13px;text-align:center;padding:40px">
                No hay usuarios registrados aún.
            </div>
        @else
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Rol</th>
                        <th>Registro</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($stats['recent_users'] as $user)
                        <tr>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                                    <div>
                                        <div class="user-name">{{ $user->name }}</div>
                                        <div class="user-email">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if ($user->is_admin)
                                    <span class="badge badge-admin">
                                        <svg width="10" height="10" fill="currentColor" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                        Admin
                                    </span>
                                @elseif ($user->isInstructor())
                                    <span class="badge" style="background:#fef3c7; color:#d97706;">Instructor</span>
                                @else
                                    <span class="badge badge-user">Estudiante</span>
                                @endif
                            </td>
                            <td style="color:var(--gray-400);font-size:12px;">
                                {{ $user->created_at->diffForHumans() }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{-- Panel derecho --}}
    <div style="display:flex;flex-direction:column;gap:18px;">

        {{-- Actividad reciente --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                    </svg>
                    Actividad reciente
                </div>
            </div>
            <div class="card-body">
                <div class="activity-list">
                    @forelse ($stats['recent_users']->take(5) as $user)
                        <div class="activity-item">
                            <div class="activity-dot {{ $loop->index % 3 === 0 ? 'sky' : ($loop->index % 3 === 1 ? 'green' : '') }}"></div>
                            <div>
                                <div class="activity-text">
                                    <strong>{{ \Illuminate\Support\Str::words($user->name, 2, '') }}</strong>
                                    se registró en la plataforma
                                </div>
                                <div class="activity-time">{{ $user->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    @empty
                        <p style="color:var(--gray-400);font-size:13px;">Sin actividad reciente.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Acciones rápidas --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    Acciones rápidas
                </div>
            </div>
            <div class="card-body">
                <div class="quick-actions">
                    <a href="{{ route('admin.users') }}" class="quick-btn">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                        </svg>
                        Usuarios
                    </a>
                    <a href="{{ route('admin.courses.index') }}" class="quick-btn">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                        </svg>
                        Cursos
                    </a>
                    <a href="{{ route('admin.contacts') }}" class="quick-btn">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                        </svg>
                        Mensajes
                    </a>
                    <a href="{{ route('inicio') }}" target="_blank" class="quick-btn">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                        </svg>
                        Sitio web
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Inscripciones recientes --}}
<div class="card" style="margin-bottom:20px;">
    <div class="card-header">
        <div class="card-title">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
            </svg>
            Inscripciones recientes
        </div>
    </div>
    @if ($stats['recent_enrollments']->isEmpty())
        <div style="padding:28px;text-align:center;color:var(--gray-400);font-size:13px;">Aún no hay inscripciones.</div>
    @else
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Curso</th>
                    <th>Nivel</th>
                    <th>Precio</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($stats['recent_enrollments'] as $e)
                    <tr>
                        <td>
                            <div class="user-info">
                                <div class="user-avatar">{{ strtoupper(substr($e->user->name ?? '?', 0, 1)) }}</div>
                                <div>
                                    <div class="user-name">{{ $e->user->name ?? '—' }}</div>
                                    <div class="user-email">{{ $e->user->email ?? '' }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="font-size:13px;max-width:200px;">{{ $e->course->name ?? '—' }}</td>
                        <td>
                            @php 
                                $level = strtolower($e->course->level ?? 'basico');
                                $colors = ['basico'=>'#dcfce7|#15803d','intermedio'=>'#fef3c7|#92400e','avanzado'=>'#fee2e2|#991b1b']; 
                                $c = explode('|', $colors[$level] ?? '#e2e8f0|#475569'); 
                            @endphp
                            <span style="background:{{ $c[0] }};color:{{ $c[1] }};padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;">{{ ucfirst($level) }}</span>
                        </td>
                        <td style="font-weight:700;">S/ {{ number_format($e->course->price ?? 0, 2) }}</td>
                        <td>
                            @if ($e->status === 'activo')
                                <span style="background:#dcfce7;color:#15803d;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;">Activo</span>
                            @elseif ($e->status === 'completado')
                                <span style="background:var(--blue-100);color:var(--blue-700);padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;">Completado</span>
                            @elseif ($e->status === 'suspendido')
                                <span style="background:#fee2e2;color:#991b1b;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;">Suspendido</span>
                            @else
                                <span style="background:#fef3c7;color:#92400e;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;">{{ ucfirst($e->status) }}</span>
                            @endif
                        </td>
                        <td style="font-size:12px;color:var(--gray-400);">{{ $e->created_at->format('d/m/Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

{{-- Mensajes de contacto --}}
<div class="card" style="margin-top:0">
    <div class="card-header">
        <div class="card-title">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
            </svg>
            Mensajes de contacto
            @if ($stats['unread_contacts'] > 0)
                <span style="background:var(--blue-600);color:#fff;font-size:11px;font-weight:700;padding:2px 8px;border-radius:20px;margin-left:6px;">
                    {{ $stats['unread_contacts'] }} nuevos
                </span>
            @endif
        </div>
        <a href="{{ route('admin.contacts') }}" class="card-link">
            Ver todos
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
        </a>
    </div>

    @if ($stats['recent_contacts']->isEmpty())
        <div style="padding:32px;text-align:center;color:var(--gray-400);font-size:13px;">
            Aún no hay mensajes recibidos.
        </div>
    @else
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Remitente</th>
                    <th>Tema</th>
                    <th>Curso</th>
                    <th>Mensaje</th>
                    <th>Recibido</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($stats['recent_contacts'] as $contact)
                    <tr style="{{ !$contact->leido ? 'background:#eff6ff;' : '' }}">
                        <td>
                            <div class="user-info">
                                <div class="user-avatar" style="background:linear-gradient(135deg,#2563eb,#0ea5e9)">
                                    {{ strtoupper(substr($contact->nombre, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="user-name">{{ $contact->nombre }}</div>
                                    <div class="user-email">{{ $contact->correo }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span style="background:var(--blue-100);color:var(--blue-700);padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;">
                                {{ ucfirst($contact->tema) }}
                            </span>
                        </td>
                        <td style="font-size:12px;color:var(--gray-400);max-width:160px;">
                            {{ $contact->curso ?? '—' }}
                        </td>
                        <td style="font-size:13px;color:var(--gray-600);max-width:220px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            {{ $contact->mensaje }}
                        </td>
                        <td style="font-size:12px;color:var(--gray-400);white-space:nowrap;">
                            {{ $contact->created_at->diffForHumans() }}
                        </td>
                        <td>
                            @if (!$contact->leido)
                                <span style="background:#dbeafe;color:#1d4ed8;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;">Nuevo</span>
                            @else
                                <span style="background:var(--gray-100);color:var(--gray-400);padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;">Leído</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    // 1. Ventas Mensuales (Líneas con gradiente)
    const ctxSales = document.getElementById('salesChart');
    if (ctxSales) {
        const ctx = ctxSales.getContext('2d');
        const gradientSales = ctx.createLinearGradient(0, 0, 0, 250);
        gradientSales.addColorStop(0, 'rgba(37, 99, 235, 0.35)');
        gradientSales.addColorStop(1, 'rgba(37, 99, 235, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($stats['month_labels']),
                datasets: [{
                    label: 'Ventas (S/)',
                    data: @json($stats['monthly_sales_data']),
                    borderColor: '#2563eb',
                    borderWidth: 3,
                    backgroundColor: gradientSales,
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: '#2563eb',
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        grid: { color: 'rgba(226, 232, 240, 0.6)' },
                        ticks: { callback: value => 'S/ ' + value }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    }

    // 2. Inscripciones Mensuales (Barras con bordes redondeados)
    const ctxEnrollments = document.getElementById('enrollmentsChart');
    if (ctxEnrollments) {
        new Chart(ctxEnrollments, {
            type: 'bar',
            data: {
                labels: @json($stats['month_labels']),
                datasets: [{
                    label: 'Matrículas',
                    data: @json($stats['monthly_enrollments_data']),
                    backgroundColor: '#0ea5e9',
                    borderRadius: 5,
                    hoverBackgroundColor: '#0284c7'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        grid: { color: 'rgba(226, 232, 240, 0.6)' },
                        ticks: { stepSize: 1 }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    }

    // 3. Top Cursos (Pie/Dona)
    const ctxTopCourses = document.getElementById('topCoursesChart');
    if (ctxTopCourses) {
        const topCoursesData = @json($stats['top_courses']);
        const courseLabels = topCoursesData.map(c => c.name.length > 20 ? c.name.substring(0, 20) + '...' : c.name);
        const courseSales = topCoursesData.map(c => c.sales_count);

        new Chart(ctxTopCourses, {
            type: 'doughnut',
            data: {
                labels: courseLabels.length ? courseLabels : ['Sin datos'],
                datasets: [{
                    data: courseSales.length ? courseSales : [1],
                    backgroundColor: [
                        '#2563eb',
                        '#0ea5e9',
                        '#10b981',
                        '#f59e0b',
                        '#ec4899',
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 12, font: { size: 10 } }
                    }
                },
                cutout: '65%'
            }
        });
    }
});
</script>
@endpush

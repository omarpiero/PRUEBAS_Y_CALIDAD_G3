<!DOCTYPE html>
<html lang="es">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel Admin') | JM y JS</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite('resources/js/app.jsx')
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --blue-900:  #1e3a5f;
            --blue-800:  #1e40af;
            --blue-700:  #1d4ed8;
            --blue-600:  #2563eb;
            --blue-500:  #3b82f6;
            --blue-400:  #60a5fa;
            --blue-100:  #dbeafe;
            --blue-50:   #eff6ff;
            --sky-500:   #0ea5e9;
            --sky-400:   #38bdf8;
            --sky-100:   #e0f2fe;
            --white:     #ffffff;
            --gray-50:   #f8fafc;
            --gray-100:  #f1f5f9;
            --gray-200:  #e2e8f0;
            --gray-400:  #94a3b8;
            --gray-600:  #475569;
            --gray-800:  #1e293b;
            --danger:    #ef4444;
            --success:   #22c55e;
            --warning:   #f59e0b;
            --sidebar-w: 260px;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--gray-100);
            color: var(--gray-800);
            display: flex;
            min-height: 100vh;
        }

        /* ══════════════ SIDEBAR ══════════════ */
        .admin-sidebar {
            width: var(--sidebar-w);
            background: linear-gradient(180deg, var(--blue-900) 0%, #0f2447 100%);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            z-index: 200;
            box-shadow: 4px 0 20px rgba(0,0,0,.25);
        }

        /* Brand */
        .sidebar-brand {
            padding: 20px 20px 18px;
            border-bottom: 1px solid rgba(255,255,255,.08);
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .sidebar-logo {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            background: var(--white);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            flex-shrink: 0;
            padding: 4px;
        }
        .sidebar-logo img { width: 100%; height: 100%; object-fit: contain; }
        .sidebar-logo-fallback {
            font-size: 14px;
            font-weight: 800;
            color: var(--blue-800);
        }
        .brand-info { flex: 1; min-width: 0; }
        .brand-name {
            font-size: 13px;
            font-weight: 700;
            color: #fff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .brand-sub { font-size: 10.5px; color: rgba(255,255,255,.5); margin-top: 1px; }
        .brand-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: linear-gradient(135deg, var(--blue-600), var(--sky-500));
            color: #fff;
            font-size: 9.5px;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 20px;
            margin-top: 5px;
            letter-spacing: .6px;
            text-transform: uppercase;
        }

        /* Nav */
        .sidebar-nav { flex: 1; padding: 12px 0; overflow-y: auto; }
        .nav-section {
            font-size: 9.5px;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: rgba(255,255,255,.3);
            padding: 14px 20px 5px;
        }
        .nav-item {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 10px 16px 10px 20px;
            color: rgba(255,255,255,.65);
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 500;
            border-left: 3px solid transparent;
            transition: all .2s;
            border-radius: 0;
            margin: 1px 0;
        }
        .nav-item:hover {
            color: #fff;
            background: rgba(255,255,255,.07);
            border-left-color: var(--sky-400);
        }
        .nav-item.active {
            color: #fff;
            background: linear-gradient(90deg, rgba(59,130,246,.25), rgba(14,165,233,.08));
            border-left-color: var(--sky-400);
        }
        .nav-item .nav-icon { flex-shrink: 0; width: 18px; height: 18px; opacity: .7; }
        .nav-item:hover .nav-icon,
        .nav-item.active .nav-icon { opacity: 1; }
        .nav-item .nav-badge {
            margin-left: auto;
            background: var(--blue-600);
            color: #fff;
            font-size: 10px;
            font-weight: 600;
            padding: 1px 7px;
            border-radius: 10px;
        }

        /* Footer */
        .sidebar-footer {
            padding: 14px 18px;
            border-top: 1px solid rgba(255,255,255,.08);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .footer-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--blue-600), var(--sky-500));
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 13px;
            font-weight: 700;
            flex-shrink: 0;
        }
        .footer-info { flex: 1; min-width: 0; }
        .footer-name { font-size: 12.5px; font-weight: 600; color: #fff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .footer-role { font-size: 10.5px; color: rgba(255,255,255,.45); margin-top: 1px; }

        /* ══════════════ MAIN ══════════════ */
        .admin-main { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; min-height: 100vh; }

        /* Topbar */
        .admin-topbar {
            background: var(--white);
            border-bottom: 1px solid var(--gray-200);
            padding: 0 28px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 1px 8px rgba(0,0,0,.06);
        }
        .topbar-left { display: flex; align-items: center; gap: 12px; }
        .topbar-title { font-size: 17px; font-weight: 700; color: var(--gray-800); }
        .topbar-breadcrumb { font-size: 12px; color: var(--gray-400); display: flex; align-items: center; gap: 4px; margin-top: 2px; }
        .topbar-right { display: flex; align-items: center; gap: 14px; }

        .topbar-date {
            font-size: 12px;
            color: var(--gray-400);
            background: var(--gray-100);
            padding: 6px 12px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .topbar-notif {
            width: 36px; height: 36px;
            border-radius: 9px;
            background: var(--blue-50);
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--blue-600);
            transition: background .18s;
        }
        .topbar-notif:hover { background: var(--blue-100); }

        .btn-logout {
            font-size: 12.5px;
            font-weight: 600;
            color: var(--danger);
            background: #fff1f1;
            border: 1px solid #fecaca;
            border-radius: 8px;
            padding: 7px 16px;
            cursor: pointer;
            font-family: inherit;
            transition: all .18s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-logout:hover { background: var(--danger); color: #fff; border-color: var(--danger); }

        /* Content */
        .admin-content { flex: 1; padding: 28px 30px; }

        /* ══════════════ COMPONENTS ══════════════ */

        /* Page header */
        .page-header {
            margin-bottom: 26px;
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
        }
        .page-header h1 { font-size: 23px; font-weight: 800; color: var(--gray-800); }
        .page-header p { font-size: 13.5px; color: var(--gray-400); margin-top: 3px; }
        .page-header-actions { display: flex; gap: 10px; }

        /* Stat cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(210px, 1fr));
            gap: 18px;
            margin-bottom: 26px;
        }
        .stat-card {
            background: var(--white);
            border-radius: 14px;
            padding: 20px 22px;
            border: 1px solid var(--gray-200);
            box-shadow: 0 2px 10px rgba(0,0,0,.04);
            display: flex;
            align-items: center;
            gap: 16px;
            position: relative;
            overflow: hidden;
            transition: transform .2s, box-shadow .2s;
        }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,.09); }
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
        }
        .stat-card.blue::before   { background: linear-gradient(90deg, var(--blue-600), var(--blue-400)); }
        .stat-card.sky::before    { background: linear-gradient(90deg, var(--sky-500), var(--sky-400)); }
        .stat-card.green::before  { background: linear-gradient(90deg, #22c55e, #86efac); }
        .stat-card.orange::before { background: linear-gradient(90deg, #f59e0b, #fcd34d); }

        .stat-icon {
            width: 50px; height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .stat-icon.blue   { background: var(--blue-100); color: var(--blue-600); }
        .stat-icon.sky    { background: var(--sky-100);  color: var(--sky-500); }
        .stat-icon.green  { background: #dcfce7; color: #16a34a; }
        .stat-icon.orange { background: #fef3c7; color: #d97706; }

        .stat-body { flex: 1; }
        .stat-value { font-size: 28px; font-weight: 800; color: var(--gray-800); line-height: 1; }
        .stat-label { font-size: 12px; color: var(--gray-400); margin-top: 4px; font-weight: 500; }
        .stat-trend {
            font-size: 11px;
            font-weight: 600;
            margin-top: 6px;
            display: inline-flex;
            align-items: center;
            gap: 3px;
            padding: 2px 7px;
            border-radius: 20px;
        }
        .stat-trend.up   { background: #dcfce7; color: #15803d; }
        .stat-trend.flat { background: var(--gray-100); color: var(--gray-400); }

        /* Grid layout */
        .content-grid {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 20px;
            margin-bottom: 20px;
        }

        /* Cards */
        .card {
            background: var(--white);
            border-radius: 14px;
            border: 1px solid var(--gray-200);
            box-shadow: 0 2px 10px rgba(0,0,0,.04);
            overflow: hidden;
        }
        .card-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--gray-100);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .card-title {
            font-size: 14px;
            font-weight: 700;
            color: var(--gray-800);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .card-title svg { color: var(--blue-500); }
        .card-link {
            font-size: 12px;
            font-weight: 600;
            color: var(--blue-600);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .card-link:hover { color: var(--blue-800); }
        .card-body { padding: 20px; }

        /* Table */
        .admin-table { width: 100%; border-collapse: collapse; font-size: 13px; }
        .admin-table th {
            padding: 10px 16px;
            text-align: left;
            font-size: 10.5px;
            font-weight: 700;
            letter-spacing: .7px;
            text-transform: uppercase;
            color: var(--gray-400);
            background: var(--gray-50);
            border-bottom: 1px solid var(--gray-200);
        }
        .admin-table td {
            padding: 12px 16px;
            border-bottom: 1px solid var(--gray-100);
            vertical-align: middle;
        }
        .admin-table tr:last-child td { border-bottom: none; }
        .admin-table tr:hover td { background: var(--blue-50); }

        /* Avatar */
        .user-avatar {
            width: 32px; height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--blue-600), var(--sky-500));
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .user-info { display: flex; align-items: center; gap: 10px; }
        .user-name { font-weight: 600; color: var(--gray-800); }
        .user-email { font-size: 11.5px; color: var(--gray-400); }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }
        .badge-admin   { background: var(--blue-100); color: var(--blue-700); }
        .badge-user    { background: var(--gray-100); color: var(--gray-600); }
        .badge-new     { background: #dcfce7; color: #15803d; }

        /* Activity feed */
        .activity-list { display: flex; flex-direction: column; }
        .activity-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid var(--gray-100);
        }
        .activity-item:last-child { border-bottom: none; padding-bottom: 0; }
        .activity-dot {
            width: 10px; height: 10px;
            border-radius: 50%;
            background: var(--blue-500);
            margin-top: 5px;
            flex-shrink: 0;
            box-shadow: 0 0 0 3px var(--blue-100);
        }
        .activity-dot.sky    { background: var(--sky-500); box-shadow: 0 0 0 3px var(--sky-100); }
        .activity-dot.green  { background: var(--success); box-shadow: 0 0 0 3px #dcfce7; }
        .activity-text { font-size: 13px; color: var(--gray-600); line-height: 1.5; }
        .activity-text strong { color: var(--gray-800); font-weight: 600; }
        .activity-time { font-size: 11px; color: var(--gray-400); margin-top: 2px; }

        /* Quick actions */
        .quick-actions { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
        .quick-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            padding: 16px 10px;
            border-radius: 12px;
            background: var(--gray-50);
            border: 1px solid var(--gray-200);
            text-decoration: none;
            color: var(--gray-600);
            font-size: 12px;
            font-weight: 600;
            transition: all .2s;
            text-align: center;
        }
        .quick-btn:hover { background: var(--blue-50); border-color: var(--blue-200); color: var(--blue-700); }
        .quick-btn svg { width: 22px; height: 22px; }

        /* Bar chart */
        .bar-chart { display: flex; align-items: flex-end; gap: 6px; height: 80px; padding: 0 4px; }
        .bar-col { flex: 1; display: flex; flex-direction: column; align-items: center; gap: 4px; }
        .bar {
            width: 100%;
            background: linear-gradient(180deg, var(--blue-500), var(--sky-400));
            border-radius: 4px 4px 0 0;
            min-height: 4px;
            transition: height .5s ease;
        }
        .bar-label { font-size: 9px; color: var(--gray-400); font-weight: 600; }

        /* Welcome banner */
        .welcome-banner {
            background: linear-gradient(135deg, var(--blue-800) 0%, var(--blue-600) 50%, var(--sky-500) 100%);
            border-radius: 16px;
            padding: 24px 28px;
            color: #fff;
            margin-bottom: 26px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            overflow: hidden;
            position: relative;
        }
        .welcome-banner::before {
            content: '';
            position: absolute;
            top: -40px; right: -40px;
            width: 200px; height: 200px;
            border-radius: 50%;
            background: rgba(255,255,255,.06);
        }
        .welcome-banner::after {
            content: '';
            position: absolute;
            bottom: -60px; right: 120px;
            width: 160px; height: 160px;
            border-radius: 50%;
            background: rgba(255,255,255,.04);
        }
        .welcome-text h2 { font-size: 20px; font-weight: 800; }
        .welcome-text p  { font-size: 13px; opacity: .8; margin-top: 4px; }
        .welcome-logo {
            width: 64px; height: 64px;
            background: rgba(255,255,255,.15);
            border-radius: 16px;
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 8px;
            flex-shrink: 0;
        }
        .welcome-logo img { width: 100%; height: 100%; object-fit: contain; }

        @stack('admin-styles')
    </style>
    @stack('styles')
</head>
<body>
<div id="ai-chat"></div>

<aside class="admin-sidebar">
    {{-- Brand --}}
    <div class="sidebar-brand">
        <div class="sidebar-logo">
            @php $logo = public_path('img/logo-jmjs.png'); @endphp
            @if (file_exists($logo))
                <img src="{{ asset('img/logo-jmjs.png') }}" alt="JM y JS">
            @else
                <span class="sidebar-logo-fallback">JM</span>
            @endif
        </div>
        <div class="brand-info">
            <div class="brand-name">JM y JS Alimentos</div>
            <div class="brand-sub">Alimentos · Huancayo</div>
            <span class="brand-badge">
                <svg width="8" height="8" viewBox="0 0 24 24" fill="currentColor"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                Admin
            </span>
        </div>
    </div>

    {{-- Nav --}}
    <nav class="sidebar-nav">
        <div class="nav-section">Principal</div>

        <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/>
                <rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/>
            </svg>
            Dashboard
        </a>

        <a href="{{ route('admin.users') }}" class="nav-item {{ request()->routeIs('admin.users') ? 'active' : '' }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
            Usuarios
            <span class="nav-badge" id="sidebar-user-count">—</span>
        </a>

        <a href="{{ route('admin.students.index') }}" class="nav-item {{ request()->routeIs('admin.students.*') ? 'active' : '' }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M12 14c4 0 8 2 8 6v2H4v-2c0-4 4-6 8-6z"/>
                <circle cx="12" cy="7" r="4"/>
            </svg>
            Estudiantes
        </a>

        <a href="{{ route('admin.contacts') }}" class="nav-item {{ request()->routeIs('admin.contacts') ? 'active' : '' }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
            </svg>
            Mensajes
            @php $unread = \App\Models\Contact::where('leido', false)->count(); @endphp
            @if ($unread > 0)
                <span class="nav-badge">{{ $unread }}</span>
            @endif
        </a>

        <a href="{{ route('admin.sales.index') }}" class="nav-item {{ request()->routeIs('admin.sales.*') ? 'active' : '' }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <line x1="12" y1="1" x2="12" y2="23"/>
                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
            </svg>
            Ventas
        </a>

        <a href="{{ route('admin.coupons.index') }}" class="nav-item {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82zM7 7h.01"/>
            </svg>
            Cupones
        </a>

        <a href="{{ route('admin.courses.index') }}" class="nav-item {{ request()->routeIs('admin.courses.*') ? 'active' : '' }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            Gestionar Cursos
        </a>

        <div class="nav-section">Ajustes y Seguridad</div>

        <a href="{{ route('admin.roles.index') }}" class="nav-item {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
            </svg>
            Roles y Permisos
        </a>

        <a href="{{ route('admin.audit.index') }}" class="nav-item {{ request()->routeIs('admin.audit.*') ? 'active' : '' }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <rect x="3" y="3" width="18" height="18" rx="2"/><line x1="9" y1="3" x2="9" y2="21"/>
            </svg>
            Auditoría
        </a>

        <a href="{{ route('admin.settings.index') }}" class="nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="3"/>
                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
            </svg>
            Configuración
        </a>

        <div class="nav-section">Sitio web</div>

        <a href="{{ route('cursos') }}" target="_blank" class="nav-item">
            <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
            </svg>
            Cursos
        </a>

        <a href="{{ route('contacto') }}" target="_blank" class="nav-item">
            <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
            </svg>
            Contacto
        </a>

        <a href="{{ route('inicio') }}" target="_blank" class="nav-item">
            <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
            Ver sitio público
        </a>
    </nav>

    {{-- Footer --}}
    <div class="sidebar-footer">
        <div class="footer-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
        <div class="footer-info">
            <div class="footer-name">{{ \Illuminate\Support\Str::limit(auth()->user()->name, 22) }}</div>
            <div class="footer-role">Administrador</div>
        </div>
    </div>
</aside>

<div class="admin-main">
    {{-- Topbar --}}
    <header class="admin-topbar">
        <div class="topbar-left">
            <div>
                <div class="topbar-title">@yield('page-title', 'Dashboard')</div>
            </div>
        </div>
        <div class="topbar-right">
            <div class="topbar-date">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
                <span id="topbar-date-text"></span>
            </div>
            <button class="topbar-notif" title="Notificaciones">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                </svg>
            </button>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <polyline points="16 17 21 12 16 7"/>
                        <line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                    Cerrar sesión
                </button>
            </form>
        </div>
    </header>

    <main class="admin-content">
        @yield('content')
    </main>
</div>

<script>
// Fecha en topbar
(function() {
    const d = new Date();
    const opts = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
    const el = document.getElementById('topbar-date-text');
    if (el) el.textContent = d.toLocaleDateString('es-PE', opts);

    // Sidebar user count desde el DOM (evitar fetch extra)
    const badge = document.getElementById('sidebar-user-count');
    if (badge) badge.textContent = '{{ \App\Models\User::count() }}';
})();
</script>

@stack('scripts')
</body>
</html>

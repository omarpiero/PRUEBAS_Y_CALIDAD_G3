@extends('layouts.app')

@section('title', 'Mi Cuenta')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Noto+Serif:wght@600;700&display=swap" rel="stylesheet">
<style>
/* ══════════════ LAYOUT BASE ══════════════ */
.mcu {
    background: #f1f5f9;
    min-height: calc(100vh - 76px);
    font-family: 'Poppins', sans-serif;
    color: #0f172a;
}

/* ══════════════ HERO BANNER ══════════════ */
.mcu-hero {
    background: linear-gradient(125deg, #0f1e5e 0%, #1e40af 45%, #0ea5e9 100%);
    padding: 52px 0 80px;
    position: relative;
    overflow: hidden;
}
.mcu-hero::before {
    content: '';
    position: absolute; inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}
.mcu-hero-blob {
    position: absolute; border-radius: 50%;
    filter: blur(80px); opacity: .2; pointer-events: none;
}
.mcu-hero-blob-1 { width: 400px; height: 400px; background: #60a5fa; top: -120px; right: -60px; }
.mcu-hero-blob-2 { width: 300px; height: 300px; background: #38bdf8; bottom: -80px; left: 10%; }

.mcu-hero-inner {
    max-width: 1160px; margin: 0 auto; padding: 0 32px;
    position: relative; z-index: 1;
    display: flex; align-items: flex-end; justify-content: space-between;
    gap: 24px; flex-wrap: wrap;
}
.mcu-hero-left { display: flex; align-items: center; gap: 24px; }

.mcu-avatar {
    width: 88px; height: 88px; border-radius: 50%;
    background: rgba(255,255,255,.2);
    border: 3px solid rgba(255,255,255,.5);
    display: flex; align-items: center; justify-content: center;
    font-family: 'Noto Serif', serif;
    font-size: 32px; font-weight: 700; color: #fff;
    flex-shrink: 0;
    box-shadow: 0 12px 32px rgba(0,0,0,.2);
}
.mcu-hero-info { color: #fff; }
.mcu-hero-name {
    font-family: 'Noto Serif', serif;
    font-size: clamp(22px, 3vw, 30px);
    font-weight: 700; line-height: 1.15; margin-bottom: 5px;
}
.mcu-hero-sub  { font-size: 13.5px; opacity: .75; margin-bottom: 12px; }
.mcu-hero-tags { display: flex; gap: 8px; flex-wrap: wrap; }
.mcu-hero-tag  {
    display: inline-flex; align-items: center; gap: 5px;
    background: rgba(255,255,255,.15);
    border: 1px solid rgba(255,255,255,.25);
    color: rgba(255,255,255,.9);
    font-size: 11.5px; font-weight: 600;
    padding: 4px 12px; border-radius: 20px;
}

.mcu-hero-right { display: flex; gap: 14px; }
.mcu-hero-stat  {
    background: rgba(255,255,255,.12);
    border: 1px solid rgba(255,255,255,.18);
    border-radius: 14px; padding: 16px 22px; text-align: center;
    backdrop-filter: blur(8px); min-width: 90px;
}
.mcu-hero-stat strong { display: block; font-size: 28px; font-weight: 800; color: #fff; line-height: 1; }
.mcu-hero-stat span   { font-size: 11px; color: rgba(255,255,255,.65); margin-top: 4px; display: block; }

/* ══════════════ MAIN CONTENT ══════════════ */
.mcu-main {
    max-width: 1160px; margin: -40px auto 0;
    padding: 0 32px 80px;
    position: relative; z-index: 2;
    display: grid;
    grid-template-columns: 1fr 300px;
    gap: 24px;
    align-items: start;
}

/* ══════════════ TABS ══════════════ */
.mcu-tabs-bar {
    display: flex; gap: 4px;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 6px;
    margin-bottom: 24px;
    box-shadow: 0 2px 10px rgba(0,0,0,.05);
}
.mcu-tab {
    flex: 1; padding: 10px 16px;
    border: none; border-radius: 10px;
    background: transparent; cursor: pointer;
    font-family: inherit; font-size: 13.5px; font-weight: 600;
    color: #64748b; display: flex; align-items: center; justify-content: center;
    gap: 7px; transition: all .2s;
}
.mcu-tab:hover   { background: #f1f5f9; color: #1e40af; }
.mcu-tab.active  { background: #1e40af; color: #fff; box-shadow: 0 4px 14px rgba(30,64,175,.3); }
.mcu-tab .tab-badge {
    background: rgba(255,255,255,.3); color: inherit;
    font-size: 10px; font-weight: 700;
    padding: 1px 7px; border-radius: 20px;
}
.mcu-tab:not(.active) .tab-badge { background: #e2e8f0; color: #64748b; }

/* Tab panels */
.mcu-panel { display: none; }
.mcu-panel.active { display: block; }

/* ══════════════ COURSE CARDS ══════════════ */
.mcu-courses-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 20px;
}
.mcu-course-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0,0,0,.05);
    transition: transform .25s, box-shadow .25s;
    display: flex; flex-direction: column;
}
.mcu-course-card:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(0,0,0,.1); }

.mcu-course-img {
    position: relative; height: 150px; overflow: hidden;
    background: linear-gradient(135deg, #1e40af, #0ea5e9);
}
.mcu-course-img img { width: 100%; height: 100%; object-fit: cover; transition: transform .4s; }
.mcu-course-card:hover .mcu-course-img img { transform: scale(1.06); }
.mcu-course-img::after {
    content: ''; position: absolute; inset: 0;
    background: linear-gradient(180deg, transparent 40%, rgba(15,23,42,.55));
}
.mcu-course-status-badge {
    position: absolute; top: 10px; right: 10px; z-index: 1;
    font-size: 10.5px; font-weight: 700; padding: 3px 10px;
    border-radius: 20px; text-transform: uppercase; letter-spacing: .4px;
}
.mcu-course-status-badge.pagado     { background: #dcfce7; color: #15803d; }
.mcu-course-status-badge.pendiente  { background: #fef3c7; color: #92400e; }
.mcu-course-status-badge.completado { background: #dbeafe; color: #1e40af; }

.mcu-course-body { padding: 16px 18px 18px; display: flex; flex-direction: column; flex: 1; }
.mcu-course-level {
    display: inline-block; font-size: 10.5px; font-weight: 700;
    padding: 3px 10px; border-radius: 20px; margin-bottom: 8px;
}
.mcu-course-level.basico     { background: #dcfce7; color: #15803d; }
.mcu-course-level.intermedio { background: #fef3c7; color: #92400e; }
.mcu-course-level.avanzado   { background: #fee2e2; color: #991b1b; }

.mcu-course-name { font-size: 14.5px; font-weight: 700; color: #0f172a; line-height: 1.35; margin-bottom: 10px; flex: 1; }

/* Barra de progreso */
.mcu-progress-wrap { margin-bottom: 14px; }
.mcu-progress-label {
    display: flex; justify-content: space-between;
    font-size: 11px; color: #64748b; font-weight: 600; margin-bottom: 5px;
}
.mcu-progress-bar {
    height: 5px; background: #f1f5f9; border-radius: 10px; overflow: hidden;
}
.mcu-progress-fill {
    height: 100%; border-radius: 10px;
    background: linear-gradient(90deg, #2563eb, #0ea5e9);
    transition: width .6s ease;
}

.mcu-course-footer {
    display: flex; align-items: center; justify-content: space-between;
    padding-top: 12px; border-top: 1px solid #f1f5f9;
}
.mcu-course-price { font-size: 18px; font-weight: 800; color: #0f172a; }
.mcu-course-date  { font-size: 11px; color: #94a3b8; }

/* Estado vacío */
.mcu-empty {
    grid-column: 1 / -1;
    background: #fff;
    border: 2px dashed #e2e8f0;
    border-radius: 16px;
    padding: 64px 32px;
    text-align: center;
}
.mcu-empty-icon {
    width: 72px; height: 72px; border-radius: 20px;
    background: #dbeafe; color: #1e40af;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 20px;
}
.mcu-empty h3 { font-size: 18px; font-weight: 700; color: #0f172a; margin-bottom: 8px; }
.mcu-empty p  { font-size: 14px; color: #64748b; margin-bottom: 24px; max-width: 340px; margin-left: auto; margin-right: auto; }
.mcu-empty-btn {
    display: inline-flex; align-items: center; gap: 7px;
    background: #1e40af; color: #fff;
    padding: 12px 24px; border-radius: 10px;
    font-size: 14px; font-weight: 700; text-decoration: none;
    transition: background .18s;
}
.mcu-empty-btn:hover { background: #0f2a5e; }

/* ══════════════ SIDEBAR DERECHO ══════════════ */
.mcu-sidebar { display: flex; flex-direction: column; gap: 18px; }

.mcu-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    box-shadow: 0 2px 10px rgba(0,0,0,.05);
    overflow: hidden;
}
.mcu-card-head {
    padding: 16px 20px;
    border-bottom: 1px solid #f1f5f9;
    display: flex; align-items: center; gap: 8px;
    font-size: 14px; font-weight: 700; color: #0f172a;
}
.mcu-card-head svg { color: #1e40af; }
.mcu-card-body { padding: 18px 20px; }

/* Perfil en sidebar */
.mcu-profile-avatar {
    width: 64px; height: 64px; border-radius: 50%;
    background: linear-gradient(135deg, #1e40af, #0ea5e9);
    color: #fff; font-size: 22px; font-weight: 800;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 14px; font-family: 'Noto Serif', serif;
}
.mcu-profile-name  { font-size: 16px; font-weight: 700; text-align: center; margin-bottom: 3px; }
.mcu-profile-email { font-size: 12px; color: #64748b; text-align: center; margin-bottom: 18px; word-break: break-word; }

.mcu-profile-row {
    display: flex; align-items: flex-start;
    padding: 9px 0; border-bottom: 1px solid #f8fafc;
    font-size: 13px; gap: 10px;
}
.mcu-profile-row:last-child { border-bottom: none; }
.mcu-profile-row svg { color: #94a3b8; flex-shrink: 0; margin-top: 1px; }
.mcu-profile-key   { font-weight: 600; color: #64748b; min-width: 80px; flex-shrink: 0; font-size: 11.5px; }
.mcu-profile-val   { color: #0f172a; word-break: break-word; }

/* Acciones rápidas */
.mcu-quick-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
.mcu-quick-btn  {
    display: flex; flex-direction: column; align-items: center;
    gap: 8px; padding: 16px 10px; border-radius: 12px;
    background: #f8fafc; border: 1px solid #e2e8f0;
    text-decoration: none; color: #334155;
    font-size: 12px; font-weight: 600; text-align: center;
    transition: all .2s;
}
.mcu-quick-btn:hover { background: #dbeafe; border-color: #93c5fd; color: #1e40af; }
.mcu-quick-btn svg { width: 22px; height: 22px; }

/* Logros */
.mcu-logro {
    display: flex; align-items: center; gap: 12px;
    padding: 11px 0; border-bottom: 1px solid #f8fafc;
}
.mcu-logro:last-child { border-bottom: none; padding-bottom: 0; }
.mcu-logro-icon {
    width: 38px; height: 38px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; font-size: 18px;
}
.mcu-logro-title { font-size: 13px; font-weight: 700; color: #0f172a; }
.mcu-logro-desc  { font-size: 11.5px; color: #64748b; }
.mcu-logro-lock  { opacity: .35; filter: grayscale(1); }

/* ══════════════ PANEL PERFIL (tab) ══════════════ */
.mcu-profile-full {
    background: #fff; border: 1px solid #e2e8f0;
    border-radius: 16px; overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,.05);
}
.mcu-profile-full-head {
    background: linear-gradient(90deg, #1e40af, #0ea5e9);
    padding: 28px 28px 0; display: flex; align-items: flex-end; gap: 20px;
}
.mcu-profile-full-avatar {
    width: 80px; height: 80px; border-radius: 50%;
    background: rgba(255,255,255,.2); border: 3px solid rgba(255,255,255,.5);
    color: #fff; font-size: 28px; font-weight: 800;
    display: flex; align-items: center; justify-content: center;
    font-family: 'Noto Serif', serif; margin-bottom: -20px; flex-shrink: 0;
}
.mcu-profile-full-name { color: #fff; font-size: 20px; font-weight: 700; margin-bottom: 24px; }
.mcu-profile-full-body { padding: 36px 28px 28px; }
.mcu-profile-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.mcu-profile-field label {
    display: block; font-size: 11px; font-weight: 700; text-transform: uppercase;
    letter-spacing: .6px; color: #94a3b8; margin-bottom: 6px;
}
.mcu-profile-field .value {
    font-size: 15px; color: #0f172a; font-weight: 500;
    padding: 10px 14px; background: #f8fafc;
    border: 1px solid #e2e8f0; border-radius: 9px;
}

/* ══════════════ RESPONSIVE ══════════════ */
@media (max-width: 900px) {
    .mcu-main    { grid-template-columns: 1fr; }
    .mcu-sidebar { order: -1; display: grid; grid-template-columns: 1fr 1fr; }
    .mcu-hero-inner { flex-direction: column; align-items: flex-start; gap: 20px; }
    .mcu-hero-right { flex-wrap: wrap; }
}
@media (max-width: 640px) {
    .mcu-hero     { padding: 36px 0 64px; }
    .mcu-hero-inner { padding: 0 20px; }
    .mcu-main     { padding: 0 16px 60px; }
    .mcu-sidebar  { grid-template-columns: 1fr; }
    .mcu-profile-grid { grid-template-columns: 1fr; }
    .mcu-tabs-bar { flex-direction: column; }
    .mcu-hero-stat  { min-width: 70px; padding: 12px 14px; }
    .mcu-hero-stat strong { font-size: 22px; }
}
</style>
@endpush

@section('content')
@php
$courseImages = [
    'bpm'          => 'https://images.unsplash.com/photo-1486297678162-eb2a19b0a32d?w=480&h=200&fit=crop',
    'iso'          => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=480&h=200&fit=crop',
    'microbiolog'  => 'https://images.unsplash.com/photo-1582719471384-894fbb16e074?w=480&h=200&fit=crop',
    'artesanal'        => 'https://images.unsplash.com/photo-1559598467-f8b76c8155d0?w=480&h=200&fit=crop',
    'fermentado'        => 'https://images.unsplash.com/photo-1488477181946-6428a0291777?w=480&h=200&fit=crop',
    'haccp'        => 'https://images.unsplash.com/photo-1607623814075-e51df1bdc82f?w=480&h=200&fit=crop',
    'pasteur'      => 'https://images.unsplash.com/photo-1550583724-b2692b85b150?w=480&h=200&fit=crop',
    'fisicoquim'   => 'https://images.unsplash.com/photo-1576867757603-05b134ebc379?w=480&h=200&fit=crop',
    'inocuidad'    => 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?w=480&h=200&fit=crop',
    'default'      => 'https://images.unsplash.com/photo-1550583724-b2692b85b150?w=480&h=200&fit=crop',
];

function getCourseImg($name, $map) {
    $lower = mb_strtolower($name);
    foreach ($map as $key => $url) {
        if (str_contains($lower, $key)) return $url;
    }
    return $map['default'];
}

$progreso = ['pendiente' => 10, 'pagado' => 45, 'completado' => 100];
@endphp

<div class="mcu">

    {{-- ══ HERO ══ --}}
    <div class="mcu-hero">
        <div class="mcu-hero-blob mcu-hero-blob-1"></div>
        <div class="mcu-hero-blob mcu-hero-blob-2"></div>
        <div class="mcu-hero-inner">
            <div class="mcu-hero-left">
                <div class="mcu-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                <div class="mcu-hero-info">
                    @if (session('status'))
                        <div style="font-size:12px;background:rgba(255,255,255,.15);padding:5px 12px;border-radius:20px;color:#fff;margin-bottom:8px;display:inline-block;">
                            ✓ {{ session('status') }}
                        </div>
                    @endif
                    <div class="mcu-hero-name">{{ \Illuminate\Support\Str::words($user->name, 3, '') }}</div>
                    <div class="mcu-hero-sub">{{ $user->email }}</div>
                    <div class="mcu-hero-tags">
                        <span class="mcu-hero-tag">
                            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            Estudiante
                        </span>
                        <span class="mcu-hero-tag">
                            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            Miembro desde {{ $user->created_at->format('M Y') }}
                        </span>
                        @if ($user->dni)
                        <span class="mcu-hero-tag">
                            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                            DNI {{ $user->dni }}
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="mcu-hero-right">
                <div class="mcu-hero-stat">
                    <strong>{{ $enrollments->count() }}</strong>
                    <span>Inscritos</span>
                </div>
                <div class="mcu-hero-stat">
                    <strong>{{ $enrollments->whereIn('status', ['activo', 'completado'])->count() }}</strong>
                    <span>Pagados</span>
                </div>
                <div class="mcu-hero-stat">
                    <strong>{{ $enrollments->where('status','completado')->count() }}</strong>
                    <span>Completados</span>
                </div>
                <div class="mcu-hero-stat">
                    <strong>S/ {{ number_format($enrollments->whereIn('status', ['activo', 'completado'])->sum(function($e) { return $e->course->price ?? 0; }), 0) }}</strong>
                    <span>Invertido</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ MAIN ══ --}}
    <div class="mcu-main">

        {{-- Columna principal --}}
        <div>
            {{-- Tabs --}}
            <div class="mcu-tabs-bar" role="tablist">
                <button class="mcu-tab active" onclick="switchTab('cursos', this)">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                    Mis Cursos
                    <span class="tab-badge">{{ $enrollments->count() }}</span>
                </button>
                <button class="mcu-tab" onclick="switchTab('perfil', this)">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    Mi Perfil
                </button>
                <button class="mcu-tab" onclick="switchTab('logros', this)">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/></svg>
                    Logros
                </button>
            </div>

            {{-- Panel: Mis Cursos --}}
            <div id="panel-cursos" class="mcu-panel active">
                @if ($enrollments->isEmpty())
                    <div class="mcu-courses-grid">
                        <div class="mcu-empty">
                            <div class="mcu-empty-icon">
                                <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                            </div>
                            <h3>Aún no tienes cursos inscritos</h3>
                            <p>Explora nuestro catálogo de 9 programas especializados en el sector alimentario peruano.</p>
                            <a href="{{ route('cursos') }}" class="mcu-empty-btn">
                                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
                                Explorar catálogo
                            </a>
                        </div>
                    </div>
                @else
                    <div class="mcu-courses-grid">
                        @foreach ($enrollments as $e)
                        @php
                            $img  = getCourseImg($e->course->name, $courseImages);
                            $pct  = (int) ($e->progress ?? 0);
                        @endphp
                        <div class="mcu-course-card">
                            <div class="mcu-course-img">
                                <img src="{{ $img }}" alt="{{ $e->course->name }}" loading="lazy">
                                <span class="mcu-course-status-badge {{ $e->status }}">{{ ucfirst($e->status) }}</span>
                            </div>
                            <div class="mcu-course-body">
                                <span class="mcu-course-level {{ strtolower($e->course->level) }}">{{ ucfirst($e->course->level) }}</span>
                                <div class="mcu-course-name">{{ $e->course->name }}</div>
                                <div class="mcu-progress-wrap">
                                    <div class="mcu-progress-label">
                                        <span>Progreso</span>
                                        <span>{{ $pct }}%</span>
                                    </div>
                                    <div class="mcu-progress-bar">
                                        <div class="mcu-progress-fill" style="width:{{ $pct }}%"></div>
                                    </div>
                                </div>
                                <div class="mcu-course-footer" style="margin-bottom: 12px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                                    <span class="mcu-course-price">S/ {{ number_format($e->course->price, 0) }}</span>
                                    <span class="mcu-course-date">{{ $e->created_at->format('d/m/Y') }}</span>
                                </div>
                                <div style="margin-top: auto;">
                                    @if(in_array($e->status, ['activo', 'completado']))
                                        <a href="{{ route('mi-cuenta.cursos.show', $e->course->slug) }}" class="mcu-empty-btn" style="display: block; text-align: center; font-size: 13px; padding: 10px 12px; border-radius: 8px; text-decoration: none;">
                                            Continuar Aprendiendo
                                        </a>
                                    @elseif($e->status === 'pendiente')
                                        <a href="{{ route('checkout') }}" class="mcu-empty-btn" style="display: block; text-align: center; font-size: 13px; padding: 10px 12px; border-radius: 8px; background: #d97706; text-decoration: none;">
                                            Proceder al Pago
                                        </a>
                                    @elseif($e->status === 'suspendido')
                                        <button disabled class="mcu-empty-btn" style="display: block; width: 100%; text-align: center; font-size: 13px; padding: 10px 12px; border-radius: 8px; background: #64748b; cursor: not-allowed; opacity: 0.75;">
                                            Acceso Suspendido
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div style="margin-top:20px;text-align:center;">
                        <a href="{{ route('cursos') }}" style="display:inline-flex;align-items:center;gap:7px;background:#fff;border:1.5px solid #bfdbfe;color:#1e40af;padding:11px 24px;border-radius:10px;font-size:13.5px;font-weight:700;text-decoration:none;">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
                            Inscribirme en más cursos
                        </a>
                    </div>
                @endif
            </div>

            {{-- Panel: Mi Perfil --}}
            <div id="panel-perfil" class="mcu-panel">
                <div class="mcu-profile-full">
                    <div class="mcu-profile-full-head">
                        <div class="mcu-profile-full-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                        <div class="mcu-profile-full-name">{{ $user->name }}</div>
                    </div>
                    <div class="mcu-profile-full-body">
                        <div class="mcu-profile-grid">
                            <div class="mcu-profile-field">
                                <label>Nombre completo</label>
                                <div class="value">{{ $user->name }}</div>
                            </div>
                            <div class="mcu-profile-field">
                                <label>Correo electrónico</label>
                                <div class="value">{{ $user->email }}</div>
                            </div>
                            <div class="mcu-profile-field">
                                <label>DNI / RUC</label>
                                <div class="value">{{ $user->dni ?? 'No registrado' }}</div>
                            </div>
                            <div class="mcu-profile-field">
                                <label>Teléfono</label>
                                <div class="value">{{ $user->phone ?? 'No registrado' }}</div>
                            </div>
                            <div class="mcu-profile-field">
                                <label>Miembro desde</label>
                                <div class="value">{{ $user->created_at->format('d \d\e F \d\e Y') }}</div>
                            </div>
                            <div class="mcu-profile-field">
                                <label>Estado de cuenta</label>
                                <div class="value" style="color:#15803d;">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="vertical-align:middle;margin-right:4px;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                    Activa
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Panel: Logros --}}
            <div id="panel-logros" class="mcu-panel">
                <div class="mcu-card">
                    <div class="mcu-card-head">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/></svg>
                        Mis logros
                    </div>
                    <div class="mcu-card-body">
                        <div class="mcu-logro {{ $enrollments->count() >= 1 ? '' : 'mcu-logro-lock' }}">
                            <div class="mcu-logro-icon" style="background:#fef3c7;">🎓</div>
                            <div>
                                <div class="mcu-logro-title">Primer Paso</div>
                                <div class="mcu-logro-desc">Inscríbete en tu primer curso</div>
                            </div>
                            @if ($enrollments->count() >= 1)
                                <svg style="margin-left:auto;color:#22c55e" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            @endif
                        </div>
                        <div class="mcu-logro {{ $enrollments->where('status','pagado')->count() >= 1 ? '' : 'mcu-logro-lock' }}">
                            <div class="mcu-logro-icon" style="background:#dcfce7;">💳</div>
                            <div>
                                <div class="mcu-logro-title">Inversión en ti</div>
                                <div class="mcu-logro-desc">Realiza tu primer pago</div>
                            </div>
                            @if ($enrollments->where('status','pagado')->count() >= 1)
                                <svg style="margin-left:auto;color:#22c55e" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            @endif
                        </div>
                        <div class="mcu-logro {{ $enrollments->count() >= 3 ? '' : 'mcu-logro-lock' }}">
                            <div class="mcu-logro-icon" style="background:#dbeafe;">📚</div>
                            <div>
                                <div class="mcu-logro-title">Aprendiz Dedicado</div>
                                <div class="mcu-logro-desc">Inscríbete en 3 o más cursos</div>
                            </div>
                            @if ($enrollments->count() >= 3)
                                <svg style="margin-left:auto;color:#22c55e" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            @endif
                        </div>
                        <div class="mcu-logro {{ $enrollments->where('status','completado')->count() >= 1 ? '' : 'mcu-logro-lock' }}">
                            <div class="mcu-logro-icon" style="background:#f3e8ff;">🏆</div>
                            <div>
                                <div class="mcu-logro-title">Certificado</div>
                                <div class="mcu-logro-desc">Completa tu primer curso</div>
                            </div>
                            @if ($enrollments->where('status','completado')->count() >= 1)
                                <svg style="margin-left:auto;color:#22c55e" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══ SIDEBAR ══ --}}
        <aside class="mcu-sidebar">

            {{-- Perfil rápido --}}
            <div class="mcu-card">
                <div class="mcu-card-head">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    Perfil
                </div>
                <div class="mcu-card-body">
                    <div class="mcu-profile-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                    <div class="mcu-profile-name">{{ \Illuminate\Support\Str::limit($user->name, 26) }}</div>
                    <div class="mcu-profile-email">{{ $user->email }}</div>
                    <div class="mcu-profile-row">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                        <span class="mcu-profile-key">DNI</span>
                        <span class="mcu-profile-val">{{ $user->dni ?? '—' }}</span>
                    </div>
                    <div class="mcu-profile-row">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.96a16 16 0 0 0 6.13 6.13l.96-.96a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        <span class="mcu-profile-key">Teléfono</span>
                        <span class="mcu-profile-val">{{ $user->phone ?? '—' }}</span>
                    </div>
                    <div class="mcu-profile-row">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        <span class="mcu-profile-key">Miembro</span>
                        <span class="mcu-profile-val">{{ $user->created_at->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>

            {{-- Acciones rápidas --}}
            <div class="mcu-card">
                <div class="mcu-card-head">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    Acciones rápidas
                </div>
                <div class="mcu-card-body">
                    <div class="mcu-quick-grid">
                        <a href="{{ route('cursos') }}" class="mcu-quick-btn">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                            Ver cursos
                        </a>
                        <a href="{{ route('checkout') }}" class="mcu-quick-btn">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                            Carrito
                        </a>
                        <a href="{{ route('contacto') }}" class="mcu-quick-btn">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                            Soporte
                        </a>
                        <a href="{{ route('nosotros') }}" class="mcu-quick-btn">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                            Nosotros
                        </a>
                    </div>
                </div>
            </div>

            {{-- Progreso general --}}
            <div class="mcu-card">
                <div class="mcu-card-head">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    Tu progreso
                </div>
                <div class="mcu-card-body">
                    @php $total = $enrollments->count(); $paid = $enrollments->whereIn('status', ['activo', 'completado'])->count(); $done = $enrollments->where('status','completado')->count(); @endphp
                    @if ($total === 0)
                        <p style="font-size:13px;color:#94a3b8;text-align:center;padding:8px 0;">Sin cursos inscritos aún.</p>
                    @else
                        <div style="margin-bottom:14px;">
                            <div style="display:flex;justify-content:space-between;font-size:12px;color:#64748b;font-weight:600;margin-bottom:5px;">
                                <span>Cursos pagados</span><span>{{ $paid }}/{{ $total }}</span>
                            </div>
                            <div style="height:6px;background:#f1f5f9;border-radius:10px;overflow:hidden;">
                                <div style="width:{{ $total > 0 ? round($paid/$total*100) : 0 }}%;height:100%;background:linear-gradient(90deg,#2563eb,#0ea5e9);border-radius:10px;transition:width .6s;"></div>
                            </div>
                        </div>
                        <div>
                            <div style="display:flex;justify-content:space-between;font-size:12px;color:#64748b;font-weight:600;margin-bottom:5px;">
                                <span>Cursos completados</span><span>{{ $done }}/{{ $total }}</span>
                            </div>
                            <div style="height:6px;background:#f1f5f9;border-radius:10px;overflow:hidden;">
                                <div style="width:{{ $total > 0 ? round($done/$total*100) : 0 }}%;height:100%;background:linear-gradient(90deg,#22c55e,#86efac);border-radius:10px;transition:width .6s;"></div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </aside>
    </div>
</div>
@endsection

@push('scripts')
<script>
function switchTab(name, btn) {
    document.querySelectorAll('.mcu-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.mcu-panel').forEach(p => p.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('panel-' + name).classList.add('active');
}
</script>
@endpush

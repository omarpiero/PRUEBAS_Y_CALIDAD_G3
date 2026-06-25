@extends('layouts.app')

@section('title', 'Inicio')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Noto+Serif:wght@600;700&display=swap" rel="stylesheet">
<style>
/* ══════════════ VARIABLES LOCALES ══════════════ */
.hp {
    --blue:      #0f2a5e;
    --blue-mid:  #1e40af;
    --blue-btn:  #0284c7;
    --sky:       #0ea5e9;
    --sky-light: #e0f2fe;
    --sky-pale:  #f0f9ff;
    --border:    rgba(2,132,199,.14);
    --text:      #0b1e38;
    --muted:     #4a6378;
    font-family: 'Poppins', sans-serif;
}

/* ══════════════ HERO ══════════════ */
.hp-hero {
    position: relative;
    min-height: 100vh;
    display: flex;
    align-items: center;
    overflow: hidden;
    background: var(--blue);
}
.hp-hero-bg {
    position: absolute; inset: 0;
    background-image: url("{{ asset('img/planta-jmjs.png') }}");
    background-size: cover;
    background-position: center;
    opacity: .18;
    will-change: transform;
    animation: hp-kb 20s ease-in-out infinite alternate;
}
@keyframes hp-kb {
    from { transform: scale(1) translate(0,0); }
    to   { transform: scale(1.07) translate(-1%,1%); }
}
.hp-hero-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(110deg, rgba(10,25,70,.92) 0%, rgba(10,40,100,.72) 50%, rgba(14,165,233,.18) 100%);
}
/* Partículas decorativas */
.hp-hero-blob {
    position: absolute;
    border-radius: 50%;
    filter: blur(80px);
    opacity: .25;
    pointer-events: none;
}
.hp-hero-blob-1 { width: 500px; height: 500px; background: #0ea5e9; top: -120px; right: -100px; }
.hp-hero-blob-2 { width: 360px; height: 360px; background: #3b82f6; bottom: -80px; left: -60px; }

.hp-hero-inner {
    position: relative; z-index: 2;
    max-width: 1280px; margin: 0 auto;
    padding: 80px 64px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 72px;
    align-items: center;
    width: 100%;
}
.hp-hero-eyebrow {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(14,165,233,.2);
    border: 1px solid rgba(14,165,233,.4);
    color: #7dd3fc;
    font-size: 12px; font-weight: 700;
    letter-spacing: 1px; text-transform: uppercase;
    padding: 6px 16px; border-radius: 30px;
    margin-bottom: 22px;
}
.hp-hero h1 {
    font-family: 'Noto Serif', serif;
    font-size: clamp(34px, 4.5vw, 58px);
    font-weight: 700;
    color: #fff;
    line-height: 1.12;
    margin-bottom: 20px;
    letter-spacing: -.5px;
}
.hp-hero h1 span { color: #7dd3fc; }
.hp-hero-lead {
    font-size: 17px; color: rgba(255,255,255,.75);
    line-height: 1.75; margin-bottom: 36px; max-width: 500px;
}
.hp-hero-actions { display: flex; gap: 14px; flex-wrap: wrap; }
.hp-btn-primary {
    display: inline-flex; align-items: center; gap: 8px;
    background: var(--blue-btn); color: #fff;
    padding: 14px 28px; border-radius: 10px;
    font-size: 14px; font-weight: 700; text-decoration: none;
    box-shadow: 0 12px 30px rgba(2,132,199,.4);
    transition: all .2s;
}
.hp-btn-primary:hover { background: #075985; transform: translateY(-2px); box-shadow: 0 18px 40px rgba(2,132,199,.5); }
.hp-btn-outline {
    display: inline-flex; align-items: center; gap: 8px;
    border: 1.5px solid rgba(255,255,255,.4); color: #fff;
    padding: 14px 28px; border-radius: 10px;
    font-size: 14px; font-weight: 600; text-decoration: none;
    backdrop-filter: blur(6px);
    transition: all .2s;
}
.hp-btn-outline:hover { background: rgba(255,255,255,.12); border-color: #fff; }

/* Hero right: stats card */
.hp-hero-card {
    background: rgba(255,255,255,.07);
    border: 1px solid rgba(255,255,255,.14);
    backdrop-filter: blur(12px);
    border-radius: 20px;
    padding: 36px;
    display: grid; gap: 20px;
}
.hp-hero-img-wrap {
    border-radius: 14px; overflow: hidden;
    height: 220px; position: relative;
}
.hp-hero-img-wrap img { width: 100%; height: 100%; object-fit: cover; }
.hp-hero-img-wrap::after {
    content: '';
    position: absolute; inset: 0;
    background: linear-gradient(180deg, transparent 40%, rgba(10,25,70,.6));
}
.hp-hero-stats-row {
    display: grid; grid-template-columns: repeat(3,1fr); gap: 14px;
}
.hp-hero-stat {
    background: rgba(255,255,255,.08);
    border: 1px solid rgba(255,255,255,.1);
    border-radius: 12px; padding: 16px;
    text-align: center;
}
.hp-hero-stat strong { display: block; font-size: 26px; font-weight: 800; color: #7dd3fc; line-height: 1; }
.hp-hero-stat span   { font-size: 11px; color: rgba(255,255,255,.6); margin-top: 4px; display: block; }

/* ══════════════ TRUST BAR ══════════════ */
.hp-trust {
    background: var(--blue-mid);
    padding: 18px 64px;
}
.hp-trust-inner {
    max-width: 1280px; margin: 0 auto;
    display: flex; align-items: center; justify-content: space-between;
    gap: 24px; flex-wrap: wrap;
}
.hp-trust-item {
    display: flex; align-items: center; gap: 10px;
    color: rgba(255,255,255,.85); font-size: 13px; font-weight: 600;
}
.hp-trust-item svg { color: #7dd3fc; flex-shrink: 0; }
.hp-trust-divider { width: 1px; height: 24px; background: rgba(255,255,255,.2); }

/* ══════════════ SECCIONES GENÉRICAS ══════════════ */
.hp-section {
    padding: 96px 64px;
    max-width: 1280px; margin: 0 auto;
}
.hp-section-alt { background: var(--sky-pale); }
.hp-section-alt .hp-section { max-width: none; margin: 0; padding: 96px 0; }
.hp-section-alt .hp-section-inner { max-width: 1280px; margin: 0 auto; padding: 0 64px; }

.hp-head { text-align: center; margin-bottom: 56px; }
.hp-head-tag {
    display: inline-block; background: var(--sky-light); color: #075985;
    font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase;
    padding: 5px 14px; border-radius: 30px; margin-bottom: 14px;
}
.hp-head h2 {
    font-family: 'Noto Serif', serif;
    font-size: clamp(26px, 3vw, 38px);
    font-weight: 700; color: var(--blue); margin-bottom: 12px;
}
.hp-head p { color: var(--muted); font-size: 16px; max-width: 540px; margin: 0 auto; line-height: 1.7; }

/* ══════════════ CURSOS DESTACADOS ══════════════ */
.hp-cursos-tabs {
    display: flex; gap: 8px; justify-content: center;
    flex-wrap: wrap; margin-bottom: 40px;
}
.hp-tab {
    padding: 8px 22px; border-radius: 30px;
    border: 1.5px solid var(--border);
    background: #fff; color: var(--muted);
    font-size: 13px; font-weight: 600; cursor: pointer;
    font-family: inherit; transition: all .2s;
}
.hp-tab:hover   { border-color: var(--blue-btn); color: var(--blue-btn); }
.hp-tab.active  { background: var(--blue-btn); border-color: var(--blue-btn); color: #fff; }

.hp-cursos-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(290px, 1fr));
    gap: 24px;
    margin-bottom: 40px;
}
.hp-curso-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0,0,0,.06);
    transition: transform .25s, box-shadow .25s;
    display: flex; flex-direction: column;
}
.hp-curso-card:hover { transform: translateY(-5px); box-shadow: 0 16px 40px rgba(0,0,0,.11); }
.hp-curso-card.hidden { display: none; }
.hp-curso-img { position: relative; height: 180px; overflow: hidden; }
.hp-curso-img img { width: 100%; height: 100%; object-fit: cover; transition: transform .4s; }
.hp-curso-card:hover .hp-curso-img img { transform: scale(1.05); }
.hp-nivel-badge {
    position: absolute; top: 12px; right: 12px;
    padding: 4px 11px; border-radius: 20px;
    font-size: 10.5px; font-weight: 700; text-transform: uppercase; letter-spacing: .4px;
}
.hp-nivel-badge.basico     { background: #dcfce7; color: #15803d; }
.hp-nivel-badge.intermedio { background: #fef3c7; color: #92400e; }
.hp-nivel-badge.avanzado   { background: #fee2e2; color: #991b1b; }
.hp-curso-body { padding: 18px 20px 20px; display: flex; flex-direction: column; flex: 1; }
.hp-curso-cat { font-size: 10.5px; font-weight: 700; text-transform: uppercase; letter-spacing: .6px; color: var(--blue-btn); margin-bottom: 6px; }
.hp-curso-nombre { font-size: 15.5px; font-weight: 700; color: var(--text); margin-bottom: 8px; line-height: 1.35; }
.hp-curso-desc {
    font-size: 13px; color: var(--muted); line-height: 1.65; margin-bottom: 14px; flex: 1;
    display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;
}
.hp-curso-footer {
    display: flex; align-items: center; justify-content: space-between;
    padding-top: 14px; border-top: 1px solid #f1f5f9;
}
.hp-precio-val { font-size: 22px; font-weight: 800; color: var(--text); }
.hp-precio-lbl { font-size: 10px; color: #94a3b8; font-weight: 500; }
.hp-inscribir {
    background: var(--blue-btn); color: #fff;
    padding: 9px 18px; border-radius: 9px;
    font-size: 13px; font-weight: 700; text-decoration: none;
    border: none; cursor: pointer; font-family: inherit;
    transition: background .18s, transform .15s;
}
.hp-inscribir:hover { background: #075985; transform: scale(1.03); }

.hp-ver-todos {
    display: flex; justify-content: center;
}
.hp-btn-ver {
    display: inline-flex; align-items: center; gap: 8px;
    border: 1.5px solid var(--border); color: var(--blue-mid);
    background: #fff; padding: 13px 32px; border-radius: 10px;
    font-size: 14px; font-weight: 700; text-decoration: none;
    transition: all .2s;
}
.hp-btn-ver:hover { background: var(--sky-light); border-color: var(--blue-btn); }

/* ══════════════ POR QUÉ NOSOTROS ══════════════ */
.hp-features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 24px;
}
.hp-feature {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 32px 28px;
    box-shadow: 0 2px 12px rgba(0,0,0,.04);
    transition: transform .25s, box-shadow .25s;
    position: relative;
    overflow: hidden;
}
.hp-feature::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--blue-btn), var(--sky));
    transform: scaleX(0); transform-origin: left;
    transition: transform .3s;
}
.hp-feature:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(0,0,0,.09); }
.hp-feature:hover::before { transform: scaleX(1); }
.hp-feature-icon {
    width: 52px; height: 52px; border-radius: 14px;
    background: var(--sky-light); color: var(--blue-btn);
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 18px;
}
.hp-feature h3 { font-size: 16px; font-weight: 700; color: var(--text); margin-bottom: 8px; }
.hp-feature p  { font-size: 13.5px; color: var(--muted); line-height: 1.65; }

/* ══════════════ PROCESO ══════════════ */
.hp-proceso-grid {
    display: grid; grid-template-columns: repeat(3,1fr); gap: 0;
    position: relative;
}
.hp-proceso-grid::before {
    content: '';
    position: absolute; top: 36px; left: calc(16.66% + 18px); right: calc(16.66% + 18px);
    height: 2px;
    background: linear-gradient(90deg, var(--blue-btn), var(--sky));
}
.hp-paso {
    display: flex; flex-direction: column; align-items: center;
    text-align: center; padding: 0 24px;
}
.hp-paso-num {
    width: 72px; height: 72px; border-radius: 50%;
    background: linear-gradient(135deg, var(--blue-mid), var(--sky));
    color: #fff; font-size: 22px; font-weight: 800;
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 22px; position: relative; z-index: 1;
    box-shadow: 0 8px 24px rgba(2,132,199,.35);
}
.hp-paso h3 { font-size: 17px; font-weight: 700; color: var(--text); margin-bottom: 10px; }
.hp-paso p  { font-size: 14px; color: var(--muted); line-height: 1.65; }

/* ══════════════ TESTIMONIOS ══════════════ */
.hp-testi-grid {
    display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 22px;
}
.hp-testi {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 28px;
    box-shadow: 0 2px 12px rgba(0,0,0,.04);
    position: relative;
}
.hp-testi-stars {
    display: flex; gap: 3px; margin-bottom: 14px;
    color: #f59e0b;
}
.hp-testi-text { font-size: 14px; color: var(--muted); line-height: 1.75; margin-bottom: 20px; font-style: italic; }
.hp-testi-user { display: flex; align-items: center; gap: 12px; }
.hp-testi-avatar {
    width: 42px; height: 42px; border-radius: 50%;
    background: linear-gradient(135deg, var(--blue-mid), var(--sky));
    color: #fff; font-size: 15px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.hp-testi-name  { font-size: 14px; font-weight: 700; color: var(--text); }
.hp-testi-role  { font-size: 12px; color: #94a3b8; }
.hp-testi-quote {
    position: absolute; top: 20px; right: 22px;
    font-size: 64px; color: #e2e8f0; line-height: 1;
    font-family: Georgia, serif; pointer-events: none;
}

/* ══════════════ CTA FINAL ══════════════ */
.hp-cta-wrap { padding: 0 64px 96px; }
.hp-cta-box {
    max-width: 1280px; margin: 0 auto;
    background: linear-gradient(135deg, var(--blue) 0%, var(--blue-mid) 55%, var(--sky) 100%);
    border-radius: 24px; padding: 72px 56px;
    display: grid; grid-template-columns: 1fr auto;
    align-items: center; gap: 40px;
    position: relative; overflow: hidden;
}
.hp-cta-box::before {
    content: ''; position: absolute;
    top: -80px; right: -80px;
    width: 300px; height: 300px;
    border-radius: 50%; background: rgba(255,255,255,.06);
}
.hp-cta-box::after {
    content: ''; position: absolute;
    bottom: -100px; left: 200px;
    width: 260px; height: 260px;
    border-radius: 50%; background: rgba(14,165,233,.12);
}
.hp-cta-text h2 {
    font-family: 'Noto Serif', serif;
    font-size: clamp(24px, 3vw, 36px);
    font-weight: 700; color: #fff; margin-bottom: 10px;
}
.hp-cta-text p { color: rgba(255,255,255,.75); font-size: 16px; line-height: 1.6; }
.hp-cta-actions { display: flex; gap: 12px; flex-wrap: wrap; position: relative; z-index: 1; }
.hp-cta-btn-w {
    display: inline-flex; align-items: center; gap: 7px;
    background: #fff; color: var(--blue-mid);
    padding: 14px 28px; border-radius: 10px;
    font-size: 14px; font-weight: 700; text-decoration: none;
    white-space: nowrap;
    transition: all .2s;
}
.hp-cta-btn-w:hover { background: #f0f9ff; transform: translateY(-2px); }
.hp-cta-btn-b {
    display: inline-flex; align-items: center; gap: 7px;
    border: 1.5px solid rgba(255,255,255,.45); color: #fff;
    padding: 14px 28px; border-radius: 10px;
    font-size: 14px; font-weight: 700; text-decoration: none;
    white-space: nowrap;
    transition: all .2s; backdrop-filter: blur(4px);
}
.hp-cta-btn-b:hover { background: rgba(255,255,255,.12); border-color: #fff; }

/* ══════════════ RESPONSIVE ══════════════ */
@media (max-width: 1000px) {
    .hp-hero-inner    { grid-template-columns: 1fr; padding: 80px 32px; gap: 48px; }
    .hp-hero-card     { display: none; }
    .hp-section       { padding: 72px 32px; }
    .hp-trust         { padding: 16px 32px; }
    .hp-proceso-grid  { grid-template-columns: 1fr; gap: 36px; }
    .hp-proceso-grid::before { display: none; }
    .hp-cta-wrap      { padding: 0 32px 72px; }
    .hp-cta-box       { grid-template-columns: 1fr; text-align: center; padding: 48px 32px; }
    .hp-cta-actions   { justify-content: center; }
    .hp-section-alt .hp-section-inner { padding: 0 32px; }
}
@media (max-width: 640px) {
    .hp-trust-divider { display: none; }
    .hp-hero-inner    { padding: 60px 20px; }
    .hp-section       { padding: 56px 20px; }
    .hp-cta-wrap      { padding: 0 20px 60px; }
    .hp-cursos-grid   { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')
<div class="hp">

{{-- ══════════════ HERO ══════════════ --}}
<section class="hp-hero">
    <div class="hp-hero-bg"></div>
    <div class="hp-hero-overlay"></div>
    <div class="hp-hero-blob hp-hero-blob-1"></div>
    <div class="hp-hero-blob hp-hero-blob-2"></div>

    <div class="hp-hero-inner">
        <div>
            <span class="hp-hero-eyebrow">
                <svg width="12" height="12" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                Especialistas en calidad alimentaria
            </span>
            <h1>Capacítate con los <span>mejores expertos</span> del sector alimentario peruano.</h1>
            <p class="hp-hero-lead">Cursos certificados, asesorías técnicas y diagnóstico de plantas. Formación especializada en BPM, HACCP e ISO adaptada al contexto real de Huancayo y el Perú.</p>
            <div class="hp-hero-actions">
                <a href="{{ route('cursos') }}" class="hp-btn-primary">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                    Ver todos los cursos
                </a>
                <a href="{{ route('contacto') }}#contacto-formulario" class="hp-btn-outline">
                    Solicitar asesoría
                </a>
            </div>
        </div>

        <div class="hp-hero-card">
            <div class="hp-hero-img-wrap">
                <img src="{{ asset('img/planta-jmjs.png') }}" alt="Planta JM y JS Alimentos">
            </div>
            <div class="hp-hero-stats-row">
                <div class="hp-hero-stat">
                    <strong>+10</strong>
                    <span>Años de experiencia</span>
                </div>
                <div class="hp-hero-stat">
                    <strong>9</strong>
                    <span>Cursos disponibles</span>
                </div>
                <div class="hp-hero-stat">
                    <strong>100%</strong>
                    <span>Online y certificado</span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══════════════ TRUST BAR ══════════════ --}}
<div class="hp-trust">
    <div class="hp-trust-inner">
        <div class="hp-trust-item">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            Certificados reconocidos
        </div>
        <div class="hp-trust-divider"></div>
        <div class="hp-trust-item">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            BPM · HACCP · ISO 22000
        </div>
        <div class="hp-trust-divider"></div>
        <div class="hp-trust-item">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            Modalidad 100% online
        </div>
        <div class="hp-trust-divider"></div>
        <div class="hp-trust-item">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
            Docentes especializados
        </div>
        <div class="hp-trust-divider"></div>
        <div class="hp-trust-item">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            Huancayo, Perú
        </div>
    </div>
</div>

{{-- ══════════════ CURSOS DESTACADOS ══════════════ --}}
<div class="hp-section-alt">
<section class="hp-section" style="max-width:none;padding:96px 0">
<div class="hp-section-inner">
    <div class="hp-head">
        <span class="hp-head-tag">Catálogo</span>
        <h2>Cursos especializados en alimentos</h2>
        <p>Todos los programas incluyen materiales descargables, acompañamiento técnico y certificado al finalizar.</p>
    </div>

    <div class="hp-cursos-tabs">
        <button class="hp-tab active" data-hpfilter="all">Todos</button>
        <button class="hp-tab" data-hpfilter="basico">Básico</button>
        <button class="hp-tab" data-hpfilter="intermedio">Intermedio</button>
        <button class="hp-tab" data-hpfilter="avanzado">Avanzado</button>
    </div>

    <div class="hp-cursos-grid">

        <article class="hp-curso-card reveal" data-hplevel="basico">
            <div class="hp-curso-img">
                <img src="https://images.unsplash.com/photo-1486297678162-eb2a19b0a32d?w=480&h=260&fit=crop&auto=format" alt="BPM" loading="lazy">
                <span class="hp-nivel-badge basico">Básico</span>
            </div>
            <div class="hp-curso-body">
                <div class="hp-curso-cat">Calidad · Certificación</div>
                <h3 class="hp-curso-nombre">BPM en Industria Alimentaria</h3>
                <p class="hp-curso-desc">Domina las Buenas Prácticas de Manufactura aplicadas al procesamiento de alimentos. Aprenderás a identificar puntos críticos y cumplir con la normativa sanitaria peruana.</p>
                <div class="hp-curso-footer">
                    <div><div class="hp-precio-lbl">Precio</div><div class="hp-precio-val">S/ 350</div></div>
                    <a href="{{ route('cursos') }}" class="hp-inscribir">Ver curso</a>
                </div>
            </div>
        </article>

        <article class="hp-curso-card reveal" data-hplevel="intermedio">
            <div class="hp-curso-img">
                <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=480&h=260&fit=crop&auto=format" alt="ISO 9001" loading="lazy">
                <span class="hp-nivel-badge intermedio">Intermedio</span>
            </div>
            <div class="hp-curso-body">
                <div class="hp-curso-cat">Normas · ISO</div>
                <h3 class="hp-curso-nombre">Gestión de Calidad ISO 9001</h3>
                <p class="hp-curso-desc">Implementa sistemas de gestión de calidad en empresas del rubro alimentario. Incluye plantillas listas para usar, auditorías internas y preparación para certificación.</p>
                <div class="hp-curso-footer">
                    <div><div class="hp-precio-lbl">Precio</div><div class="hp-precio-val">S/ 450</div></div>
                    <a href="{{ route('cursos') }}" class="hp-inscribir">Ver curso</a>
                </div>
            </div>
        </article>

        <article class="hp-curso-card reveal" data-hplevel="basico">
            <div class="hp-curso-img">
                <img src="https://images.unsplash.com/photo-1559598467-f8b76c8155d0?w=480&h=260&fit=crop&auto=format" alt="Alimentos artesanales" loading="lazy">
                <span class="hp-nivel-badge basico">Básico</span>
            </div>
            <div class="hp-curso-body">
                <div class="hp-curso-cat">Producción · Alimentos</div>
                <h3 class="hp-curso-nombre">Procesamiento de Alimentos Artesanales</h3>
                <p class="hp-curso-desc">Aprende técnicas de procesamiento artesanal de alimentos con estándares de inocuidad aplicables a pequeña y mediana escala.</p>
                <div class="hp-curso-footer">
                    <div><div class="hp-precio-lbl">Precio</div><div class="hp-precio-val">S/ 280</div></div>
                    <a href="{{ route('cursos') }}" class="hp-inscribir">Ver curso</a>
                </div>
            </div>
        </article>

        <article class="hp-curso-card reveal" data-hplevel="avanzado">
            <div class="hp-curso-img">
                <img src="https://images.unsplash.com/photo-1607623814075-e51df1bdc82f?w=480&h=260&fit=crop&auto=format" alt="HACCP" loading="lazy">
                <span class="hp-nivel-badge avanzado">Avanzado</span>
            </div>
            <div class="hp-curso-body">
                <div class="hp-curso-cat">Inocuidad · HACCP</div>
                <h3 class="hp-curso-nombre">HACCP en Plantas de Alimentos</h3>
                <p class="hp-curso-desc">Diseño e implementación del sistema HACCP adaptado a la industria alimentaria peruana. Identificación de peligros y elaboración del plan HACCP completo.</p>
                <div class="hp-curso-footer">
                    <div><div class="hp-precio-lbl">Precio</div><div class="hp-precio-val">S/ 420</div></div>
                    <a href="{{ route('cursos') }}" class="hp-inscribir">Ver curso</a>
                </div>
            </div>
        </article>

    </div>

    <div class="hp-ver-todos">
        <a href="{{ route('cursos') }}" class="hp-btn-ver">
            Ver los 9 cursos disponibles
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
        </a>
    </div>
</div>
</section>
</div>

{{-- ══════════════ POR QUÉ NOSOTROS ══════════════ --}}
<section class="hp-section">
    <div class="hp-head">
        <span class="hp-head-tag">Nuestras ventajas</span>
        <h2>¿Por qué elegir JM y JS Alimentos?</h2>
        <p>Somos especialistas en el sector alimentario peruano con más de 10 años de experiencia técnica y formativa.</p>
    </div>
    <div class="hp-features-grid">
        <div class="hp-feature reveal">
            <div class="hp-feature-icon">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            </div>
            <h3>Contenido técnico real</h3>
            <p>Casos aplicados a plantas alimentarias peruanas. Nada genérico — todo adaptado a la normativa MINSA, SENASA y DIGESA.</p>
        </div>
        <div class="hp-feature reveal">
            <div class="hp-feature-icon">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            </div>
            <h3>Certificado reconocido</h3>
            <p>Al finalizar cada curso recibes un certificado digital con validez para acreditar tu formación ante empleadores y entidades.</p>
        </div>
        <div class="hp-feature reveal">
            <div class="hp-feature-icon">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
            <h3>A tu ritmo, 100% online</h3>
            <p>Accede desde cualquier dispositivo, en cualquier momento. Materiales descargables y grabaciones disponibles siempre.</p>
        </div>
        <div class="hp-feature reveal">
            <div class="hp-feature-icon">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <h3>Acompañamiento continuo</h3>
            <p>No estás solo. Nuestro equipo responde dudas y orienta durante y después del curso para que apliques lo aprendido.</p>
        </div>
    </div>
</section>

{{-- ══════════════ PROCESO ══════════════ --}}
<div class="hp-section-alt">
<section class="hp-section" style="max-width:none;padding:96px 0">
<div class="hp-section-inner">
    <div class="hp-head">
        <span class="hp-head-tag">¿Cómo funciona?</span>
        <h2>Tres pasos para empezar a formarte</h2>
        <p>Desde el registro hasta el certificado, el proceso es simple y está diseñado para que te enfoques en aprender.</p>
    </div>
    <div class="hp-proceso-grid">
        <div class="hp-paso reveal">
            <div class="hp-paso-num">1</div>
            <h3>Regístrate gratis</h3>
            <p>Crea tu cuenta en minutos con solo tu nombre y correo. Sin pagos ni compromisos para explorar el catálogo.</p>
        </div>
        <div class="hp-paso reveal">
            <div class="hp-paso-num">2</div>
            <h3>Elige tu curso</h3>
            <p>Explora los 9 programas disponibles, filtra por nivel y agrega al carrito los que más se ajusten a tu perfil.</p>
        </div>
        <div class="hp-paso reveal">
            <div class="hp-paso-num">3</div>
            <h3>Aprende y certifícate</h3>
            <p>Accede al contenido, completa los módulos y recibe tu certificado digital al finalizar el programa.</p>
        </div>
    </div>
</div>
</section>
</div>

{{-- ══════════════ TESTIMONIOS ══════════════ --}}
<section class="hp-section">
    <div class="hp-head">
        <span class="hp-head-tag">Testimonios</span>
        <h2>Lo que dicen nuestros estudiantes</h2>
        <p>Profesionales del sector alimentario que han mejorado sus procesos con nuestros programas.</p>
    </div>
    <div class="hp-testi-grid">
        <div class="hp-testi reveal">
            <span class="hp-testi-quote">"</span>
            <div class="hp-testi-stars">
                @for ($i = 0; $i < 5; $i++)<svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>@endfor
            </div>
            <p class="hp-testi-text">El curso de BPM fue exactamente lo que necesitaba. Los casos prácticos con plantas de alimentos de la sierra me ayudaron a implementar mejoras concretas en nuestro negocio familiar.</p>
            <div class="hp-testi-user">
                <div class="hp-testi-avatar">M</div>
                <div>
                    <div class="hp-testi-name">María Quispe Huanca</div>
                    <div class="hp-testi-role">Responsable de Calidad · Agroindustria Andina, Junín</div>
                </div>
            </div>
        </div>
        <div class="hp-testi reveal">
            <span class="hp-testi-quote">"</span>
            <div class="hp-testi-stars">
                @for ($i = 0; $i < 5; $i++)<svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>@endfor
            </div>
            <p class="hp-testi-text">Tomé el HACCP para plantas de alimentos y en 3 meses ya teníamos el plan implementado en nuestra empresa. El acompañamiento post-curso marcó la diferencia.</p>
            <div class="hp-testi-user">
                <div class="hp-testi-avatar">C</div>
                <div>
                    <div class="hp-testi-name">Carlos Mendoza Rivera</div>
                    <div class="hp-testi-role">Jefe de Planta · Alimentos del Centro, Huancayo</div>
                </div>
            </div>
        </div>
        <div class="hp-testi reveal">
            <span class="hp-testi-quote">"</span>
            <div class="hp-testi-stars">
                @for ($i = 0; $i < 5; $i++)<svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>@endfor
            </div>
            <p class="hp-testi-text">La ISO 9001 parecía inalcanzable para nuestro negocio, pero JM y JS lo hicieron comprensible y aplicable. Ahora somos una empresa con sistema de gestión real.</p>
            <div class="hp-testi-user">
                <div class="hp-testi-avatar">L</div>
                <div>
                    <div class="hp-testi-name">Lucía Flores Paredes</div>
                    <div class="hp-testi-role">Gerente General · Productora Alimentaria San Isidro</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══════════════ CTA ══════════════ --}}
<div class="hp-cta-wrap">
    <div class="hp-cta-box">
        <div class="hp-cta-text">
            <h2>¿Listo para mejorar la calidad de tu planta?</h2>
            <p>Únete a los profesionales del sector alimentario que ya trabajan con estándares más altos. Tu primera consulta es gratis.</p>
        </div>
        <div class="hp-cta-actions">
            <a href="{{ route('register') }}" class="hp-cta-btn-w">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
                Crear cuenta gratis
            </a>
            <a href="{{ route('contacto') }}#contacto-formulario" class="hp-cta-btn-b">
                Hablar con un experto
            </a>
        </div>
    </div>
</div>

</div>{{-- .hp --}}
@endsection

@push('scripts')
<script>
// Filtro de cursos en inicio
document.querySelectorAll('.hp-tab').forEach(tab => {
    tab.addEventListener('click', () => {
        document.querySelectorAll('.hp-tab').forEach(t => t.classList.remove('active'));
        tab.classList.add('active');
        const filter = tab.dataset.hpfilter;
        document.querySelectorAll('.hp-curso-card').forEach(card => {
            card.classList.toggle('hidden', filter !== 'all' && card.dataset.hplevel !== filter);
        });
    });
});
</script>
@endpush

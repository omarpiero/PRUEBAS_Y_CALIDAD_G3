@extends('layouts.app')

@section('title', 'Nosotros')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Noto+Serif:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet">
<style>
/* ── Variables ── */
.ns-page {
    --ns-green:      #0f2a5e;
    --ns-green-mid:  #1e40af;
    --ns-green-soft: #1d4ed8;
    --ns-green-bg:   #bae6fd;
    --ns-green-pale: #dbeafe;
    --ns-sky-bg:     #e0f2fe;
    --ns-cream:      #f0f7ff;
    --ns-surface:    #f1f5f9;
    --ns-border:     #bfdbfe;
    --ns-text:       #0f172a;
    --ns-muted:      #334155;
    --ns-tan:        #e2e8f0;

    font-family: 'Plus Jakarta Sans', sans-serif;
    color: var(--ns-text);
    background: var(--ns-cream);
}

/* ── Hero ── */
.ns-hero {
    position: relative;
    height: 92vh;
    min-height: 580px;
    max-height: 860px;
    display: flex;
    align-items: center;
    overflow: hidden;
}
.ns-hero-img {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    will-change: transform;
    animation: ns-kb 18s ease-in-out infinite alternate;
}
@keyframes ns-kb {
    from { transform: scale(1)    translate(0, 0); }
    to   { transform: scale(1.08) translate(-1.5%, 1%); }
}
.ns-hero-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(100deg, rgba(10,28,80,.75) 0%, rgba(10,28,80,.40) 55%, transparent 100%);
}
.ns-hero-content {
    position: relative;
    z-index: 2;
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 64px;
    width: 100%;
}
.ns-hero-tag {
    display: inline-block;
    background: rgba(186,230,253,.18);
    border: 1px solid rgba(186,230,253,.38);
    color: var(--ns-green-bg);
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 1px;
    text-transform: uppercase;
    padding: 6px 16px;
    border-radius: 30px;
    margin-bottom: 20px;
    backdrop-filter: blur(4px);
}
.ns-hero h1 {
    font-family: 'Noto Serif', serif;
    font-size: clamp(36px, 5vw, 62px);
    font-weight: 700;
    color: #fff;
    line-height: 1.15;
    max-width: 640px;
    margin-bottom: 20px;
}
.ns-hero p {
    font-size: 18px;
    color: rgba(255,255,255,.82);
    max-width: 520px;
    line-height: 1.7;
    margin-bottom: 36px;
}
.ns-hero-btns { display: flex; gap: 14px; flex-wrap: wrap; }
.ns-btn-primary {
    display: inline-block;
    background: var(--ns-green-mid);
    color: #fff;
    padding: 14px 28px;
    border-radius: 30px;
    font-weight: 600;
    font-size: 14px;
    text-decoration: none;
    transition: background .2s, transform .15s;
}
.ns-btn-primary:hover { background: var(--ns-green); transform: scale(1.03); }
.ns-btn-outline {
    display: inline-block;
    border: 1.5px solid rgba(255,255,255,.55);
    color: #fff;
    padding: 14px 28px;
    border-radius: 30px;
    font-weight: 600;
    font-size: 14px;
    text-decoration: none;
    transition: all .2s;
    backdrop-filter: blur(4px);
}
.ns-btn-outline:hover { background: rgba(255,255,255,.12); border-color: #fff; }

/* ── Historia ── */
.ns-historia {
    padding: 96px 64px;
    max-width: 1280px;
    margin: 0 auto;
}
.ns-tag {
    display: inline-block;
    padding: 5px 16px;
    border-radius: 30px;
    background: var(--ns-sky-bg);
    color: #1e3a8a;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: .8px;
    text-transform: uppercase;
    margin-bottom: 16px;
}
.ns-historia-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 80px;
    align-items: center;
}
.ns-historia h2 {
    font-family: 'Noto Serif', serif;
    font-size: clamp(28px, 3vw, 40px);
    font-weight: 700;
    color: var(--ns-green);
    margin-bottom: 24px;
    line-height: 1.25;
}
.ns-historia p {
    color: var(--ns-muted);
    font-size: 16px;
    line-height: 1.75;
    margin-bottom: 18px;
}
.ns-historia-img-wrap {
    position: relative;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 24px 60px rgba(0,0,0,.14);
}
.ns-historia-img-wrap img {
    width: 100%;
    aspect-ratio: 1/1;
    object-fit: cover;
    display: block;
    transition: transform .6s ease;
}
.ns-historia-img-wrap:hover img { transform: scale(1.04); }
.ns-img-badge {
    position: absolute;
    bottom: 20px;
    left: 20px;
    background: rgba(10,28,80,.85);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(186,230,253,.25);
    color: #fff;
    padding: 10px 16px;
    border-radius: 12px;
    font-size: 13px;
}
.ns-img-badge strong { display: block; font-size: 18px; font-weight: 700; color: var(--ns-green-bg); }

/* ── Proceso ── */
.ns-proceso {
    background: var(--ns-surface);
    padding: 96px 64px;
}
.ns-proceso-inner { max-width: 1280px; margin: 0 auto; }
.ns-section-head {
    text-align: center;
    margin-bottom: 56px;
}
.ns-section-head h2 {
    font-family: 'Noto Serif', serif;
    font-size: clamp(28px, 3vw, 38px);
    font-weight: 700;
    color: var(--ns-green);
    margin: 10px 0 12px;
}
.ns-section-head p { color: var(--ns-muted); font-size: 16px; max-width: 500px; margin: 0 auto; }

.ns-proceso-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
}
.ns-proceso-card {
    background: #fff;
    border-radius: 20px;
    padding: 40px 32px;
    border: 1px solid rgba(191,219,254,.5);
    box-shadow: 0 2px 16px rgba(0,0,0,.05);
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    transition: transform .25s, box-shadow .25s;
}
.ns-proceso-card:hover { transform: translateY(-5px); box-shadow: 0 12px 36px rgba(0,0,0,.10); }
.ns-proceso-icon {
    width: 68px;
    height: 68px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 24px;
    font-size: 32px;
}
.ns-proceso-icon.green  { background: var(--ns-green-pale); color: var(--ns-green); }
.ns-proceso-icon.sky    { background: var(--ns-sky-bg);     color: #075985; }
.ns-proceso-icon.tan    { background: var(--ns-tan);        color: #1e3a8a; }
.ns-proceso-card h3 {
    font-family: 'Noto Serif', serif;
    font-size: 20px;
    font-weight: 600;
    color: var(--ns-green);
    margin-bottom: 12px;
}
.ns-proceso-card p { color: var(--ns-muted); font-size: 15px; line-height: 1.65; }

/* ── Valores bento ── */
.ns-valores {
    padding: 96px 64px;
    max-width: 1280px;
    margin: 0 auto;
}
.ns-valores h2 {
    font-family: 'Noto Serif', serif;
    font-size: clamp(28px, 3vw, 38px);
    font-weight: 700;
    color: var(--ns-green);
    text-align: center;
    margin-bottom: 48px;
}
.ns-bento {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    grid-template-rows: repeat(2, 280px);
    gap: 20px;
}
.ns-bento-item {
    border-radius: 20px;
    padding: 36px;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    overflow: hidden;
    position: relative;
    transition: transform .25s;
}
.ns-bento-item:hover { transform: scale(1.015); }
.ns-bento-item h3 {
    font-family: 'Noto Serif', serif;
    font-size: 22px;
    font-weight: 700;
    margin-bottom: 10px;
}
.ns-bento-item p { font-size: 14px; line-height: 1.65; }
.ns-bento-item .bento-icon {
    position: absolute;
    top: 24px;
    right: 24px;
    opacity: .18;
    font-size: 80px !important;
}

/* Bento: Calidad — grande verde oscuro */
.ns-bento-calidad {
    grid-column: span 2;
    grid-row: span 2;
    background: var(--ns-green);
    color: #fff;
    justify-content: flex-end;
}
.ns-bento-calidad p { opacity: .85; font-size: 16px; }

/* Bento: Claridad */
.ns-bento-claridad {
    grid-column: span 2;
    background: var(--ns-sky-bg);
    color: #075985;
}
/* Bento: Cercanía */
.ns-bento-cercania {
    grid-column: span 1;
    background: var(--ns-tan);
    color: #1e3a8a;
}
/* Bento: Mejora */
.ns-bento-mejora {
    grid-column: span 1;
    background: var(--ns-green-pale);
    color: var(--ns-green);
}

/* ── Misión / Visión ── */
.ns-mv {
    background: var(--ns-green);
    padding: 80px 64px;
}
.ns-mv-inner {
    max-width: 1280px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 48px;
}
.ns-mv-card {
    border: 1px solid rgba(186,230,253,.22);
    border-radius: 20px;
    padding: 40px;
    background: rgba(186,230,253,.08);
    backdrop-filter: blur(4px);
}
.ns-mv-card .mv-kicker {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 1.2px;
    text-transform: uppercase;
    color: var(--ns-green-bg);
    margin-bottom: 14px;
}
.ns-mv-card h3 {
    font-family: 'Noto Serif', serif;
    font-size: 24px;
    font-weight: 700;
    color: #fff;
    margin-bottom: 14px;
}
.ns-mv-card p { color: rgba(255,255,255,.75); font-size: 15px; line-height: 1.7; }

/* ── CTA ── */
.ns-cta {
    padding: 80px 64px 100px;
    max-width: 1280px;
    margin: 0 auto;
}
.ns-cta-box {
    background: var(--ns-surface);
    border-radius: 28px;
    padding: 80px 48px;
    text-align: center;
    position: relative;
    overflow: hidden;
}
.ns-cta-box h2 {
    font-family: 'Noto Serif', serif;
    font-size: clamp(26px, 3vw, 36px);
    font-weight: 700;
    color: var(--ns-green);
    margin-bottom: 16px;
}
.ns-cta-box p { color: var(--ns-muted); font-size: 17px; max-width: 560px; margin: 0 auto 40px; line-height: 1.7; }
.ns-cta-blob1 {
    position: absolute;
    bottom: -80px; left: -80px;
    width: 260px; height: 260px;
    background: radial-gradient(circle, rgba(186,230,253,.45), transparent 70%);
    border-radius: 50%;
}
.ns-cta-blob2 {
    position: absolute;
    top: -80px; right: -80px;
    width: 260px; height: 260px;
    background: radial-gradient(circle, rgba(147,197,253,.35), transparent 70%);
    border-radius: 50%;
}

/* ── Responsive ── */
@media (max-width: 900px) {
    .ns-hero-content  { padding: 0 24px; }
    .ns-historia      { padding: 64px 24px; }
    .ns-historia-grid { grid-template-columns: 1fr; gap: 40px; }
    .ns-proceso       { padding: 64px 24px; }
    .ns-proceso-grid  { grid-template-columns: 1fr; }
    .ns-valores       { padding: 64px 24px; }
    .ns-bento         { grid-template-columns: 1fr 1fr; grid-template-rows: auto; height: auto; }
    .ns-bento-calidad { grid-column: span 2; grid-row: span 1; min-height: 260px; }
    .ns-mv            { padding: 64px 24px; }
    .ns-mv-inner      { grid-template-columns: 1fr; }
    .ns-cta           { padding: 64px 24px 80px; }
    .ns-cta-box       { padding: 56px 28px; }
}
@media (max-width: 560px) {
    .ns-bento { grid-template-columns: 1fr; }
    .ns-bento-calidad,
    .ns-bento-claridad { grid-column: span 1; }
}
</style>
@endpush

@section('content')
<div class="ns-page">

    {{-- ── HERO ── --}}
    <section class="ns-hero">
        <img class="ns-hero-img"
             src="https://images.unsplash.com/photo-1500595046743-cd271d694d30?auto=format&fit=crop&w=1600&q=85"
             alt="Producción alimentaria JM y JS">
        <div class="ns-hero-overlay"></div>
        <div class="ns-hero-content">
            <span class="ns-hero-tag">Nuestra empresa</span>
            <h1>Conocimiento técnico con atención cercana.</h1>
            <p>JM y JS Alimentos acompaña a empresas y profesionales que quieren mejorar su calidad sin perder claridad en el proceso.</p>
            <div class="ns-hero-btns">
                <a href="{{ route('cursos') }}" class="ns-btn-primary">Ver cursos</a>
                <a href="{{ route('contacto') }}#contacto-formulario" class="ns-btn-outline">Hablar con nosotros</a>
            </div>
        </div>
    </section>

    {{-- ── HISTORIA ── --}}
    <section class="ns-historia">
        <div class="ns-historia-grid">
            <div>
                <span class="ns-tag">Nuestra Historia</span>
                <h2>Desde Huancayo para el sector alimentario peruano.</h2>
                <p>JM y JS Alimentos nació de la convicción de que las empresas del sector alimentario merecen asesoría técnica de calidad y accesible. Desde Huancayo, hemos acompañado a decenas de plantas y productores a mejorar sus procesos.</p>
                <p>Nuestra experiencia en BPM, HACCP e ISO nos permite ofrecer soluciones adaptadas al contexto real del productor peruano, sin fórmulas genéricas y con resultados medibles.</p>
            </div>
            <div class="ns-historia-img-wrap">
                <img src="https://images.unsplash.com/photo-1560493676-04071c5f467b?auto=format&fit=crop&w=800&q=85"
                     alt="Planta de alimentos JM y JS">
                <div class="ns-img-badge">
                    <strong>+10 años</strong>
                    Experiencia en el sector alimentario peruano
                </div>
            </div>
        </div>
    </section>

    {{-- ── PROCESO ── --}}
    <section class="ns-proceso">
        <div class="ns-proceso-inner">
            <div class="ns-section-head">
                <span class="ns-tag">Metodología</span>
                <h2>Del diagnóstico a la mejora continua.</h2>
                <p>Un proceso claro y transparente que garantiza resultados desde la primera sesión.</p>
            </div>
            <div class="ns-proceso-grid">
                <div class="ns-proceso-card">
                    <div class="ns-proceso-icon green">
                        <span class="material-symbols-outlined">search</span>
                    </div>
                    <h3>Diagnóstico Inicial</h3>
                    <p>Evaluamos el estado actual de tu planta o proceso con criterios BPM, HACCP e ISO para identificar las brechas reales.</p>
                </div>
                <div class="ns-proceso-card">
                    <div class="ns-proceso-icon sky">
                        <span class="material-symbols-outlined">verified</span>
                    </div>
                    <h3>Plan de Mejora</h3>
                    <p>Diseñamos un plan personalizado con acciones concretas, priorizadas según el impacto en calidad e inocuidad del producto.</p>
                </div>
                <div class="ns-proceso-card">
                    <div class="ns-proceso-icon tan">
                        <span class="material-symbols-outlined">rocket_launch</span>
                    </div>
                    <h3>Acompañamiento</h3>
                    <p>Te acompañamos en la implementación con capacitaciones, visitas técnicas y soporte continuo hasta alcanzar los objetivos.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ── VALORES BENTO ── --}}
    <section class="ns-valores">
        <h2>Valores que el cliente puede notar.</h2>
        <div class="ns-bento">

            {{-- Calidad — grande --}}
            <div class="ns-bento-item ns-bento-calidad">
                <span class="material-symbols-outlined bento-icon">eco</span>
                <h3>Calidad</h3>
                <p>Criterios BPM e ISO adaptados al contexto real de cada cliente. Nuestro trabajo no termina con el informe — termina cuando el cliente ve los resultados.</p>
            </div>

            {{-- Claridad --}}
            <div class="ns-bento-item ns-bento-claridad">
                <span class="material-symbols-outlined bento-icon">lightbulb</span>
                <h3>Claridad</h3>
                <p>Explicaciones directas, sin tecnicismos innecesarios. Cada diagnóstico y cada curso está diseñado para que cualquier operador lo entienda.</p>
            </div>

            {{-- Cercanía --}}
            <div class="ns-bento-item ns-bento-cercania">
                <span class="material-symbols-outlined bento-icon">groups</span>
                <h3>Cercanía</h3>
                <p>Acompañamiento antes, durante y después del servicio. Respondemos y resolvemos.</p>
            </div>

            {{-- Mejora --}}
            <div class="ns-bento-item ns-bento-mejora">
                <span class="material-symbols-outlined bento-icon">trending_up</span>
                <h3>Mejora</h3>
                <p>Resultados medibles y recomendaciones concretas para avanzar hacia la siguiente etapa.</p>
            </div>

        </div>
    </section>

    {{-- ── MISIÓN / VISIÓN ── --}}
    <section class="ns-mv">
        <div class="ns-mv-inner">
            <div class="ns-mv-card">
                <div class="mv-kicker">Misión</div>
                <h3>Elevar la calidad productiva del sector alimentario.</h3>
                <p>Ayudamos a que empresas y profesionales trabajen con procesos más ordenados, seguros y sostenibles, aplicando estándares técnicos al contexto real peruano.</p>
            </div>
            <div class="ns-mv-card">
                <div class="mv-kicker">Visión</div>
                <h3>Ser referentes en calidad alimentaria en el Perú.</h3>
                <p>Acercar herramientas técnicas de clase mundial a más negocios del sector alimentario, desde Huancayo hacia todo el país.</p>
            </div>
        </div>
    </section>

    {{-- ── CTA ── --}}
    <section class="ns-cta">
        <div class="ns-cta-box">
            <div class="ns-cta-blob1"></div>
            <div class="ns-cta-blob2"></div>
            <div style="position:relative;z-index:1;">
                <h2>¿Listo para mejorar tu proceso alimentario?</h2>
                <p>Únete a las empresas y profesionales que ya trabajan con estándares más altos. Contáctanos y cuéntanos en qué podemos ayudarte.</p>
                <div style="display:flex;gap:14px;justify-content:center;flex-wrap:wrap;">
                    <a href="{{ route('contacto') }}#contacto-formulario" class="ns-btn-primary">Hablar con nosotros</a>
                    <a href="{{ route('cursos') }}" style="
                        display:inline-block;
                        border:1.5px solid #bfdbfe;
                        color:#1e40af;
                        padding:14px 28px;
                        border-radius:30px;
                        font-weight:600;
                        font-size:14px;
                        text-decoration:none;
                        transition:all .2s;
                        background:#eff6ff;
                    ">Ver cursos disponibles</a>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection

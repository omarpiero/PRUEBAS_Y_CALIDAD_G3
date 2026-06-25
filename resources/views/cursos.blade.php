@extends('layouts.app')

@section('title', 'Cursos de Calidad e Inocuidad Alimentaria')
@section('meta_description', 'Explora nuestro catálogo de capacitaciones especializadas en Buenas Prácticas de Manufactura (BPM), HACCP, e ISO para el sector de alimentos.')

@section('content')
<main class="page">

    {{-- ── Hero ── --}}
    <section class="ch-hero">
        <div class="ch-bg"></div>
        <div class="ch-overlay"></div>
        <div class="ch-blob ch-blob-1"></div>
        <div class="ch-blob ch-blob-2"></div>

        <div class="ch-inner">
            {{-- Columna izquierda --}}
            <div class="ch-left">
                <span class="ch-eyebrow">
                    <svg width="12" height="12" fill="currentColor" viewBox="0 0 24 24"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                    Capacitaciones · Sector Alimentario
                </span>

                <h1 class="ch-title">
                    Fórmate con los <em>mejores expertos</em> en calidad alimentaria del Perú.
                </h1>

                <p class="ch-lead">
                    Programas certificados en BPM, HACCP e ISO. Diseñados para técnicos, jefes de planta y emprendedores del sector alimentario. Aprende a tu ritmo, aplica desde el primer módulo.
                </p>

                {{-- Stats pills --}}
                <div class="ch-pills">
                    <span class="ch-pill">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        Programas Certificados
                    </span>
                    <span class="ch-pill">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        A tu propio ritmo
                    </span>
                    <span class="ch-pill">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                        100% online
                    </span>
                </div>

                <div class="ch-actions">
                    <a href="#catalogo" class="ch-btn-primary">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                        Ver catálogo completo
                    </a>
                    <a href="{{ route('contacto') }}#contacto-formulario" class="ch-btn-outline">
                        Consultar matrícula
                    </a>
                </div>
            </div>

            {{-- Columna derecha: tarjeta destacada dinámicamente --}}
            @php
                $featuredCourse = $courses->where('is_featured', true)->first() ?? $courses->first();
            @endphp
            @if($featuredCourse)
            <div class="ch-right">
                <div class="ch-card">
                    <div class="ch-card-img">
                        @php
                            $featCover = $featuredCourse->cover_image;
                            if ($featCover && !str_starts_with($featCover, 'http')) {
                                $featCover = asset('storage/' . $featCover);
                            }
                            $featCover = $featCover ?: 'https://images.unsplash.com/photo-1563636619-e9143da7973b?auto=format&fit=crop&w=700&q=88';
                        @endphp
                        <a href="{{ route('cursos.show', $featuredCourse->slug) }}">
                            <img src="{{ $featCover }}" alt="{{ $featuredCourse->name }}">
                        </a>
                        <span class="ch-card-badge">Más popular</span>
                    </div>
                    <div class="ch-card-body">
                        <div class="ch-card-cat">{{ $featuredCourse->category?->name }}</div>
                        <div class="ch-card-title">
                            <a href="{{ route('cursos.show', $featuredCourse->slug) }}" style="color: inherit; text-decoration: none;">
                                {{ $featuredCourse->name }}
                            </a>
                        </div>
                        <div class="ch-card-meta">
                            <span>
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                {{ $featuredCourse->duration_weeks }} semanas
                            </span>
                            <span>
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                Certificado
                            </span>
                            <span>
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                Nivel {{ ucfirst($featuredCourse->level) }}
                            </span>
                        </div>
                        <div class="ch-card-footer">
                            <div>
                                <div class="ch-card-price-lbl">Precio</div>
                                @if($featuredCourse->has_active_offer)
                                    <div class="ch-card-price" style="text-decoration: line-through; color: #9ca3af; font-size: 13px;">S/ {{ number_format($featuredCourse->price, 0) }}</div>
                                    <div class="ch-card-price" style="color: #ef4444;">S/ {{ number_format($featuredCourse->effective_price, 0) }}</div>
                                @else
                                    <div class="ch-card-price">S/ {{ number_format($featuredCourse->price, 0) }}</div>
                                @endif
                            </div>
                            <button class="ch-card-btn" onclick="inscribir(this)" data-course-id="{{ $featuredCourse->id }}">
                                Inscribirme
                            </button>
                        </div>
                    </div>

                    {{-- Stats flotantes --}}
                    <div class="ch-float-stat ch-float-stat-1">
                        <svg width="14" height="14" fill="none" stroke="#22c55e" stroke-width="2" viewBox="0 0 24 24"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
                        <span><strong>+200</strong> estudiantes</span>
                    </div>
                    <div class="ch-float-stat ch-float-stat-2">
                        <svg width="14" height="14" fill="#f59e0b" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        <span><strong>4.9</strong> valoración</span>
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- Scroll indicator --}}
        <div class="ch-scroll">
            <a href="#catalogo">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
            </a>
        </div>
    </section>

    {{-- ── Filtros ── --}}
    <section id="catalogo" class="section" style="padding-bottom:0">
        <div class="section-header">
            <p class="eyebrow">Catálogo</p>
            <h2 class="section-title">Programas especializados en alimentos.</h2>
            <p class="section-subtitle">Cada curso incluye materiales, certificado y acompañamiento técnico.</p>
        </div>

        <form action="{{ route('cursos') }}#catalogo" method="GET" class="catalog-filter-form">
            <div class="filters-row" style="display: flex; gap: 15px; flex-wrap: wrap; margin-top: 24px; align-items: center;">
                <div class="search-wrap" style="flex: 1; min-width: 250px;">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar cursos (ej. BPM, ISO, HACCP)..." 
                           style="width: 100%; padding: 10px 20px; border-radius: 30px; border: 1.5px solid #d1d5db; font-family: inherit; font-size: 13.5px; outline: none; transition: border-color 0.2s;">
                </div>
                
                <div class="select-wrap" style="min-width: 180px;">
                    <select name="category_id" onchange="this.form.submit()" 
                            style="width: 100%; padding: 10px 20px; border-radius: 30px; border: 1.5px solid #d1d5db; font-family: inherit; font-size: 13.5px; background: white; cursor: pointer; outline: none;">
                        <option value="">Todas las Categorías</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="level-filters" style="display: flex; gap: 8px;">
                    <input type="hidden" name="level" id="filter-level-input" value="{{ request('level') }}">
                    <button type="button" class="filter-btn {{ !request('level') ? 'active' : '' }}" onclick="filterLevel('')">Todos</button>
                    <button type="button" class="filter-btn {{ request('level') === 'basico' ? 'active' : '' }}" onclick="filterLevel('basico')">Básico</button>
                    <button type="button" class="filter-btn {{ request('level') === 'intermedio' ? 'active' : '' }}" onclick="filterLevel('intermedio')">Intermedio</button>
                    <button type="button" class="filter-btn {{ request('level') === 'avanzado' ? 'active' : '' }}" onclick="filterLevel('avanzado')">Avanzado</button>
                </div>

                <button type="submit" class="btn-inscribir" style="border: none; cursor: pointer; border-radius: 30px; padding: 10px 25px;">Buscar</button>
                
                @if(request()->anyFilled(['search', 'category_id', 'level']))
                    <a href="{{ route('cursos') }}#catalogo" class="filter-btn" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center; height: 38px;">Limpiar Filtros</a>
                @endif
            </div>
        </form>
    </section>

    {{-- ── Catálogo ── --}}
    <section class="section" style="padding-top:24px">
        @if($courses->isEmpty())
            <div style="text-align: center; padding: 60px 20px; background: #f9fafb; border-radius: 16px; border: 1.5px dashed #d1d5db; max-width: 600px; margin: 0 auto;">
                <p style="font-size: 16px; color: #6b7280; font-weight: 600; margin-bottom: 15px;">No se encontraron cursos con los filtros seleccionados.</p>
                <a href="{{ route('cursos') }}#catalogo" class="btn-inscribir" style="text-decoration: none; display: inline-block;">Ver todos los cursos</a>
            </div>
        @else
            <div class="cursos-grid">
                @foreach($courses as $course)
                    @php
                        $cover = $course->cover_image;
                        if ($cover && !str_starts_with($cover, 'http')) {
                            $cover = asset('storage/' . $cover);
                        }
                        $cover = $cover ?: 'https://images.unsplash.com/photo-1563636619-e9143da7973b?w=480&h=260&fit=crop&auto=format';
                    @endphp
                    <article class="curso-card reveal" data-level="{{ $course->level }}">
                        <div class="curso-img">
                            <a href="{{ route('cursos.show', $course->slug) }}">
                                <img src="{{ $cover }}" alt="{{ $course->name }}" loading="lazy">
                            </a>
                            <span class="nivel-badge {{ $course->level }}">
                                @if($course->level === 'basico') Básico
                                @elseif($course->level === 'intermedio') Intermedio
                                @else Avanzado
                                @endif
                            </span>
                        </div>
                        <div class="curso-body">
                            <div class="curso-cat-tag">{{ $course->category?->name }}</div>
                            <h3 class="curso-nombre">
                                <a href="{{ route('cursos.show', $course->slug) }}" style="color: inherit; text-decoration: none;">
                                    {{ $course->name }}
                                </a>
                            </h3>
                            <p class="curso-resena">{{ $course->short_description }}</p>
                            <div class="curso-detalles">
                                <span>
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                    {{ $course->duration_weeks }} semanas
                                </span>
                                <span>
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                                    Online
                                </span>
                                <span>
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                    Certificado
                                </span>
                            </div>
                            <div class="curso-footer">
                                <div class="curso-precio">
                                    @if($course->has_active_offer)
                                        <span class="precio-label" style="text-decoration: line-through; color: #9ca3af; font-size: 11px;">S/ {{ number_format($course->price, 0) }}</span>
                                        <span class="precio-valor" style="color: #ef4444;">S/ {{ number_format($course->effective_price, 0) }}</span>
                                    @else
                                        <span class="precio-label">Precio</span>
                                        <span class="precio-valor">S/ {{ number_format($course->price, 0) }}</span>
                                    @endif
                                </div>
                                <button class="btn-inscribir" onclick="inscribir(this)" data-course-id="{{ $course->id }}">Inscribirme</button>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>

    {{-- ── Metodología ── --}}
    <section class="section alt">
        <div class="split">
            <div>
                <p class="eyebrow">Metodología</p>
                <h2 class="section-title">La clase se convierte en acción.</h2>
                <p class="section-subtitle">El participante no solo ve contenido: entiende qué debe revisar, documentar y mejorar en su planta.</p>
            </div>
            <div class="feature-panel reveal">
                <ul class="list-clean">
                    <li>Sesiones breves con objetivos por módulo.</li>
                    <li>Casos aplicados a plantas y negocios alimentarios.</li>
                    <li>Materiales descargables para el equipo.</li>
                    <li>Orientación final para aplicar lo aprendido.</li>
                </ul>
            </div>
        </div>
    </section>

</main>
@endsection

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Noto+Serif:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
<style>
/* ══════════════ HERO CURSOS ══════════════ */
.ch-hero {
    position: relative;
    min-height: 92vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
    overflow: hidden;
    background: #07172e;
}
.ch-bg {
    position: absolute; inset: 0;
    background-image: url("https://images.unsplash.com/photo-1486297678162-eb2a19b0a32d?auto=format&fit=crop&w=1600&q=80");
    background-size: cover; background-position: center;
    opacity: .12;
    will-change: transform;
    animation: ch-kb 18s ease-in-out infinite alternate;
}
@keyframes ch-kb {
    from { transform: scale(1); }
    to   { transform: scale(1.07) translate(-1%, .8%); }
}
.ch-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(115deg, rgba(5,15,50,.95) 0%, rgba(10,30,90,.78) 50%, rgba(14,165,233,.15) 100%);
}
.ch-blob {
    position: absolute; border-radius: 50%;
    filter: blur(100px); opacity: .2; pointer-events: none;
}
.ch-blob-1 { width: 560px; height: 560px; background: #2563eb; top: -160px; right: -100px; }
.ch-blob-2 { width: 400px; height: 400px; background: #0ea5e9; bottom: -100px; left: -80px; }

.ch-inner {
    position: relative; z-index: 2;
    max-width: 1280px; margin: 0 auto;
    padding: 80px 64px;
    display: grid;
    grid-template-columns: 1fr 420px;
    gap: 72px;
    align-items: center;
    width: 100%;
}

.ch-eyebrow {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(37,99,235,.25);
    border: 1px solid rgba(96,165,250,.4);
    color: #93c5fd;
    font-size: 12px; font-weight: 700;
    letter-spacing: 1px; text-transform: uppercase;
    padding: 6px 16px; border-radius: 30px;
    margin-bottom: 22px;
}

.ch-title {
    font-family: 'Noto Serif', serif;
    font-size: clamp(32px, 4vw, 54px);
    font-weight: 700; color: #fff;
    line-height: 1.13; margin-bottom: 20px;
    letter-spacing: -.4px;
}
.ch-title em { font-style: normal; color: #7dd3fc; }

.ch-lead {
    font-size: 16.5px; color: rgba(255,255,255,.72);
    line-height: 1.75; margin-bottom: 28px;
    max-width: 520px;
}

.ch-pills {
    display: flex; flex-wrap: wrap; gap: 10px;
    margin-bottom: 32px;
}
.ch-pill {
    display: inline-flex; align-items: center; gap: 6px;
    background: rgba(255,255,255,.08);
    border: 1px solid rgba(255,255,255,.14);
    color: rgba(255,255,255,.82);
    font-size: 12.5px; font-weight: 600;
    padding: 7px 14px; border-radius: 30px;
    backdrop-filter: blur(6px);
}
.ch-pill svg { color: #7dd3fc; flex-shrink: 0; }

.ch-actions { display: flex; gap: 12px; flex-wrap: wrap; }
.ch-btn-primary {
    display: inline-flex; align-items: center; gap: 8px;
    background: #2563eb; color: #fff;
    padding: 13px 26px; border-radius: 10px;
    font-size: 14px; font-weight: 700; text-decoration: none;
    box-shadow: 0 12px 28px rgba(37,99,235,.4);
    transition: all .2s;
}
.ch-btn-primary:hover { background: #1d4ed8; transform: translateY(-2px); }
.ch-btn-outline {
    display: inline-flex; align-items: center; gap: 8px;
    border: 1.5px solid rgba(255,255,255,.4); color: #fff;
    padding: 13px 26px; border-radius: 10px;
    font-size: 14px; font-weight: 600; text-decoration: none;
    backdrop-filter: blur(4px);
    transition: all .2s;
}
.ch-btn-outline:hover { background: rgba(255,255,255,.1); border-color: #fff; }

.ch-card {
    background: rgba(255,255,255,.07);
    border: 1px solid rgba(255,255,255,.13);
    backdrop-filter: blur(14px);
    border-radius: 20px;
    overflow: hidden;
    position: relative;
    box-shadow: 0 32px 80px rgba(0,0,0,.4);
}
.ch-card-img { position: relative; height: 200px; overflow: hidden; }
.ch-card-img img { width: 100%; height: 100%; object-fit: cover; transition: transform .5s; }
.ch-card:hover .ch-card-img img { transform: scale(1.05); }
.ch-card-img::after {
    content: ''; position: absolute; inset: 0;
    background: linear-gradient(180deg, transparent 40%, rgba(7,23,46,.7));
}
.ch-card-badge {
    position: absolute; top: 14px; left: 14px; z-index: 1;
    background: linear-gradient(135deg, #f59e0b, #fbbf24);
    color: #7c2d12;
    font-size: 10.5px; font-weight: 800;
    padding: 4px 12px; border-radius: 20px;
    text-transform: uppercase; letter-spacing: .5px;
}
.ch-card-body { padding: 20px 22px 22px; }
.ch-card-cat   { font-size: 10.5px; font-weight: 700; text-transform: uppercase; letter-spacing: .6px; color: #7dd3fc; margin-bottom: 6px; }
.ch-card-title { font-size: 17px; font-weight: 700; color: #fff; margin-bottom: 12px; line-height: 1.3; }
.ch-card-meta  {
    display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 16px;
}
.ch-card-meta span {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: 11.5px; color: rgba(255,255,255,.6);
    background: rgba(255,255,255,.08);
    padding: 4px 10px; border-radius: 8px;
}
.ch-card-footer {
    display: flex; align-items: center; justify-content: space-between;
    padding-top: 14px; border-top: 1px solid rgba(255,255,255,.1);
}
.ch-card-price-lbl { font-size: 10px; color: rgba(255,255,255,.45); }
.ch-card-price     { font-size: 24px; font-weight: 800; color: #fff; line-height: 1; }
.ch-card-btn {
    background: #2563eb; color: #fff;
    border: none; border-radius: 9px;
    padding: 10px 18px;
    font-size: 13px; font-weight: 700; font-family: inherit;
    cursor: pointer; transition: background .18s, transform .15s;
}
.ch-card-btn:hover { background: #1d4ed8; transform: scale(1.04); }

.ch-float-stat {
    position: absolute;
    display: flex; align-items: center; gap: 7px;
    background: rgba(255,255,255,.95);
    border-radius: 10px; padding: 8px 14px;
    font-size: 12px; color: #0f172a;
    box-shadow: 0 8px 24px rgba(0,0,0,.2);
    white-space: nowrap;
    animation: ch-float 4s ease-in-out infinite alternate;
}
.ch-float-stat strong { font-weight: 800; }
.ch-float-stat-1 { top: 24px; right: -16px; animation-delay: 0s; }
.ch-float-stat-2 { bottom: 90px; right: -20px; animation-delay: 1.5s; }
@keyframes ch-float {
    from { transform: translateY(0); }
    to   { transform: translateY(-6px); }
}

.ch-scroll {
    position: absolute; bottom: 28px; left: 50%;
    transform: translateX(-50%);
    z-index: 2;
}
.ch-scroll a {
    display: flex; align-items: center; justify-content: center;
    width: 40px; height: 40px;
    border: 1.5px solid rgba(255,255,255,.3);
    border-radius: 50%; color: rgba(255,255,255,.7);
    animation: ch-bounce 2s ease-in-out infinite;
    transition: border-color .2s;
}
.ch-scroll a:hover { border-color: #fff; color: #fff; }
@keyframes ch-bounce {
    0%, 100% { transform: translateY(0); }
    50%       { transform: translateY(5px); }
}

@media (max-width: 960px) {
    .ch-inner { grid-template-columns: 1fr; padding: 60px 32px 80px; gap: 48px; }
    .ch-right { display: none; }
    .ch-hero  { min-height: 80vh; }
}
@media (max-width: 560px) {
    .ch-inner { padding: 50px 20px 70px; }
    .ch-pills { gap: 8px; }
}

.curso-filters {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-top: 24px;
}
.filter-btn {
    padding: 8px 20px;
    border-radius: 30px;
    border: 1.5px solid #d1d5db;
    background: #fff;
    font-family: 'Poppins', sans-serif;
    font-size: 13px;
    font-weight: 500;
    color: #6b7280;
    cursor: pointer;
    transition: all .2s;
}
.filter-btn:hover   { border-color: #38bdf8; color: #0284c7; background: #e0f2fe; }
.filter-btn.active  { background: #0284c7; border-color: #0284c7; color: #fff; font-weight: 600; }

.cursos-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(310px, 1fr));
    gap: 26px;
}

.curso-card {
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    border: 1px solid #e5e7eb;
    box-shadow: 0 2px 12px rgba(0,0,0,.06);
    display: flex;
    flex-direction: column;
    transition: transform .25s, box-shadow .25s;
}
.curso-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 32px rgba(0,0,0,.12);
}

.curso-img {
    position: relative;
    height: 190px;
    overflow: hidden;
}
.curso-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform .4s;
}
.curso-card:hover .curso-img img { transform: scale(1.05); }

.nivel-badge {
    position: absolute;
    top: 12px;
    right: 12px;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .4px;
    text-transform: uppercase;
}
.nivel-badge.basico     { background: #d1fae5; color: #065f46; }
.nivel-badge.intermedio { background: #fef3c7; color: #92400e; }
.nivel-badge.avanzado   { background: #fee2e2; color: #991b1b; }

.curso-body {
    padding: 18px 20px 20px;
    display: flex;
    flex-direction: column;
    flex: 1;
}
.curso-cat-tag {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .6px;
    color: #0284c7;
    margin-bottom: 6px;
}
.curso-nombre {
    font-size: 16px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 10px;
    line-height: 1.35;
}
.curso-resena {
    font-size: 13px;
    color: #6b7280;
    line-height: 1.65;
    margin-bottom: 14px;
    flex: 1;
    display: -webkit-box;
    -webkit-line-clamp: 4;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.curso-detalles {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 16px;
}
.curso-detalles span {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 12px;
    color: #6b7280;
    background: #f3f4f6;
    padding: 4px 10px;
    border-radius: 8px;
}
.curso-detalles span svg { flex-shrink: 0; }

.curso-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-top: 14px;
    border-top: 1px solid #f3f4f6;
    margin-top: auto;
}
.curso-precio { display: flex; flex-direction: column; }
.precio-label { font-size: 10.5px; color: #9ca3af; font-weight: 500; }
.precio-valor { font-size: 22px; font-weight: 800; color: #111827; line-height: 1.1; }

.btn-inscribir {
    display: inline-block;
    padding: 9px 20px;
    background: #0284c7;
    color: #fff;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    font-family: 'Poppins', sans-serif;
    text-decoration: none;
    box-shadow: 0 10px 22px rgba(2, 132, 199, 0.18);
    transition: background .2s, transform .15s, box-shadow .2s;
    white-space: nowrap;
}
.btn-inscribir:hover {
    background: #075985;
    box-shadow: 0 14px 28px rgba(2, 132, 199, 0.24);
    transform: scale(1.03);
}

@media (max-width: 640px) {
    .cursos-grid { grid-template-columns: 1fr; }
}
</style>
@endpush

@push('scripts')
<script>
// Manejo de nivel
function filterLevel(level) {
    document.getElementById('filter-level-input').value = level;
    document.querySelector('.catalog-filter-form').submit();
}

// Agregar al carrito (Usando únicamente ID del curso para máxima seguridad)
async function inscribir(btn) {
    @guest
        window.location.href = '{{ route("login") }}';
        return;
    @endguest

    const courseId = btn.dataset.courseId;
    if (!courseId) {
        showToast('❌ ID de curso no especificado.');
        return;
    }

    btn.disabled    = true;
    btn.textContent = 'Agregando…';

    try {
        const res  = await fetch('{{ route("cart.add") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept':       'application/json',
            },
            body: JSON.stringify({ course_id: courseId }),
        });
        const data = await res.json();

        if (res.ok && data.ok) {
            btn.textContent      = '✓ En el carrito';
            btn.style.background = '#0284c7';
            // actualizar badge navbar
            const badge = document.getElementById('nav-cart-count');
            if (badge) {
                badge.textContent = data.count;
                badge.style.display = 'inline-flex';
                badge.style.transform = 'scale(1.3)';
                setTimeout(() => badge.style.transform = '', 300);
            }
            showToast('🛒 ' + data.msg + ' — <a href="{{ route("checkout") }}" style="color:#fff;font-weight:700;">Ir al carrito →</a>');
        } else {
            btn.disabled    = false;
            btn.textContent = 'Inscribirme';
            showToast('ℹ️ ' + (data.msg || 'No se pudo agregar.'));
        }
    } catch (e) {
        btn.disabled    = false;
        btn.textContent = 'Inscribirme';
        showToast('❌ Error de conexión.');
    }
}
</script>
@endpush

@extends('layouts.app')

@section('title', $course->name)

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;500&display=swap" rel="stylesheet">
<style>
    /* ══════════════ CLASSROOM THEME (Premium Dark & Glass) ══════════════ */
    .aula-container {
        background: #090d16;
        min-height: calc(100vh - 76px);
        font-family: 'Poppins', sans-serif;
        color: #f8fafc;
        display: grid;
        grid-template-columns: 340px 1fr;
        position: relative;
    }

    /* ══════════════ SIDEBAR ══════════════ */
    .aula-sidebar {
        background: rgba(15, 23, 42, 0.95);
        border-right: 1px solid rgba(255, 255, 255, 0.08);
        display: flex;
        flex-direction: column;
        height: calc(100vh - 76px);
        position: sticky;
        top: 76px;
        z-index: 10;
        overflow-y: auto;
    }
    .aula-sidebar-head {
        padding: 24px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }
    .aula-back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #38bdf8;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        margin-bottom: 16px;
        transition: color 0.2s;
    }
    .aula-back-link:hover {
        color: #0ea5e9;
    }
    .aula-sidebar-title {
        font-size: 16px;
        font-weight: 700;
        color: #fff;
        line-height: 1.4;
    }

    /* Progress block in sidebar */
    .aula-progress-block {
        padding: 18px 24px;
        background: rgba(255, 255, 255, 0.02);
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }
    .aula-progress-info {
        display: flex;
        justify-content: space-between;
        font-size: 12px;
        font-weight: 600;
        color: #94a3b8;
        margin-bottom: 6px;
    }
    .aula-progress-pct {
        color: #38bdf8;
    }
    .aula-progress-bar {
        height: 6px;
        background: #1e293b;
        border-radius: 10px;
        overflow: hidden;
    }
    .aula-progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #38bdf8, #0ea5e9);
        border-radius: 10px;
        transition: width 0.4s ease;
    }

    /* Modules Accordion */
    .aula-modules {
        flex: 1;
    }
    .aula-module-item {
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }
    .aula-module-btn {
        width: 100%;
        padding: 16px 24px;
        background: transparent;
        border: none;
        text-align: left;
        color: #fff;
        font-family: inherit;
        font-size: 13.5px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        transition: background 0.2s;
    }
    .aula-module-btn:hover {
        background: rgba(255, 255, 255, 0.02);
    }
    .aula-module-btn svg {
        transition: transform 0.2s;
        color: #64748b;
        flex-shrink: 0;
    }
    .aula-module-btn.active svg {
        transform: rotate(90deg);
        color: #38bdf8;
    }
    .aula-module-content {
        display: none;
        background: rgba(0, 0, 0, 0.15);
        padding: 4px 0;
    }
    .aula-module-content.active {
        display: block;
    }

    /* Materials list */
    .aula-material-link {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 24px 12px 36px;
        color: #94a3b8;
        font-size: 13px;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s;
        border-left: 3px solid transparent;
    }
    .aula-material-link:hover {
        background: rgba(56, 189, 248, 0.05);
        color: #fff;
    }
    .aula-material-link.active {
        background: rgba(56, 189, 248, 0.08);
        color: #fff;
        font-weight: 600;
        border-left-color: #38bdf8;
    }
    .aula-material-icon {
        font-size: 16px;
        flex-shrink: 0;
    }
    .aula-material-title {
        flex: 1;
        line-height: 1.35;
    }
    .aula-check-icon {
        width: 16px;
        height: 16px;
        border-radius: 50%;
        border: 1.5px solid rgba(255,255,255,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: all 0.2s;
    }
    .aula-material-link.completed .aula-check-icon {
        background: #22c55e;
        border-color: #22c55e;
        color: #fff;
    }
    .aula-check-icon svg {
        width: 10px;
        height: 10px;
        opacity: 0;
        transition: opacity 0.2s;
    }
    .aula-material-link.completed .aula-check-icon svg {
        opacity: 1;
    }

    /* ══════════════ MAIN WORKSPACE ══════════════ */
    .aula-workspace {
        padding: 40px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 28px;
        max-width: 1000px;
        margin: 0 auto;
        width: 100%;
    }

    /* Visualizer (Dynamic content viewer) */
    .aula-visualizer {
        background: #0f172a;
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        position: relative;
    }
    .aula-visualizer-video {
        aspect-ratio: 16/9;
        width: 100%;
        display: block;
        border: none;
    }
    .aula-visualizer-iframe {
        aspect-ratio: 16/9;
        width: 100%;
        height: 100%;
        border: none;
        display: block;
    }
    .aula-visualizer-document {
        height: 600px;
        width: 100%;
        border: none;
        display: block;
    }

    /* Visualizer fallback/empty states */
    .aula-visualizer-empty {
        padding: 80px 40px;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 16px;
    }
    .aula-visualizer-empty-icon {
        font-size: 48px;
    }

    /* Rich text container */
    .aula-visualizer-text {
        background: rgba(15, 23, 42, 0.6);
        padding: 40px;
        max-height: 700px;
        overflow-y: auto;
        line-height: 1.8;
        font-size: 15.5px;
        color: #cbd5e1;
    }
    .aula-visualizer-text h1, .aula-visualizer-text h2, .aula-visualizer-text h3 {
        color: #fff;
        margin-top: 24px;
        margin-bottom: 12px;
        font-family: inherit;
    }
    .aula-visualizer-text p {
        margin-bottom: 16px;
    }
    .aula-visualizer-text pre {
        background: #020617;
        font-family: 'Fira Code', monospace;
        padding: 16px;
        border-radius: 8px;
        overflow-x: auto;
        font-size: 13.5px;
        border: 1px solid rgba(255, 255, 255, 0.05);
        margin: 20px 0;
    }
    .aula-visualizer-text ul, .aula-visualizer-text ol {
        margin-left: 24px;
        margin-bottom: 16px;
    }
    .aula-visualizer-text li {
        margin-bottom: 8px;
    }
    .aula-visualizer-text blockquote {
        border-left: 4px solid #38bdf8;
        background: rgba(56, 189, 248, 0.05);
        padding: 16px 20px;
        border-radius: 0 8px 8px 0;
        font-style: italic;
        margin: 20px 0;
    }

    /* Visualizer Download Card */
    .aula-download-card {
        padding: 60px 40px;
        text-align: center;
        background: rgba(30, 41, 59, 0.3);
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 20px;
    }
    .aula-download-icon {
        font-size: 56px;
        background: rgba(56, 189, 248, 0.1);
        width: 100px;
        height: 100px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid rgba(56, 189, 248, 0.2);
        color: #38bdf8;
        margin-bottom: 8px;
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(56, 189, 248, 0.2); }
        70% { transform: scale(1.03); box-shadow: 0 0 0 15px rgba(56, 189, 248, 0); }
        100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(56, 189, 248, 0); }
    }
    .aula-download-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #0284c7;
        color: #fff;
        font-weight: 700;
        font-size: 14px;
        padding: 12px 28px;
        border-radius: 10px;
        text-decoration: none;
        transition: background 0.2s, transform 0.15s;
        border: none;
        cursor: pointer;
    }
    .aula-download-btn:hover {
        background: #0369a1;
        transform: translateY(-2px);
    }
    .aula-download-btn:active {
        transform: translateY(0);
    }

    /* Lesson details and actions */
    .aula-meta {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 24px;
        flex-wrap: wrap;
        border-bottom: 1px solid rgba(255,255,255,0.08);
        padding-bottom: 24px;
    }
    .aula-meta-left {
        flex: 1;
    }
    .aula-meta-title {
        font-size: 22px;
        font-weight: 700;
        color: #fff;
        margin-bottom: 8px;
    }
    .aula-meta-desc {
        color: #94a3b8;
        font-size: 14.5px;
        line-height: 1.6;
    }
    .aula-meta-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255, 255, 255, 0.05);
        color: #94a3b8;
        font-size: 12px;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 20px;
        margin-top: 8px;
    }

    /* Completion Toggle Button */
    .aula-toggle-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: transparent;
        border: 2px solid #22c55e;
        color: #22c55e;
        font-family: inherit;
        font-size: 14px;
        font-weight: 700;
        padding: 12px 24px;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .aula-toggle-btn:hover {
        background: rgba(34, 197, 94, 0.08);
    }
    .aula-toggle-btn.completed {
        background: #22c55e;
        color: #fff;
    }
    .aula-toggle-btn.completed:hover {
        background: #16a34a;
        border-color: #16a34a;
    }

    /* Navigation buttons (Prev / Next) */
    .aula-nav-wrap {
        display: flex;
        justify-content: space-between;
        gap: 16px;
    }
    .aula-nav-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.08);
        color: #cbd5e1;
        font-family: inherit;
        font-size: 13.5px;
        font-weight: 600;
        padding: 10px 18px;
        border-radius: 8px;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s;
    }
    .aula-nav-btn:hover:not(:disabled) {
        background: rgba(255, 255, 255, 0.1);
        color: #fff;
    }
    .aula-nav-btn:disabled {
        opacity: 0.35;
        cursor: not-allowed;
    }

    /* ══════════════ RESPONSIVE ══════════════ */
    @media (max-width: 900px) {
        .aula-container {
            grid-template-columns: 1fr;
        }
        .aula-sidebar {
            height: auto;
            position: relative;
            top: 0;
            border-right: none;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }
        .aula-workspace {
            padding: 24px;
        }
    }
</style>
@endpush

@section('content')

@php
    // Find active material
    $activeMaterial = null;
    $activeModule = null;
    $materialId = request('material');

    if ($materialId) {
        foreach ($course->modules as $mod) {
            foreach ($mod->materials as $mat) {
                if ($mat->id == $materialId) {
                    $activeMaterial = $mat;
                    $activeModule = $mod;
                    break 2;
                }
            }
        }
    }

    // Default to first material in the course if none selected
    if (!$activeMaterial) {
        foreach ($course->modules as $mod) {
            if ($mod->materials->isNotEmpty()) {
                $activeMaterial = $mod->materials->first();
                $activeModule = $mod;
                break;
            }
        }
    }

    // Determine all materials in order to compute prev/next links
    $orderedMaterials = [];
    foreach ($course->modules as $mod) {
        foreach ($mod->materials as $mat) {
            $orderedMaterials[] = $mat;
        }
    }

    $currentIndex = -1;
    if ($activeMaterial) {
        foreach ($orderedMaterials as $index => $mat) {
            if ($mat->id === $activeMaterial->id) {
                $currentIndex = $index;
                break;
            }
        }
    }

    $prevMaterial = ($currentIndex > 0) ? $orderedMaterials[$currentIndex - 1] : null;
    $nextMaterial = ($currentIndex >= 0 && $currentIndex < count($orderedMaterials) - 1) ? $orderedMaterials[$currentIndex + 1] : null;
@endphp

<div class="aula-container">

    {{-- ══════════════ SIDEBAR ══════════════ --}}
    <aside class="aula-sidebar">
        <div class="aula-sidebar-head">
            <a href="{{ route('mi-cuenta') }}" class="aula-back-link">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                Volver a Mi Cuenta
            </a>
            <div class="aula-sidebar-title">{{ $course->name }}</div>
        </div>

        {{-- Dynamic Progress Bar --}}
        <div class="aula-progress-block">
            <div class="aula-progress-info">
                <span>Progreso del curso</span>
                <span class="aula-progress-pct">{{ (int) ($enrollment->progress ?? 0) }}%</span>
            </div>
            <div class="aula-progress-bar">
                <div id="courseProgressFill" class="aula-progress-fill" style="width: {{ (int) ($enrollment->progress ?? 0) }}%"></div>
            </div>
        </div>

        {{-- Modules & Materials Accordion --}}
        <div class="aula-modules">
            @foreach ($course->modules as $modIndex => $mod)
                @php
                    $isModuleActive = $activeModule && ($activeModule->id === $mod->id);
                @endphp
                <div class="aula-module-item">
                    <button class="aula-module-btn {{ $isModuleActive ? 'active' : '' }}" onclick="toggleModuleAccordion(this, 'module-content-{{ $mod->id }}')">
                        <span>M&oacute;dulo {{ $modIndex + 1 }}: {{ $mod->name }}</span>
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
                    </button>
                    <div id="module-content-{{ $mod->id }}" class="aula-module-content {{ $isModuleActive ? 'active' : '' }}">
                        @if ($mod->materials->isEmpty())
                            <div style="padding:12px 24px;font-size:12px;color:#64748b;font-style:italic;">No hay lecciones en este módulo</div>
                        @else
                            @foreach ($mod->materials as $mat)
                                @php
                                    $isActive = $activeMaterial && ($activeMaterial->id === $mat->id);
                                    $isCompleted = in_array($mat->id, $completedMaterialIds);
                                @endphp
                                <a href="?material={{ $mat->id }}" id="sidebar-material-{{ $mat->id }}" class="aula-material-link {{ $isActive ? 'active' : '' }} {{ $isCompleted ? 'completed' : '' }}">
                                    <span class="aula-check-icon">
                                        <svg fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                                    </span>
                                    <span class="aula-material-icon">{{ $mat->type_icon }}</span>
                                    <span class="aula-material-title">{{ $mat->title }}</span>
                                </a>
                            @endforeach
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </aside>

    {{-- ══════════════ WORKSPACE ══════════════ --}}
    <main class="aula-workspace">
        @if (!$activeMaterial)
            <div class="aula-visualizer">
                <div class="aula-visualizer-empty">
                    <div class="aula-visualizer-empty-icon">📂</div>
                    <h2 style="font-size: 18px; font-weight:700;">Este curso aún no tiene materiales de estudio</h2>
                    <p style="color:#64748b; font-size:14px; max-width:320px;">Vuelve más tarde o ponte en contacto con soporte si crees que esto es un error.</p>
                </div>
            </div>
        @else
            {{-- Material Visualizer --}}
            <div class="aula-visualizer">

                {{-- Type: VIDEO (Embed) --}}
                @if ($activeMaterial->type === 'video' && in_array($activeMaterial->video_source, ['youtube', 'vimeo']))
                    <iframe class="aula-visualizer-iframe" src="{{ $activeMaterial->embed_url }}" allowfullscreen allow="autoplay; encrypted-media"></iframe>

                {{-- Type: VIDEO (Uploaded File) --}}
                @elseif ($activeMaterial->type === 'video' && $activeMaterial->is_uploaded_video)
                    <video class="aula-visualizer-video" controls controlsList="nodownload">
                        <source src="{{ route('mi-cuenta.cursos.file', [$course, $activeMaterial]) }}" type="{{ $activeMaterial->file_type ?? 'video/mp4' }}">
                        Tu navegador no soporta reproducción de video HTML5.
                    </video>

                {{-- Type: DOCUMENT / PRESENTATION (PDF) --}}
                @elseif (in_array($activeMaterial->type, ['documento', 'presentacion']) && $activeMaterial->file_path && str_contains($activeMaterial->file_type, 'pdf'))
                    <iframe class="aula-visualizer-document" src="{{ route('mi-cuenta.cursos.file', [$course, $activeMaterial]) }}#toolbar=0"></iframe>

                {{-- Type: DOCUMENT / PRESENTATION (Fallback for non-PDF files like docx, pptx) --}}
                @elseif (in_array($activeMaterial->type, ['documento', 'presentacion']))
                    <div class="aula-download-card">
                        <div class="aula-download-icon">📄</div>
                        <h2 style="font-size:18px; font-weight:700;">Archivo de estudio adjunto</h2>
                        <p style="color:#94a3b8; font-size:13.5px; max-width:380px;">Este archivo no puede visualizarse directamente en el aula virtual. Haz clic a continuación para descargarlo y revisarlo localmente.</p>
                        <a href="{{ route('mi-cuenta.cursos.file', [$course, $activeMaterial]) }}?download=1" class="aula-download-btn">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                            Descargar documento ({{ strtoupper(pathinfo($activeMaterial->file_path, PATHINFO_EXTENSION)) }})
                        </a>
                    </div>

                {{-- Type: TEXTO (Rich text) --}}
                @elseif ($activeMaterial->type === 'texto')
                    <div class="aula-visualizer-text">
                        {!! $activeMaterial->content !!}
                    </div>

                {{-- Type: RECURSO (Downloadable file) --}}
                @elseif ($activeMaterial->type === 'recurso')
                    <div class="aula-download-card">
                        <div class="aula-download-icon">📦</div>
                        <h2 style="font-size:18px; font-weight:700;">Recurso adicional descargable</h2>
                        <p style="color:#94a3b8; font-size:13.5px; max-width:380px;">Descarga esta plantilla, hoja de cálculo o material de soporte práctico provisto por tu docente.</p>
                        <a href="{{ route('mi-cuenta.cursos.file', [$course, $activeMaterial]) }}?download=1" class="aula-download-btn" style="background:#059669;">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                            Descargar recurso ({{ strtoupper(pathinfo($activeMaterial->file_path, PATHINFO_EXTENSION)) }})
                        </a>
                    </div>
                @endif

            </div>

            {{-- Lesson Description & Complete Action --}}
            <div class="aula-meta">
                <div class="aula-meta-left">
                    <h1 class="aula-meta-title">{{ $activeMaterial->title }}</h1>
                    @if ($activeMaterial->description)
                        <p class="aula-meta-desc">{{ $activeMaterial->description }}</p>
                    @endif
                    <div class="aula-meta-badge">
                        <span>{{ $activeMaterial->type_icon }} {{ ucfirst($activeMaterial->type) }}</span>
                        @if ($activeMaterial->duration_minutes)
                            <span>• ⏱ {{ $activeMaterial->duration_minutes }} minutos</span>
                        @endif
                    </div>
                </div>
                <div>
                    @php
                        $isActiveCompleted = in_array($activeMaterial->id, $completedMaterialIds);
                    @endphp
                    <button id="toggleCompletionBtn" class="aula-toggle-btn {{ $isActiveCompleted ? 'completed' : '' }}" onclick="toggleLessonCompletion('{{ $activeMaterial->id }}')">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                        <span id="toggleCompletionText">{{ $isActiveCompleted ? 'Completado' : 'Marcar como Completado' }}</span>
                    </button>
                </div>
            </div>

            {{-- Prev / Next Navigation --}}
            <div class="aula-nav-wrap">
                @if ($prevMaterial)
                    <a href="?material={{ $prevMaterial->id }}" class="aula-nav-btn">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
                        Anterior: {{ Str::limit($prevMaterial->title, 25) }}
                    </a>
                @else
                    <button class="aula-nav-btn" disabled>
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
                        Anterior
                    </button>
                @endif

                @if ($nextMaterial)
                    <a href="?material={{ $nextMaterial->id }}" class="aula-nav-btn" style="margin-left: auto;">
                        Siguiente: {{ Str::limit($nextMaterial->title, 25) }}
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
                    </a>
                @else
                    <button class="aula-nav-btn" disabled style="margin-left: auto;">
                        Siguiente
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
                    </button>
                @endif
            </div>
        @endif
    </main>

</div>

@endsection

@push('scripts')
<script>
    /**
     * Toggle Module Accordion lists
     */
    function toggleModuleAccordion(btn, contentId) {
        const content = document.getElementById(contentId);
        btn.classList.toggle('active');
        content.classList.toggle('active');
    }

    /**
     * AJAX call to toggle lesson completion status
     */
    function toggleLessonCompletion(materialId) {
        const button = document.getElementById('toggleCompletionBtn');
        const textSpan = document.getElementById('toggleCompletionText');
        
        // Prevent double submit during request
        button.disabled = true;

        fetch('{{ route("mi-cuenta.cursos.complete-material", [$course, "MATERIAL_ID"]) }}'.replace('MATERIAL_ID', materialId), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en el servidor');
            }
            return response.json();
        })
        .then(data => {
            if (data.ok) {
                // Update button styles
                if (data.completed) {
                    button.classList.add('completed');
                    textSpan.textContent = 'Completado';
                } else {
                    button.classList.remove('completed');
                    textSpan.textContent = 'Marcar como Completado';
                }

                // Update sidebar list styling
                const sidebarItem = document.getElementById('sidebar-material-' + materialId);
                if (sidebarItem) {
                    if (data.completed) {
                        sidebarItem.classList.add('completed');
                    } else {
                        sidebarItem.classList.remove('completed');
                    }
                }

                // Update course progress fill & text
                const progressFill = document.getElementById('courseProgressFill');
                const progressText = document.querySelector('.aula-progress-pct');
                if (progressFill && progressText) {
                    const progressPct = Math.round(data.progress);
                    progressFill.style.width = progressPct + '%';
                    progressText.textContent = progressPct + '%';
                }
            }
        })
        .catch(error => {
            console.error('Error toggling material completion:', error);
            alert('Ocurrió un error al registrar el progreso. Inténtalo de nuevo.');
        })
        .finally(() => {
            button.disabled = false;
        });
    }
</script>
@endpush

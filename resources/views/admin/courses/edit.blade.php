@extends('layouts.admin')

@section('page-title', 'Editar Curso')

@push('admin-styles')
<style>
    .tabs {
        display: flex;
        border-bottom: 1px solid var(--gray-200);
        margin-bottom: 24px;
        gap: 8px;
    }
    .tab-btn {
        padding: 12px 20px;
        background: none;
        border: none;
        border-bottom: 2px solid transparent;
        font-family: inherit;
        font-weight: 600;
        font-size: 14px;
        color: var(--gray-400);
        cursor: pointer;
        transition: all .2s;
    }
    .tab-btn:hover {
        color: var(--blue-600);
    }
    .tab-btn.active {
        color: var(--blue-600);
        border-bottom-color: var(--blue-600);
    }
    .tab-content {
        display: none;
    }
    .tab-content.active {
        display: block;
    }
    .form-group {
        margin-bottom: 20px;
    }
    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
    }
    input[type="text"], input[type="number"], input[type="date"], select, textarea {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid var(--gray-200);
        border-radius: 8px;
        font-family: inherit;
        font-size: 13.5px;
        background: var(--white);
        color: var(--gray-800);
        transition: all .2s;
        outline: none;
        box-sizing: border-box;
    }
    input:focus, select:focus, textarea:focus {
        border-color: var(--blue-500);
        box-shadow: 0 0 0 3px var(--blue-100);
    }
    label {
        display: block;
        font-size: 12.5px;
        font-weight: 600;
        margin-bottom: 6px;
        color: var(--gray-600);
    }
    .help-text {
        font-size: 11px;
        color: var(--gray-400);
        margin-top: 4px;
    }
    .btn-submit {
        background: linear-gradient(135deg, var(--blue-600), var(--sky-500));
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-submit:hover {
        opacity: 0.95;
        transform: translateY(-1px);
    }

    /* Modales */
    .modal-backdrop {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(15, 23, 42, 0.45);
        backdrop-filter: blur(4px);
        z-index: 1000;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.25s ease;
    }
    .modal-backdrop.open {
        opacity: 1;
        pointer-events: auto;
    }
    .modal-box {
        background: var(--white);
        border-radius: 16px;
        width: 100%;
        max-width: 600px;
        box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
        border: 1px solid var(--gray-200);
        overflow: hidden;
        transform: scale(0.95);
        transition: transform 0.25s ease;
    }
    .modal-backdrop.open .modal-box {
        transform: scale(1);
    }
    .modal-header {
        padding: 16px 24px;
        border-bottom: 1px solid var(--gray-100);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .modal-title {
        font-weight: 700;
        font-size: 15px;
        color: var(--gray-800);
    }
    .modal-close {
        background: none;
        border: none;
        font-size: 20px;
        color: var(--gray-400);
        cursor: pointer;
    }
    .modal-close:hover {
        color: var(--gray-600);
    }
    .modal-body {
        padding: 24px;
        max-height: 70vh;
        overflow-y: auto;
    }

    /* Constructor de Contenido */
    .modules-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
    .module-card {
        background: var(--white);
        border-radius: 12px;
        border: 1px solid var(--gray-200);
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        overflow: hidden;
    }
    .module-header {
        padding: 14px 20px;
        background: var(--gray-50);
        border-bottom: 1px solid var(--gray-100);
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
    }
    .module-header-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .drag-handle {
        cursor: grab;
        color: var(--gray-400);
        font-size: 16px;
    }
    .drag-handle:active {
        cursor: grabbing;
    }
    .materials-list {
        padding: 12px 20px;
        display: flex;
        flex-direction: column;
        gap: 8px;
        background: var(--white);
    }
    .material-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 14px;
        border-radius: 8px;
        border: 1px solid var(--gray-100);
        background: var(--gray-50);
        font-size: 13px;
        transition: all 0.2s;
    }
    .material-item:hover {
        border-color: var(--blue-200);
        background: var(--blue-50);
    }
    .material-left {
        display: flex;
        align-items: center;
        gap: 10px;
    }
</style>
@endpush

@section('content')

<div class="page-header" style="margin-bottom: 20px;">
    <div>
        <h1>Editar Curso</h1>
        <p>Modifica la información general, comercial, SEO o el temario estructurado del curso.</p>
    </div>
    <div>
        <a href="{{ route('admin.courses.index') }}" class="btn-secondary" style="text-decoration:none;">
            Volver al listado
        </a>
    </div>
</div>

@if (session('success'))
    <div style="background:#dcfce7;color:#15803d;padding:12px 16px;border-radius:8px;margin-bottom:20px;border:1px solid #bbf7d0;font-size:14px;font-weight:500;">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div style="background:#fee2e2;color:#b91c1c;padding:12px 16px;border-radius:8px;margin-bottom:20px;border:1px solid #fecaca;font-size:14px;font-weight:500;">
        {{ session('error') }}
    </div>
@endif

@if ($errors->any())
    <div style="background:#fee2e2;color:#b91c1c;padding:12px 16px;border-radius:8px;margin-bottom:20px;border:1px solid #fecaca;font-size:13px;">
        <strong style="display:block;margin-bottom:6px;">Por favor corrige los siguientes errores:</strong>
        <ul style="margin-left:20px;padding-left:0;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="tabs">
    <button type="button" class="tab-btn active" data-tab="general">Información General</button>
    <button type="button" class="tab-btn" data-tab="comercial">Comercial & Fechas</button>
    <button type="button" class="tab-btn" data-tab="seo">Optimización SEO</button>
    <button type="button" class="tab-btn" data-tab="contenido">Contenido (Temario)</button>
</div>

{{-- FORMULARIO EDICIÓN --}}
<form id="course-update-form" method="POST" action="{{ route('admin.courses.update', $course) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    {{-- TAB GENERAL --}}
    <div class="tab-content active" id="tab-general">
        <div class="card" style="padding:24px;">
            <div class="form-row">
                <div class="form-group" style="grid-column: span 2;">
                    <label for="name">Nombre del Curso <span style="color:var(--danger)">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name', $course->name) }}" required>
                </div>
                <div class="form-group" style="grid-column: span 1;">
                    <label for="slug">Slug <span style="color:var(--danger)">*</span></label>
                    <input type="text" id="slug" name="slug" value="{{ old('slug', $course->slug) }}" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="category_id">Categoría <span style="color:var(--danger)">*</span></label>
                    <select id="category_id" name="category_id" required>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $course->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="level">Nivel <span style="color:var(--danger)">*</span></label>
                    <select id="level" name="level" required>
                        <option value="basico" {{ old('level', $course->level) == 'basico' ? 'selected' : '' }}>Básico</option>
                        <option value="intermedio" {{ old('level', $course->level) == 'intermedio' ? 'selected' : '' }}>Intermedio</option>
                        <option value="avanzado" {{ old('level', $course->level) == 'avanzado' ? 'selected' : '' }}>Avanzado</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="instructor_id">Instructor</label>
                    <select id="instructor_id" name="instructor_id">
                        <option value="">Sin instructor asignado</option>
                        @foreach ($instructors as $inst)
                            <option value="{{ $inst->id }}" {{ old('instructor_id', $course->instructor_id) == $inst->id ? 'selected' : '' }}>{{ $inst->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="short_description">Descripción Corta</label>
                <textarea id="short_description" name="short_description" rows="2">{{ old('short_description', $course->short_description) }}</textarea>
            </div>

            <div class="form-group">
                <label for="description">Descripción Detallada</label>
                <textarea id="description" name="description" rows="6">{{ old('description', $course->description) }}</textarea>
            </div>

            <div class="form-row" style="align-items: center;">
                <div class="form-group" style="flex:1;">
                    <label for="cover_image">Imagen de Portada</label>
                    <input type="file" id="cover_image" name="cover_image" accept="image/*">
                    <span class="help-text">Sube una nueva imagen solo si deseas reemplazar la portada actual.</span>
                </div>
                <div class="form-group" style="width:200px;text-align:center;">
                    <label>Portada actual</label>
                    <div style="width:100%;height:100px;border:1px solid var(--gray-200);border-radius:8px;display:flex;align-items:center;justify-content:center;background:var(--gray-50);overflow:hidden;">
                        <img id="cover_preview" src="{{ $course->cover_image ?: '#' }}" alt="Preview" style="width:100%;height:100%;object-fit:cover; {{ $course->cover_image ? '' : 'display:none;' }}">
                        <span id="cover_placeholder" style="font-size:11px;color:var(--gray-400); {{ $course->cover_image ? 'display:none;' : '' }}">Sin imagen</span>
                    </div>
                </div>
                <div class="form-group" style="width:150px;">
                    <label for="status">Estado <span style="color:var(--danger)">*</span></label>
                    <select id="status" name="status" required>
                        <option value="borrador" {{ old('status', $course->status) == 'borrador' ? 'selected' : '' }}>Borrador</option>
                        <option value="publicado" {{ old('status', $course->status) == 'publicado' ? 'selected' : '' }}>Publicado</option>
                        <option value="archivado" {{ old('status', $course->status) == 'archivado' ? 'selected' : '' }}>Archivado</option>
                    </select>
                </div>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:12px;margin-top:20px;border-top:1px solid var(--gray-100);padding-top:20px;">
                <button type="submit" class="btn-submit">Guardar Cambios del Curso</button>
            </div>
        </div>
    </div>

    {{-- TAB COMERCIAL --}}
    <div class="tab-content" id="tab-comercial">
        <div class="card" style="padding:24px;">
            <div class="form-row">
                <div class="form-group">
                    <label for="price">Precio Base (S/) <span style="color:var(--danger)">*</span></label>
                    <input type="number" id="price" name="price" step="0.01" min="0" value="{{ old('price', $course->price) }}" required>
                </div>
                <div class="form-group">
                    <label for="sale_price">Precio de Oferta (S/)</label>
                    <input type="number" id="sale_price" name="sale_price" step="0.01" min="0" value="{{ old('sale_price', $course->sale_price) }}">
                </div>
                <div class="form-group">
                    <label for="duration_weeks">Duración (en semanas) <span style="color:var(--danger)">*</span></label>
                    <input type="number" id="duration_weeks" name="duration_weeks" min="1" value="{{ old('duration_weeks', $course->duration_weeks) }}" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="sale_start">Inicio de Oferta</label>
                    <input type="date" id="sale_start" name="sale_start" value="{{ old('sale_start', $course->sale_start ? $course->sale_start->format('Y-m-d') : '') }}">
                </div>
                <div class="form-group">
                    <label for="sale_end">Fin de Oferta</label>
                    <input type="date" id="sale_end" name="sale_end" value="{{ old('sale_end', $course->sale_end ? $course->sale_end->format('Y-m-d') : '') }}">
                </div>
                <div class="form-group" style="display:flex;align-items:center;padding-top:20px;">
                    <label style="display:inline-flex;align-items:center;cursor:pointer;font-weight:500;">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $course->is_featured) ? 'checked' : '' }} style="width:18px;height:18px;margin-right:8px;cursor:pointer;">
                        Destacar curso en el catálogo
                    </label>
                </div>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:12px;margin-top:20px;border-top:1px solid var(--gray-100);padding-top:20px;">
                <button type="submit" class="btn-submit">Guardar Cambios del Curso</button>
            </div>
        </div>
    </div>

    {{-- TAB SEO --}}
    <div class="tab-content" id="tab-seo">
        <div class="card" style="padding:24px;">
            <div class="form-group">
                <label for="meta_description">Meta Descripción</label>
                <textarea id="meta_description" name="meta_description" rows="4" maxlength="300">{{ old('meta_description', $course->meta_description) }}</textarea>
                <span class="help-text">Escribe un texto atractivo para los buscadores de internet.</span>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:12px;margin-top:20px;border-top:1px solid var(--gray-100);padding-top:20px;">
                <button type="submit" class="btn-submit">Guardar Cambios del Curso</button>
            </div>
        </div>
    </div>
</form>

{{-- TAB CONTENIDO (CONSTRUCTOR DE TEMARIO) --}}
<div class="tab-content" id="tab-contenido">
    <div class="page-header" style="margin-bottom:15px;align-items:center;">
        <div>
            <h2 style="font-size:16px;font-weight:700;">Temario del Curso</h2>
            <p>Define los módulos del curso y sus lecciones/materiales. Los módulos son arrastrables para ordenar.</p>
        </div>
        <div>
            <button type="button" class="btn-primary" onclick="openCreateModuleModal()">
                + Crear Módulo
            </button>
        </div>
    </div>

    @if ($course->modules->isEmpty())
        <div class="card" style="padding:40px;text-align:center;color:var(--gray-400)">
            <p style="font-size:14px;margin-bottom:10px;">Este curso aún no tiene módulos de aprendizaje.</p>
            <button type="button" class="btn-secondary" onclick="openCreateModuleModal()">Crear el primer módulo</button>
        </div>
    @else
        <div class="modules-list" id="modules-container">
            @foreach ($course->modules as $module)
                <div class="module-card" data-id="{{ $module->id }}">
                    <div class="module-header">
                        <div class="module-header-left">
                            <span class="drag-handle">☰</span>
                            <strong>{{ $module->name }}</strong>
                            @if ($module->status !== 'activo')
                                <span class="badge" style="background:#fee2e2;color:#b91c1c;font-size:10px;">Inactivo</span>
                            @endif
                        </div>
                        <div style="display:flex;gap:8px;align-items:center;" onclick="event.stopPropagation()">
                            <button type="button" class="btn-secondary" style="padding:4px 10px;font-size:11.5px;" onclick="openCreateMaterialModal({{ $module->id }})">
                                + Agregar Material
                            </button>
                            <button type="button" style="background:none;border:none;cursor:pointer;font-size:13px;" onclick="openEditModuleModal({{ $module->id }}, '{{ addslashes($module->name) }}', '{{ addslashes($module->description) }}', '{{ $module->status }}')" title="Editar Módulo">✏️</button>
                            <form method="POST" action="{{ route('admin.modules.destroy', $module) }}" style="display:inline" onsubmit="return confirm('¿Seguro de eliminar este módulo? Eliminará todos sus materiales asociados.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background:none;border:none;cursor:pointer;font-size:13px;" title="Eliminar Módulo">🗑️</button>
                            </form>
                        </div>
                    </div>
                    <div class="materials-list">
                        @if ($module->materials->isEmpty())
                            <p style="font-size:11.5px;color:var(--gray-400);padding:8px 0;text-align:center;">No hay materiales cargados en este módulo.</p>
                        @else
                            @foreach ($module->materials as $material)
                                <div class="material-item">
                                    <div class="material-left">
                                        <span style="font-size:14px;">{{ $material->type_icon }}</span>
                                        <strong>{{ $material->title }}</strong>
                                        <span style="font-size:10px;background:var(--blue-50);color:var(--blue-600);padding:2px 6px;border-radius:4px;text-transform:uppercase;">
                                            {{ $material->type }}
                                        </span>
                                        @if ($material->is_downloadable)
                                            <span style="font-size:10px;background:#dcfce7;color:#16a34a;padding:2px 6px;border-radius:4px;">Descargable</span>
                                        @endif
                                    </div>
                                    <div style="display:flex;gap:8px;align-items:center;">
                                        <button type="button" style="background:none;border:none;cursor:pointer;font-size:12px;" onclick="openEditMaterialModal({{ json_encode($material) }})" title="Editar Material">✏️</button>
                                        <form method="POST" action="{{ route('admin.materials.destroy', $material) }}" style="display:inline" onsubmit="return confirm('¿Seguro de eliminar este material?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" style="background:none;border:none;cursor:pointer;font-size:12px;" title="Eliminar Material">🗑️</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- MODAL CREAR MÓDULO --}}
<div class="modal-backdrop" id="modal-create-module" onclick="closeModal('modal-create-module')">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3 class="modal-title">Crear Nuevo Módulo</h3>
            <button class="modal-close" onclick="closeModal('modal-create-module')">&times;</button>
        </div>
        <form method="POST" action="{{ route('admin.modules.store') }}" class="modal-body">
            @csrf
            <input type="hidden" name="course_id" value="{{ $course->id }}">
            <div class="form-group">
                <label for="module_name">Nombre del Módulo <span style="color:var(--danger)">*</span></label>
                <input type="text" id="module_name" name="name" required placeholder="Ej. Conceptos clave y definiciones">
            </div>
            <div class="form-group">
                <label for="module_desc">Descripción</label>
                <textarea id="module_desc" name="description" rows="3" placeholder="Descripción breve del contenido de este módulo..."></textarea>
            </div>
            <div class="form-group">
                <label for="module_status">Estado</label>
                <select id="module_status" name="status" required>
                    <option value="activo">Activo</option>
                    <option value="inactivo">Inactivo</option>
                </select>
            </div>
            <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:20px;">
                <button type="button" class="btn-secondary" onclick="closeModal('modal-create-module')">Cancelar</button>
                <button type="submit" class="btn-submit" style="padding:10px 20px;">Crear Módulo</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDITAR MÓDULO --}}
<div class="modal-backdrop" id="modal-edit-module" onclick="closeModal('modal-edit-module')">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3 class="modal-title">Editar Módulo</h3>
            <button class="modal-close" onclick="closeModal('modal-edit-module')">&times;</button>
        </div>
        <form id="form-edit-module" method="POST" action="#" class="modal-body">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="edit_module_name">Nombre del Módulo <span style="color:var(--danger)">*</span></label>
                <input type="text" id="edit_module_name" name="name" required>
            </div>
            <div class="form-group">
                <label for="edit_module_desc">Descripción</label>
                <textarea id="edit_module_desc" name="description" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label for="edit_module_status">Estado</label>
                <select id="edit_module_status" name="status" required>
                    <option value="activo">Activo</option>
                    <option value="inactivo">Inactivo</option>
                </select>
            </div>
            <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:20px;">
                <button type="button" class="btn-secondary" onclick="closeModal('modal-edit-module')">Cancelar</button>
                <button type="submit" class="btn-submit" style="padding:10px 20px;">Guardar Módulo</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL CREAR MATERIAL --}}
<div class="modal-backdrop" id="modal-create-material" onclick="closeModal('modal-create-material')">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3 class="modal-title">Agregar Material de Aprendizaje</h3>
            <button class="modal-close" onclick="closeModal('modal-create-material')">&times;</button>
        </div>
        <form method="POST" action="{{ route('admin.materials.store') }}" enctype="multipart/form-data" class="modal-body">
            @csrf
            <input type="hidden" name="module_id" id="create-material-module-id">

            <div class="form-row">
                <div class="form-group">
                    <label for="material_type">Tipo de Material <span style="color:var(--danger)">*</span></label>
                    <select id="material_type" name="type" required onchange="toggleMaterialTypeFields(this.value, 'create')">
                        <option value="video">Vídeo</option>
                        <option value="documento">Documento (PDF, Word)</option>
                        <option value="presentacion">Presentación (PPTX)</option>
                        <option value="texto">Texto Enriquecido (Lectura)</option>
                        <option value="recurso">Recurso Descargable (ZIP, Excel)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="material_title">Título del Material <span style="color:var(--danger)">*</span></label>
                    <input type="text" id="material_title" name="title" required placeholder="Ej. Introducción a la inocuidad">
                </div>
            </div>

            <div class="form-group">
                <label for="material_desc">Descripción / Resumen</label>
                <textarea id="material_desc" name="description" rows="2" placeholder="Opcional. Breve indicación para el estudiante..."></textarea>
            </div>

            {{-- FIELDS: VIDEO SOURCE --}}
            <div class="form-row type-field-video-source-create">
                <div class="form-group">
                    <label for="video_source">Origen del Vídeo <span style="color:var(--danger)">*</span></label>
                    <select id="video_source" name="video_source" onchange="toggleVideoSourceFields(this.value, 'create')">
                        <option value="youtube">YouTube (URL)</option>
                        <option value="vimeo">Vimeo (URL)</option>
                        <option value="upload">Subir Archivo de Vídeo</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="duration_minutes">Duración (minutos)</label>
                    <input type="number" id="duration_minutes" name="duration_minutes" min="0" placeholder="Ej. 15">
                </div>
            </div>

            {{-- FIELDS: VIDEO URL --}}
            <div class="form-group type-field-video-url-create">
                <label for="video_url">URL del Vídeo <span style="color:var(--danger)">*</span></label>
                <input type="text" id="video_url" name="video_url" placeholder="https://www.youtube.com/watch?v=...">
            </div>

            {{-- FIELDS: FILE UPLOAD --}}
            <div class="form-group type-field-file-create" style="display:none;">
                <label for="file_upload">Subir Archivo <span style="color:var(--danger)">*</span></label>
                <input type="file" id="file_upload" name="file">
                <span class="help-text" id="file_limits_help"></span>
            </div>

            {{-- FIELDS: RICH TEXT (QUILL) --}}
            <div class="form-group type-field-text-create" style="display:none;">
                <label>Contenido de Lectura (Texto Enriquecido) <span style="color:var(--danger)">*</span></label>
                <div id="quill-editor-create" style="height: 200px;background:white;"></div>
                <input type="hidden" name="content" id="quill-content-create">
            </div>

            <div class="form-group" id="downloadable-wrapper-create">
                <label style="display:inline-flex;align-items:center;cursor:pointer;font-weight:500;">
                    <input type="checkbox" name="is_downloadable" value="1" style="width:18px;height:18px;margin-right:8px;cursor:pointer;">
                    Permitir descarga del archivo
                </label>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:20px;">
                <button type="button" class="btn-secondary" onclick="closeModal('modal-create-material')">Cancelar</button>
                <button type="submit" class="btn-submit" style="padding:10px 20px;">Agregar Material</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDITAR MATERIAL --}}
<div class="modal-backdrop" id="modal-edit-material" onclick="closeModal('modal-edit-material')">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3 class="modal-title">Editar Material</h3>
            <button class="modal-close" onclick="closeModal('modal-edit-material')">&times;</button>
        </div>
        <form id="form-edit-material" method="POST" action="#" enctype="multipart/form-data" class="modal-body">
            @csrf
            @method('PUT')

            <div class="form-row">
                <div class="form-group">
                    <label for="edit_material_type">Tipo de Material <span style="color:var(--danger)">*</span></label>
                    <select id="edit_material_type" name="type" required onchange="toggleMaterialTypeFields(this.value, 'edit')">
                        <option value="video">Vídeo</option>
                        <option value="documento">Documento (PDF, Word)</option>
                        <option value="presentacion">Presentación (PPTX)</option>
                        <option value="texto">Texto Enriquecido (Lectura)</option>
                        <option value="recurso">Recurso Descargable (ZIP, Excel)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="edit_material_title">Título del Material <span style="color:var(--danger)">*</span></label>
                    <input type="text" id="edit_material_title" name="title" required>
                </div>
            </div>

            <div class="form-group">
                <label for="edit_material_desc">Descripción / Resumen</label>
                <textarea id="edit_material_desc" name="description" rows="2"></textarea>
            </div>

            {{-- FIELDS: VIDEO SOURCE --}}
            <div class="form-row type-field-video-source-edit">
                <div class="form-group">
                    <label for="edit_video_source">Origen del Vídeo <span style="color:var(--danger)">*</span></label>
                    <select id="edit_video_source" name="video_source" onchange="toggleVideoSourceFields(this.value, 'edit')">
                        <option value="youtube">YouTube (URL)</option>
                        <option value="vimeo">Vimeo (URL)</option>
                        <option value="upload">Subir Archivo de Vídeo</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="edit_duration_minutes">Duración (minutos)</label>
                    <input type="number" id="edit_duration_minutes" name="duration_minutes" min="0">
                </div>
            </div>

            {{-- FIELDS: VIDEO URL --}}
            <div class="form-group type-field-video-url-edit">
                <label for="edit_video_url">URL del Vídeo <span style="color:var(--danger)">*</span></label>
                <input type="text" id="edit_video_url" name="video_url">
            </div>

            {{-- FIELDS: FILE UPLOAD --}}
            <div class="form-group type-field-file-edit" style="display:none;">
                <label for="edit_file_upload">Reemplazar Archivo</label>
                <input type="file" id="edit_file_upload" name="file">
                <span class="help-text" id="edit_file_limits_help">Deja en blanco para conservar el archivo existente.</span>
            </div>

            {{-- FIELDS: RICH TEXT (QUILL) --}}
            <div class="form-group type-field-text-edit" style="display:none;">
                <label>Contenido de Lectura (Texto Enriquecido) <span style="color:var(--danger)">*</span></label>
                <div id="quill-editor-edit" style="height: 200px;background:white;"></div>
                <input type="hidden" name="content" id="quill-content-edit">
            </div>

            <div class="form-group" id="downloadable-wrapper-edit">
                <label style="display:inline-flex;align-items:center;cursor:pointer;font-weight:500;">
                    <input type="checkbox" id="edit_is_downloadable" name="is_downloadable" value="1" style="width:18px;height:18px;margin-right:8px;cursor:pointer;">
                    Permitir descarga del archivo
                </label>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:20px;">
                <button type="button" class="btn-secondary" onclick="closeModal('modal-edit-material')">Cancelar</button>
                <button type="submit" class="btn-submit" style="padding:10px 20px;">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Global instances for Quill
let quillCreateInstance = null;
let quillEditInstance = null;

document.addEventListener('DOMContentLoaded', () => {
    // Manejo de tabs principales
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            
            btn.classList.add('active');
            document.getElementById('tab-' + btn.dataset.tab).classList.add('active');
        });
    });

    // Autogenerar slug en edición
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    if (nameInput && slugInput) {
        nameInput.addEventListener('input', () => {
            let slug = nameInput.value.toLowerCase()
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .replace(/[^a-z0-9\s-]/g, '')
                .trim()
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
            slugInput.value = slug;
        });
    }

    // Preview de portada
    const coverInput = document.getElementById('cover_image');
    const previewImg = document.getElementById('cover_preview');
    const placeholder = document.getElementById('cover_placeholder');
    if (coverInput && previewImg && placeholder) {
        coverInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                previewImg.src = URL.createObjectURL(file);
                previewImg.style.display = 'block';
                placeholder.style.display = 'none';
            }
        });
    }

    // Drag-and-drop de Módulos (SortableJS)
    const el = document.getElementById('modules-container');
    if (el) {
        Sortable.create(el, {
            handle: '.drag-handle',
            animation: 150,
            onEnd: function (evt) {
                const ids = Array.from(el.querySelectorAll('.module-card')).map(card => card.dataset.id);
                fetch('{{ route("admin.modules.reorder") }}', {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ ids: ids })
                })
                .then(res => res.json())
                .then(data => {
                    console.log('Reordenamiento guardado con éxito:', data);
                })
                .catch(err => console.error('Error reordenando módulos:', err));
            }
        });
    }

    // Inicializar Quill para creación
    const createEditorEl = document.getElementById('quill-editor-create');
    if (createEditorEl) {
        quillCreateInstance = new Quill('#quill-editor-create', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['clean']
                ]
            }
        });
        quillCreateInstance.on('text-change', () => {
            document.getElementById('quill-content-create').value = quillCreateInstance.root.innerHTML;
        });
    }

    // Inicializar Quill para edición
    const editEditorEl = document.getElementById('quill-editor-edit');
    if (editEditorEl) {
        quillEditInstance = new Quill('#quill-editor-edit', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['clean']
                ]
            }
        });
        quillEditInstance.on('text-change', () => {
            document.getElementById('quill-content-edit').value = quillEditInstance.root.innerHTML;
        });
    }

    // Default fields view initialization
    toggleMaterialTypeFields('video', 'create');
});

// MODALES HELPERS
function openModal(id) {
    document.getElementById(id).classList.add('open');
}

function closeModal(id) {
    document.getElementById(id).classList.remove('open');
}

function openCreateModuleModal() {
    openModal('modal-create-module');
}

function openEditModuleModal(id, name, desc, status) {
    const form = document.getElementById('form-edit-module');
    form.action = '/admin/modules/' + id;
    document.getElementById('edit_module_name').value = name;
    document.getElementById('edit_module_desc').value = desc;
    document.getElementById('edit_module_status').value = status;
    openModal('modal-edit-module');
}

function openCreateMaterialModal(moduleId) {
    document.getElementById('create-material-module-id').value = moduleId;
    openModal('modal-create-material');
}

function openEditMaterialModal(material) {
    const form = document.getElementById('form-edit-material');
    form.action = '/admin/materials/' + material.id;
    document.getElementById('edit_material_type').value = material.type;
    document.getElementById('edit_material_title').value = material.title;
    document.getElementById('edit_material_desc').value = material.description || '';
    
    toggleMaterialTypeFields(material.type, 'edit');

    if (material.type === 'video') {
        document.getElementById('edit_video_source').value = material.video_source || 'youtube';
        document.getElementById('edit_duration_minutes').value = material.duration_minutes || '';
        document.getElementById('edit_video_url').value = material.video_url || '';
        toggleVideoSourceFields(material.video_source || 'youtube', 'edit');
    } else if (material.type === 'texto') {
        if (quillEditInstance) {
            quillEditInstance.root.innerHTML = material.content || '';
            document.getElementById('quill-content-edit').value = material.content || '';
        }
    }

    document.getElementById('edit_is_downloadable').checked = !!material.is_downloadable;

    openModal('modal-edit-material');
}

// CONTROL DE CAMPOS POR TIPO DE MATERIAL
function toggleMaterialTypeFields(type, mode) {
    const videoSourceRow = document.querySelector(`.type-field-video-source-${mode}`);
    const videoUrlRow = document.querySelector(`.type-field-video-url-${mode}`);
    const fileRow = document.querySelector(`.type-field-file-${mode}`);
    const textRow = document.querySelector(`.type-field-text-${mode}`);
    const downloadableWrapper = document.getElementById(`downloadable-wrapper-${mode}`);

    // Hide all first
    videoSourceRow.style.display = 'none';
    videoUrlRow.style.display = 'none';
    fileRow.style.display = 'none';
    textRow.style.display = 'none';
    if (downloadableWrapper) downloadableWrapper.style.display = 'block';

    if (type === 'video') {
        videoSourceRow.style.display = 'flex';
        const sourceVal = document.getElementById(mode === 'create' ? 'video_source' : 'edit_video_source').value;
        toggleVideoSourceFields(sourceVal, mode);
        if (downloadableWrapper) downloadableWrapper.style.display = 'none'; // Videos generally not downloadable directly
    } else if (type === 'texto') {
        textRow.style.display = 'block';
        if (downloadableWrapper) downloadableWrapper.style.display = 'none';
    } else {
        // documento, presentacion, recurso
        fileRow.style.display = 'block';
        const limitsHelp = document.getElementById(mode === 'create' ? 'file_limits_help' : 'edit_file_limits_help');
        if (limitsHelp) {
            let size = '50 MB';
            let ext = 'PDF, DOCX';
            if (type === 'presentacion') { size = '50 MB'; ext = 'PPTX, PDF'; }
            else if (type === 'recurso') { size = '100 MB'; ext = 'ZIP, RAR, PDF, XLSX'; }
            
            limitsHelp.textContent = `Límite de tamaño: ${size}. Extensiones permitidas: ${ext}.`;
        }
    }
}

function toggleVideoSourceFields(source, mode) {
    const videoUrlRow = document.querySelector(`.type-field-video-url-${mode}`);
    const fileRow = document.querySelector(`.type-field-file-${mode}`);

    if (source === 'youtube' || source === 'vimeo') {
        videoUrlRow.style.display = 'block';
        fileRow.style.display = 'none';
    } else if (source === 'upload') {
        videoUrlRow.style.display = 'none';
        fileRow.style.display = 'block';
        const limitsHelp = document.getElementById(mode === 'create' ? 'file_limits_help' : 'edit_file_limits_help');
        if (limitsHelp) {
            limitsHelp.textContent = 'Límite de tamaño: 500 MB. Extensiones permitidas: MP4, WEBM.';
        }
    }
}
</script>
@endpush

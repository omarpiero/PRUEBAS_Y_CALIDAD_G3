@extends('layouts.admin')

@section('page-title', 'Crear Curso')

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
</style>
@endpush

@section('content')

<div class="page-header" style="margin-bottom: 20px;">
    <div>
        <h1>Crear Nuevo Curso</h1>
        <p>Completa la información básica, comercial y SEO para registrar el curso.</p>
    </div>
    <div>
        <a href="{{ route('admin.courses.index') }}" class="btn-secondary" style="text-decoration:none;">
            Volver al listado
        </a>
    </div>
</div>

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

<form method="POST" action="{{ route('admin.courses.store') }}" enctype="multipart/form-data" class="card" style="padding:24px;">
    @csrf

    <div class="tabs">
        <button type="button" class="tab-btn active" data-tab="general">Información General</button>
        <button type="button" class="tab-btn" data-tab="comercial">Comercial & Fechas</button>
        <button type="button" class="tab-btn" data-tab="seo">Optimización SEO</button>
    </div>

    {{-- TAB GENERAL --}}
    <div class="tab-content active" id="tab-general">
        <div class="form-row">
            <div class="form-group" style="grid-column: span 2;">
                <label for="name">Nombre del Curso <span style="color:var(--danger)">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Ej. Buenas Prácticas de Manufactura (BPM)">
            </div>
            <div class="form-group" style="grid-column: span 1;">
                <label for="slug">Slug <span style="color:var(--danger)">*</span></label>
                <input type="text" id="slug" name="slug" value="{{ old('slug') }}" required placeholder="bpm-en-industria-alimentaria">
                <span class="help-text">URL amigable del curso. Se autogenera al escribir el nombre.</span>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="category_id">Categoría <span style="color:var(--danger)">*</span></label>
                <select id="category_id" name="category_id" required>
                    <option value="">Seleccione una categoría</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="level">Nivel <span style="color:var(--danger)">*</span></label>
                <select id="level" name="level" required>
                    <option value="basico" {{ old('level') == 'basico' ? 'selected' : '' }}>Básico</option>
                    <option value="intermedio" {{ old('level', 'intermedio') == 'intermedio' ? 'selected' : '' }}>Intermedio</option>
                    <option value="avanzado" {{ old('level') == 'avanzado' ? 'selected' : '' }}>Avanzado</option>
                </select>
            </div>
            <div class="form-group">
                <label for="instructor_id">Instructor</label>
                <select id="instructor_id" name="instructor_id">
                    <option value="">Sin instructor asignado</option>
                    @foreach ($instructors as $inst)
                        <option value="{{ $inst->id }}" {{ old('instructor_id') == $inst->id ? 'selected' : '' }}>{{ $inst->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="short_description">Descripción Corta</label>
            <textarea id="short_description" name="short_description" rows="2" placeholder="Un breve resumen de 2-3 líneas para las tarjetas de catálogo...">{{ old('short_description') }}</textarea>
        </div>

        <div class="form-group">
            <label for="description">Descripción Detallada</label>
            <textarea id="description" name="description" rows="6" placeholder="Descripción completa del curso, objetivos, requisitos, etc. (Soporta Markdown/HTML básico)...">{{ old('description') }}</textarea>
        </div>

        <div class="form-row" style="align-items: center;">
            <div class="form-group" style="flex:1;">
                <label for="cover_image">Imagen de Portada</label>
                <input type="file" id="cover_image" name="cover_image" accept="image/*">
                <span class="help-text">Dimensiones sugeridas: 800x450px. Formatos: JPG, PNG, WEBP. Tamaño máx: 2MB.</span>
            </div>
            <div class="form-group" style="width:200px;text-align:center;">
                <label>Vista previa de portada</label>
                <div style="width:100%;height:100px;border:1px dashed var(--gray-200);border-radius:8px;display:flex;align-items:center;justify-content:center;background:var(--gray-50);overflow:hidden;">
                    <img id="cover_preview" src="#" alt="Preview" style="width:100%;height:100%;object-fit:cover;display:none;">
                    <span id="cover_placeholder" style="font-size:11px;color:var(--gray-400)">Sin imagen</span>
                </div>
            </div>
            <div class="form-group" style="width:150px;">
                <label for="status">Estado Inicial <span style="color:var(--danger)">*</span></label>
                <select id="status" name="status" required>
                    <option value="borrador" {{ old('status', 'borrador') == 'borrador' ? 'selected' : '' }}>Borrador</option>
                    <option value="publicado" {{ old('status') == 'publicado' ? 'selected' : '' }}>Publicado</option>
                    <option value="archivado" {{ old('status') == 'archivado' ? 'selected' : '' }}>Archivado</option>
                </select>
            </div>
        </div>
    </div>

    {{-- TAB COMERCIAL --}}
    <div class="tab-content" id="tab-comercial">
        <div class="form-row">
            <div class="form-group">
                <label for="price">Precio Base (S/) <span style="color:var(--danger)">*</span></label>
                <input type="number" id="price" name="price" step="0.01" min="0" value="{{ old('price', '0.00') }}" required placeholder="0.00">
            </div>
            <div class="form-group">
                <label for="sale_price">Precio de Oferta (S/)</label>
                <input type="number" id="sale_price" name="sale_price" step="0.01" min="0" value="{{ old('sale_price') }}" placeholder="Opcional">
            </div>
            <div class="form-group">
                <label for="duration_weeks">Duración (en semanas) <span style="color:var(--danger)">*</span></label>
                <input type="number" id="duration_weeks" name="duration_weeks" min="1" value="{{ old('duration_weeks', '4') }}" required placeholder="4">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="sale_start">Inicio de Oferta</label>
                <input type="date" id="sale_start" name="sale_start" value="{{ old('sale_start') }}">
            </div>
            <div class="form-group">
                <label for="sale_end">Fin de Oferta</label>
                <input type="date" id="sale_end" name="sale_end" value="{{ old('sale_end') }}">
            </div>
            <div class="form-group" style="display:flex;align-items:center;padding-top:20px;">
                <label style="display:inline-flex;align-items:center;cursor:pointer;font-weight:500;">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} style="width:18px;height:18px;margin-right:8px;cursor:pointer;">
                    Destacar curso en el catálogo
                </label>
            </div>
        </div>
    </div>

    {{-- TAB SEO --}}
    <div class="tab-content" id="tab-seo">
        <div class="form-group">
            <label for="meta_description">Meta Descripción</label>
            <textarea id="meta_description" name="meta_description" rows="4" maxlength="300" placeholder="Meta descripción para buscadores de Google (Máx 300 caracteres)...">{{ old('meta_description') }}</textarea>
            <span class="help-text">Escribe un texto atractivo para los buscadores de internet.</span>
        </div>
    </div>

    <div style="border-top:1px solid var(--gray-200);padding-top:20px;margin-top:20px;display:flex;justify-content:flex-end;gap:12px;">
        <a href="{{ route('admin.courses.index') }}" class="btn-secondary" style="padding:11px 22px;text-decoration:none;">
            Cancelar
        </a>
        <button type="submit" class="btn-submit">
            Guardar Curso
        </button>
    </div>
</form>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Manejo de tabs
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            
            btn.classList.add('active');
            document.getElementById('tab-' + btn.dataset.tab).classList.add('active');
        });
    });

    // Autogenerar slug
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    if (nameInput && slugInput) {
        nameInput.addEventListener('input', () => {
            // Slugify function
            let slug = nameInput.value.toLowerCase()
                .normalize('NFD') // decompose to separate diacritics
                .replace(/[\u0300-\u036f]/g, '') // remove diacritics
                .replace(/[^a-z0-9\s-]/g, '') // remove non-alphanumeric chars
                .trim()
                .replace(/\s+/g, '-') // replace spaces with hyphens
                .replace(/-+/g, '-'); // collapse multiple hyphens
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
});
</script>
@endpush

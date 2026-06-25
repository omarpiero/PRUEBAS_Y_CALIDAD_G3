@extends('layouts.admin')

@section('page-title', 'Cursos')

@section('content')

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

<div class="page-header" style="margin-bottom:20px;">
    <div>
        <h1>Gesti&oacute;n de Cursos</h1>
        <p>Crea, edita, publica, duplica y gestiona el cat&aacute;logo de cursos de la plataforma.</p>
    </div>
    <div>
        <a href="{{ route('admin.courses.create') }}" class="btn-primary" style="text-decoration:none;">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="vertical-align:middle;margin-right:4px;">
                <path d="M12 5v14M5 12h14"/>
            </svg>
            Nuevo Curso
        </a>
    </div>
</div>

{{-- Filtros --}}
<div class="card" style="margin-bottom:26px;">
    <div class="card-body" style="padding:16px 20px;">
        <form method="GET" action="{{ route('admin.courses.index') }}" style="display:flex;flex-wrap:wrap;gap:12px;align-items:flex-end;">
            <div style="flex:1;min-width:200px;">
                <label for="search" style="font-size:11.5px;margin-bottom:4px;">Buscar por nombre o descripción</label>
                <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Ej. BPM, Inocuidad..." style="padding:8px 12px;font-size:13px;">
            </div>
            
            <div style="width:160px;">
                <label for="category_id" style="font-size:11.5px;margin-bottom:4px;">Categor&iacute;a</label>
                <select id="category_id" name="category_id" style="padding:8px 12px;font-size:13px;">
                    <option value="">Todas</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div style="width:130px;">
                <label for="level" style="font-size:11.5px;margin-bottom:4px;">Nivel</label>
                <select id="level" name="level" style="padding:8px 12px;font-size:13px;">
                    <option value="">Todos</option>
                    <option value="basico" {{ request('level') == 'basico' ? 'selected' : '' }}>B&aacute;sico</option>
                    <option value="intermedio" {{ request('level') == 'intermedio' ? 'selected' : '' }}>Intermedio</option>
                    <option value="avanzado" {{ request('level') == 'avanzado' ? 'selected' : '' }}>Avanzado</option>
                </select>
            </div>

            <div style="width:130px;">
                <label for="status" style="font-size:11.5px;margin-bottom:4px;">Estado</label>
                <select id="status" name="status" style="padding:8px 12px;font-size:13px;">
                    <option value="">Todos</option>
                    <option value="borrador" {{ request('status') == 'borrador' ? 'selected' : '' }}>Borrador</option>
                    <option value="publicado" {{ request('status') == 'publicado' ? 'selected' : '' }}>Publicado</option>
                    <option value="archivado" {{ request('status') == 'archivado' ? 'selected' : '' }}>Archivado</option>
                </select>
            </div>

            <div style="display:flex;gap:8px;">
                <button type="submit" class="btn-primary" style="padding:8.5px 16px;">
                    Filtrar
                </button>
                <a href="{{ route('admin.courses.index') }}" class="btn-secondary" style="padding:8.5px 16px;text-decoration:none;">
                    Limpiar
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Tabla de Cursos --}}
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            Cat&aacute;logo General ({{ $courses->total() }} cursos)
        </div>
    </div>
    <div class="card-body" style="padding:0;">
        @if ($courses->isEmpty())
            <div style="padding:40px;text-align:center;color:var(--gray-400);font-size:14px;">
                <p>No se encontraron cursos que coincidan con los filtros seleccionados.</p>
            </div>
        @else
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Portada</th>
                        <th>Nombre / Detalle</th>
                        <th>Categor&iacute;a / Nivel</th>
                        <th>M&oacute;dulos</th>
                        <th>Estudiantes</th>
                        <th>Precio</th>
                        <th>Estado</th>
                        <th style="width:180px;text-align:right;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($courses as $course)
                        <tr>
                            <td>
                                <div style="width:60px;height:40px;border-radius:6px;overflow:hidden;background:#cbd5e1;border:1px solid var(--gray-200);">
                                    @if ($course->cover_image)
                                        <img src="{{ $course->cover_image }}" alt="Portada" style="width:100%;height:100%;object-fit:cover;">
                                    @else
                                        <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:10px;color:var(--gray-400);font-weight:600;">SIN FOTO</div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div style="font-weight:600;font-size:14px;color:var(--gray-800);">{{ $course->name }}</div>
                                <div style="font-size:11px;color:var(--gray-400);margin-top:2px;max-width:300px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                    {{ $course->short_description ?: 'Sin descripción corta.' }}
                                </div>
                            </td>
                            <td>
                                <div style="font-size:13px;font-weight:500;">{{ $course->category->name }}</div>
                                <div style="font-size:11px;color:var(--gray-400);margin-top:2px;text-transform:capitalize;">Nivel: {{ $course->level }}</div>
                            </td>
                            <td>
                                <span style="font-weight:600;color:var(--gray-600)">{{ $course->modules_count }}</span>
                            </td>
                            <td>
                                <span style="font-weight:600;color:var(--gray-600)">{{ $course->enrollments_count }}</span>
                            </td>
                            <td>
                                @if ($course->has_active_offer)
                                    <span style="font-weight:700;color:#16a34a;">S/ {{ number_format($course->effective_price, 2) }}</span>
                                    <div style="font-size:10.5px;color:var(--danger);text-decoration:line-through;margin-top:2px;">S/ {{ number_format($course->price, 2) }}</div>
                                @else
                                    <span style="font-weight:600;">S/ {{ number_format($course->price, 2) }}</span>
                                @endif
                            </td>
                            <td>
                                @if ($course->status === 'publicado')
                                    <span class="badge" style="background:#dcfce7;color:#15803d;">Publicado</span>
                                @elseif ($course->status === 'borrador')
                                    <span class="badge" style="background:#f1f5f9;color:#475569;">Borrador</span>
                                @else
                                    <span class="badge" style="background:#fee2e2;color:#b91c1c;">Archivado</span>
                                @endif
                            </td>
                            <td style="text-align:right;white-space:nowrap;">
                                <div style="display:inline-flex;gap:4px;align-items:center;">
                                    {{-- Publicar / Despublicar --}}
                                    @if ($course->status === 'publicado')
                                        <form method="POST" action="{{ route('admin.courses.unpublish', $course) }}" style="display:inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" title="Despublicar curso" style="background:none;border:1px solid #cbd5e1;padding:5px 8px;border-radius:6px;cursor:pointer;color:var(--gray-600);transition:all .15s;">
                                                🔒
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.courses.publish', $course) }}" style="display:inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" title="Publicar curso" style="background:none;border:1px solid #15803d;padding:5px 8px;border-radius:6px;cursor:pointer;color:#15803d;transition:all .15s;">
                                                🚀
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Duplicar --}}
                                    <form method="POST" action="{{ route('admin.courses.duplicate', $course) }}" style="display:inline;" onsubmit="return confirm('¿Desea duplicar la estructura completa de este curso?')">
                                        @csrf
                                        <button type="submit" title="Duplicar curso" style="background:none;border:1px solid #cbd5e1;padding:5px 8px;border-radius:6px;cursor:pointer;color:var(--gray-600);transition:all .15s;">
                                            👥
                                        </button>
                                    </form>

                                    {{-- Editar --}}
                                    <a href="{{ route('admin.courses.edit', $course) }}" title="Editar curso y temario" style="text-decoration:none;border:1px solid var(--blue-500);padding:5px 8px;border-radius:6px;color:var(--blue-600);font-size:12px;font-weight:600;display:inline-block;transition:all .15s;">
                                        ✏️
                                    </a>

                                    {{-- Eliminar --}}
                                    <form method="POST" action="{{ route('admin.courses.destroy', $course) }}" style="display:inline;" onsubmit="return confirm('¿Est&aacute; completamente seguro de eliminar este curso? Esta acci&oacute;n no se puede deshacer.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Eliminar curso" style="background:none;border:1px solid var(--danger);padding:5px 8px;border-radius:6px;cursor:pointer;color:var(--danger);transition:all .15s;">
                                            🗑️
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if ($courses->hasPages())
                <div style="padding:14px 16px;border-top:1px solid var(--border)">
                    {{ $courses->links() }}
                </div>
            @endif
        @endif
    </div>
</div>

@endsection

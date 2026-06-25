@extends('layouts.admin')

@section('title', 'Gestionar Estudiantes')

@section('content')
<div class="page-header" style="margin-bottom:20px;">
    <div>
        <h1>Gesti&oacute;n de Estudiantes</h1>
        <p>Administra los accesos de los alumnos, sus estados de matr&iacute;cula y revisa su progreso académico.</p>
    </div>
</div>

{{-- Filters --}}
<div class="card" style="margin-bottom:20px;">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.students.index') }}" style="display:flex;gap:16px;align-items:flex-end;flex-wrap:wrap;">
            <div style="flex:1;min-width:240px;">
                <label for="search" style="font-size:11.5px;margin-bottom:4px;display:block;">B&uacute;squeda</label>
                <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Nombre, correo o DNI..." style="padding:8px 12px;font-size:13px;width:100%;">
            </div>
            
            <div style="width:200px;">
                <label for="course_id" style="font-size:11.5px;margin-bottom:4px;display:block;">Curso</label>
                <select id="course_id" name="course_id" style="padding:8px 12px;font-size:13px;width:100%;">
                    <option value="">Todos</option>
                    @foreach ($courses as $c)
                        <option value="{{ $c->id }}" {{ request('course_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <div style="width:160px;">
                <label for="status" style="font-size:11.5px;margin-bottom:4px;display:block;">Estado</label>
                <select id="status" name="status" style="padding:8px 12px;font-size:13px;width:100%;">
                    <option value="">Todos</option>
                    <option value="activo" {{ request('status') == 'activo' ? 'selected' : '' }}>Activo</option>
                    <option value="completado" {{ request('status') == 'completado' ? 'selected' : '' }}>Completado</option>
                    <option value="suspendido" {{ request('status') == 'suspendido' ? 'selected' : '' }}>Suspendido</option>
                </select>
            </div>

            <button type="submit" class="btn-primary" style="padding:10px 20px;">Filtrar</button>
            <a href="{{ route('admin.students.index') }}" class="btn-secondary" style="padding:10px 20px;text-decoration:none;">Limpiar</a>
        </form>
    </div>
</div>

@if (session('success'))
    <div style="background:#dcfce7;border:1px solid #bbf7d0;border-radius:8px;padding:12px 16px;color:#15803d;font-size:13px;margin-bottom:20px;">
        ✓ {{ session('success') }}
    </div>
@endif

{{-- Student list table --}}
<div class="card" style="padding:0;">
    <div class="card-body" style="padding:0;">
        @if ($students->isEmpty())
            <div style="padding:40px;text-align:center;color:var(--slate-400);">No se encontraron alumnos con los filtros seleccionados.</div>
        @else
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Nombre / Datos</th>
                        <th>Email / DNI</th>
                        <th>Cursos Matriculados</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($students as $student)
                        <tr>
                            <td>
                                <strong style="color:#fff;font-size:14px;">{{ $student->name }}</strong>
                                <div style="font-size:11px;color:var(--slate-400);margin-top:2px;">Registrado: {{ $student->created_at->format('d/m/Y') }}</div>
                            </td>
                            <td>
                                <div>{{ $student->email }}</div>
                                <div style="font-size:11px;color:var(--slate-400);margin-top:2px;">DNI: {{ $student->dni ?? 'No registrado' }}</div>
                            </td>
                            <td>
                                <div style="display:flex;flex-direction:column;gap:4px;">
                                    @foreach ($student->enrollments as $e)
                                        <div style="font-size:12px;display:flex;align-items:center;gap:6px;">
                                            <span style="font-weight:600;color:#fff;">{{ $e->course->name ?? 'Curso' }}</span>
                                            <span style="font-size:10px;padding:2px 8px;border-radius:10px;background:rgba(255,255,255,0.06);" class="status-badge-{{ $e->status }}">
                                                {{ ucfirst($e->status) }} ({{ (int)$e->progress }}%)
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('admin.students.show', $student) }}" class="btn-primary" style="padding:6px 12px;font-size:12px;text-decoration:none;">
                                    Ver Detalle Acad&eacute;mico
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div style="padding:20px;">
                {{ $students->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

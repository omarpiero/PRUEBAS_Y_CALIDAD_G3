@extends('layouts.admin')

@section('page-title', 'Historial de Auditoría')

@section('content')
<div class="page-header">
    <h1>Historial de Auditoría</h1>
    <p>Historial completo de acciones y modificaciones administrativas en el sistema</p>
</div>

{{-- Filters --}}
<div class="card" style="margin-bottom: 20px;">
    <form method="GET" action="{{ route('admin.audit.index') }}" style="padding: 20px; display: flex; flex-direction: column; gap: 15px;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px;">
            <div>
                <label class="form-label">Búsqueda Rápida</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Usuario, email..." class="form-input">
            </div>
            <div>
                <label class="form-label">Acción</label>
                <select name="action" class="form-input">
                    <option value="">Todas las acciones</option>
                    @foreach($actions as $act)
                        <option value="{{ $act }}" {{ request('action') === $act ? 'selected' : '' }}>{{ $act }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Tipo Entidad</label>
                <select name="entity_type" class="form-input">
                    <option value="">Todas las entidades</option>
                    @foreach($entityTypes as $ent)
                        @php $basename = class_basename($ent); @endphp
                        <option value="{{ $ent }}" {{ request('entity_type') === $ent ? 'selected' : '' }}>{{ $basename }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Desde</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-input">
            </div>
            <div>
                <label class="form-label">Hasta</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-input">
            </div>
        </div>
        <div style="display: flex; justify-content: flex-end; gap: 10px; flex-wrap: wrap;">
            <a href="{{ route('admin.audit.index') }}" style="background: var(--gray-100); color: var(--gray-600); border: 1px solid var(--gray-200); border-radius: 8px; text-decoration: none; padding: 8px 18px; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center;">
                Limpiar Filtros
            </a>
            <a href="{{ route('admin.audit.export', request()->query()) }}" style="background: white; color: var(--gray-700); border: 1px solid var(--gray-300); border-radius: 8px; text-decoration: none; padding: 8px 18px; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center;">
                Exportar CSV
            </a>
            <button type="submit" style="background: var(--blue-600); color: white; border: none; border-radius: 8px; padding: 8px 18px; font-size: 13px; font-weight: 600; cursor: pointer; transition: background 0.18s; font-family: inherit;">
                🔍 Filtrar
            </button>
        </div>
    </form>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <rect x="3" y="3" width="18" height="18" rx="2"/><line x1="9" y1="3" x2="9" y2="21"/>
            </svg>
            Registros Encontrados ({{ $logs->total() }})
        </div>
    </div>
    <div class="card-body">
        @if ($logs->isEmpty())
            <p style="padding: 20px; color: var(--gray-400); font-size: 13px; text-align: center;">No se encontraron registros de auditoría.</p>
        @else
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Usuario</th>
                        <th>Acción</th>
                        <th>Entidad</th>
                        <th>IP / Navegador</th>
                        <th>Modificaciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($logs as $log)
                        <tr>
                            <td style="white-space: nowrap; font-size: 12px; color: var(--gray-600);">
                                {{ $log->created_at->format('d/m/Y H:i:s') }}
                            </td>
                            <td>
                                @if ($log->user)
                                    <div class="user-info">
                                        <div class="user-avatar" style="width: 26px; height: 26px; font-size: 10px;">
                                            {{ strtoupper(substr($log->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="user-name" style="font-size: 12px;">{{ $log->user->name }}</div>
                                            <div class="user-email" style="font-size: 11px;">{{ $log->user->email }}</div>
                                        </div>
                                    </div>
                                @else
                                    <span style="color: var(--gray-400); font-size: 12px;">Sistema / Anónimo</span>
                                @endif
                            </td>
                            <td>
                                <span style="background: var(--blue-50); color: var(--blue-700); border: 1px solid var(--blue-100); padding: 3px 8px; border-radius: 6px; font-size: 11.5px; font-weight: 600; display: inline-block;">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td style="font-size: 12px; color: var(--gray-600);">
                                @if ($log->entity_type)
                                    <span style="font-weight: 500;">{{ class_basename($log->entity_type) }}</span>
                                    @if ($log->entity_id)
                                        <span style="color: var(--gray-400);">#{{ $log->entity_id }}</span>
                                    @endif
                                @else
                                    —
                                @endif
                            </td>
                            <td style="font-size: 11.5px; color: var(--gray-400); max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $log->user_agent }}">
                                <span style="font-weight: 600; color: var(--gray-600);">{{ $log->ip_address }}</span><br>
                                {{ \Illuminate\Support\Str::limit($log->user_agent, 25) }}
                            </td>
                            <td>
                                @if (!empty($log->old_values) || !empty($log->new_values))
                                    <button onclick="toggleDetails({{ $log->id }}, event)" class="btn-details" id="btn-{{ $log->id }}">
                                        Ver Datos
                                    </button>
                                    <div id="details-{{ $log->id }}" class="details-pane">
                                        @if(!empty($log->old_values))
                                            <div style="margin-bottom: 8px;">
                                                <strong style="color: var(--danger); font-size: 10.5px; text-transform: uppercase;">Valores Anteriores:</strong>
                                                <pre style="margin-top: 3px; font-size: 10px; background: white; padding: 6px; border: 1px solid var(--gray-200); border-radius: 4px; overflow-x: auto;">{{ json_encode($log->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                            </div>
                                        @endif
                                        @if(!empty($log->new_values))
                                            <div>
                                                <strong style="color: var(--success); font-size: 10.5px; text-transform: uppercase;">Valores Nuevos:</strong>
                                                <pre style="margin-top: 3px; font-size: 10px; background: white; padding: 6px; border: 1px solid var(--gray-200); border-radius: 4px; overflow-x: auto;">{{ json_encode($log->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <span style="color: var(--gray-400); font-size: 12px;">Sin cambios</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if ($logs->hasPages())
                <div style="padding:14px 16px;border-top:1px solid var(--gray-100)">
                    {{ $logs->links() }}
                </div>
            @endif
        @endif
    </div>
</div>

<style>
.btn-details {
    background: none;
    border: 1px solid var(--gray-200);
    color: var(--gray-600);
    border-radius: 6px;
    padding: 3px 8px;
    font-size: 11.5px;
    cursor: pointer;
    font-family: inherit;
    transition: all 0.2s;
}
.btn-details:hover {
    background: var(--blue-50);
    border-color: var(--blue-200);
    color: var(--blue-700);
}
.details-pane {
    display: none;
    position: absolute;
    background: var(--gray-50);
    border: 1px solid var(--gray-200);
    border-radius: 8px;
    padding: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    z-index: 500;
    width: 320px;
    max-height: 250px;
    overflow-y: auto;
    text-align: left;
}
.form-label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: var(--gray-800);
    margin-bottom: 5px;
}
.form-input {
    width: 100%;
    padding: 8px 10px;
    border: 1px solid var(--gray-200);
    border-radius: 6px;
    font-family: inherit;
    font-size: 12.5px;
    color: var(--gray-800);
    background: white;
}
</style>

<script>
function toggleDetails(logId, event) {
    event.stopPropagation();
    const pane = document.getElementById('details-' + logId);
    const btn = document.getElementById('btn-' + logId);
    const isAlreadyOpen = pane.style.display === 'block';
    
    // Hide all other panes first
    document.querySelectorAll('.details-pane').forEach(p => {
        p.style.display = 'none';
    });

    if (!isAlreadyOpen) {
        pane.style.display = 'block';
        // Position relative to button
        const rect = btn.getBoundingClientRect();
        pane.style.top = (window.scrollY + rect.bottom + 5) + 'px';
        pane.style.left = (window.scrollX + rect.left - 180) + 'px';
    }
}

// Close details pane on click outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('.details-pane')) {
        document.querySelectorAll('.details-pane').forEach(p => p.style.display = 'none');
    }
});
</script>
@endsection

@extends('layouts.admin')

@section('page-title', 'Detalle de Rol')

@section('content')
<div class="page-header">
    <h1>Rol: {{ $role->display_name }}</h1>
    <p>Detalle de permisos y usuarios asignados</p>
</div>

<div class="content-grid" style="display: grid; grid-template-columns: 1fr 360px; gap: 20px; margin-bottom: 20px;">
    {{-- Left column: Permissions list grouped by module --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="3" y="11" width="18" height="10" rx="2"/><circle cx="12" cy="5" r="3"/>
                </svg>
                Permisos Habilitados ({{ $role->permissions->count() }})
            </div>
        </div>
        <div class="card-body" style="padding: 20px;">
            @if($role->permissions->isEmpty())
                <p style="color: var(--gray-400); font-size: 13px;">Este rol no tiene permisos habilitados.</p>
            @else
                @php
                    $grouped = $role->permissions->groupBy('module');
                @endphp
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    @foreach($grouped as $module => $perms)
                        <div>
                            <h4 style="font-size: 12px; font-weight: 700; color: var(--blue-600); text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 8px; border-bottom: 1px solid var(--gray-100); padding-bottom: 4px;">
                                Módulo: {{ ucfirst($module) }}
                            </h4>
                            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 8px;">
                                @foreach($perms as $perm)
                                    <div style="display: flex; align-items: center; gap: 6px; font-size: 12.5px; color: var(--gray-800);">
                                        <svg width="12" height="12" fill="none" stroke="#22c55e" stroke-width="3" viewBox="0 0 24 24">
                                            <polyline points="20 6 9 17 4 12"/>
                                        </svg>
                                        {{ $perm->display_name }}
                                        <span style="font-size: 10.5px; color: var(--gray-400); margin-left: 2px;">({{ $perm->name }})</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Right column: Users with this role --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                </svg>
                Usuarios Asignados ({{ $role->users->count() }})
            </div>
        </div>
        <div class="card-body" style="padding: 0;">
            @if($role->users->isEmpty())
                <p style="padding: 20px; color: var(--gray-400); font-size: 13px; text-align: center;">
                    No hay usuarios asignados a este rol.
                </p>
            @else
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th style="width: 80px; text-align: center;">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($role->users as $u)
                            <tr>
                                <td>
                                    <div class="user-info">
                                        <div class="user-avatar" style="width: 28px; height: 28px; font-size: 11px;">
                                            {{ strtoupper(substr($u->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="user-name" style="font-size: 12.5px;">{{ \Illuminate\Support\Str::limit($u->name, 20) }}</div>
                                            <div class="user-email" style="font-size: 11px;">{{ \Illuminate\Support\Str::limit($u->email, 22) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td style="text-align: center;">
                                    <a href="{{ route('admin.users.edit', $u) }}" style="font-size: 11.5px; color: var(--blue-600); text-decoration: none; font-weight: 600;">
                                        Editar
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>

<div>
    <a href="{{ route('admin.roles.index') }}" style="background: var(--gray-200); color: var(--gray-800); border: 1px solid var(--gray-300); border-radius: 8px; text-decoration: none; padding: 8px 18px; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <polyline points="15 18 9 12 15 6"/>
        </svg>
        Volver a la Lista
    </a>
</div>
@endsection

@extends('layouts.admin')

@section('page-title', 'Roles de Usuario')

@section('content')
<div class="page-header">
    <h1>Roles y Permisos</h1>
    <p>Visualizar la jerarquía y distribución de accesos en la plataforma</p>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-bottom: 26px;">
    @foreach ($roles as $role)
        <div class="card" style="display: flex; flex-direction: column; justify-content: space-between;">
            <div>
                <div class="card-header" style="background: linear-gradient(135deg, var(--blue-900) 0%, #0f2447 100%); color: white; border-bottom: none;">
                    <div class="card-title" style="color: white;">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                        {{ $role->display_name }}
                    </div>
                    <span style="font-size: 11px; background: rgba(255,255,255,0.15); padding: 2px 8px; border-radius: 10px; font-weight: 500;">
                        {{ $role->name }}
                    </span>
                </div>
                <div class="card-body" style="padding-top: 15px;">
                    <p style="font-size: 12.5px; color: var(--gray-600); line-height: 1.5; min-height: 55px; margin-bottom: 12px;">
                        {{ $role->description }}
                    </p>
                    <div style="display: flex; gap: 25px; border-top: 1px solid var(--gray-100); padding-top: 12px;">
                        <div>
                            <span style="font-size: 10.5px; color: var(--gray-400); display: block; font-weight: 600; text-transform: uppercase;">Usuarios</span>
                            <strong style="font-size: 18px; color: var(--gray-800);">{{ $role->users_count }}</strong>
                        </div>
                        <div>
                            <span style="font-size: 10.5px; color: var(--gray-400); display: block; font-weight: 600; text-transform: uppercase;">Permisos</span>
                            <strong style="font-size: 18px; color: var(--gray-800);">{{ $role->permissions_count }}</strong>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body" style="border-top: 1px solid var(--gray-100); padding-top: 10px; padding-bottom: 10px; display: flex; justify-content: flex-end; background: var(--gray-50);">
                <a href="{{ route('admin.roles.show', $role) }}" style="
                    font-size: 12px; font-weight: 600; color: var(--blue-600); text-decoration: none; display: inline-flex; align-items: center; gap: 4px;
                " class="view-permissions-link">
                    Ver Permisos y Usuarios
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <polyline points="9 18 15 12 9 6"/>
                    </svg>
                </a>
            </div>
        </div>
    @endforeach
</div>

<style>
.view-permissions-link:hover {
    color: var(--blue-800);
}
</style>
@endsection

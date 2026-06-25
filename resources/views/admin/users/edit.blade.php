@extends('layouts.admin')

@section('page-title', 'Editar Roles de Usuario')

@section('content')
<div class="page-header">
    <h1>Editar Roles - {{ $user->name }}</h1>
    <p>Asignar roles y permisos del sistema al usuario</p>
</div>

<div class="card" style="max-width: 600px;">
    <div class="card-header">
        <div class="card-title">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M12 15c4 0 8 2 8 6v2H4v-2c0-4 4-6 8-6z"/>
                <circle cx="12" cy="7" r="4"/>
            </svg>
            Roles disponibles
        </div>
    </div>
    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf
        @method('PUT')
        
        <div class="card-body" style="display: flex; flex-direction: column; gap: 15px;">
            <p style="font-size: 13px; color: var(--gray-400); margin-bottom: 5px;">
                Selecciona los roles que deseas asignar a este usuario. Cada rol otorga un conjunto diferente de permisos en la plataforma.
            </p>

            @foreach ($roles as $role)
                <label style="display: flex; align-items: flex-start; gap: 12px; padding: 12px; border: 1px solid var(--gray-200); border-radius: 8px; cursor: pointer; transition: background 0.2s;" class="role-checkbox-label">
                    <input type="checkbox" name="roles[]" value="{{ $role->id }}" 
                           {{ $user->roles->contains($role->id) ? 'checked' : '' }}
                           style="margin-top: 4px; cursor: pointer; width: 16px; height: 16px;">
                    <div>
                        <strong style="font-size: 14px; color: var(--gray-800); display: block;">{{ $role->display_name }}</strong>
                        <p style="font-size: 12px; color: var(--gray-400); margin-top: 2px;">{{ $role->description }}</p>
                    </div>
                </label>
            @endforeach
        </div>

        <div class="card-body" style="border-top: 1px solid var(--gray-100); display: flex; gap: 10px; justify-content: flex-end; padding-top: 15px;">
            <a href="{{ route('admin.users') }}" style="background: var(--gray-100); color: var(--gray-600); border: 1px solid var(--gray-200); border-radius: 8px; text-decoration: none; padding: 8px 18px; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center;">
                Cancelar
            </a>
            <button type="submit" style="background: var(--blue-600); color: white; border: none; border-radius: 8px; padding: 8px 18px; font-size: 13px; font-weight: 600; cursor: pointer; transition: background 0.18s; font-family: inherit;">
                Guardar Cambios
            </button>
        </div>
    </form>
</div>

<style>
.role-checkbox-label:hover {
    background: var(--blue-50);
    border-color: var(--blue-200) !important;
}
</style>
@endsection

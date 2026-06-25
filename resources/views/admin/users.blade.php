@extends('layouts.admin')

@section('page-title', 'Usuarios')

@section('content')

<div class="page-header">
    <h1>Usuarios</h1>
    <p>Listado completo de usuarios registrados en la plataforma y gestión de roles</p>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
            Todos los usuarios ({{ $users->total() }})
        </div>
    </div>
    <div class="card-body">
        @if ($users->isEmpty())
            <p style="padding:20px;color:var(--gray-400);font-size:13px;">No hay usuarios registrados.</p>
        @else
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Roles Asignados</th>
                        <th>Registrado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td style="color:var(--gray-400)">{{ $user->id }}</td>
                            <td><strong>{{ $user->name }}</strong></td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->roles->isEmpty())
                                    <span class="badge badge-user">Estudiante</span>
                                @else
                                    @foreach($user->roles as $role)
                                        @if($role->name === 'admin')
                                            <span class="badge badge-admin">{{ $role->display_name }}</span>
                                        @elseif($role->name === 'instructor')
                                            <span class="badge" style="background:#fef3c7; color:#d97706; margin-right: 2px;">{{ $role->display_name }}</span>
                                        @elseif($role->name === 'soporte')
                                            <span class="badge" style="background:var(--blue-50); color:var(--blue-600); border: 1px solid var(--blue-100); margin-right: 2px;">{{ $role->display_name }}</span>
                                        @else
                                            <span class="badge badge-user" style="margin-right: 2px;">{{ $role->display_name }}</span>
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                            <td style="color:var(--gray-400)">{{ $user->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @if ($user->id !== auth()->id())
                                    <a href="{{ route('admin.users.edit', $user) }}" style="
                                        font-family:inherit;font-size:12px;cursor:pointer;
                                        background:none;border:1px solid var(--gray-200);text-decoration:none;
                                        border-radius:6px;padding:5px 12px;color:var(--gray-600);
                                        transition:all .18s; display:inline-flex; align-items:center; gap: 4px;
                                    " class="edit-roles-btn">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                            <path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                        </svg>
                                        Editar Roles
                                    </a>
                                @else
                                    <span style="font-size:12px;color:var(--gray-400)">Tú (Admin)</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if ($users->hasPages())
                <div style="padding:14px 16px;border-top:1px solid var(--gray-100)">
                    {{ $users->links() }}
                </div>
            @endif
        @endif
    </div>
</div>

<style>
.edit-roles-btn:hover {
    background: var(--blue-50);
    border-color: var(--blue-200);
    color: var(--blue-700);
}
</style>

@endsection

@extends('layouts.admin')

@section('title', 'Mensajes')
@section('page-title', 'Mensajes de contacto')

@section('content')

<div class="page-header">
    <div>
        <h1>Mensajes</h1>
        <p>Formularios de contacto recibidos desde el sitio web</p>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
            </svg>
            Total: {{ $contacts->total() }} mensajes
        </div>
    </div>

    @if ($contacts->isEmpty())
        <div style="padding:48px;text-align:center;color:var(--gray-400);font-size:14px;">
            Aún no hay mensajes de contacto.
        </div>
    @else
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Remitente</th>
                    <th>Tema</th>
                    <th>Curso</th>
                    <th>Mensaje</th>
                    <th>Recibido</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($contacts as $contact)
                    <tr style="{{ !$contact->leido ? 'background:#eff6ff;' : '' }}">
                        <td>
                            <div class="user-info">
                                <div class="user-avatar" style="background:linear-gradient(135deg,#2563eb,#0ea5e9);">
                                    {{ strtoupper(substr($contact->nombre, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="user-name">{{ $contact->nombre }}</div>
                                    <div class="user-email">{{ $contact->correo }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span style="background:var(--blue-100);color:var(--blue-700);padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;">
                                {{ ucfirst($contact->tema) }}
                            </span>
                        </td>
                        <td style="font-size:12px;color:var(--gray-400);max-width:150px;">
                            {{ $contact->curso ?? '—' }}
                        </td>
                        <td style="max-width:260px;">
                            <div style="font-size:13px;color:var(--gray-600);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:260px;"
                                 title="{{ $contact->mensaje }}">
                                {{ $contact->mensaje }}
                            </div>
                        </td>
                        <td style="font-size:12px;color:var(--gray-400);white-space:nowrap;">
                            {{ $contact->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td>
                            @if (!$contact->leido)
                                <span style="background:#dbeafe;color:#1d4ed8;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;">Nuevo</span>
                            @else
                                <span style="background:var(--gray-100);color:var(--gray-400);padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;">Leído</span>
                            @endif
                        </td>
                        <td>
                            <div style="display:flex;gap:6px;">
                                @if (!$contact->leido)
                                    <form method="POST" action="{{ route('admin.contacts.read', $contact) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit" title="Marcar como leído" style="background:var(--blue-100);border:none;border-radius:7px;padding:5px 10px;cursor:pointer;color:var(--blue-700);font-size:12px;font-weight:600;font-family:inherit;transition:background .18s;">
                                            ✓ Leído
                                        </button>
                                    </form>
                                @endif
                                <form method="POST" action="{{ route('admin.contacts.destroy', $contact) }}"
                                      onsubmit="return confirm('¿Eliminar este mensaje?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" title="Eliminar" style="background:#fee2e2;border:none;border-radius:7px;padding:5px 10px;cursor:pointer;color:#dc2626;font-size:12px;font-weight:600;font-family:inherit;transition:background .18s;">
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if ($contacts->hasPages())
            <div style="padding:14px 16px;border-top:1px solid var(--gray-200);">
                {{ $contacts->links() }}
            </div>
        @endif
    @endif
</div>

@endsection

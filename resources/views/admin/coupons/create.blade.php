@extends('layouts.admin')

@section('title', 'Crear Nuevo Cupón')

@section('content')
<div style="margin-bottom:20px;">
    <a href="{{ route('admin.coupons.index') }}" style="color:#38bdf8;text-decoration:none;font-size:13px;font-weight:600;display:inline-flex;align-items:center;gap:6px;">
        ← Volver a Cupones
    </a>
</div>

<div class="page-header" style="margin-bottom:24px;">
    <div>
        <h1>Crear Nuevo Cup&oacute;n</h1>
        <p>Define las reglas de descuento, vigencia y l&iacute;mites del cup&oacute;n.</p>
    </div>
</div>

<div class="card" style="max-width:640px;">
    <div class="card-body" style="padding:28px;">
        <form method="POST" action="{{ route('admin.coupons.store') }}" style="display:grid;gap:20px;">
            @csrf

            <div style="display:grid;gap:5px;">
                <label for="code" style="font-size:11.5px;font-weight:700;color:var(--slate-400);text-transform:uppercase;letter-spacing:0.6px;">Código del Cupón</label>
                <input type="text" id="code" name="code" value="{{ old('code') }}" placeholder="Ej. CALIDAD20" style="padding:10px 14px;font-size:14px;text-transform:uppercase;color:#fff;background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.1);border-radius:8px;outline:none;" required>
                @error('code')<span style="color:#ef4444;font-size:12px;margin-top:2px;">{{ $message }}</span>@enderror
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                <div style="display:grid;gap:5px;">
                    <label for="type" style="font-size:11.5px;font-weight:700;color:var(--slate-400);text-transform:uppercase;letter-spacing:0.6px;">Tipo de Descuento</label>
                    <select id="type" name="type" style="padding:10px 14px;font-size:14px;color:#fff;background:rgba(0,0,0,0.4);border:1px solid rgba(255,255,255,0.1);border-radius:8px;outline:none;" required>
                        <option value="porcentaje" {{ old('type') == 'porcentaje' ? 'selected' : '' }}>Porcentaje (%)</option>
                        <option value="monto_fijo" {{ old('type') == 'monto_fijo' ? 'selected' : '' }}>Monto Fijo (S/)</option>
                    </select>
                    @error('type')<span style="color:#ef4444;font-size:12px;margin-top:2px;">{{ $message }}</span>@enderror
                </div>

                <div style="display:grid;gap:5px;">
                    <label for="value" style="font-size:11.5px;font-weight:700;color:var(--slate-400);text-transform:uppercase;letter-spacing:0.6px;">Valor Descuento</label>
                    <input type="number" id="value" name="value" value="{{ old('value') }}" step="0.01" min="0" placeholder="Ej. 15.00" style="padding:10px 14px;font-size:14px;color:#fff;background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.1);border-radius:8px;outline:none;" required>
                    @error('value')<span style="color:#ef4444;font-size:12px;margin-top:2px;">{{ $message }}</span>@enderror
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                <div style="display:grid;gap:5px;">
                    <label for="start_date" style="font-size:11.5px;font-weight:700;color:var(--slate-400);text-transform:uppercase;letter-spacing:0.6px;">Fecha de Inicio</label>
                    <input type="date" id="start_date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}" style="padding:10px 14px;font-size:14px;color:#fff;background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.1);border-radius:8px;outline:none;" required>
                    @error('start_date')<span style="color:#ef4444;font-size:12px;margin-top:2px;">{{ $message }}</span>@enderror
                </div>

                <div style="display:grid;gap:5px;">
                    <label for="end_date" style="font-size:11.5px;font-weight:700;color:var(--slate-400);text-transform:uppercase;letter-spacing:0.6px;">Fecha de Fin</label>
                    <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}" style="padding:10px 14px;font-size:14px;color:#fff;background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.1);border-radius:8px;outline:none;" required>
                    @error('end_date')<span style="color:#ef4444;font-size:12px;margin-top:2px;">{{ $message }}</span>@enderror
                </div>
            </div>

            <div style="display:grid;gap:5px;">
                <label for="usage_limit" style="font-size:11.5px;font-weight:700;color:var(--slate-400);text-transform:uppercase;letter-spacing:0.6px;">Límite de Uso Global (Opcional)</label>
                <input type="number" id="usage_limit" name="usage_limit" value="{{ old('usage_limit') }}" placeholder="Ej. 100 (vacío para ilimitado)" style="padding:10px 14px;font-size:14px;color:#fff;background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.1);border-radius:8px;outline:none;">
                @error('usage_limit')<span style="color:#ef4444;font-size:12px;margin-top:2px;">{{ $message }}</span>@enderror
            </div>

            <div style="display:flex;align-items:center;gap:8px;margin-top:10px;">
                <input type="checkbox" id="is_active" name="is_active" value="1" checked style="width:16px;height:16px;accent-color:#38bdf8;">
                <label for="is_active" style="font-size:13px;color:#fff;cursor:pointer;user-select:none;">Activar cupón inmediatamente</label>
            </div>

            <div style="margin-top:10px;display:flex;justify-content:flex-end;gap:12px;">
                <a href="{{ route('admin.coupons.index') }}" class="btn-secondary" style="padding:12px 24px;text-decoration:none;font-size:13.5px;font-weight:700;">
                    Cancelar
                </a>
                <button type="submit" class="btn-primary" style="padding:12px 24px;font-size:13.5px;font-weight:700;">
                    Crear Cupón
                </button>
            </div>

        </form>
    </div>
</div>
@endsection

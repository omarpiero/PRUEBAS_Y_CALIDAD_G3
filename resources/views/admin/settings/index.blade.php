@extends('layouts.admin')

@section('page-title', 'Configuración del Sistema')

@section('content')
<div class="page-header">
    <h1>Configuración Global</h1>
    <p>Ajustes de empresa, métodos de pago y límites del sistema</p>
</div>

@if(session('success'))
    <div style="background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 13.5px; font-weight: 500;">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div style="background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 13.5px; font-weight: 500;">
        <ul style="margin-left: 15px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <div class="card-header" style="padding: 0; border-bottom: 1px solid var(--gray-200); background: var(--gray-50);">
        <div style="display: flex; gap: 4px;">
            <button onclick="switchTab('empresa')" id="tab-btn-empresa" class="tab-btn active">
                🏢 Datos de la Empresa
            </button>
            <button onclick="switchTab('pagos')" id="tab-btn-pagos" class="tab-btn">
                💳 Métodos de Pago
            </button>
            <button onclick="switchTab('general')" id="tab-btn-general" class="tab-btn">
                ⚙️ Límites y Ajustes
            </button>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- TAB 1: Empresa --}}
        <div id="tab-content-empresa" class="tab-content active-content">
            <div style="display: flex; flex-direction: column; gap: 18px;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 18px;">
                    <div>
                        <label class="form-label">Nombre de la Empresa</label>
                        <input type="text" name="company_name" value="{{ old('company_name', setting('company_name')) }}" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Correo Electrónico de Contacto</label>
                        <input type="email" name="company_email" value="{{ old('company_email', setting('company_email')) }}" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Teléfono de Contacto</label>
                        <input type="text" name="company_phone" value="{{ old('company_phone', setting('company_phone')) }}" class="form-input">
                    </div>
                </div>

                <div>
                    <label class="form-label">Descripción o Lema Comercial</label>
                    <input type="text" name="company_description" value="{{ old('company_description', setting('company_description')) }}" class="form-input">
                </div>

                <div>
                    <label class="form-label">Logo de la Empresa (2MB máx, se guardará en storage público)</label>
                    <div style="display: flex; align-items: center; gap: 15px; margin-top: 5px;">
                        @if(setting('company_logo'))
                            <div style="width: 60px; height: 60px; border-radius: 8px; border: 1px solid var(--gray-200); display: flex; align-items: center; justify-content: center; background: white; padding: 4px;">
                                <img src="{{ asset('storage/' . setting('company_logo')) }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                            </div>
                        @else
                            <div style="width: 60px; height: 60px; border-radius: 8px; border: 1px dashed var(--gray-400); display: flex; align-items: center; justify-content: center; color: var(--gray-400); font-size: 11px;">
                                Sin Logo
                            </div>
                        @endif
                        <input type="file" name="company_logo" accept="image/*" style="font-size: 13px;">
                    </div>
                </div>
            </div>
        </div>

        {{-- TAB 2: Pagos --}}
        <div id="tab-content-pagos" class="tab-content">
            <div style="display: flex; flex-direction: column; gap: 18px;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 18px;">
                    <div>
                        <label class="form-label">Número Yape</label>
                        <input type="text" name="payment_yape" value="{{ old('payment_yape', setting('payment_yape')) }}" class="form-input" placeholder="Ej: 999999999">
                    </div>
                    <div>
                        <label class="form-label">Número Plin</label>
                        <input type="text" name="payment_plin" value="{{ old('payment_plin', setting('payment_plin')) }}" class="form-input" placeholder="Ej: 999999999">
                    </div>
                </div>

                <div style="border-top: 1px solid var(--gray-100); padding-top: 15px;">
                    <h3 style="font-size: 13.5px; font-weight: 700; color: var(--gray-800); margin-bottom: 12px;">Transferencia Bancaria</h3>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 15px;">
                        <div>
                            <label class="form-label">Nombre del Banco</label>
                            <input type="text" name="payment_bank_name" value="{{ old('payment_bank_name', setting('payment_bank_name')) }}" class="form-input" placeholder="Ej: BCP, Interbank">
                        </div>
                        <div>
                            <label class="form-label">Número de Cuenta</label>
                            <input type="text" name="payment_bank_account" value="{{ old('payment_bank_account', setting('payment_bank_account')) }}" class="form-input" placeholder="Ej: 191-12345678-0-12">
                        </div>
                        <div>
                            <label class="form-label">Código Interbancario (CCI)</label>
                            <input type="text" name="payment_bank_cci" value="{{ old('payment_bank_cci', setting('payment_bank_cci')) }}" class="form-input" placeholder="Ej: 002-191-123456780123-56">
                        </div>
                    </div>
                </div>

                <div style="border-top: 1px solid var(--gray-100); padding-top: 15px;">
                    <label class="form-label">Enlace de Pago PayPal (Mock / Botón)</label>
                    <input type="text" name="payment_paypal" value="{{ old('payment_paypal', setting('payment_paypal')) }}" class="form-input" placeholder="Ej: https://paypal.me/tuusuario">
                </div>
            </div>
        </div>

        {{-- TAB 3: General --}}
        <div id="tab-content-general" class="tab-content">
            <div style="display: flex; flex-direction: column; gap: 18px;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 18px;">
                    <div>
                        <label class="form-label">Tamaño Máximo Archivos Subidos (MB)</label>
                        <input type="number" name="max_upload_size_mb" value="{{ old('max_upload_size_mb', setting('max_upload_size_mb', 50)) }}" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Tamaño Máximo Videos Privados (MB)</label>
                        <input type="number" name="max_video_size_mb" value="{{ old('max_video_size_mb', setting('max_video_size_mb', 500)) }}" class="form-input">
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body" style="border-top: 1px solid var(--gray-100); display: flex; justify-content: flex-end; padding-top: 15px; padding-bottom: 20px;">
            <button type="submit" style="background: var(--blue-600); color: white; border: none; border-radius: 8px; padding: 10px 24px; font-size: 13.5px; font-weight: 600; cursor: pointer; transition: background 0.18s; font-family: inherit;">
                💾 Guardar Configuraciones
            </button>
        </div>
    </form>
</div>

<style>
.tab-btn {
    border: none;
    background: none;
    padding: 14px 22px;
    font-family: inherit;
    font-size: 13px;
    font-weight: 600;
    color: var(--gray-600);
    cursor: pointer;
    border-bottom: 2px solid transparent;
    transition: all 0.2s;
}
.tab-btn:hover {
    color: var(--blue-600);
    background: rgba(37, 99, 235, 0.04);
}
.tab-btn.active {
    color: var(--blue-600);
    border-bottom-color: var(--blue-600);
    background: white;
}
.tab-content {
    display: none;
    padding: 24px 28px;
}
.tab-content.active-content {
    display: block;
}
.form-label {
    display: block;
    font-size: 12.5px;
    font-weight: 600;
    color: var(--gray-800);
    margin-bottom: 6px;
}
.form-input {
    width: 100%;
    padding: 9px 12px;
    border: 1px solid var(--gray-200);
    border-radius: 8px;
    font-family: inherit;
    font-size: 13px;
    color: var(--gray-800);
    background: white;
    transition: border 0.18s;
}
.form-input:focus {
    outline: none;
    border-color: var(--blue-500);
}
</style>

<script>
function switchTab(tabId) {
    // Buttons
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    document.getElementById('tab-btn-' + tabId).classList.add('active');

    // Contents
    document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active-content'));
    document.getElementById('tab-content-' + tabId).classList.add('active-content');
}
</script>
@endsection

@extends('layouts.app')

@section('title', 'Contacto')

@section('content')
<main class="page">
    <section class="page-hero">
        <div class="hero-grid">
            <div>
                <p class="eyebrow">Contacto</p>
                <h1>Cuéntanos qué necesitas y te guiamos al siguiente paso.</h1>
                <p class="lead">
                    Podemos orientarte sobre cursos, asesorías, diagnóstico de planta o seguimiento de calidad.
                </p>

                <div class="hero-actions">
                    <a href="{{ route('cursos') }}" class="btn-outline">Ver cursos</a>
                </div>
            </div>

            <div class="hero-showcase">
                <div class="media-frame image-milk">
                    <div class="showcase-label">
                        <strong>Atención cercana</strong>
                        <span>Respondemos con una ruta clara según tu necesidad.</span>
                    </div>
                </div>

                <div class="floating-note">
                    <strong>24 h</strong>
                    <span>para recibir una primera orientación del equipo.</span>
                </div>
            </div>
        </div>
    </section>

    <section id="contacto-formulario" class="section contact-anchor">
        <div class="contact-grid">
            <div class="info-box reveal">
                <div class="contact-item">
                    <strong>Ubicación</strong>
                    Huancayo, Junín, Perú
                </div>
                <div class="contact-item">
                    <strong>WhatsApp</strong>
                    +51 987 654 321
                </div>
                <div class="contact-item">
                    <strong>Correo</strong>
                    contacto@jmjsalimentos.pe
                </div>
                <div class="contact-item">
                    <strong>Horario</strong>
                    Lunes a viernes, 8:00 a.m. a 6:00 p.m.
                </div>
            </div>

            <div class="form-box reveal">
                <form id="form-contacto" novalidate>
                @csrf
                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input id="nombre" name="nombre" type="text" placeholder="Tu nombre completo">
                </div>

                <div class="form-group">
                    <label for="correo">Correo electrónico</label>
                    <input id="correo" name="correo" type="email" placeholder="correo@empresa.com">
                </div>

                <div class="form-group">
                    <div class="label-row">
                        <div class="label-icon">
                            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                        </div>
                        <label for="tema">Tema de consulta</label>
                    </div>
                    <select id="tema">
                        <option value="" disabled selected>Selecciona un tema…</option>
                        <option value="cursos">🎓 Inscripción a un curso</option>
                        <option value="asesoria">🔬 Asesoría técnica</option>
                        <option value="diagnostico">🏭 Diagnóstico de planta</option>
                        <option value="calidad">✅ Gestión de calidad</option>
                        <option value="empresa">👥 Capacitación para empresa</option>
                        <option value="otro">💬 Otro</option>
                    </select>
                </div>

                <div class="form-group" id="grupo-curso">
                    <div class="label-row">
                        <div class="label-icon">
                            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                        </div>
                        <label for="curso">Curso de interés</label>
                        <span class="curso-badge-count">
                            <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            9 disponibles
                        </span>
                    </div>
                    <div class="curso-select-wrap">
                        <select id="curso">
                            <option value="" disabled selected>Selecciona el curso…</option>
                            <optgroup label="— Básico">
                                <option>BPM en Industria Alimentaria — S/ 350</option>
                                <option>Procesamiento de Alimentos Artesanales — S/ 280</option>
                                <option>Pasteurización y Tratamiento Térmico — S/ 290</option>
                            </optgroup>
                            <optgroup label="— Intermedio">
                                <option>Gestión de Calidad ISO 9001 — S/ 450</option>
                                <option>Elaboración de Alimentos Fermentados — S/ 320</option>
                                <option>Análisis Fisicoquímico de Alimentos — S/ 360</option>
                            </optgroup>
                            <optgroup label="— Avanzado">
                                <option>Control Microbiológico en Alimentos — S/ 380</option>
                                <option>HACCP en Plantas de Alimentos — S/ 420</option>
                                <option>Gestión de Inocuidad Alimentaria ISO 22000 — S/ 480</option>
                            </optgroup>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="mensaje">Mensaje</label>
                    <textarea id="mensaje" name="mensaje" placeholder="Cuéntanos en qué podemos ayudarte"></textarea>
                </div>

                <button type="submit" id="btn-enviar" class="btn-enviar">Enviar mensaje</button>
                </form>
            </div>
        </div>
    </section>
</main>
@endsection

@push('styles')
<style>
/* ── Select base (igual que inputs) ── */
.form-group select {
    width: 100%;
    border: 1px solid var(--line);
    border-radius: var(--radius);
    padding: 11px 40px 11px 14px;
    font-family: inherit;
    font-size: 15px;
    color: #1e293b;
    background-color: #fff;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='none' stroke='%230284c7' stroke-width='2' viewBox='0 0 24 24'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    appearance: none;
    -webkit-appearance: none;
    cursor: pointer;
    outline: none;
    transition: border-color .2s, box-shadow .2s;
    line-height: 1.5;
}
.form-group select:focus {
    border-color: var(--leaf);
    box-shadow: 0 0 0 4px rgba(2,132,199,.12);
}
.form-group select option {
    color: #1e293b;
    font-size: 14px;
    padding: 8px;
}
.form-group select optgroup {
    font-weight: 700;
    color: var(--leaf-dark);
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: .5px;
}

/* ── Label con ícono ── */
.form-group .label-row {
    display: flex;
    align-items: center;
    gap: 7px;
    margin-bottom: 7px;
}
.form-group .label-row label {
    margin-bottom: 0;
}
.form-group .label-icon {
    width: 18px;
    height: 18px;
    border-radius: 5px;
    background: var(--mint);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.form-group .label-icon svg { color: var(--leaf); }

/* ── Grupo curso animado ── */
#grupo-curso {
    display: none;
    overflow: hidden;
    max-height: 0;
    opacity: 0;
    transition: max-height .38s cubic-bezier(.4,0,.2,1),
                opacity .28s ease,
                margin .28s ease;
    margin-top: 0;
}
#grupo-curso.abierto {
    display: block;
    max-height: 160px;
    opacity: 1;
    margin-top: 0;
}

/* Contenedor especial del selector de curso */
.curso-select-wrap {
    position: relative;
}
.curso-select-wrap::before {
    content: '';
    position: absolute;
    left: 0; top: 0; bottom: 0;
    width: 3px;
    background: linear-gradient(180deg, var(--leaf), var(--gold));
    border-radius: 4px 0 0 4px;
}
.curso-select-wrap select {
    padding-left: 18px;
    background-color: #f0f9ff;
    border-color: rgba(2,132,199,.3);
}
.curso-select-wrap select:focus {
    background-color: #fff;
}

/* Badge de cursos disponibles */
.curso-badge-count {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 11px;
    font-weight: 600;
    color: var(--leaf);
    background: var(--mint);
    padding: 3px 9px;
    border-radius: 20px;
    margin-left: auto;
}
</style>
@endpush

@push('scripts')
<script>
/* ── Toggle curso select ── */
document.getElementById('tema').addEventListener('change', function () {
    const grupo = document.getElementById('grupo-curso');
    const curso = document.getElementById('curso');

    if (this.value === 'cursos') {
        grupo.style.display = 'block';
        void grupo.offsetHeight;
        grupo.classList.add('abierto');
    } else {
        grupo.classList.remove('abierto');
        setTimeout(() => { grupo.style.display = 'none'; curso.value = ''; }, 320);
    }
});

/* ── Envío AJAX ── */
document.getElementById('form-contacto').addEventListener('submit', async function (e) {
    e.preventDefault();

    const btn     = document.getElementById('btn-enviar');
    const nombre  = document.getElementById('nombre').value.trim();
    const correo  = document.getElementById('correo').value.trim();
    const tema    = document.getElementById('tema').value;
    const curso   = document.getElementById('curso').value;
    const mensaje = document.getElementById('mensaje').value.trim();

    // Validación básica frontend
    if (!nombre || !correo || !tema || !mensaje) {
        showToast('Por favor completa todos los campos.');
        return;
    }

    btn.disabled    = true;
    btn.textContent = 'Enviando…';

    try {
        const res = await fetch('{{ route("contacto.enviar") }}', {
            method:  'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept':       'application/json',
            },
            body: JSON.stringify({ nombre, correo, tema, curso: curso || null, mensaje }),
        });

        const data = await res.json();

        if (res.ok && data.ok) {
            showToast('✅ Mensaje enviado correctamente. Te responderemos pronto.');

            // Limpiar formulario
            document.getElementById('nombre').value  = '';
            document.getElementById('correo').value  = '';
            document.getElementById('tema').value    = '';
            document.getElementById('mensaje').value = '';
            document.getElementById('curso').value   = '';

            // Ocultar grupo curso
            const grupo = document.getElementById('grupo-curso');
            grupo.classList.remove('abierto');
            setTimeout(() => { grupo.style.display = 'none'; }, 320);
        } else {
            const errors = data.errors ? Object.values(data.errors).flat().join(' ') : 'Error al enviar.';
            showToast('❌ ' + errors);
        }
    } catch {
        showToast('❌ Error de conexión. Intenta de nuevo.');
    } finally {
        btn.disabled    = false;
        btn.textContent = 'Enviar mensaje';
    }
});
</script>
@endpush

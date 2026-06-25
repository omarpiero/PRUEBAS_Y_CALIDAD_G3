@extends('layouts.app')

@section('title', 'Politica de Privacidad')
@section('meta_description', 'Politica de privacidad de la plataforma JM y JS Alimentos LMS.')

@push('styles')
<style>
    .legal-page { padding: 132px 24px 72px; background: #f8fcff; min-height: 100vh; }
    .legal-wrap { max-width: 920px; margin: 0 auto; color: #0b2538; }
    .legal-wrap h1 { margin: 0 0 14px; color: #075985; font-size: 38px; line-height: 1.15; }
    .legal-wrap h2 { margin: 34px 0 10px; color: #075985; font-size: 22px; }
    .legal-wrap p, .legal-wrap li { color: #334155; font-size: 15px; line-height: 1.8; }
    .legal-wrap ul { padding-left: 22px; }
    .legal-note { margin: 24px 0; padding: 16px 18px; border: 1px solid #bae6fd; border-radius: 8px; background: #e0f2fe; color: #075985; }
</style>
@endpush

@section('content')
<main class="legal-page">
    <article class="legal-wrap">
        <h1>Politica de Privacidad</h1>
        <p>Ultima actualizacion: 10 de junio de 2026.</p>

        <h2>1. Responsable</h2>
        <p>El responsable del tratamiento de datos es el titular que opera la plataforma JM y JS Alimentos LMS. Las solicitudes relacionadas con datos personales se atienden por el formulario de contacto publicado en el sitio.</p>

        <h2>2. Datos tratados</h2>
        <ul>
            <li>Datos de cuenta: nombre, correo, DNI, telefono y credenciales cifradas.</li>
            <li>Datos academicos: cursos adquiridos, progreso, materiales completados y estado de matricula.</li>
            <li>Datos comerciales: carrito, cupones, ventas y comprobantes internos.</li>
            <li>Mensajes enviados por formularios de contacto y asistente IA.</li>
            <li>Datos tecnicos: sesiones, logs de seguridad y registros de auditoria.</li>
        </ul>

        <h2>3. Finalidades</h2>
        <p>Gestionar cuentas, matriculas, acceso al aula, soporte, ventas, administracion academica, seguridad, auditoria y mejora del servicio.</p>

        <h2>4. Pagos</h2>
        <p>Los pagos se procesan mediante Stripe Checkout fuera del servidor de JM y JS Alimentos LMS. La plataforma registra ventas, cupones y matriculas, pero no almacena numero de tarjeta, CVC ni fecha de expiracion.</p>

        <h2>5. Servicios externos</h2>
        <p>La plataforma puede usar servicios externos como Google Gemini, Google Fonts, Stripe u otros proveedores configurados por el titular. Las claves se almacenan en variables de entorno del servidor.</p>

        <h2>6. Conservacion y seguridad</h2>
        <p>Los datos se conservaran durante el tiempo necesario para prestar el servicio, cumplir obligaciones legales y atender auditorias. La plataforma aplica controles de acceso, CSRF, rate limiting, auditoria y almacenamiento privado de materiales.</p>

        <h2>7. Derechos del titular de datos</h2>
        <p>Los usuarios podran solicitar acceso, rectificacion, cancelacion u oposicion conforme a la normativa peruana aplicable, escribiendo al canal oficial que defina el titular.</p>

        <h2>8. Contacto</h2>
        <p>Para ejercer derechos sobre datos personales o solicitar informacion adicional, utiliza el formulario de contacto de la plataforma.</p>
    </article>
</main>
@endsection

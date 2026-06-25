@extends('layouts.app')

@section('title', 'Terminos y Condiciones')
@section('meta_description', 'Terminos y condiciones de uso de la plataforma JM y JS Alimentos LMS.')

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
        <h1>Terminos y Condiciones</h1>
        <p>Ultima actualizacion: 10 de junio de 2026.</p>

        <h2>1. Servicio</h2>
        <p>JM y JS Alimentos LMS permite consultar cursos, gestionar matriculas, acceder a materiales de aula virtual y recibir asistencia relacionada con capacitacion en calidad e inocuidad alimentaria.</p>

        <h2>2. Cuentas</h2>
        <p>El usuario es responsable de la veracidad de sus datos y de mantener la confidencialidad de sus credenciales. El titular podra suspender cuentas ante uso indebido, accesos no autorizados o infraccion de estos terminos.</p>

        <h2>3. Cursos y materiales</h2>
        <p>Los materiales publicados son para uso personal del alumno matriculado. No se permite copiarlos, revenderlos, publicarlos o distribuirlos sin autorizacion escrita del titular.</p>

        <h2>4. Pagos</h2>
        <p>Los pagos se realizan mediante Stripe Checkout. El acceso al curso se activa cuando Stripe confirma el pago y la plataforma registra la venta como pagada. El titular debe publicar condiciones de precio, comprobantes, reembolsos y anulaciones antes de operar comercialmente.</p>

        <h2>5. Asistente IA</h2>
        <p>El asistente IA brinda orientacion general. Sus respuestas no sustituyen asesoria profesional, legal, sanitaria ni certificaciones oficiales. El usuario debe validar informacion critica con especialistas.</p>

        <h2>6. Propiedad intelectual</h2>
        <p>El codigo, diseno, documentacion, contenidos propios, estructura del sistema, marca y materiales pertenecen a sus respectivos titulares. Las dependencias de terceros mantienen sus propias licencias.</p>

        <h2>7. Responsabilidad</h2>
        <p>La plataforma se ofrece conforme a la disponibilidad tecnica del servicio. El titular no sera responsable por interrupciones derivadas de mantenimiento, proveedores externos, fuerza mayor o uso indebido del sistema.</p>

        <h2>8. Contacto</h2>
        <p>Para soporte, consultas comerciales o solicitudes relacionadas con estos terminos, utiliza el formulario de contacto de la plataforma.</p>
    </article>
</main>
@endsection

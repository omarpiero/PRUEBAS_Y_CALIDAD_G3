# Manual Admin LMS - JM y JS Alimentos

## Acceso

1. Inicia sesion con una cuenta administradora.
2. Entra al panel desde `/admin`.
3. Si el usuario tiene roles parciales, solo podra usar las areas permitidas por sus permisos.

## Roles Y Usuarios

- `admin`: acceso total y bypass de permisos.
- `instructor`: gestiona cursos, modulos y materiales propios.
- `soporte`: revisa estudiantes y mensajes de contacto.
- `estudiante`: accede solo a sus cursos comprados.

Para cambiar roles:

1. Abre `Admin > Usuarios`.
2. Selecciona editar en el usuario.
3. Marca los roles requeridos.
4. Guarda. El sistema sincroniza `is_admin` si se asigna o retira el rol `admin`.
5. Revisa `Admin > Auditoria` para confirmar el registro del cambio.

## Crear Curso

1. Abre `Admin > Gestionar Cursos`.
2. Usa `Nuevo curso`.
3. Completa categoria, instructor, nombre, slug, descripcion corta, precio, nivel y duracion.
4. Carga portada o define una URL valida.
5. Guarda inicialmente como `borrador`.

Buenas practicas:

- No publiques un curso sin modulos y materiales.
- Usa descripciones limpias. El sistema sanitiza HTML basico, pero no debe usarse para scripts.
- Mantiene slugs legibles y unicos.

## Modulos Y Materiales

1. Entra a editar un curso.
2. Crea modulos por orden pedagogico.
3. Agrega materiales por modulo: video, documento, presentacion, texto enriquecido o recurso descargable.
4. Usa el ordenamiento para ajustar la secuencia.

Los archivos privados se guardan fuera de `public` y solo son servidos a estudiantes matriculados.

## Publicar Curso

Antes de publicar, el curso debe tener:

- nombre,
- portada,
- descripcion corta,
- descripcion completa,
- precio valido,
- al menos un modulo activo,
- al menos un material en cada modulo activo.

Usa `Publicar` desde el listado de cursos. Si falta algo, el sistema mostrara el motivo.

## Cupones Y Ventas

Para crear cupon:

1. Abre `Admin > Cupones`.
2. Define codigo, tipo, valor, fechas y limite de uso.
3. Activa el cupon si debe estar disponible.

Para revisar ventas:

1. Abre `Admin > Ventas`.
2. Filtra por cliente, ID o estado.
3. Entra al detalle para ver cursos comprados, descuento, total y metodo de pago.

## Estudiantes

Desde `Admin > Estudiantes` puedes filtrar estudiantes, revisar matriculas, suspender acceso, reactivar acceso o reiniciar progreso.

Toda accion critica queda registrada en auditoria.

## Settings

En `Admin > Configuracion` se gestionan datos de empresa, logo, correo, telefono, medios de pago visibles y limites de subida.

No guardes secretos como claves Stripe, Gemini o credenciales SMTP en settings. Esos valores deben vivir en `.env`.

## Auditoria

`Admin > Auditoria` permite filtrar por busqueda, accion, entidad y rango de fechas.

Usala para revisar cambios de cursos, roles, estudiantes, cupones y configuracion.

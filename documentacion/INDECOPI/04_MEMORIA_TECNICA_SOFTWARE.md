# Memoria tecnica del software

## Nombre del sistema

JM y JS Alimentos LMS

## Finalidad

Plataforma web para gestion de cursos, ventas, matriculas y aula virtual orientada a capacitacion en calidad e inocuidad alimentaria.

## Problema que atiende

Empresas, profesionales y estudiantes vinculados al sector alimentario necesitan acceder a capacitaciones especializadas, materiales privados, seguimiento de progreso y rutas de aprendizaje relacionadas con BPM, HACCP, ISO y control de calidad.

## Usuarios principales

| Usuario | Funciones |
| --- | --- |
| Visitante | Ver inicio, cursos, detalle, contacto y asistente IA |
| Estudiante | Comprar cursos, acceder al aula, marcar progreso, descargar materiales autorizados |
| Administrador | Gestionar cursos, modulos, materiales, estudiantes, ventas, cupones, usuarios, roles, settings y auditoria |
| Instructor/soporte | Acceso limitado segun permisos |

## Arquitectura

| Capa | Tecnologia |
| --- | --- |
| Backend | Laravel 12, PHP 8.2+ |
| Frontend | Blade, React, Vite |
| Base de datos | MySQL/MariaDB o SQLite para demo local |
| UI complementaria | Chart.js, Quill, SortableJS |
| IA | Google Gemini API mediante backend Laravel |
| Pagos | Stripe Checkout con confirmacion por retorno seguro y webhook |
| Storage | Laravel Storage para archivos publicos y privados |

## Modulos funcionales

1. Catalogo publico dinamico.
2. Detalle de curso por slug.
3. Carrito y checkout.
4. Matriculas.
5. Aula privada del estudiante.
6. Progreso por material.
7. Gestion admin de cursos.
8. Gestion admin de modulos y materiales.
9. Gestion de ventas y cupones.
10. Gestion de estudiantes.
11. Dashboard ejecutivo.
12. Roles y permisos.
13. Settings.
14. Auditoria.
15. Asistente IA con Gemini.
16. Contacto.

## Seguridad implementada

- CSRF en formularios.
- Login con rate limit.
- `/api/chat` con throttle.
- Rutas admin protegidas por permisos.
- Compatibilidad con rol admin legacy.
- Headers de seguridad.
- Materiales privados servidos por controlador.
- Claves externas en `.env`.
- Gemini invocado desde servidor, no desde navegador.
- Validacion de uploads por tipo de material.
- Checkout envuelto en transaccion de base de datos y confirmacion idempotente.
- Stripe Checkout procesa datos de tarjeta fuera de la plataforma; el servidor no almacena numero de tarjeta, CVC ni fecha de expiracion.

## Pendientes para produccion

- Activar HTTPS real.
- Configurar backups.
- Configurar credenciales Stripe de produccion y endpoint webhook firmado.
- Revisar politicas de privacidad y terminos.
- Hacer pentest basico.
- Configurar monitoreo y rotacion de logs.
- Firmar cesiones de derechos de autor.

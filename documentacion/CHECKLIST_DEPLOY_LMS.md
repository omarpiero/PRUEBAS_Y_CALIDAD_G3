# Checklist Deploy LMS - JM y JS Alimentos

## Pre-Deploy

- Ejecutar `composer install --no-dev --optimize-autoloader`.
- Ejecutar `npm ci` y `npm run build`.
- Confirmar `composer audit`.
- Confirmar `npm audit --audit-level=moderate`.
- Confirmar `php artisan test`.
- Confirmar que `/privacidad` y `/terminos` estan revisados por el titular.
- Confirmar que Stripe use credenciales correctas de prueba o produccion antes de aceptar pagos reales.

## Variables De Entorno

Configurar `.env`:

- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL=https://dominio-final`
- `APP_KEY` generado con `php artisan key:generate`
- `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
- `FILESYSTEM_DISK=public`
- `SESSION_ENCRYPT=true`
- `SESSION_SECURE_COOKIE=true`
- `SESSION_SAME_SITE=lax`
- `GEMINI_API_KEY`
- `GEMINI_MODEL=gemini-2.5-flash`
- `GEMINI_CA_BUNDLE=storage/certs/cacert.pem`
- `GEMINI_VERIFY_SSL=true`
- `STRIPE_KEY`
- `STRIPE_SECRET`
- `STRIPE_WEBHOOK_SECRET`
- `STRIPE_CURRENCY=pen`
- variables SMTP si se activan correos reales

No versionar `.env`.

## Base De Datos

- Crear base de datos y usuario con permisos minimos.
- Ejecutar `php artisan migrate --force`.
- Ejecutar `php artisan db:seed --force` solo si se necesita data base o demo.
- No ejecutar `migrate:fresh` en produccion.

## Storage Y Archivos

- Ejecutar `php artisan storage:link`.
- Verificar permisos de escritura en `storage/app`, `storage/framework`, `storage/logs` y `bootstrap/cache`.
- Verificar subida de portadas y materiales.
- Verificar descarga privada de materiales desde aula.

## Cache Y Rendimiento

- Ejecutar `php artisan config:cache`.
- Ejecutar `php artisan route:cache`.
- Ejecutar `php artisan view:cache`.
- Confirmar paginacion en listados admin.
- Confirmar dashboard con cache de metricas.

## Seguridad

- Forzar HTTPS en servidor web.
- Activar HSTS si todo el dominio opera con HTTPS.
- Mantener headers de seguridad activos.
- Confirmar rate limit en login y `/api/chat`.
- Confirmar que roles no admin reciben 403 en areas no permitidas.
- Confirmar que el chatbot funciona sin exponer la clave.
- Confirmar que terminos y politica de privacidad estan publicados.
- Confirmar que no se recopilan datos reales de tarjeta en el servidor.
- Confirmar que `/stripe/webhook` esta configurado en Stripe y responde OK con eventos firmados.
- Configurar backups de BD y archivos privados.
- Configurar rotacion de logs.

## Smoke Test Final

1. Abrir home, catalogo, detalle de curso y contacto.
2. Registrar usuario.
3. Iniciar sesion.
4. Agregar curso publicado al carrito.
5. Aplicar cupon valido.
6. Procesar checkout y completar pago en Stripe.
7. Confirmar venta pagada, item de venta, webhook y matricula.
8. Entrar a `Mi cuenta`.
9. Abrir aula del curso comprado.
10. Marcar material como completado.
11. Entrar como admin.
12. Crear curso borrador.
13. Crear modulo y material.
14. Publicar curso.
15. Revisar dashboard, ventas, estudiantes, settings y auditoria.

## Rollback

- Mantener backup previo de BD.
- Mantener artefacto previo de `public/build`.
- Si falla una migracion, restaurar backup o aplicar una migracion correctiva revisada.

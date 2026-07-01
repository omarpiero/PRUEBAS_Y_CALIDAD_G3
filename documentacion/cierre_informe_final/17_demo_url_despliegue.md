# 17. URL del aplicativo y evidencia de despliegue

Fecha de cierre: 2026-06-27

## Estado actual

No se ha verificado una URL publica de produccion. La evidencia disponible corresponde a una demo local documentada con capturas tomadas sobre:

```text
http://127.0.0.1:8000
```

Por tanto, el cierre de entrega se declara como **demo local / primera version MVP**, no como despliegue comercial en produccion. Esta decision cierra el punto para sustentacion academica bajo la opcion de demo local con capturas.

## Texto recomendado para el informe

El aplicativo fue validado en ambiente local de demostracion (`http://127.0.0.1:8000`) con capturas de pantallas publicas, autenticacion, panel administrativo y area del estudiante. La publicacion en una URL HTTPS queda como paso previo a produccion comercial, junto con la configuracion de Stripe live, webhook firmado, rotacion de credenciales y prueba contra MySQL staging.

## Evidencia disponible

| Evidencia | Ruta |
| --- | --- |
| Capturas publicas | `documentacion/CAPTURAS/01_PUBLICAS/` |
| Capturas auth | `documentacion/CAPTURAS/02_AUTH/` |
| Capturas admin | `documentacion/CAPTURAS/03_ADMIN/` |
| Capturas estudiante | `documentacion/CAPTURAS/04_ESTUDIANTE/` |
| Checklist deploy | `documentacion/CHECKLIST_DEPLOY_LMS.md` |
| Despliegue Docker propuesto | `documentacion/COMPLETADOS/DOCKER_DESPLIEGUE.md` |

## Checklist futuro para declarar URL publica

- [ ] Dominio o subdominio HTTPS activo.
- [ ] `APP_URL` actualizado.
- [ ] MySQL operativo en staging/produccion.
- [ ] `php artisan migrate --force` ejecutado sin errores.
- [ ] `npm run build` ejecutado.
- [ ] Stripe webhook firmado y probado.
- [ ] Llaves Stripe/Gemini rotadas.
- [ ] Smoke test: home, cursos, registro, login, carrito, aula, admin y auditoria.

## Criterio de aceptacion

El punto queda cerrado para sustentacion con la opcion 2: demo local formal, capturas del LMS y declaracion de primera version MVP. La URL publica queda como mejora futura si el equipo publica el aplicativo en hosting.

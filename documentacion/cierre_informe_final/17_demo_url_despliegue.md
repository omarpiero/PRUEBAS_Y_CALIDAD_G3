# 17. URL del aplicativo y evidencia de despliegue

Fecha de cierre: 2026-06-27

## Estado actual

No se ha verificado una URL publica de produccion. La evidencia disponible corresponde a una demo local documentada con capturas tomadas sobre:

```text
http://127.0.0.1:8000
```

Por tanto, el informe no debe declarar despliegue comercial en produccion. Debe presentar el estado como demo local o MVP funcional avanzado hasta publicar una URL HTTPS real.

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
| Despliegue Docker propuesto | `documentacion/PENDIENTES/DOCKER_DESPLIEGUE.md` |

## Checklist para declarar URL publica

- [ ] Dominio o subdominio HTTPS activo.
- [ ] `APP_URL` actualizado.
- [ ] MySQL operativo en staging/produccion.
- [ ] `php artisan migrate --force` ejecutado sin errores.
- [ ] `npm run build` ejecutado.
- [ ] Stripe webhook firmado y probado.
- [ ] Llaves Stripe/Gemini rotadas.
- [ ] Smoke test: home, cursos, registro, login, carrito, aula, admin y auditoria.

## Criterio de aceptacion

El punto queda cerrado para sustentacion si el equipo elige una de estas dos opciones:

1. Pegar URL publica HTTPS y evidencia de smoke test.
2. Declarar formalmente demo local y adjuntar capturas como evidencia de primera version.

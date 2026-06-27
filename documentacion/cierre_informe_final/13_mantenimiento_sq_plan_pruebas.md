# 13. Mantenimiento, SQ e implementacion del plan de pruebas

Fecha de cierre: 2026-06-27  
SQ: Software Quality / aseguramiento de calidad del software.

## Objetivo

Formalizar el mantenimiento posterior a la primera version y explicar como se implementa el plan de pruebas en cada cambio del LMS.

## Modelo de mantenimiento

| Tipo de mantenimiento | Aplicacion en el LMS | Evidencia |
| --- | --- | --- |
| Correctivo | Correccion de defectos como DEF-001, rate limiting, CSP o incompatibilidades de entorno. | `documentacion/AUDITORIA_FINAL_PROYECTO_2026_06_24.md` |
| Adaptativo | Ajustes por cambios de PHP, MySQL, Stripe, Gemini, hosting o navegador. | `composer.json`, `.env.example`, checklist deploy. |
| Perfectivo | Mejoras como certificados PDF, dashboard ampliado, UX de estudiante. | recomendaciones del informe. |
| Preventivo | Pruebas automatizadas, auditoria de secretos, refactor CSP, backups. | `tests/`, `documentacion/PRUEBAS/`, `INDECOPI/`. |

## Flujo SQ por cambio

1. Registrar cambio o defecto.
2. Clasificar impacto: funcional, seguridad, datos, despliegue o documentacion.
3. Asociar RF/RNF e ISO aplicable.
4. Implementar en rama controlada.
5. Ejecutar pruebas unitarias o feature relacionadas.
6. Ejecutar suite completa antes de integrar.
7. Actualizar matriz de doble entrada si cambia un RF/RNF.
8. Adjuntar evidencia de prueba o captura.
9. Integrar a rama principal.
10. Actualizar bitacora/recomendaciones si queda deuda tecnica.

## Plan minimo de pruebas por release

| Momento | Comando/evidencia | Responsable | Criterio |
| --- | --- | --- | --- |
| Antes de commit | `php artisan test --filter=<TestRelacionado>` | Desarrollador del modulo | Sin fallos en prueba impactada. |
| Antes de merge | `php artisan test` | QA / jefe de grupo | Suite completa sin fallos. |
| Antes de demo | Smoke test manual de pantallas principales | QA / expositor | Home, cursos, login, carrito, aula y admin operativos. |
| Antes de produccion real | Suite contra MySQL staging + webhook Stripe firmado | DevOps / backend | Sin incompatibilidades de BD ni pagos simulados fallidos. |
| Despues de despliegue | Checklist post-deploy | DevOps | Logs limpios y rutas criticas operativas. |

## Indicadores de calidad

| Indicador | Meta | Fuente |
| --- | ---: | --- |
| Exito de pruebas automatizadas | 100 % | `php artisan test` |
| RF cubiertos por matriz | 21/21 | matriz de doble entrada |
| RNF cubiertos por evidencia | 10/10 | matriz RNF |
| Defectos criticos abiertos | 0 antes de produccion | registro de defectos |
| Incidentes con datos reales en demo | 0 | declaracion de no datos reales |

## Criterio de aceptacion

La seccion de mantenimiento queda completa cuando el informe incluye:

- tipos de mantenimiento,
- flujo SQ,
- plan de pruebas por release,
- indicadores de calidad,
- responsables,
- evidencia final de ejecucion.

# Seguimiento continuo del proyecto

Este archivo centraliza el avance continuo del proyecto. Sirve para registrar lo que ya se completo, lo que paso de `PENDIENTES` a `COMPLETADOS`, y lo que todavia debe validarse para la entrega final.

## Regla de trabajo

1. Todo material nuevo que falte revisar entra primero en `documentacion/PENDIENTES/`.
2. Cuando el material quede revisado, integrado al informe o validado por el grupo, se mueve a `documentacion/COMPLETADOS/`.
3. Cada movimiento o cierre debe registrarse en este archivo.
4. Si el cambio se sube a GitHub, se agrega el commit asociado.

## Estados usados

| Estado | Significado |
| --- | --- |
| Completado | El punto ya fue desarrollado y subido al repositorio. |
| En validacion | El punto esta desarrollado, pero requiere revision del grupo o del docente. |
| Pendiente | Falta crear, corregir o completar evidencia. |
| Bloqueado | No se puede cerrar por falta de acceso, dependencia, permiso o informacion externa. |

## Tareas completadas anteriormente

| ID | Fecha | Tarea | Evidencia principal | Estado | Commit |
| --- | --- | --- | --- | --- | --- |
| C-001 | 2026-06-27 | Cierre documental 08 al 15 del informe final. | `documentacion/cierre_informe_final/08_unificacion_metricas_pruebas.md` a `15_conclusiones_recomendaciones_cierre.md`. | Completado | `e7ae34f` |
| C-002 | 2026-06-27 | Correccion de seguridad DEF-001 para evitar asignacion masiva de `is_admin`. | `app/Models/User.php`, `app/Http/Controllers/Admin/UserController.php`, `tests/Feature/AdminSecurityAndRolesTest.php`. | Completado | `e7ae34f` |
| C-003 | 2026-06-27 | Documentacion complementaria 16 al 21 para repositorio, demo, mockups, Word, release y pruebas. | `documentacion/cierre_informe_final/16_repositorio_modulos_integrantes.md` a `21_estado_pruebas_instalacion.md`. | Completado | `5539777` |
| C-004 | 2026-06-27 | Presentacion final para sustentacion. | `documentacion/presentacion_final/Presentacion_Final_LMS_G03.pptx`. | Completado | `5539777` |
| C-005 | 2026-06-27 | Tag inicial de cierre documental. | Tag `informe-final-g03-2026-06-27`. | Completado | `5539777` |
| C-006 | 2026-06-27 | Informe final actualizado en Word con Anexo D. | `documentacion/informe_final_actualizado/INFORME_FINAL_ACTUALIZADO_G03.docx`. | Completado | `71b2abf` |
| C-007 | 2026-06-27 | Generador reproducible del informe actualizado. | `documentacion/informe_final_actualizado/build_informe_final_actualizado.py`. | Completado | `71b2abf` |
| C-008 | 2026-06-27 | Tag completo de cierre final v2. | Tag `informe-final-g03-2026-06-27-v2`. | Completado | `71b2abf` |
| C-009 | 2026-06-28 | Reorganizacion documental: avances movidos de `PENDIENTES` a `COMPLETADOS`. | `documentacion/COMPLETADOS/` y `documentacion/PENDIENTES/README.md`. | Completado | `fd63b00` |
| C-010 | 2026-06-28 | Actualizacion de referencias internas hacia `COMPLETADOS`. | `documentacion/MATRIZ_24_06.md`, `documentacion/cierre_informe_final/10_anexos_macroprocesos.md`, `16_repositorio_modulos_integrantes.md`, `17_demo_url_despliegue.md`. | Completado | `fd63b00` |

## Avances completados sin commit directo en el repo

| ID | Fecha | Tarea | Evidencia local | Estado | Nota |
| --- | --- | --- | --- | --- | --- |
| L-001 | 2026-06-27 | ZIP final generado desde `git archive`. | `documentacion/INDECOPI/entregables/JMJS-LMS-informe-final-g03-2026-06-27-v2.zip`. | En validacion | El ZIP queda local para entrega; no se versiona para evitar duplicar el repo dentro del repo. |
| L-002 | 2026-06-27 | Hash SHA256 del ZIP final. | `4FFAA50C5238C3CB844A5AEE97D76B5C049466829E959561FCF81CB553EB5DDC`. | En validacion | Puede copiarse a una evidencia formal si el docente lo solicita. |
| L-003 | 2026-06-27 | Auditoria y desglose del informe final. | `documentacion/auditoria_informe_final/`, `documentacion/informe_final_work/`, `documentacion/DESGLOSE_DOCUMENTACION_INFORME_FINAL.md`. | En validacion | Permanecen como respaldo local no versionado. |

## Pendientes que deben seguirse registrando

| ID | Tarea pendiente | Responsable sugerido | Evidencia esperada | Estado |
| --- | --- | --- | --- | --- |
| P-001 | Exportar manualmente el Word final a PDF. | Jefe de grupo / responsable de informe. | PDF generado desde `INFORME_FINAL_ACTUALIZADO_G03.docx`. | Pendiente |
| P-002 | Confirmar nombres reales y modulo exacto de cada integrante. | Jefe de grupo. | Tabla actualizada en informe y presentacion. | Pendiente |
| P-003 | Agregar URL publica si el aplicativo se despliega. | Responsable de despliegue. | Link HTTPS y captura de smoke test. | Pendiente |
| P-004 | Resolver instalacion de Composer/vendor y ejecutar pruebas. | Responsable QA. | Salida de `php artisan test` o captura de resultado. | Bloqueado |
| P-005 | Insertar o validar capturas finales de mockups/Figma. | Responsable de diseno. | Capturas en `documentacion/CAPTURAS/` o anexo final. | Pendiente |
| P-006 | Formalizar evidencia legal si se presentara a INDECOPI. | Responsable legal/documentacion. | Declaraciones, titularidad, licencias y expediente. | Pendiente |

## Plantilla para nuevos avances

Cuando completes una nueva tarea, agregala aqui con este formato:

| ID | Fecha | Tarea | Evidencia principal | Estado | Commit |
| --- | --- | --- | --- | --- | --- |
| C-011 | AAAA-MM-DD | Descripcion corta de la tarea. | Ruta del archivo o evidencia. | Completado | `hash` |

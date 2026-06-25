# Matriz de cierre RF/RNF - 2026-06-24

Rama: `feat_LMS_v2.0`

| ID | Tipo | Prioridad | Estado anterior | Cierre implementado |
| --- | --- | --- | --- | --- |
| RF-005 | RF | Alta | Implementado con riesgo | Ventas registradas en `sales` y `sale_items` dentro de transaccion. En la rama actual el pago se inicia con Stripe Checkout en estado `pendiente`. |
| RF-006 | RF | Alta | Implementado con riesgo | Las matriculas se crean solo cuando la venta queda `pagado`, por retorno seguro de Stripe o webhook firmado. La confirmacion es idempotente. |
| RNF-004 | RNF | Media | Pendiente de migracion exacta | `audit_logs` existe con usuario, accion, entidad, valores anteriores/nuevos, IP, user-agent y timestamps. `AuditService` centraliza el registro. |
| RNF-005 | RNF | Alta | Pendiente recomendado | El checkout usa `DB::transaction` para crear venta e items juntos. Se agrego manejo de error y prueba de rollback ante item invalido. |
| RNF-006 | RNF | Alta | Pendiente recomendado | Se bloquea retirar el ultimo administrador activo y se audita el intento rechazado. |
| RNF-007 | RNF | Media | Pendiente recomendado | CSP en `production` no incluye `unsafe-inline` ni `unsafe-eval`. En local se conserva compatibilidad con Vite y scripts de desarrollo. |
| RNF-008 | RNF | Media | Pendiente por entorno | La prueba de portada usa un PNG minimo en base64 y no depende de GD. La suite completa pasa en el entorno local. |
| RNF-009 | RNF | Alta | Pendiente recomendado | `documentacion/KANBAN.md` fue actualizado con estado real y evidencia RF/RNF. |
| RNF-010 | RNF | Media | PENDIENTE_DE_IDENTIFICAR | Se formalizo licencia propietaria en `LICENSE.md` y `composer.json` declara `proprietary`, evitando una declaracion MIT sin licencia formal. |

## Verificacion

- Ejecucion previa con PHP compatible: `php artisan test`, 85 pruebas y 407 aserciones en verde.
- Verificacion actual de esta consola: `php artisan test` queda bloqueado porque el PATH resuelve XAMPP PHP 8.0.30 y el proyecto requiere PHP `^8.2`.
- Tests nuevos o reforzados: rollback de checkout, CSP productiva, ultimo administrador, auditoria CSV, Gemini operativo y validacion de reemplazo/tipo de materiales.

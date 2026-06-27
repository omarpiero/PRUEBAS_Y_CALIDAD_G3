# 11. Cierre del defecto DEF-001

Fecha de cierre: 2026-06-27  
Defecto: `is_admin` en `$fillable` permite escalada de privilegios por asignacion masiva.  
Severidad: Alta.

## Riesgo

Si `is_admin` permanece como atributo asignable masivamente, cualquier flujo que use `User::create(...)`, `$user->update(...)` o datos no filtrados podria elevar privilegios de un usuario sin pasar por el flujo administrativo de roles.

## Correccion aplicada

| Archivo | Cambio |
| --- | --- |
| `app/Models/User.php` | Se retiro `is_admin` del arreglo `$fillable`. |
| `app/Http/Controllers/Admin/UserController.php` | La sincronizacion administrativa de roles ahora actualiza `is_admin` con `forceFill(...)->save()` dentro de la transaccion controlada. |
| `tests/Feature/AdminSecurityAndRolesTest.php` | Se agrego una prueba que confirma que `is_admin` no puede asignarse por mass assignment. |

## Justificacion tecnica

El sistema conserva compatibilidad con el flag legacy `is_admin`, pero limita su modificacion a un flujo autorizado:

1. El administrador edita roles desde el panel.
2. `UserController@update` valida los roles.
3. Se evita degradar al ultimo administrador.
4. Se sincronizan roles y se actualiza `is_admin` dentro de una transaccion.
5. Se registra auditoria con `AuditService`.

## Prueba agregada

Caso: `test_is_admin_cannot_be_assigned_by_mass_assignment`.

Resultado esperado:

- Crear un usuario con `is_admin => true` por asignacion masiva no debe convertirlo en administrador.
- La promocion real a administrador debe seguir pasando por el flujo de roles.

## Comando de validacion

```bash
php artisan test --filter=AdminSecurityAndRolesTest
```

## Criterio de aceptacion

DEF-001 se considera cerrado cuando:

- `is_admin` no aparece en `$fillable`.
- El flujo de roles sigue actualizando correctamente el estado admin.
- La nueva prueba pasa junto con la suite completa.
- La recomendacion del informe final cambia de "corregir DEF-001" a "DEF-001 corregido y validado".

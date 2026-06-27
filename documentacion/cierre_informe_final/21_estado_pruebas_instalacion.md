# 21. Estado de instalacion de dependencias y pruebas

Fecha de intento: 2026-06-27  
Objetivo: instalar dependencias PHP y ejecutar la suite de pruebas final.

## Comandos intentados

```powershell
composer install
$env:COMPOSER_CACHE_DIR=(Get-Item -LiteralPath '.composer-cache').FullName
$env:COMPOSER_HOME=(Get-Item -LiteralPath '.composer-home').FullName
composer install --prefer-dist
php artisan test --filter=AdminSecurityAndRolesTest
```

## Resultado

No se pudo completar la instalacion de dependencias en este entorno local.

Motivos observados:

1. El primer intento no pudo escribir en `C:\Users\Ani\AppData\Local\Composer`.
2. Se creo cache local dentro del repositorio, pero Composer siguio fallando al escribir ZIP temporales en `vendor/composer`.
3. Al caer a descarga desde fuente, Git no pudo conectar a GitHub por red/permiso.
4. `vendor/autoload.php` no llego a generarse, por lo que `php artisan test` no puede ejecutarse todavia.

## Evidencia tecnica

Error principal:

```text
Failed to open stream: Permission denied
```

Error final al intentar clonar desde fuente:

```text
Failed to connect to github.com port 443
ssh: connect to host github.com port 22: Permission denied
```

## Estado para el informe

La suite de pruebas sigue pendiente de ejecucion final en una maquina con dependencias instalables. Las metricas `89 tests / 426 aserciones` deben mantenerse como cifra documental hasta que el equipo ejecute `php artisan test` y adjunte captura actualizada.

## Accion requerida

Ejecutar en una maquina con Composer y acceso de red:

```bash
composer install
php artisan test
```

Luego actualizar:

- captura final de pruebas,
- Capitulo 13 del informe si cambian las metricas,
- `08_unificacion_metricas_pruebas.md`.

## Criterio de cierre

Este punto queda cerrado cuando `vendor/autoload.php` existe, `php artisan test` termina sin fallos y se adjunta captura con el total real de tests/aserciones.

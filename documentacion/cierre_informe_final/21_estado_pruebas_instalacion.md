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

## Evidencia Drive verificada

La carpeta Drive del proyecto contiene dos evidencias de pruebas y calidad:

| Archivo Drive | Evidencia |
| --- | --- |
| `Informe_PHPUnit_JM_JS_Alimentos_mejorado.pdf` | Inventario de 89 pruebas PHPUnit, 426 aserciones, 21/21 RF cubiertos, pruebas Unit y Feature. |
| `Reporte_SQ_Plataforma_Capacitacion.pdf` | Reporte de calidad de software con alcance, estrategia, entorno QA, resultados y matriz SQ. |

## Estado para el informe

La ejecucion local en esta maquina sigue sin poder repetirse porque `vendor/autoload.php` no existe. Sin embargo, el pendiente queda **cerrado documentalmente** para la entrega, porque Drive contiene el informe PHPUnit y el reporte SQ con evidencia de `89 pruebas / 426 aserciones / 21 RF cubiertos`.

## Accion futura recomendada

Si el docente solicita reproduccion en vivo, ejecutar en una maquina con Composer y acceso de red:

```bash
composer install
php artisan test
```

Luego actualizar:

- captura final de pruebas,
- Capitulo 13 del informe si cambian las metricas,
- `08_unificacion_metricas_pruebas.md`.

## Criterio de cierre

Para la entrega documental queda cerrado con los PDFs de Drive. Para verificacion tecnica reproducible queda recomendado repetir `composer install` y `php artisan test` en una maquina con dependencias instalables.

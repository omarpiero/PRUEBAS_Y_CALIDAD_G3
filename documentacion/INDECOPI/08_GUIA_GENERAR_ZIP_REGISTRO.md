# Guia para generar ZIP de registro

## Objetivo

Crear un paquete limpio para registro de software, sin secretos, caches, dependencias instaladas ni datos personales reales.

## Nombre sugerido

`JMJS_LMS_v1_0_indecopi_2026_06_10.zip`

## Contenido incluido

- Codigo fuente propio.
- Configuracion versionable.
- Migraciones, seeders y factories.
- Vistas, estilos y frontend.
- Pruebas.
- Documentacion.
- Archivos de licencia y avisos de terceros.
- Locks de dependencias (`composer.lock`, `package-lock.json`).

## Contenido excluido

- `.env`
- `.git`
- `.npm-cache`
- `vendor`
- `node_modules`
- `public/build`
- `public/storage`
- `storage/logs`
- `storage/framework/cache`
- `storage/framework/sessions`
- `storage/framework/views`
- `database/database.sqlite`
- archivos temporales de Office (`~$*.docx`)

## Comando PowerShell sugerido

Ejecutar desde la raiz del proyecto:

```powershell
$root = Resolve-Path .
$outDir = Join-Path $root 'documentacion\INDECOPI\entregables'
New-Item -ItemType Directory -Force -Path $outDir | Out-Null
$zip = Join-Path $outDir 'JMJS_LMS_v1_0_indecopi_2026_06_10.zip'
$exclude = @(
  '.git', '.npm-cache', 'vendor', 'node_modules',
  'public\build', 'public\storage',
  'storage\logs', 'storage\framework\cache', 'storage\framework\sessions', 'storage\framework\views',
  'database\database.sqlite',
  'documentacion\INDECOPI\entregables'
)
$files = Get-ChildItem -Path $root -Recurse -File | Where-Object {
  $relative = $_.FullName.Substring($root.Path.Length + 1)
  -not ($relative -eq '.env') -and
  -not ($relative -like '~$*') -and
  -not ($exclude | Where-Object { $relative -eq $_ -or $relative.StartsWith($_ + '\') })
}
Compress-Archive -Path $files.FullName -DestinationPath $zip -Force
Get-FileHash -Algorithm SHA256 -Path $zip
```

## Evidencia recomendada

Guardar junto al ZIP:

- Hash SHA256.
- Fecha y hora de generacion.
- Usuario/equipo que genero el paquete.
- Resultado de pruebas y auditorias.
- Capturas de pantallas principales.

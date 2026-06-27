# 20. Release final, tag, ZIP y hash

Fecha de cierre: 2026-06-27

## Objetivo

Dejar preparado el procedimiento de congelamiento de version final para evidencia academica y expediente legal.

## Tag sugerido

```text
informe-final-g03-2026-06-27
```

El tag debe crearse sobre el commit final que incluya:

- cierre documental 08-20,
- presentacion final,
- correccion de DEF-001,
- cualquier ajuste posterior de Word o evidencias.

## Comandos recomendados

```bash
git status
git tag -a informe-final-g03-2026-06-27 -m "Version final informe Grupo 03"
git archive --format=zip --output=documentacion/INDECOPI/entregables/JMJS-LMS-informe-final-g03-2026-06-27.zip HEAD
Get-FileHash documentacion/INDECOPI/entregables/JMJS-LMS-informe-final-g03-2026-06-27.zip -Algorithm SHA256
```

## Archivos que no deben entrar al ZIP manual

Si se genera con una herramienta distinta de `git archive`, excluir:

- `.git/`
- `.env`
- `vendor/`
- `node_modules/`
- caches,
- logs,
- `.presentation_tmp/`,
- datos personales reales.

## Evidencia esperada

| Artefacto | Estado esperado |
| --- | --- |
| Tag Git | Creado sobre commit final. |
| ZIP limpio | Generado desde `git archive`. |
| SHA256 | Registrado en archivo o captura. |
| Captura de pruebas | Adjunta a anexos. |
| PPTX final | En `documentacion/presentacion_final/`. |

## Criterio de aceptacion

La version se considera congelada cuando el tag, ZIP y hash pertenecen al mismo commit final y el equipo puede reconstruir el entregable desde Git sin archivos temporales ni dependencias pesadas.

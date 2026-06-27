# 08. Unificacion de metricas de pruebas

Fecha de cierre: 2026-06-27  
Proyecto: JM y JS Alimentos LMS v2.0  
Repositorio oficial de trabajo: `https://github.com/omarpiero/PRUEBAS_Y_CALIDAD_G3.git`

## Objetivo

Cerrar la contradiccion documental entre evidencias que mencionan 407 aserciones y evidencias recientes que mencionan 426 aserciones. Para el informe final se adopta una unica metrica canonica, condicionada a una ejecucion final de la suite antes de sustentar.

## Metrica canonica para el informe final

| Indicador | Valor canonico | Uso en informe |
| --- | ---: | --- |
| Total de pruebas automatizadas | 89 | Capitulos 11, 12, 13 y conclusiones |
| Pruebas Feature | 77 | Capitulo 13, metricas de calidad |
| Pruebas Unit | 12 | Capitulo 13, metricas de calidad |
| Total de aserciones | 426 | Capitulo 13, anexos de pruebas |
| Resultado esperado | 100 % SUCCESS | Evidencia de cierre |
| Entorno QA | SQLite in-memory | Capitulo 12 e ISO/IEC 29119 |

## Evidencias base disponibles

| Evidencia | Ruta | Uso |
| --- | --- | --- |
| Estrategia y automatizacion de pruebas | `documentacion/PRUEBAS/AUTOMATIZACION_PRUEBAS.md` | Sustenta PHPUnit, SQLite in-memory, mocks de Stripe/Gemini y resultado 89/426. |
| Plan de pruebas y tecnicas | `documentacion/PRUEBAS/PRUEBAS_CALIDAD.md` | Sustenta caja negra, caja blanca, unitarias e integracion. |
| Evidencias textuales de ejecucion | `documentacion/PRUEBAS/screenshots_p/` | Adjuntar como anexos o reemplazar por captura final. |
| Tests fuente | `tests/Feature/` y `tests/Unit/` | Fuente tecnica verificable. |
| Informe extraido | `documentacion/auditoria_informe_final/INFORME_FINAL_extraccion_texto.md` | Contiene la cifra 89 tests / 426 aserciones. |

## Regla de cierre

La cifra `89 pruebas / 426 aserciones` se debe usar en todo el informe. Cualquier mencion anterior a `407 aserciones`, `73 pruebas`, `85 pruebas` u otro conteo queda marcada como historica y no debe aparecer en la version final.

## Accion obligatoria antes de entregar

1. Instalar dependencias si el clon no tiene `vendor/autoload.php`.
2. Ejecutar:

```bash
composer install
php artisan test
```

3. Guardar captura de la terminal con:
   - comando ejecutado,
   - total de tests,
   - total de aserciones,
   - resultado sin fallos.
4. Si el conteo difiere, actualizar este archivo, el Capitulo 13 del Word y las conclusiones.

## Criterio de aceptacion

El informe queda consistente cuando:

- Todos los capitulos usan la misma cifra de pruebas y aserciones.
- La captura final de `php artisan test` coincide con la cifra declarada.
- La matriz de doble entrada referencia las mismas clases de pruebas que existen en `tests/`.
- La sustentacion no mezcla cifras historicas con la metrica canonica.

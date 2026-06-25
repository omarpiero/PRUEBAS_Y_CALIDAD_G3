# Evidencia de verificacion - 2026-06-10

## Estado

Version candidata verificada tecnicamente para expediente de registro de software y revision previa a marca/patente.

## Entorno usado

| Elemento | Valor |
| --- | --- |
| PHP | 8.4.13 (`C:\Program Files\php-8.4.13-nts-Win32-vs17-x64\php.exe`) |
| Laravel | 12.61.1 |
| Node.js | v24.14.1 |
| npm | 11.11.0 |
| Rama | `feat_LMS_v2.0` |
| Commit base | `58d6c6f - Completar LMS MVP y documentacion de release` |

## Comandos ejecutados

| Comando | Resultado | Observacion |
| --- | --- | --- |
| `php artisan test` | OK | 73 pruebas pasaron, 319 aserciones |
| `npm run build` | OK | Vite genero `public/build` |
| `composer audit` | OK | No security vulnerability advisories found |
| `npm audit --audit-level=moderate --cache .npm-cache` | OK | found 0 vulnerabilities |
| `php artisan route:list --except-vendor` | OK | 69 rutas registradas |
| `Invoke-WebRequest http://127.0.0.1:8000/cursos` | OK | HTTP 200 |

## Evidencias generadas

| Archivo | Uso |
| --- | --- |
| `documentacion/INDECOPI/entregables/JMJS-LMS-v1.0-candidata-indecopi-source-2026-06-10.zip` | ZIP limpio de codigo fuente sin `.env`, `.git`, `vendor`, `node_modules`, caches ni logs |
| `documentacion/INDECOPI/entregables/SHA256SUMS.txt` | Hash SHA256 del ZIP |
| `documentacion/INDECOPI/capturas/01-cursos.png` | Captura del catalogo publico |
| `documentacion/INDECOPI/capturas/02-privacidad.png` | Captura de politica de privacidad |
| `documentacion/INDECOPI/capturas/03-terminos.png` | Captura de terminos y condiciones |
| `documentacion/INDECOPI/11_DIAGRAMA_ARQUITECTURA.md` | Diagrama de arquitectura Mermaid |

## SHA256 del ZIP fuente

```text
54716fcc46f0bc3a62972099c8e3333ad43ce4a88049f35b0b16d7afcf2b08f1  JMJS-LMS-v1.0-candidata-indecopi-source-2026-06-10.zip
```

## Correcciones tecnicas aplicadas

- Checkout envuelto en transaccion de base de datos.
- Cupos de cupon bloqueados con `lockForUpdate` durante checkout.
- Checkout actualizado a Stripe Checkout con venta pendiente, retorno seguro y webhook firmado.
- Confirmacion de venta idempotente para evitar matriculas o usos de cupon duplicados.
- Servicio Stripe real exige `STRIPE_SECRET` y `STRIPE_WEBHOOK_SECRET`.
- Migracion de `enrollments` reemplazada por proceso no destructivo con preservacion de campos legacy.
- Validacion de archivos de materiales en update fortalecida.
- CSP endurecida eliminando `unsafe-eval` y declarando fuentes por tipo.
- Prueba de portada de curso independizada de extension GD.
- Dependencia `concurrently` actualizada a `^10.0.3`.
- Politica de privacidad y terminos publicados sin placeholders visibles al usuario.
- ZIP, hash, capturas y diagrama agregados al expediente.

## Bloqueantes externos no resolubles desde codigo

- Definir titular legal, RUC/DNI, domicilio y representante.
- Firmar cesiones de derechos patrimoniales de autores y proveedores.
- Validar antecedentes de marca en INDECOPI antes de presentar.
- Presentar solicitudes, pagar tasas y guardar numero de expediente.
- Para patente, identificar una invencion tecnica concreta distinta del software LMS como tal.

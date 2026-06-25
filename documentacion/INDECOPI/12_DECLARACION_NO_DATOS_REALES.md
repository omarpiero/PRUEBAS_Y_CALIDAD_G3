# Declaracion de evidencia sin datos personales reales

Fecha: 2026-06-10

La version candidata del expediente tecnico se preparo con el criterio de no incluir datos personales reales de alumnos, clientes, trabajadores o terceros en los anexos de evidencia.

## Alcance

- El ZIP fuente excluye `.env`, caches, logs, sesiones, dependencias instaladas y artefactos generados.
- Las capturas generadas corresponden a pantallas publicas o documentos legales de la plataforma.
- Stripe Checkout procesa los datos de tarjeta fuera de la plataforma; el servidor no almacena numero de tarjeta, CVC ni fecha de expiracion.
- Las claves de servicios externos deben conservarse solo en variables de entorno del servidor y no en el expediente publico.

## Verificaciones realizadas

- Se verifico que el ZIP no contiene `.env`, `.git`, `vendor` ni `node_modules`.
- El hash SHA256 del ZIP quedo registrado en `entregables/SHA256SUMS.txt`.
- Las dependencias de terceros estan inventariadas en `THIRD_PARTY_NOTICES.md`.

## Pendiente externo

Antes de presentar formalmente, el titular debe revisar que no existan datos personales reales en la base de datos, archivos privados, logs o materiales que decida adjuntar fuera del ZIP fuente.

# Registro de software por derecho de autor

## Via recomendada

Registrar la plataforma como "software o programa de ordenador" ante la Direccion de Derecho de Autor de INDECOPI.

Esta via protege la expresion concreta de la obra: codigo fuente, estructura de archivos, documentacion, manuales, pantallas originales, diagramas y materiales propios. No protege ideas abstractas, reglas de negocio genericas ni librerias de terceros.

## Denominacion propuesta

JM y JS Alimentos LMS - Plataforma de capacitacion en calidad e inocuidad alimentaria

## Tipo de obra

Software o programa de ordenador.

## Titular sugerido

Pendiente de confirmar por el usuario:

- Persona natural: [Nombres, DNI, domicilio]
- Persona juridica: [Razon social, RUC, representante legal]

## Autores/desarrolladores

Completar antes de presentar:

| Autor | Documento | Rol | Periodo | Entrego cesion? |
| --- | --- | --- | --- | --- |
| [Nombre] | [DNI/CE] | Full stack / arquitectura | [Fecha] | Si/No |
| [Nombre] | [DNI/CE] | UI/UX / contenidos | [Fecha] | Si/No |

## Anexos sugeridos

1. Formulario F-DDA-02 de INDECOPI para software/programa de ordenador.
2. Copia del codigo fuente congelado.
3. Manual tecnico y funcional.
4. Manual de administrador.
5. Capturas de pantallas principales.
6. Diagrama de arquitectura.
7. Inventario de librerias de terceros.
8. Contratos o declaraciones de cesion de derechos.
9. Declaracion de titularidad y originalidad.
10. Comprobante de pago de tasa.

## Preparacion del ZIP tecnico

Incluir:

- `app/`
- `bootstrap/`
- `config/`
- `database/`
- `public/` sin archivos generados innecesarios ni uploads privados
- `resources/`
- `routes/`
- `tests/`
- `composer.json`
- `composer.lock`
- `package.json`
- `package-lock.json`
- `README.md`
- `LICENSE.md`
- `THIRD_PARTY_NOTICES.md`
- `documentacion/`

Excluir:

- `.env`
- `vendor/`
- `node_modules/`
- `storage/logs/`
- `storage/framework/cache/`
- `storage/framework/sessions/`
- `.npm-cache/`
- datos personales reales de alumnos/clientes
- claves Gemini, Stripe, SMTP, DB o cualquier secreto

## Verificaciones antes de presentar

```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan test
composer audit
npm audit --audit-level=moderate
```

## Nota importante

El registro de derecho de autor no equivale a patente. Protege la obra de software frente a copia o explotacion no autorizada, pero no otorga monopolio sobre el concepto general de LMS, catalogo de cursos, carrito, aula virtual o chatbot.

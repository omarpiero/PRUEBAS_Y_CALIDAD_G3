# 14. Privacidad, terminos y licencias

Fecha de cierre: 2026-06-27  
Objetivo: dejar control documental sobre datos personales, terminos de uso y dependencias/activos de terceros.

## Privacidad

El LMS trata datos de usuarios como nombre, correo, DNI, telefono, compras, matriculas y progreso academico. Por ello, el informe debe indicar que la plataforma requiere politica de privacidad visible antes de uso real.

### Controles esperados

| Control | Estado esperado | Evidencia |
| --- | --- | --- |
| Politica de privacidad | Publicada en `/privacidad` | ruta `routes/web.php` y captura |
| Terminos y condiciones | Publicados en ruta visible | captura o enlace |
| No exponer `.env` | Verificado en ZIP limpio | guia INDECOPI |
| No usar datos personales reales en demo | Declarado | `documentacion/INDECOPI/12_DECLARACION_NO_DATOS_REALES.md` |
| Credenciales Stripe/Gemini fuera del codigo | Variables de entorno | `.env.example`, config services |
| HTTPS para produccion | Pendiente antes de pagos reales | checklist deploy |

## Licencias de software

| Componente | Uso | Accion |
| --- | --- | --- |
| Laravel | Framework backend | Conservar licencia upstream y dependencias Composer. |
| PHPUnit | Pruebas | Conservar dependencias dev. |
| Stripe PHP SDK | Pagos | Revisar terminos de Stripe y usar credenciales seguras. |
| Vite/Tailwind/React | Frontend y chatbot | Conservar licencias de paquetes Node. |
| Google Gemini API | IA generativa | Cumplir terminos de API y no enviar datos sensibles innecesarios. |

## Activos visuales y contenido

| Activo | Riesgo | Accion requerida |
| --- | --- | --- |
| Logo y marca JM y JS Alimentos | Titularidad no formalizada en informe | Confirmar autorizacion o creacion propia. |
| Imagenes de cursos/login | Posible origen externo | Documentar fuente/licencia o reemplazar por activos propios. |
| Iconos | Licencia de terceros | Registrar biblioteca o fuente. |
| Contenido de cursos | Derechos de autor | Confirmar que el equipo/empresa tiene autorizacion de uso. |
| Capturas | Datos personales | Usar usuarios demo y ocultar datos reales. |

## Texto sugerido para el informe

Para la version academica se emplean datos de prueba y capturas controladas. Antes de operar comercialmente, el sistema debe publicar politicas de privacidad, terminos y condiciones, y mantener las credenciales de servicios externos fuera del repositorio. Las imagenes, iconos, logos y contenidos educativos deben contar con licencia, autorizacion o declaracion de autoria propia.

## Criterio de aceptacion

El punto queda cerrado cuando:

- existe evidencia de privacidad y terminos,
- el ZIP final no contiene `.env`, `vendor`, `node_modules`, logs ni datos reales,
- los activos visuales tienen origen documentado,
- el informe distingue demo academica de produccion comercial real.

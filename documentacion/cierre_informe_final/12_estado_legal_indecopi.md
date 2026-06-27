# 12. Estado legal e INDECOPI

Fecha de cierre: 2026-06-27  
Documento de apoyo para el apartado legal, anexos y recomendaciones del informe final.

## Estado general

El proyecto cuenta con una carpeta documental para preparar un expediente INDECOPI, pero el expediente no debe presentarse como cerrado hasta completar datos legales, firmas, titularidad y version final congelada.

## Ruta recomendada

| Via | Estado | Decision |
| --- | --- | --- |
| Derecho de autor de software | Preparado tecnicamente | Via principal recomendada. |
| Registro de marca | Pendiente de decision | Confirmar signo distintivo, logo y titular. |
| Patente | No recomendada por defecto | Solo evaluar si existe una invencion tecnica concreta distinta al LMS/e-commerce/chatbot. |

## Evidencias disponibles

| Documento | Ruta | Uso |
| --- | --- | --- |
| Guia de expediente | `documentacion/INDECOPI/README_EXPEDIENTE_INDECOPI.md` | Resumen legal-tecnico. |
| Registro de software | `documentacion/INDECOPI/01_REGISTRO_SOFTWARE_DERECHO_AUTOR.md` | Requisitos para software. |
| Registro de marca | `documentacion/INDECOPI/02_REGISTRO_MARCA.md` | Estrategia de marca. |
| Evaluacion de patente | `documentacion/INDECOPI/03_EVALUACION_PATENTABILIDAD.md` | Filtro tecnico-juridico. |
| Memoria tecnica | `documentacion/INDECOPI/04_MEMORIA_TECNICA_SOFTWARE.md` | Descripcion de arquitectura y modulos. |
| Autorias y cesiones | `documentacion/INDECOPI/05_EVIDENCIA_AUTORIA_TITULARIDAD.md` | Matriz pendiente de completar. |
| Checklist | `documentacion/INDECOPI/06_CHECKLIST_PRE_PRESENTACION.md` | Control antes de presentar. |
| Activos PI | `documentacion/INDECOPI/07_INVENTARIO_ACTIVOS_PI.md` | Codigo, marca, logo, imagenes, docs. |
| Modelos de declaraciones | `documentacion/INDECOPI/09_MODELOS_DECLARACIONES.md` | Plantillas para firma. |
| No datos reales | `documentacion/INDECOPI/12_DECLARACION_NO_DATOS_REALES.md` | Sustento de privacidad. |

## Pendientes legales

| ID | Pendiente | Responsable sugerido | Evidencia requerida |
| --- | --- | --- | --- |
| LEG-01 | Definir titular del software | Jefe de grupo | Nombre legal, DNI/RUC, domicilio. |
| LEG-02 | Completar autores y aportes | Cada integrante | Modulo, commits, archivos y declaracion de autoria. |
| LEG-03 | Firmar cesiones o autorizaciones | Cada integrante | Documento firmado o acuerdo interno. |
| LEG-04 | Confirmar licencia de imagenes/logo/iconos | Responsable UI/legal | Fuente, licencia o declaracion de creacion propia. |
| LEG-05 | Congelar version final | Responsable Git | Tag, hash de commit, ZIP limpio y SHA256. |
| LEG-06 | Revisar privacidad y terminos | Responsable legal/QA | Capturas o URL de politicas publicadas. |
| LEG-07 | Buscar antecedentes de marca | Responsable legal | Consulta en INDECOPI o registro de busqueda. |

## Texto sugerido para el informe

El proyecto cuenta con documentacion preliminar para un expediente de proteccion de propiedad intelectual ante INDECOPI. La via principal recomendada es el registro de software por derecho de autor, complementada por una posible solicitud de marca si el titular confirma el signo distintivo. La patente no se considera recomendable salvo que se formule una invencion tecnica novedosa y no evidente que exceda la funcionalidad comun de un LMS, e-commerce o chatbot.

## Criterio de aceptacion

El estado legal se considera completo para el informe cuando se incluyen:

- tabla de titularidad,
- autores y aportes,
- decision de marca,
- declaracion de licencias de terceros,
- declaracion de no uso de datos personales reales,
- ZIP/hash o tag final del codigo fuente.

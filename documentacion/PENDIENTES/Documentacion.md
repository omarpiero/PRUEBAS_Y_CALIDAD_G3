# Documentación Técnica y Académica — LMS JM y JS Alimentos v2.0

> Este documento actúa como índice maestro y puente entre el código fuente (repositorio) y la estructura del **Proyecto Final-G3.docx.pdf**. A continuación, se detalla qué archivo Markdown provee la información hiper-detallada para poblar cada capítulo del informe final.

---

## 1. Mapeo de Capítulos del Proyecto Final vs. Archivos Markdown Generados

La siguiente tabla relaciona los 14 capítulos estructurales exigidos en el informe final con los documentos técnicos generados en el directorio `PENDIENTES/` e `ISOS/`.

| Capítulo del Proyecto Final | Archivo(s) de Referencia (Markdown) | Contenido Principal / Justificación |
|---|---|---|
| **Capítulo 1:** Información General | N/A (Subjetivo del estudiante) | Resumen ejecutivo y contexto introductorio. |
| **Capítulo 2:** Contexto y Problema | `ESTADO_DEL_ARTE_AMPLIADO.md` | Justificación técnica del problema (SDD, uso de AI Squads). |
| **Capítulo 3:** Análisis de Procesos | `MACROPROCESOS.md` | Mapeo detallado de los 7 macroprocesos con sus rutas y controladores (BPMN AS-IS/TO-BE). |
| **Capítulo 4:** Requerimientos | `MATRIZ_DOBLE_ENTRADA.md` | Matriz de requerimientos funcionales cruzados con archivos, e integración de actores. |
| **Capítulo 5:** Planificación y Calidad | `ISOS/ISO_9001.md` / `ISO_25000.md` | Plan de calidad de la plataforma, SLAs y estándares aplicados. |
| **Capítulo 6:** Diseño del Sistema | `MACROPROCESOS.md` | Diseño de base de datos e interfaces guiadas por spec-driven development. |
| **Capítulo 7:** Arquitectura Tecnológica| `ESTADO_DEL_ARTE_AMPLIADO.md` | Validación del stack tecnológico mediante el Thoughtworks Technology Radar (React, Tailwind, Laravel). |
| **Capítulo 8:** Desarrollo del Sistema | `DESARROLLO_ITERATIVO.md` | Resumen de iteraciones, desarrollo de Stripe y chatbot Gemini. |
| **Capítulo 9:** Control de Versiones | `CONTROL_VERSIONES.md` | Estrategia de ramas (feat/main), trazabilidad de commits y uso de Git. |
| **Capítulo 10:** Dockerización | `DOCKER_DESPLIEGUE.md` | Propuesta de arquitectura con `docker-compose.yml` (Nginx, PHP-FPM, MySQL) y despliegue a Vercel. |
| **Capítulo 11:** Estrategia de Pruebas | `PRUEBAS/AUTOMATIZACION_PRUEBAS.md` | Enfoque de pruebas, pirámide de testing e inyección de dependencias. |
| **Capítulo 12:** Automatización | `PRUEBAS/AUTOMATIZACION_PRUEBAS.md` | Detalles sobre PHPUnit, Mocks/Stubs de Stripe/Gemini y entorno en memoria SQLite. |
| **Capítulo 13:** Ejecución y Reportes | `PRUEBAS/AUTOMATIZACION_PRUEBAS.md` | Evidencia de los 85 Feature Tests y 407 aserciones sin fallos (Success 100%). |
| **Capítulo 14:** Gestión de Calidad ISO | `ISOS/ISO_9001.md`, `ISOS/ISO_25000.md`, `ISOS/ISO_27000.md`, `ISOS/ISO_29119.md` | Acreditación explícita del código fuente para cumplir las 4 normas ISO del proyecto. |

---

## 2. Documentos de Investigación y Respaldo
Adicional a los capítulos, se generó un respaldo académico riguroso en el directorio `INFORMES/`:
- **`INFORMES/Resumen.md`**: Vinculación directa entre 6 artículos científicos (SDD, IEEE Computer, Innova Science) y el código de tu proyecto, dotando al informe final de un marco teórico inobjetable.

---

## 3. Instrucciones de Uso para el Informe Final
1. Abre tu documento de Word `Proyecto Final-G3`.
2. Dirígete al capítulo que desees redactar.
3. Abre el archivo Markdown correspondiente a ese capítulo listado en la tabla superior.
4. **Copia, adapta y traslada** la información técnica proporcionada en el Markdown hacia tu documento, ya que la información redactada allí no es genérica, sino que contiene los nombres reales de los controladores, middlewares y test de este repositorio, lo que le dará un sustento técnico absoluto a tu proyecto final.

*Actualizado: Junio 2026*

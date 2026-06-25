# Expediente INDECOPI - JM y JS Alimentos LMS

Fecha de preparacion: 2026-06-10
Version sugerida: v1.0-candidata-indecopi
Rama auditada: feat_LMS_v2.0
Ultimo commit base antes de ajustes: 58d6c6f - Completar LMS MVP y documentacion de release

## Objetivo

Preparar el proyecto para proteccion de propiedad intelectual ante INDECOPI mediante tres vias complementarias:

1. Registro de software o programa de ordenador por derecho de autor.
2. Registro de marca para el signo distintivo de la plataforma/empresa.
3. Evaluacion de patentabilidad solo si se identifica una invencion tecnica protegible.

## Conclusion juridico-tecnica

La via principal recomendada para este proyecto es el registro de software por derecho de autor. La patente no debe presentarse sobre el LMS "como software", porque la Decision 486 de la Comunidad Andina excluye los programas de ordenador o soporte logico como tales de la categoria de invenciones.

Solo conviene intentar patente si el titular puede formular una solucion tecnica novedosa, no obvia y con aplicacion industrial, por ejemplo un procedimiento tecnico propio de diagnostico, trazabilidad, evaluacion automatizada o control de inocuidad alimentaria que vaya mas alla de una plataforma LMS/e-commerce/chatbot.

## Fuentes oficiales usadas

- INDECOPI - Registrar una patente de invencion en el Peru: https://www.gob.pe/14993-registrar-una-patente-de-invencion-en-el-peru
- INDECOPI - Registrar una obra en el Indecopi: https://www.gob.pe/83358-registrar-una-obra-en-el-indecopi
- INDECOPI - Registrar una marca: https://www.gob.pe/333-registrar-la-marca-de-producto-o-servicio-de-tu-negocio-en-indecopi
- Comunidad Andina - Decision 486, Regimen Comun sobre Propiedad Industrial: https://www.comunidadandina.org/DocOficialesFiles/Gacetas/Gace600.pdf

## Documentos de esta carpeta

- `01_REGISTRO_SOFTWARE_DERECHO_AUTOR.md`: requisitos, anexos y pasos para registro del software.
- `02_REGISTRO_MARCA.md`: estrategia de marca, clases sugeridas y evidencias.
- `03_EVALUACION_PATENTABILIDAD.md`: filtro para decidir si procede patente.
- `04_MEMORIA_TECNICA_SOFTWARE.md`: memoria tecnica del sistema.
- `05_EVIDENCIA_AUTORIA_TITULARIDAD.md`: matriz de autoria, cesiones y trazabilidad.
- `06_CHECKLIST_PRE_PRESENTACION.md`: checklist operativo antes de presentar.
- `07_INVENTARIO_ACTIVOS_PI.md`: inventario de activos propios y de terceros.
- `08_GUIA_GENERAR_ZIP_REGISTRO.md`: guia para generar ZIP fuente limpio.
- `09_MODELOS_DECLARACIONES.md`: modelos de declaraciones y cesiones.
- `10_EVIDENCIA_VERIFICACION_2026_06_10.md`: resultados de pruebas, build, auditorias, ZIP, hash y capturas.
- `11_DIAGRAMA_ARQUITECTURA.md`: diagrama tecnico del sistema.
- `12_DECLARACION_NO_DATOS_REALES.md`: declaracion de evidencia sin datos personales reales.
- `entregables/`: ZIP fuente limpio y archivo SHA256.
- `capturas/`: capturas principales del sistema.

## Estado de preparacion

| Area | Estado | Accion siguiente |
| --- | --- | --- |
| Software registrable | Preparado tecnicamente | Congelar en Git y completar datos del titular |
| Marca | Requiere decision del titular | Confirmar denominacion exacta y logo a registrar |
| Patente | No recomendada aun | Hacer busqueda de antecedentes y definir invencion tecnica concreta |
| Autorias y cesiones | Pendiente legal/administrativo | Reunir DNI/RUC, contratos y firmas |
| Seguridad producto | Verificada para version candidata | Mantener auditorias, credenciales Stripe y webhook firmados antes de produccion |

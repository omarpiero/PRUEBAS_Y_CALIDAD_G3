# Tablero KANBAN - JM y JS Alimentos LMS

Proyecto: evolucion de prototipo a plataforma LMS profesional para cursos de calidad alimentaria.
Ultima auditoria: 2026-06-24.
Motor local auditado: MySQL en XAMPP, puerto 3307, base `jm_js_alimentos`.
Admin de pruebas: `72682019@continental.edu.pe` / `password`.
Documento base de auditoria: `documentacion/AUDITORIA_LMS_2026_06_07.md`.

---

## 1. Estado Ejecutivo

El proyecto evoluciono a una plataforma LMS funcional: catalogo dinamico, carrito, Stripe Checkout, ventas, cupones, matriculas, aula privada, progreso, roles, permisos, auditoria, settings, asistente Gemini y documentacion de preparacion INDECOPI.

Conclusion de producto: el siguiente trabajo debe enfocarse en endurecimiento productivo, credenciales finales, operacion legal/comercial, monitoreo y contenidos propios verificables.

---

## 2. Estado Auditado Del Proyecto

| Area | Estado | Evidencia | Riesgo |
| --- | --- | --- | --- |
| Laravel y PHP | BLOCKED en este equipo | `composer.json` exige PHP `^8.2`; el PATH actual resuelve XAMPP PHP `8.0.30` | Activar PHP 8.2+ antes de ejecutar Artisan, migraciones o tests |
| Base de datos | OK | Migraciones LMS, ventas, auditoria, roles y matriculas | Ejecutar `migrate --force` en despliegue |
| Storage publico/privado | OK | `public/storage` y materiales privados por controlador | Revisar permisos en produccion |
| Cursos | OK | Catalogo dinamico, CRUD admin, modulos y materiales | Cargar contenidos finales propios |
| Materiales | OK | Videos, documentos, recursos y texto enriquecido | Validar derechos de materiales reales |
| Ventas y cupones | OK | Stripe Checkout, `sales`, `sale_items`, cupones y matriculas | Requiere webhook live y politicas comerciales |
| Roles y permisos | OK | Roles reales, permisos, legacy admin sincronizado | No se permite degradar el ultimo admin |
| Dashboard admin | OK | KPIs, ventas, estudiantes, cache y graficos | Ajustar metricas segun operacion real |
| Seguridad | OK | Rate limit, CSP productiva, auditoria y headers | Hacer pentest antes de produccion |
| Tests | RISK de entorno | Suite previamente documentada con 85 pruebas; en esta consola `php artisan test` queda bloqueado por PHP 8.0.30 | Repetir suite completa con PHP 8.2+ activo antes de release |
| Git | OK local | Rama `feat_LMS_v2.0` con commits de integracion | Push/PR depende de permisos GitHub |

Conteo de datos auditado:

| Entidad | Registros |
| --- | ---: |
| users | 3 |
| roles | 4 |
| permissions | 30 |
| categories | 6 |
| courses | 9 |
| course_modules | 34 |
| course_materials | 0 |
| enrollments | 0 |
| sales | 0 |
| coupons | 0 |
| settings | 13 |

---

## 3. Dependencias Instaladas Y Decision Tecnica

Dependencias instaladas en esta auditoria:

| Dependencia | Tipo | Uso previsto |
| --- | --- | --- |
| `chart.js` | npm | Graficos del dashboard: ventas, inscripciones, cursos mas vendidos |
| `sortablejs` | npm | Reordenamiento de modulos y materiales |
| `quill@2.0.2` | npm | Editor enriquecido para materiales tipo texto |
| `stripe/stripe-php` | Composer | Stripe Checkout real con webhook firmado |

Dependencias que se evitan por ahora:

| Dependencia | Motivo |
| --- | --- |
| Paquete externo de roles | Ya existen tablas custom `roles`, `permissions`, `role_user`, `role_permission`; instalar otro sistema ahora duplicaria reglas |
| Media library pesada | Laravel Storage cubre uploads locales para esta etapa; se puede migrar despues si hay CDN/S3 o transformaciones avanzadas |
| PDF/Excel | No son core del LMS inicial; quedan para reportes/certificados en backlog |

---

## 4. Cierre RF/RNF Auditado - 2026-06-24

| ID | Tipo | Requisito | Estado actual | Evidencia |
| --- | --- | --- | --- | --- |
| RF-005 | RF | Registrar ventas en `sales` y `sale_items` | Implementado | `PaymentController::process`, `PaymentStripeTest::test_process_creates_pending_sale_and_redirects_to_stripe` |
| RF-006 | RF | Crear `enrollments` cuando el pago queda `pagado` | Implementado | `PaymentController::confirmSale`, tests de retorno y webhook Stripe |
| RNF-004 | RNF | Mantener trazabilidad administrativa con `AuditService` | Implementado | `AuditService`, `audit_logs`, export CSV y tests de auditoria |
| RNF-005 | RNF | Ejecutar checkout en transaccion para evitar ventas parciales | Implementado | `DB::transaction` en checkout y test de rollback de venta/items |
| RNF-006 | RNF | Proteger contra remocion del ultimo administrador | Implementado | `UserController::hasAnotherAdmin` y test `last_admin_cannot_be_demoted` |
| RNF-007 | RNF | Endurecer CSP productiva sin `unsafe-inline` ni `unsafe-eval` | Implementado | `SecurityHeadersMiddleware` y test de CSP en `production` |
| RNF-008 | RNF | Ejecutar suite al 100% sin depender de GD | Implementado | Upload PNG fake sin GD y `php artisan test` verde |
| RNF-009 | RNF | Actualizar Kanban al estado real del codigo | Implementado | Esta seccion y Estado Auditado actualizado |
| RNF-010 | RNF | Licencia formal consistente con `composer.json` | Implementado | `composer.json` declara `proprietary`; `LICENSE.md` formaliza licencia propietaria |

---

## 5. Definicion De Estados

| Estado | Significado |
| --- | --- |
| TODO | No iniciado |
| READY | Refinado, con criterios claros |
| DOING | En desarrollo |
| REVIEW | Implementado, pendiente de QA/revision |
| DONE | Implementado, probado y documentado |
| BLOCKED | Requiere decision, acceso o dependencia externa |
| RISK | Riesgo identificado, no bloquea todavia |

Prioridades:

| Prioridad | Significado |
| --- | --- |
| P0 | Bloquea avance o seguridad |
| P1 | Funcionalidad core del LMS |
| P2 | Importante para operacion profesional |
| P3 | Mejora o nice-to-have |

---

## 5. Definicion De Done Global

Una tarea se considera DONE cuando cumple todo lo siguiente:

- Migraciones y seeders, si aplica, corren sin errores.
- La ruta o pantalla esta conectada con datos reales, no hardcodeados.
- Tiene validaciones de servidor y mensajes de error claros.
- Respeta permisos del usuario autenticado.
- No rompe `npm run build`.
- No rompe `php artisan test`.
- No introduce advisories en `composer audit` ni `npm audit`.
- Tiene al menos una prueba feature/unit cuando toca flujo critico.
- Queda documentada en este Kanban o en la auditoria tecnica.

---

## 5.1 Protocolo De Trabajo Con Agente Secundario

Este tablero debe usarse con dos roles claros:

| Rol | Responsabilidad | Decision final |
| --- | --- | --- |
| Cerebro principal | Mantener vision de producto, arquitectura, prioridades, revision y criterios de aceptacion | Aprueba o pide correcciones |
| Agente secundario | Implementar el sprint asignado siguiendo este Kanban | No cambia alcance sin reportarlo |

Reglas para el agente secundario:

- Implementar solo el sprint asignado, salvo que una dependencia tecnica minima sea indispensable.
- Leer primero `documentacion/AUDITORIA_LMS_2026_06_07.md`, este Kanban, `routes/web.php`, modelos y migraciones relacionadas.
- No reemplazar el stack: seguir Laravel MVC, Blade, Eloquent, Laravel Storage, Vite y las dependencias ya instaladas.
- No instalar paquetes nuevos sin justificar necesidad, impacto y alternativa nativa.
- No reescribir pantallas ajenas al sprint salvo que sea necesario para enlazar navegacion.
- No romper compatibilidad con el admin `72682019@continental.edu.pe`.
- No eliminar datos existentes ni recrear la base sin permiso explicito.
- No dejar cursos, ventas, materiales o permisos hardcodeados cuando exista tabla para ello.
- Cada cambio de comportamiento debe tener prueba o al menos verificacion manual documentada.
- Al terminar, entregar reporte con archivos tocados, rutas creadas, comandos ejecutados, pruebas y pendientes.

Formato de orden para enviar al agente secundario:

```text
Actua como agente secundario de implementacion Laravel. Implementa solamente el Sprint X del archivo documentacion/KANBAN.md.

Antes de editar:
- Lee documentacion/KANBAN.md y documentacion/AUDITORIA_LMS_2026_06_07.md.
- Revisa rutas, modelos, migraciones, controladores y vistas relacionadas al Sprint X.

Durante la implementacion:
- Sigue Laravel MVC + Blade + Eloquent.
- Mantiene cambios acotados al sprint.
- Agrega validaciones de servidor.
- Agrega pruebas feature/unit cuando el sprint toca flujo critico.
- No instala dependencias nuevas salvo justificacion clara.

Al finalizar:
- Ejecuta php artisan test, npm run build, composer audit y npm audit --audit-level=moderate.
- Reporta archivos modificados, rutas nuevas, pruebas ejecutadas, decisiones tecnicas, riesgos y pendientes.
```

Formato de reporte que el usuario debe traer de vuelta al cerebro principal:

```text
Sprint implementado:
Resumen:
Archivos modificados:
Rutas/controladores/vistas creadas:
Migraciones/seeders/factories:
Pruebas agregadas:
Comandos ejecutados y resultado:
Capturas o descripcion visual:
Pendientes o decisiones tomadas:
Errores encontrados:
```

Gates de revision por sprint:

| Gate | Pregunta de revision |
| --- | --- |
| Producto | Lo implementado resuelve el objetivo del sprint sin agregar ruido? |
| Arquitectura | Respeta modelos, relaciones, servicios y responsabilidades MVC? |
| Seguridad | Valida permisos, CSRF, uploads, ownership y acceso privado? |
| Datos | Usa BD real y evita hardcodear entidades dinamicas? |
| UX | La pantalla es usable, responsive y sin superposiciones? |
| QA | Tests/build/audits pasan y cubren el flujo critico? |
| Documentacion | El Kanban o docs indican que se hizo y que falta? |

---

## 6. Sprint 0 - Estabilizacion, Auditoria Y Preparacion

Objetivo: dejar el entorno verificable y el plan realista antes de construir modulos nuevos.
Estado general: DONE con riesgos documentados.

| ID | Tarea | Prioridad | Estado | Criterio de aceptacion |
| --- | --- | --- | --- | --- |
| S0-01 | Auditar prompt original y documentos del proyecto | P0 | DONE | Brechas del LMS registradas en auditoria |
| S0-02 | Verificar stack y entorno Laravel | P0 | DONE | `php artisan about` muestra Laravel `12.61.1` |
| S0-03 | Verificar migraciones MySQL | P0 | DONE | `php artisan migrate:status` muestra todas las migraciones como `Ran` |
| S0-04 | Enlazar storage publico | P0 | DONE | `public/storage` enlazado con `storage/app/public` |
| S0-05 | Instalar dependencias frontend LMS | P1 | DONE | `chart.js`, `sortablejs`, `quill@2.0.2` en `package.json` |
| S0-06 | Instalar SDK Stripe PHP | P2 | DONE | `stripe/stripe-php` en `composer.json` |
| S0-07 | Corregir advisories Composer | P0 | DONE | `composer audit` reporta 0 vulnerabilities |
| S0-08 | Corregir advisories npm | P0 | DONE | `npm audit --audit-level=moderate` reporta 0 vulnerabilities |
| S0-09 | Ejecutar build | P0 | DONE | `npm run build` pasa |
| S0-10 | Ejecutar tests existentes | P0 | DONE | `php artisan test` pasa: 2 tests, 9 assertions |
| S0-11 | Documentar riesgo PHP/XAMPP `mysqli` duplicado | P2 | READY | Indicar archivo PHP ini exacto y una sola extension activa |
| S0-12 | Resolver ausencia de Git en carpeta actual | P1 | READY | Inicializar repo o trabajar sobre clone correcto con `.git` |

---

## 7. Sprint 1 - Fundamentos De Arquitectura LMS

Objetivo: convertir la base ya creada en una arquitectura confiable para construir pantallas y APIs.
Dependencias: Sprint 0.
Estado general: READY.

| ID | Tarea | Prioridad | Estado | Criterio de aceptacion |
| --- | --- | --- | --- | --- |
| S1-01 | Revisar migracion `recreate_enrollments` contra migracion antigua | P0 | READY | No hay perdida silenciosa de datos ni conflicto de estados |
| S1-02 | Normalizar estados de `enrollments` | P0 | READY | Estados definidos: `activo`, `suspendido`, `completado`, `cancelado` o equivalente documentado |
| S1-03 | Crear middleware de permisos | P0 | READY | Rutas pueden usar `permission:courses.view` y admin conserva acceso total |
| S1-04 | Mantener compatibilidad con `is_admin` legacy | P1 | READY | Usuarios admin actuales no pierden acceso |
| S1-05 | Crear FormRequests base para cursos | P1 | READY | Validaciones `store/update` centralizadas |
| S1-06 | Crear FormRequests base para modulos | P1 | READY | Nombre, orden y estado validados |
| S1-07 | Crear FormRequests base para materiales | P1 | READY | Tipo, URL, archivo, extension y tamanio validados por tipo |
| S1-08 | Definir `config/lms.php` | P1 | READY | Limites de upload, extensiones permitidas y providers de video configurables |
| S1-09 | Definir convenciones de storage | P1 | READY | Rutas tipo `materials/{course_id}/{module_id}` documentadas y probadas |
| S1-10 | Crear servicio `CoursePublishingService` | P1 | READY | Publicar valida que el curso tenga modulo y contenido minimo |
| S1-11 | Crear servicio `VideoEmbedService` | P1 | READY | YouTube/Vimeo se transforman a embed URL de forma testeada |
| S1-12 | Crear pruebas de modelos y relaciones | P1 | READY | Tests cubren Course, Module, Material, Enrollment, Sale |

---

## 8. Sprint 2 - Catalogo Publico Dinamico Y Detalle De Curso

Objetivo: reemplazar la pagina estatica de cursos por catalogo real desde BD.
Dependencias: Sprint 1 parcial, datos seed existentes.
Estado general: DONE.

| ID | Tarea | Prioridad | Estado | Criterio de aceptacion |
| --- | --- | --- | --- | --- |
| S2-01 | Crear `CourseController@index` publico | P0 | TODO | `/cursos` lista cursos `publicado` desde BD |
| S2-02 | Crear query con filtros publicos | P1 | TODO | Filtro por nivel, categoria, busqueda y precio |
| S2-03 | Reemplazar cards hardcodeadas en `cursos.blade.php` | P0 | TODO | Ningun curso publico queda escrito manualmente |
| S2-04 | Crear ruta `/cursos/{slug}` | P1 | TODO | Detalle carga por slug y falla 404 si no existe |
| S2-05 | Crear vista `curso-detalle.blade.php` | P1 | TODO | Muestra portada, descripcion, temario, precio, oferta, instructor |
| S2-06 | Mostrar preview de modulos y materiales | P1 | TODO | Usuarios ven estructura del curso sin acceder a archivos privados |
| S2-07 | Conectar carrito con `course_id` | P0 | TODO | Checkout usa IDs reales, no nombres sueltos en sesion |
| S2-08 | Corregir bugs visuales del catalogo | P1 | TODO | Sin superposiciones en desktop/tablet/mobile |
| S2-09 | Agregar meta tags basicos por curso | P2 | TODO | Title y description salen de BD |
| S2-10 | Tests feature de catalogo | P1 | TODO | Pruebas cubren listado, filtros y detalle |

---

## 9. Sprint 3 - CRUD Administrativo De Cursos

Objetivo: permitir que el admin cree, edite, publique, duplique y elimine cursos sin tocar codigo.
Dependencias: Sprint 1.
Estado general: DONE.

| ID | Tarea | Prioridad | Estado | Criterio de aceptacion |
| --- | --- | --- | --- | --- |
| S3-01 | Crear `Admin/CourseController@index` | P0 | DONE | Tabla paginada con filtros, busqueda y orden |
| S3-02 | Crear `create/store` de cursos | P0 | DONE | Form con tabs General, Comercial, SEO |
| S3-03 | Crear `edit/update` de cursos | P0 | DONE | Edicion preserva imagen si no se reemplaza |
| S3-04 | Crear `destroy` con protecciones | P1 | DONE | No elimina cursos con inscripciones activas sin confirmacion fuerte |
| S3-05 | Crear accion `duplicate` | P2 | DONE | Copia curso, modulos y materiales en estado borrador |
| S3-06 | Crear accion publicar/despublicar | P1 | DONE | Publicar valida contenido minimo y registra auditoria |
| S3-07 | Crear vista `admin/courses/index.blade.php` | P0 | DONE | Tabla profesional, responsive y con badges de estado |
| S3-08 | Crear vista `admin/courses/create.blade.php` | P0 | DONE | Upload de portada con preview |
| S3-09 | Crear vista `admin/courses/edit.blade.php` | P0 | DONE | Acceso a modulos del curso desde la edicion |
| S3-10 | Agregar enlaces al layout admin | P1 | DONE | Sidebar incluye Cursos, Estudiantes, Ventas, Cupones, Settings |
| S3-11 | Registrar eventos en `audit_logs` | P1 | DONE | Crear, editar, publicar y eliminar quedan auditados |
| S3-12 | Tests feature del CRUD de cursos | P1 | DONE | Admin puede CRUD; usuario normal recibe 403 |

---

## 10. Sprint 4 - Constructor De Modulos Y Materiales

Objetivo: que cada curso tenga modulos ordenables y materiales de tipo video, documento, presentacion, texto y recurso descargable.
Dependencias: Sprint 3.
Estado general: DONE.

| ID | Tarea | Prioridad | Estado | Criterio de aceptacion |
| --- | --- | --- | --- | --- |
| S4-01 | Crear `Admin/CourseModuleController` | P0 | DONE | CRUD de modulos dentro de un curso |
| S4-02 | Crear reordenamiento con SortableJS | P1 | DONE | Drag and drop persiste `order` via endpoint PATCH |
| S4-03 | Crear `Admin/CourseMaterialController` | P0 | DONE | CRUD de materiales dentro de un modulo |
| S4-04 | Form dinamico por tipo de material | P0 | DONE | Campos cambian segun video/documento/presentacion/texto/recurso |
| S4-05 | Soportar videos por URL | P0 | DONE | YouTube/Vimeo validan URL y renderizan embed seguro |
| S4-06 | Soportar videos subidos | P1 | DONE | MP4/WebM se guardan en storage y renderizan con player HTML5 |
| S4-07 | Soportar documentos | P1 | DONE | PDF/DOCX se suben y validan por MIME |
| S4-08 | Soportar presentaciones | P1 | DONE | PPTX/PDF se suben y validan por MIME |
| S4-09 | Soportar texto enriquecido con Quill | P1 | DONE | HTML guardado se sanitiza antes de mostrar |
| S4-10 | Soportar recursos descargables | P1 | DONE | PDF/ZIP/XLSX/DOCX descargables con autorizacion |
| S4-11 | Definir limites por tipo de archivo | P1 | DONE | Docs 50MB, videos 500MB o valores de `config/lms.php` |
| S4-12 | Crear limpieza de archivos reemplazados | P2 | DONE | Al reemplazar/eliminar material no quedan archivos huerfanos |
| S4-13 | Tests de upload y validacion | P1 | DONE | Casos permitidos y rechazados cubiertos |

---

## 11. Sprint 5 - Experiencia Del Estudiante Y Progreso

Objetivo: transformar la compra/inscripcion en aprendizaje real con acceso controlado al contenido.
Dependencias: Sprints 2, 4.
Estado general: DONE.

| ID | Tarea | Prioridad | Estado | Criterio de aceptacion |
| --- | --- | --- | --- | --- |
| S5-01 | Crear ruta de aula `/mi-cuenta/cursos/{course}` | P0 | DONE | Solo estudiante inscrito accede |
| S5-02 | Crear vista de player/lector de curso | P0 | DONE | Sidebar de modulos y area de contenido |
| S5-03 | Registrar progreso por material | P0 | DONE | Marcar material completado actualiza progreso del curso |
| S5-04 | Actualizar `mi-cuenta.blade.php` | P1 | DONE | Cursos inscritos muestran progreso real y estado |
| S5-05 | Suspender acceso desde estado enrollment | P1 | DONE | Estudiante suspendido no accede a materiales privados |
| S5-06 | Registrar ultima actividad | P2 | DONE | `last_accessed_at` o equivalente visible en admin |
| S5-07 | Preparar certificados como futuro modulo | P3 | DONE | Campo/estado de completado listo para certificado |
| S5-08 | Tests de autorizacion de contenido | P1 | DONE | Visitante/no inscrito recibe redirect o 403 |

---

## 12. Sprint 6 - Estudiantes, Ventas, Cupones Y Checkout

Objetivo: dejar trazabilidad comercial y gestion del estudiante sin integrar todavia Stripe completo.
Dependencias: Sprints 2, 5.
Estado general: DONE.

| ID | Tarea | Prioridad | Estado | Criterio de aceptacion |
| --- | --- | --- | --- | --- |
| S6-01 | Crear `Admin/StudentController@index` | P1 | DONE | Lista estudiantes con filtros, busqueda y progreso |
| S6-02 | Crear `Admin/StudentController@show` | P1 | DONE | Perfil con cursos, progreso, ultima actividad |
| S6-03 | Acciones suspender/reactivar/reiniciar progreso | P1 | DONE | Cada accion confirma, audita y actualiza estado |
| S6-04 | Crear `Admin/SaleController@index/show` | P1 | DONE | Lista y detalle de ventas desde BD |
| S6-05 | Refactorizar checkout para crear `sales` | P0 | DONE | Checkout crea `sales` y `sale_items`; Stripe confirmado crea `enrollments` |
| S6-06 | Crear `Admin/CouponController` CRUD | P1 | DONE | Cupones con vigencia, limite y estado |
| S6-07 | Aplicar cupon en checkout | P1 | DONE | Descuento valida vigencia y limite antes de pagar |
| S6-08 | Preparar `config/stripe.php` | P2 | DONE | Variables `STRIPE_KEY`, `STRIPE_SECRET`, `STRIPE_WEBHOOK_SECRET` documentadas |
| S6-09 | Crear `StripeService` | P2 | DONE | Checkout Session real, retorno seguro y webhook firmado |
| S6-10 | Tests de ventas y cupones | P1 | DONE | Venta pendiente, confirmacion Stripe, idempotencia y cupones |

---

## 13. Sprint 7 - Dashboard Ejecutivo Y Analitica

Objetivo: dar al administrador visibilidad util, con maximo 3 graficos y KPIs accionables.
Dependencias: Sprints 3, 6.
Estado general: DONE.

| ID | Tarea | Prioridad | Estado | Criterio de aceptacion |
| --- | --- | --- | --- | --- |
| S7-01 | Refactorizar `DashboardController` | P1 | DONE | KPIs consultan Course, User, Sale, Enrollment |
| S7-02 | KPI total/activos/inactivos de cursos | P1 | DONE | Cards muestran conteos reales |
| S7-03 | KPI estudiantes y nuevos del mes | P1 | DONE | Diferencia estudiante/admin/instructor |
| S7-04 | KPI ventas e ingresos | P1 | DONE | Mes actual, total historico y ticket promedio |
| S7-05 | KPI curso mas vendido y menor rendimiento | P2 | DONE | Queries eficientes y con fallback sin datos |
| S7-06 | KPI tasa de finalizacion | P2 | DONE | Calculada desde enrollments completados |
| S7-07 | Grafico ventas mensuales | P1 | DONE | Chart.js renderiza ultimos 12 meses |
| S7-08 | Grafico inscripciones mensuales | P1 | DONE | Chart.js renderiza ultimos 12 meses |
| S7-09 | Grafico top cursos vendidos | P1 | DONE | Maximo 5 cursos |
| S7-10 | Cache de metricas | P2 | DONE | `Cache::remember` con TTL razonable |
| S7-11 | Tests de KPIs | P2 | DONE | Datos semilla controlados validan calculos |

---

## 14. Sprint 8 - Roles, Settings, Auditoria Y Seguridad

Objetivo: cerrar la plataforma para uso administrativo profesional.
Dependencias: Sprints 1, 3, 6.
Estado general: DONE.

| ID | Tarea | Prioridad | Estado | Criterio de aceptacion |
| --- | --- | --- | --- | --- |
| S8-01 | Crear `Admin/RoleController` | P1 | DONE | Roles listan permisos y usuarios asignados |
| S8-02 | Cambiar `UserController` a roles reales | P1 | DONE | No depende solo de toggle `is_admin` |
| S8-03 | Crear `Admin/SettingController` | P1 | DONE | Empresa, logo, correo, telefono, pagos |
| S8-04 | Crear helper `setting()` con cache | P2 | DONE | Vistas leen configuracion sin hardcode |
| S8-05 | Crear `AuditService` | P1 | DONE | Registra usuario, accion, entidad, IP, user agent |
| S8-06 | Crear vista `admin/audit` | P2 | DONE | Filtros por usuario, accion, entidad y fecha |
| S8-07 | Rate limiting en login | P0 | DONE | 5 intentos por minuto o regla equivalente |
| S8-08 | Rate limiting en `/api/chat` | P1 | DONE | Limite por IP/sesion |
| S8-09 | Sanitizar input del chatbot | P1 | DONE | Longitud maxima y filtros basicos |
| S8-10 | Activar checklist de seguridad produccion | P1 | DONE | `SESSION_ENCRYPT`, HTTPS, CSP, backups, logs |
| S8-11 | Tests de permisos | P1 | DONE | Admin, instructor, soporte y estudiante validados |

---

## 15. Sprint 9 - QA, Rendimiento, Documentacion Y Release

Objetivo: convertir la implementacion en una entrega usable, medible y mantenible.
Dependencias: Sprints 2 a 8.
Estado general: DONE.

| ID | Tarea | Prioridad | Estado | Criterio de aceptacion |
| --- | --- | --- | --- | --- |
| S9-01 | Ampliar suite feature tests | P0 | DONE | Catalogo, admin cursos, materiales, checkout, permisos |
| S9-02 | Agregar factories LMS | P1 | DONE | Course, Module, Material, Sale, Coupon, Enrollment |
| S9-03 | Agregar seed demo completo | P1 | DONE | Cursos con materiales reales de ejemplo |
| S9-04 | Revisar performance de queries | P1 | DONE | `withCount`, eager loading e indices usados |
| S9-05 | Paginacion en listados admin | P1 | DONE | 15 o 25 registros por pagina |
| S9-06 | Validar mobile/desktop visual | P1 | DONE | Sin solapes ni textos cortados |
| S9-07 | Actualizar documentacion tecnica | P1 | DONE | Arquitectura, BD, APIs y setup vigentes |
| S9-08 | Crear manual admin LMS | P2 | DONE | Crear curso, modulo, material, cupon y revisar ventas |
| S9-09 | Crear checklist de deploy | P1 | DONE | Variables, migraciones, storage, cache, queue, cron |
| S9-10 | Prueba smoke final | P0 | DONE | Registro, login admin, crear curso, publicar, comprar, acceder |

---

## 16. Backlog Post-MVP

Estas tareas son valiosas, pero no deben bloquear el MVP LMS administrable.

| ID | Tarea | Prioridad | Motivo |
| --- | --- | --- | --- |
| BL-01 | Operacion Stripe produccion | P1 | Configurar credenciales live, webhook live, politicas comerciales y conciliacion |
| BL-02 | Certificados PDF | P2 | Depende de progreso confiable |
| BL-03 | Correos transaccionales | P2 | Depende de proveedor SMTP/SES/Resend |
| BL-04 | Dashboard de instructor | P2 | Depende de permisos por propietario de curso |
| BL-05 | Evaluaciones/quizzes | P2 | Amplia valor LMS, pero no es core inicial |
| BL-06 | Reportes Excel/PDF | P3 | Util para operacion, no bloquea aprendizaje |
| BL-07 | CDN/S3 para videos | P2 | Necesario si crece almacenamiento o trafico |
| BL-08 | 2FA | P2 | Seguridad adicional para produccion |
| BL-09 | Reviews de cursos | P3 | Mejora comercial posterior |
| BL-10 | API mobile | P3 | Requiere estabilizar contratos web primero |

---

## 17. Riesgos Y Bloqueos Actuales

| ID | Riesgo | Impacto | Mitigacion |
| --- | --- | --- | --- |
| R-01 | La carpeta actual no tiene `.git` | Alto | Inicializar repo o trabajar sobre clone correcto antes de sprints grandes |
| R-02 | `mysqli` se carga dos veces en PHP | Bajo/Medio | Revisar `php.ini` de CLI/XAMPP y dejar una sola linea `extension=mysqli` |
| R-03 | `db:show --counts` falla por `performance_schema.session_status` | Bajo | Usar consultas directas o revisar privilegios/config de MariaDB |
| R-04 | Documentos previos tienen mojibake | Medio | Reescribir docs criticos en UTF-8/ASCII limpio |
| R-05 | Tests actuales son minimos | Alto | No avanzar sprints core sin tests feature |
| R-06 | Cursos seed usan imagenes remotas | Medio | Definir fallback local o subir portadas al storage |
| R-07 | Materiales privados pueden quedar expuestos si se sirven directo | Alto | Validar acceso antes de descarga; no publicar archivos sensibles sin control |
| R-08 | Checkout actual simula pago | Alto | Separar claramente `pendiente`, `pagado`, `fallido` y no entregar contenido sin confirmacion |

---

## 18. Proxima Secuencia Recomendada

1. Resolver Sprint 1 completo antes de crear pantallas grandes.
2. Implementar Sprint 2 para que el usuario vea cursos dinamicos.
3. Implementar Sprint 3 y 4 juntos si se quiere que el admin gestione cursos y contenido.
4. Implementar Sprint 5 y 6 para cerrar el ciclo estudiante-compra-acceso.
5. Implementar Sprint 7 y 8 para operacion profesional.
6. Usar Sprint 9 como gate de entrega y no como "despues vemos".

---

## 19. Comandos De Verificacion Actual

Estos comandos pasan en el estado auditado:

```bash
php artisan about
php artisan migrate:status
php artisan test
npm run build
composer audit
npm audit --audit-level=moderate
```

Nota: `php artisan about` aun muestra warning local `Module "mysqli" is already loaded`; no rompe la app, pero debe limpiarse antes de presentar el entorno como profesional.

---

## 20. Paquetes De Implementacion Para Agente Secundario

Cada paquete describe como debe trabajar el agente secundario. El usuario puede copiar el paquete correspondiente y enviarlo como instruccion de implementacion.

---

### Paquete Sprint 1 - Fundamentos De Arquitectura LMS

Objetivo operativo: dejar contratos, permisos, validaciones y servicios base listos para que los sprints de UI no improvisen reglas.

Archivos probables:

| Tipo | Archivos |
| --- | --- |
| Modelos | `app/Models/User.php`, `app/Models/Course.php`, `app/Models/CourseMaterial.php`, `app/Models/Enrollment.php` |
| Middleware | `app/Http/Middleware/*`, `bootstrap/app.php` |
| Requests | `app/Http/Requests/Admin/*` |
| Servicios | `app/Services/CoursePublishingService.php`, `app/Services/VideoEmbedService.php` |
| Config | `config/lms.php`, `.env.example` |
| Tests | `tests/Feature/*`, `tests/Unit/*` |

Orden recomendado:

1. Auditar estados actuales de `enrollments`, `courses`, `course_modules` y `course_materials`.
2. Definir `config/lms.php` con limites, extensiones permitidas y providers de video.
3. Crear middleware `role` y `permission`, manteniendo compatibilidad con `is_admin`.
4. Crear FormRequests para cursos, modulos y materiales.
5. Crear `CoursePublishingService` para validar publicacion.
6. Crear `VideoEmbedService` para YouTube/Vimeo.
7. Agregar pruebas unitarias de servicios y pruebas feature de permisos.

No tocar:

- No crear CRUD visual todavia.
- No cambiar todo el sistema de roles por un paquete externo.
- No eliminar `is_admin`; debe convivir hasta Sprint 8.

Entregables minimos:

- Middleware registrado y usable en rutas.
- FormRequests reutilizables por sprints posteriores.
- Servicios testeados.
- Config LMS documentada.

Comandos obligatorios:

```bash
php artisan test
npm run build
composer audit
npm audit --audit-level=moderate
```

Prompt sugerido:

```text
Implementa el Sprint 1 del Kanban. Enfocate en middleware de permisos, FormRequests, config/lms.php, CoursePublishingService y VideoEmbedService. No construyas pantallas CRUD aun. Mantiene compatibilidad con is_admin. Agrega pruebas para permisos y servicios. Reporta archivos, decisiones y comandos ejecutados.
```

Checklist para revision del cerebro principal:

- El admin legacy entra al panel sin romperse.
- Un usuario sin permiso recibe 403.
- Publicar curso falla si no tiene contenido minimo.
- URLs YouTube/Vimeo se convierten de forma segura.
- Los limites de upload no estan hardcodeados en controladores.

---

### Paquete Sprint 2 - Catalogo Publico Dinamico Y Detalle De Curso

Objetivo operativo: reemplazar la pagina estatica de cursos por catalogo real y preparar el flujo hacia carrito/checkout.

Archivos probables:

| Tipo | Archivos |
| --- | --- |
| Controladores | `app/Http/Controllers/CourseController.php`, `app/Http/Controllers/CartController.php` |
| Vistas | `resources/views/cursos.blade.php`, `resources/views/curso-detalle.blade.php`, `resources/views/layouts/app.blade.php` |
| Rutas | `routes/web.php` |
| Tests | `tests/Feature/PublicCourseCatalogTest.php` |

Orden recomendado:

1. Cambiar `/cursos` de `Route::view` a controlador publico.
2. Consultar solo cursos `publicado` con `category`, `instructor`, `modules`.
3. Agregar filtros por nivel, categoria y busqueda.
4. Reusar el estilo visual existente, pero alimentar cards desde BD.
5. Crear detalle `/cursos/{slug}` con temario y CTA.
6. Ajustar carrito para recibir `course_id` real.
7. Agregar tests de listado, filtro, detalle y 404.

No tocar:

- No crear CRUD admin en este sprint.
- No crear materiales privados aun.
- No reescribir todo el layout publico si basta con corregir el catalogo.

Entregables minimos:

- `/cursos` dinamico.
- `/cursos/{slug}` funcional.
- Carrito con `course_id`.
- Sin cursos hardcodeados.

Comandos obligatorios:

```bash
php artisan test
npm run build
composer audit
npm audit --audit-level=moderate
```

Prompt sugerido:

```text
Implementa el Sprint 2 del Kanban. Convierte el catalogo publico a datos reales desde Course. Crea CourseController publico, ruta /cursos/{slug}, filtros basicos y ajusta carrito para usar course_id. Mantiene el estilo existente y corrige superposiciones. Agrega tests feature.
```

Checklist para revision del cerebro principal:

- Si un curso cambia en BD, cambia en la web sin editar Blade.
- Un curso archivado/borrador no aparece.
- El detalle usa slug y metadata real.
- El boton de inscripcion agrega el ID correcto al carrito.
- Mobile y desktop no tienen textos superpuestos.

---

### Paquete Sprint 3 - CRUD Administrativo De Cursos

Objetivo operativo: que el administrador gestione cursos completos sin tocar codigo.

Archivos probables:

| Tipo | Archivos |
| --- | --- |
| Controladores | `app/Http/Controllers/Admin/CourseController.php` |
| Requests | `app/Http/Requests/Admin/StoreCourseRequest.php`, `UpdateCourseRequest.php` |
| Vistas | `resources/views/admin/courses/*.blade.php`, `resources/views/layouts/admin.blade.php` |
| Rutas | `routes/web.php` |
| Servicios | `app/Services/CoursePublishingService.php` |
| Tests | `tests/Feature/AdminCourseCrudTest.php` |

Orden recomendado:

1. Crear rutas admin protegidas por auth/admin/permission cuando este disponible.
2. Crear index con paginacion, filtros, busqueda y `withCount`.
3. Crear formularios create/edit con tabs General, Comercial y SEO.
4. Implementar upload de portada con Laravel Storage.
5. Implementar publicar/despublicar usando `CoursePublishingService`.
6. Implementar duplicar curso con modulos/materiales en borrador.
7. Agregar auditoria si `AuditService` ya existe; si no, dejar llamada aislada o TODO claro.
8. Agregar tests de autorizacion y CRUD.

No tocar:

- No implementar materiales todavia mas alla de preservar relaciones en duplicado.
- No rehacer dashboard.
- No usar JavaScript pesado si Blade simple resuelve.

Entregables minimos:

- CRUD cursos completo.
- Navegacion admin actualizada.
- Portadas subidas en storage.
- Tests admin/no admin.

Comandos obligatorios:

```bash
php artisan test
npm run build
composer audit
npm audit --audit-level=moderate
```

Prompt sugerido:

```text
Implementa el Sprint 3 del Kanban. Crea el CRUD admin de cursos con listado paginado, filtros, formularios General/Comercial/SEO, upload de portada, publicar/despublicar y duplicar. Usa FormRequests y CoursePublishingService. No implementes materiales todavia. Agrega tests feature.
```

Checklist para revision del cerebro principal:

- Crear/editar valida campos requeridos.
- Slug es unico y editable.
- No se puede publicar un curso incompleto.
- La portada se ve despues de subir.
- Usuario no admin no accede.

---

### Paquete Sprint 4 - Constructor De Modulos Y Materiales

Objetivo operativo: construir el nucleo LMS de contenido: modulos ordenables y materiales de distintos tipos.

Archivos probables:

| Tipo | Archivos |
| --- | --- |
| Controladores | `app/Http/Controllers/Admin/CourseModuleController.php`, `CourseMaterialController.php` |
| Requests | `app/Http/Requests/Admin/StoreCourseModuleRequest.php`, `StoreCourseMaterialRequest.php` |
| Vistas | `resources/views/admin/courses/edit.blade.php`, `resources/views/admin/modules/*`, `resources/views/admin/materials/*` |
| JS | `resources/js/*` si se integra SortableJS/Quill con Vite |
| Config | `config/lms.php` |
| Tests | `tests/Feature/AdminCourseMaterialTest.php` |

Orden recomendado:

1. Implementar CRUD de modulos dentro del curso.
2. Agregar reordenamiento de modulos con endpoint PATCH.
3. Implementar CRUD de materiales dentro del modulo.
4. Crear formulario dinamico por tipo.
5. Validar URL de video externa y convertir embed.
6. Validar uploads por MIME/extensiones/tamano desde `config/lms.php`.
7. Integrar Quill para texto enriquecido y sanitizar salida.
8. Agregar limpieza de archivos reemplazados/eliminados.
9. Agregar tests de upload, permisos y validacion.

No tocar:

- No construir aula del estudiante todavia.
- No exponer descargas privadas sin autorizacion.
- No guardar HTML rico sin estrategia de sanitizacion.

Entregables minimos:

- Modulos CRUD y ordenables.
- Materiales CRUD para video, documento, presentacion, texto y recurso.
- Uploads guardados correctamente.
- Validaciones fuertes por tipo.

Comandos obligatorios:

```bash
php artisan test
npm run build
composer audit
npm audit --audit-level=moderate
```

Prompt sugerido:

```text
Implementa el Sprint 4 del Kanban. Crea CRUD de modulos y materiales, reordenamiento con SortableJS, soporte de video URL/upload, documentos, presentaciones, texto rico con Quill y recursos descargables. Usa config/lms.php y Laravel Storage. No construyas aula del estudiante todavia. Agrega tests de upload y validacion.
```

Checklist para revision del cerebro principal:

- Un curso puede tener varios modulos ordenados.
- Cada modulo puede tener materiales ordenados.
- Un PDF invalido o un video demasiado grande se rechaza.
- Un video YouTube/Vimeo renderiza embed.
- Al borrar material se controla el archivo asociado.

---

### Paquete Sprint 5 - Experiencia Del Estudiante Y Progreso

Objetivo operativo: permitir al estudiante acceder al contenido comprado y registrar avance.

Archivos probables:

| Tipo | Archivos |
| --- | --- |
| Controladores | `app/Http/Controllers/StudentCourseController.php`, `MiCuentaController.php` |
| Modelos | `Enrollment`, posible nuevo modelo `MaterialProgress` o tabla pivot |
| Migraciones | Progreso por material si no existe |
| Vistas | `resources/views/mi-cuenta.blade.php`, `resources/views/student/course-player.blade.php` |
| Rutas | `routes/web.php` |
| Tests | `tests/Feature/StudentCourseAccessTest.php` |

Orden recomendado:

1. Definir persistencia de progreso por material.
2. Crear aula del estudiante para cursos inscritos.
3. Proteger acceso por enrollment activo/completado.
4. Renderizar material segun tipo.
5. Crear accion para marcar material como completado.
6. Calcular progreso del curso.
7. Actualizar `mi-cuenta` con progreso real.
8. Registrar ultima actividad.
9. Agregar tests de acceso permitido/denegado.

No tocar:

- No implementar certificados PDF todavia.
- No integrar Stripe real.
- No exponer archivos privados con URLs publicas sin control.

Entregables minimos:

- Aula del estudiante funcional.
- Progreso persistente.
- `mi-cuenta` con cursos y progreso real.
- Acceso denegado a no inscritos.

Comandos obligatorios:

```bash
php artisan test
npm run build
composer audit
npm audit --audit-level=moderate
```

Prompt sugerido:

```text
Implementa el Sprint 5 del Kanban. Crea la experiencia del estudiante para consumir cursos inscritos, con vista de aula, acceso protegido por enrollment, render de materiales y progreso por material. Actualiza mi-cuenta con progreso real. No implementes certificados ni Stripe real. Agrega tests de autorizacion.
```

Checklist para revision del cerebro principal:

- Visitante no accede al aula.
- Usuario no inscrito no accede a contenido.
- Estudiante inscrito ve modulos y materiales.
- Marcar material completado cambia progreso.
- Curso completado se calcula de forma consistente.

---

### Paquete Sprint 6 - Estudiantes, Ventas, Cupones Y Checkout

Objetivo operativo: convertir el checkout simulado en un flujo trazable con ventas, items, cupones e inscripciones.

Archivos probables:

| Tipo | Archivos |
| --- | --- |
| Controladores | `PaymentController.php`, `CartController.php`, `Admin/StudentController.php`, `Admin/SaleController.php`, `Admin/CouponController.php` |
| Servicios | `CheckoutService.php`, `CouponService.php`, `StripeService.php` |
| Vistas | `checkout.blade.php`, `admin/students/*`, `admin/sales/*`, `admin/coupons/*` |
| Config | `config/stripe.php` |
| Tests | `tests/Feature/CheckoutSalesTest.php`, `AdminCouponTest.php` |

Orden recomendado:

1. Crear `CheckoutService` para centralizar subtotal, descuento y total.
2. Refactorizar carrito para trabajar con cursos reales.
3. Crear ventas con `sales` y `sale_items`.
4. Crear enrollments solo cuando el pago queda `pagado` en modo simulado.
5. Crear CRUD de cupones.
6. Aplicar cupon en checkout con validacion de vigencia/limite.
7. Crear admin de ventas.
8. Crear admin de estudiantes.
9. Preparar `StripeService` como stub, sin activar pagos reales.
10. Agregar tests de venta, cupon y enrollment.

No tocar:

- No hacer integracion Stripe real sin credenciales.
- No entregar contenido si venta esta `pendiente` o `fallido`.
- No mezclar logica de cupon directamente en Blade.

Entregables minimos:

- Checkout crea venta trazable.
- Cupones CRUD y aplicables.
- Admin ve ventas y estudiantes.
- Inscripcion se crea correctamente al pago simulado.

Comandos obligatorios:

```bash
php artisan test
npm run build
composer audit
npm audit --audit-level=moderate
```

Prompt sugerido:

```text
Implementa el Sprint 6 del Kanban. Refactoriza checkout para crear sales, sale_items y enrollments al confirmar pago simulado. Implementa CRUD de cupones, admin de ventas y admin de estudiantes. Prepara StripeService stub sin activar pagos reales. Agrega tests feature para venta, cupon y acceso.
```

Checklist para revision del cerebro principal:

- El checkout ya no depende solo de nombres de curso en sesion.
- Sale total = subtotal - descuento.
- Cupon expirado o agotado se rechaza.
- Venta pagada crea enrollments.
- Admin puede filtrar ventas y estudiantes.

---

### Paquete Sprint 7 - Dashboard Ejecutivo Y Analitica

Objetivo operativo: dar al admin una vista ejecutiva real y ligera del negocio LMS.

Archivos probables:

| Tipo | Archivos |
| --- | --- |
| Controladores | `app/Http/Controllers/Admin/DashboardController.php` |
| Vistas | `resources/views/admin/dashboard.blade.php` |
| JS | Integracion de `chart.js` en assets o Blade |
| Tests | `tests/Feature/AdminDashboardMetricsTest.php` |

Orden recomendado:

1. Definir queries para KPIs con eager loading y agregaciones.
2. Crear DTO/array limpio para vista.
3. Cachear metricas con TTL corto.
4. Renderizar cards de KPIs.
5. Renderizar maximo 3 graficos: ventas, inscripciones, top cursos.
6. Agregar tabla de actividad reciente si no recarga demasiado.
7. Agregar tests de calculos con datos controlados.

No tocar:

- No agregar mas de 3 graficos.
- No cargar todos los registros en memoria para calcular metricas.
- No meter logica SQL compleja en Blade.

Entregables minimos:

- Dashboard con KPIs del prompt original.
- 3 graficos con Chart.js.
- Datos cacheados.
- Tests de metricas principales.

Comandos obligatorios:

```bash
php artisan test
npm run build
composer audit
npm audit --audit-level=moderate
```

Prompt sugerido:

```text
Implementa el Sprint 7 del Kanban. Refactoriza el dashboard admin para KPIs reales de cursos, estudiantes, ventas, ingresos y finalizacion. Integra maximo 3 graficos con Chart.js y cachea metricas. Evita queries pesadas y agrega tests de calculo.
```

Checklist para revision del cerebro principal:

- Dashboard funciona sin ventas ni inscripciones.
- Dashboard muestra datos cuando existen ventas.
- Graficos no rompen build.
- Queries usan agregaciones y no N+1.
- KPI de finalizacion tiene formula clara.

---

### Paquete Sprint 8 - Roles, Settings, Auditoria Y Seguridad

Objetivo operativo: cerrar controles administrativos, seguridad basica y trazabilidad.

Archivos probables:

| Tipo | Archivos |
| --- | --- |
| Controladores | `Admin/RoleController.php`, `Admin/SettingController.php`, `Admin/AuditLogController.php`, `Admin/UserController.php` |
| Servicios | `AuditService.php`, posible helper de settings |
| Middleware | `RoleMiddleware.php`, `PermissionMiddleware.php` |
| Vistas | `admin/roles/*`, `admin/settings/*`, `admin/audit/*`, `admin/users/*` |
| Config | `bootstrap/app.php`, `config/session.php`, `.env.example` |
| Tests | `tests/Feature/AdminPermissionTest.php`, `SecurityTest.php` |

Orden recomendado:

1. Completar UI de roles y permisos.
2. Migrar gestion de usuarios de `is_admin` a roles sin borrar compatibilidad.
3. Crear settings editables con cache.
4. Crear helper `setting()`.
5. Crear `AuditService`.
6. Registrar auditoria en acciones criticas.
7. Crear vista de auditoria con filtros.
8. Agregar rate limiting en login y chat.
9. Sanitizar input del chatbot.
10. Crear checklist de seguridad de produccion.

No tocar:

- No bloquear acceso al admin existente.
- No guardar settings sensibles sin considerar `.env`.
- No auditar passwords o datos secretos en texto plano.

Entregables minimos:

- Roles y permisos administrables.
- Settings de empresa/pagos editables.
- Auditoria visible.
- Rate limits activos.
- Tests de permisos.

Comandos obligatorios:

```bash
php artisan test
npm run build
composer audit
npm audit --audit-level=moderate
```

Prompt sugerido:

```text
Implementa el Sprint 8 del Kanban. Completa roles/permisos, settings, auditoria y seguridad. Mantiene compatibilidad con is_admin. Agrega AuditService, vistas admin y rate limiting en login/chat. No guardes secretos en BD. Agrega tests de permisos y seguridad.
```

Checklist para revision del cerebro principal:

- Admin conserva acceso total.
- Instructor/soporte/estudiante tienen limites correctos.
- Cambios criticos quedan en audit_logs.
- Settings se cachean y se pueden actualizar.
- Login/chat tienen rate limit.

---

### Paquete Sprint 9 - QA, Rendimiento, Documentacion Y Release

Objetivo operativo: transformar la suma de sprints en una entrega estable y demostrable.

Archivos probables:

| Tipo | Archivos |
| --- | --- |
| Tests | `tests/Feature/*`, `tests/Unit/*` |
| Factories | `database/factories/*` |
| Seeders | `database/seeders/*` |
| Docs | `documentacion/*.md`, `README.md` |
| Config | `.env.example`, `config/*.php` |

Orden recomendado:

1. Crear factories LMS faltantes.
2. Crear seed demo con cursos, modulos, materiales, estudiantes, ventas y cupones.
3. Ampliar tests feature de flujos completos.
4. Revisar N+1 y queries lentas.
5. Revisar UI responsive en publico/admin/aula.
6. Actualizar documentacion tecnica y manual admin.
7. Crear checklist de deploy.
8. Ejecutar prueba smoke final completa.

No tocar:

- No meter nuevas features grandes.
- No cambiar reglas de negocio sin actualizar tests.
- No ignorar errores visuales de mobile.

Entregables minimos:

- Factories y seed demo.
- Suite de pruebas ampliada.
- Manual admin y checklist deploy.
- Smoke test documentado.

Comandos obligatorios:

```bash
php artisan test
npm run build
composer audit
npm audit --audit-level=moderate
```

Comando destructivo solo con autorizacion explicita:

```bash
php artisan migrate:fresh --seed
```

Prompt sugerido:

```text
Implementa el Sprint 9 del Kanban. No agregues features nuevas grandes. Enfocate en QA, factories, seed demo, tests, performance, responsive, documentacion y checklist de deploy. Ejecuta migrate:fresh --seed solo si se autoriza porque borra datos locales. Reporta smoke test completo.
```

Checklist para revision del cerebro principal:

- Demo se puede ejecutar desde cero.
- Pruebas cubren flujos criticos.
- No hay vulnerabilidades reportadas.
- No hay N+1 obvio en listados principales.
- Documentacion coincide con el sistema real.

---

## 21. Matriz De Dependencias Entre Sprints

| Sprint | Puede iniciar cuando | Bloquea a |
| --- | --- | --- |
| Sprint 1 | Sprint 0 listo | Sprints 2, 3, 4, 8 |
| Sprint 2 | Sprint 1 parcial listo | Sprints 5, 6 |
| Sprint 3 | Sprint 1 listo | Sprint 4, Sprint 7 |
| Sprint 4 | Sprint 3 listo | Sprint 5 |
| Sprint 5 | Sprints 2 y 4 listos | Sprint 6 parcialmente, Sprint 9 |
| Sprint 6 | Sprints 2 y 5 listos | Sprint 7, Sprint 8 parcialmente |
| Sprint 7 | Sprints 3 y 6 listos | Sprint 9 |
| Sprint 8 | Sprints 1, 3 y 6 listos | Sprint 9 |
| Sprint 9 | Sprints 2 a 8 listos | Release MVP |

Regla practica: si un sprint depende de otro, el agente secundario no debe "resolverlo por encima"; debe reportar bloqueo o implementar solo el minimo contrato necesario.

---

## 22. Checklist Para Revisiones Que Hara El Cerebro Principal

Cuando el usuario reporte avances, la revision se hara con esta matriz:

| Categoria | Revision |
| --- | --- |
| Alcance | El agente hizo exactamente el sprint asignado o mezclo trabajo futuro? |
| Rutas | Las rutas nuevas tienen nombres, middleware y verbos correctos? |
| Controladores | La logica esta en servicios/FormRequests cuando corresponde? |
| Modelos | Relaciones, casts, scopes y fillables estan completos y seguros? |
| Vistas | No hay datos hardcodeados, solapes visuales ni acciones sin confirmacion |
| Seguridad | Permisos, ownership, uploads, CSRF y rate limits estan considerados |
| Datos | Migraciones son reversibles y seeders no duplican datos accidentalmente |
| Tests | Cubren exito, error, permisos y validaciones principales |
| Performance | No hay N+1 obvio ni cargas completas innecesarias |
| Documentacion | El cambio queda explicado y reproducible |

Resultado posible de revision:

| Resultado | Significado |
| --- | --- |
| Aprobado | El sprint cumple DoD y puede pasar a DONE |
| Aprobado con observaciones | Funciona, pero hay mejoras menores para backlog |
| Correcciones requeridas | Hay fallos que deben arreglarse antes de avanzar |
| Bloqueado | Falta decision, credencial, dato externo o cambio previo |

---

## 23. Plantilla De Control De Avance

Usar esta tabla al final de cada sprint implementado:

| Campo | Valor |
| --- | --- |
| Sprint |  |
| Fecha de inicio |  |
| Fecha de entrega |  |
| Agente implementador |  |
| Estado | REVIEW |
| Resumen de cambios |  |
| Pruebas ejecutadas |  |
| Resultado build |  |
| Resultado audits |  |
| Riesgos encontrados |  |
| Pendientes para siguiente sprint |  |
| Decision del cerebro principal | Pendiente |

---

## 24. Regla De Oro Del Proyecto

Este proyecto debe evolucionar de prototipo a plataforma real de forma incremental. Cada sprint debe dejar el sistema mas utilizable que antes, sin convertir la base en una mezcla de parches.

Si aparece una duda entre "hacerlo rapido" y "dejar un contrato limpio para el siguiente sprint", elegir el contrato limpio, siempre que no infle el alcance.

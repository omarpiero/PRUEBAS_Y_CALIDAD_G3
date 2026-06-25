# Estrategia, Automatización y Ejecución de Pruebas (Capítulos 11, 12 y 13)

> **Referencia al Informe Final:** Este documento provee el contenido íntegro y técnico para poblar los Capítulos 11 (Estrategia de Pruebas), 12 (Automatización) y 13 (Ejecución y Errores). La información aquí descrita ha sido validada directamente desde el repositorio del proyecto y las ejecuciones nativas de PHPUnit.

El LMS JM y JS Alimentos v2.0 ha sido construido bajo un estándar hiper-riguroso de calidad que valida de manera técnica y demostrable los Requisitos Funcionales (RF) y No Funcionales (RNF) mediante aserciones matemáticas a nivel de base de datos y de red.

---

## Capítulo 11. Estrategia y Niveles de Pruebas (Alineación con ISO 29119)

La estrategia de QA del LMS adopta un enfoque híbrido, combinando Pruebas de Caja Blanca, Caja Negra y Pruebas Unitarias. Todo el ecosistema de pruebas se orquesta bajo el paradigma de *Continuous Testing* o *Spec-Anchored Development*.

### 11.1. Pruebas Unitarias (Componentes Aislados)
Validan métodos estáticos o servicios sin tocar las capas HTTP del framework.
- **Enfoque:** Lógica pura.
- **Ejemplos en el código:** `CoursePublishingServiceTest` (evalúa si los módulos vacíos impiden la publicación), `VideoEmbedServiceTest` (convierte URLs de YouTube/Vimeo en iframes limpios), `LmsRelationshipsTest` (valida integridad de Eloquent ORM).

### 11.2. Pruebas de Caja Negra (Feature Testing / Funcional)
Validan el comportamiento del sistema simulando la experiencia de los distintos actores (Estudiante, Admin, Guest).
- **Enfoque:** Entradas y salidas en las rutas y controladores web, verificando redirecciones y respuestas HTTP.
- **Ejemplos en el código:** `PublicCourseCatalogTest` (simula compras desde el catálogo), `PaymentStripeTest` (simula peticiones POST con el carrito vacío), `StudentCourseAccessTest` (verifica que un usuario no matriculado es bloqueado al intentar consumir contenido).

### 11.3. Pruebas de Caja Blanca (Seguridad e Integración)
Validan los caminos lógicos, el flujo de Middlewares y las reglas de bases de datos.
- **Enfoque:** Inyección de dependencias, auditorías del código interno.
- **Ejemplos en el código:** `AdminSecurityAndRolesTest` (comprueba *rate limiting*, cabeceras de seguridad y evalúa que el "último administrador" del sistema no pueda ser degradado de rol), `PermissionMiddlewareTest` (valida la asignación rigurosa de permisos tipo ACL).

---

## Capítulo 12. Automatización de Pruebas y Entorno de Ejecución

Se ha implementado una suite que corre de forma autónoma (Headless) sin depender de servicios de pago en la nube ni exponer datos de producción.

### 12.1. Framework y Entorno (PHPUnit + SQLite In-Memory)
- **Herramienta Core:** `PHPUnit 11` orquestado nativamente a través del runner de Artisan (`php artisan test`).
- **Aislamiento Total:** El archivo `phpunit.xml` reemplaza la conexión nativa hacia MySQL por una base de datos dinámica en memoria (`sqlite: memory`). Se levanta, puebla y destruye una base de datos nueva y limpia por *cada método de prueba* (Trait `RefreshDatabase`). Esto garantiza idempotencia y velocidad.

### 12.2. Uso Avanzado de Mocks y Stubs (Desacoplamiento)
Dado que el LMS integra plataformas de pago y de inteligencia artificial de terceros, los tests deben evitar consumir la API real para no incurrir en costos ni demoras.
1. **Pasarela de Pago (Stripe):** Los cargos en tarjeta no viajan por internet. El `StripeService` y los Webhooks asíncronos son simulados (`PaymentStripeTest.php`) enviando *arrays* falsificados para probar que el LMS registra los ingresos sin alterar la red real de Stripe.
2. **Asistente Cognitivo IA (Gemini 2.5):** Las peticiones complejas hacia Google están emuladas por el facade de Laravel `Http::fake()` (`GeminiAssistantTest.php`). El test inyecta una respuesta artificial para validar si React la renderiza correctamente.
3. **Cargas de Archivos Multimedia:** Se inyectan archivos gráficos vacíos o corruptos usando `UploadedFile::fake()->image()` para probar la resiliencia de la plataforma ante inyecciones.

---

## Capítulo 13. Ejecución de Pruebas, Cobertura y Resultados (Evidencias)

La suite de pruebas fue ejecutada de manera íntegra, agrupando **89 pruebas funcionales y unitarias** con **426 aserciones atómicas (Assertions)**. La ejecución completa demoró un promedio de 27.32 segundos con 100% de éxito (0 regresiones).

A continuación, se detalla el desglose de resultados por categoría, los cuales tienen sus archivos de evidencia generados en la carpeta `documentacion/PRUEBAS/screenshots_p/`:

### 13.1. Ejecución de Pruebas Unitarias
- **Evidencia:** `evidencia_pruebas_unitarias.txt`
- **Volumen:** 12 tests exitosos, 43 aserciones (Tiempo: ~2.17s).
- **Cobertura Destacada:** 
  - Validaciones de *VideoEmbedService* para YouTube/Vimeo.
  - Comprobación de relaciones de Eloquent en *LmsRelationshipsTest*.
  - Reglas de negocio del *CoursePublishingService*.

### 13.2. Ejecución de Pruebas de Caja Negra
- **Evidencia:** `evidencia_caja_negra.txt`
- **Volumen:** 34 tests exitosos, 158 aserciones (Tiempo: ~4.77s).
- **Cobertura Destacada:**
  - `AdminCourseCrudTest`: Operaciones CRUD del panel, publicando y duplicando cursos.
  - `PaymentStripeTest`: Validación de carritos vacíos, redireccionamientos, idempotencia de Webhooks de pago y reserva de cupones pendientes.
  - `PublicCourseCatalogTest`: Flujo del visitante público desde el registro hasta agregar al carrito.

### 13.3. Ejecución de Pruebas de Caja Blanca
- **Evidencia:** `evidencia_caja_blanca.txt`
- **Volumen:** 21 tests exitosos, 109 aserciones (Tiempo: ~4.93s).
- **Cobertura Destacada:**
  - `AdminSecurityAndRolesTest`: Protección de cabeceras de seguridad HTTP, prevención de *lockout* (el último admin no puede perder su cargo).
  - Tolerancia al *Rate Limiting* en el Chatbot y Login para mitigar ataques de fuerza bruta (DDoS).
  - Restricción lógica estricta para roles como Instructores y Soporte.

### 13.4. Resto de Pruebas (Integraciones y Lógica Compleja)
- **Evidencia:** `evidencia_resto_pruebas.txt`
- **Volumen:** 22 tests exitosos, 116 aserciones (Completando así las 426 aserciones del sistema).
- **Cobertura Destacada:**
  - `GeminiAssistantTest`: Comprobación del agente conversacional de IA.
  - `AdminDashboardAnalyticsTest`: Consistencia en los cálculos financieros mostrados en las gráficas de administración.
  - `LmsReleaseReadinessTest`: Prueba final que audita el sistema de despliegue antes de entrar a Producción.

> **Conclusión General:** La ejecución constante de estas 426 aserciones previene regresiones futuras. La política establecida indica que ningún código es aceptado o fusionado hacia la rama `main` de GitHub si `php artisan test` no retorna 100% SUCCESS en todos sus grupos.

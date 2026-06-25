# Software Design Document (SDD) - Plataforma E-learning

## 1. Introducción
### 1.1 Propósito
Este documento describe la arquitectura y el diseño de procesos para la plataforma E-learning. El objetivo es proporcionar una guía técnica exhaustiva para el desarrollo del sistema, detallando los flujos de interacción entre el cliente, el sistema (backend/frontend) y servicios externos.

### 1.2 Alcance del Sistema
La plataforma permite la visualización de un catálogo digital, registro y autenticación de usuarios, procesamiento de pagos seguros, consumo de material educativo multimedia, gestión administrativa de la oferta comercial (CRUD) y soporte inteligente.

## 2. Arquitectura del Sistema
El sistema se diseña bajo principios **SOLID** y buscará cumplir con los atributos de calidad de la norma **ISO/IEC 25010** (específicamente en eficiencia de desempeño, usabilidad y seguridad).

### 2.1 Stack Tecnológico Propuesto
* **Frontend (Cliente / Administrador):** Interfaces construidas con componentes reactivos (ej. React) y estilizadas mediante frameworks de utilidades (ej. Tailwind CSS) para garantizar un diseño responsivo y de carga rápida.
* **Backend (Core Lógico):** Laravel, encargado del enrutamiento, validaciones de seguridad, comunicación con pasarelas de pago y lógica de negocio.
* **Base de Datos Principal:**
    * **Relacional (MySQL):** Actuará como la única fuente de verdad (Single Source of Truth) para todo el sistema. Gestionará usuarios, perfiles, transacciones financieras, auditoría, el catálogo de cursos y los registros de soporte al cliente.

---

## 3. Especificación de Procesos de Negocio

A continuación, se detallan los 6 procesos core del sistema, estructurados a partir de los diagramas BPMN definidos en la fase de levantamiento de requerimientos.

### 3.1 Proceso 1: Registro y Autenticación de Usuarios
* **Relación de Requerimientos:** RF04, RF05.
* **Actores:** Cliente (Usuario), Sistema (Laravel & MySQL).
* **Objetivo:** Gestionar el acceso seguro a la plataforma y la creación automatizada de perfiles.

| Paso | Actor | Acción | Descripción Técnica |
| :--- | :--- | :--- | :--- |
| 1 | Cliente | Intento de acceso | El usuario accede a la vista de login/registro. Evalúa si posee cuenta. |
| 2 | Cliente | Selección de método | Elige entre SSO (Google Auth) o credenciales clásicas (Correo/Contraseña). |
| 3 | Sistema | Validación | El controlador de Laravel recibe el request y verifica las credenciales o el token OAuth. |
| 4 | Sistema | Bifurcación (Fallo) | Si la validación falla, retorna un código HTTP 401 y muestra mensaje de error en la UI. Finaliza el flujo. |
| 5 | Sistema | Comprobación BD (Éxito) | Si es exitoso, verifica la existencia en MySQL. Si es nuevo, inserta el registro creando un perfil automáticamente. |
| 6 | Sistema | Autorización | Genera token de sesión, autentica al usuario y redirige al Dashboard (HTTP 302). |

### 3.2 Proceso 2: Exploración de Catálogo y Selección
* **Relación de Requerimientos:** RF01, RF02, RF03.
* **Actores:** Cliente, Sistema Web.
* **Objetivo:** Presentar la oferta comercial de manera dinámica para incentivar la conversión.

| Paso | Actor | Acción | Descripción Técnica |
| :--- | :--- | :--- | :--- |
| 1 | Cliente | Inicio de navegación | Accede a la ruta principal (Landing Page). |
| 2 | Sistema | Carga de Catálogo | Consulta la base de datos (MySQL) y renderiza la lista de cursos activos e información institucional. |
| 3 | Cliente | Interacción | Aplica filtros (categoría, precio) y selecciona un curso específico. |
| 4 | Sistema | Vista de Detalle | Despliega la vista dinámica con metadata: descripción, costo, duración y temario estructurado. |
| 5 | Cliente | Decisión | Evalúa la compra. Si abandona, el flujo termina (Posible trigger para Analytics). |
| 6 | Cliente | Acción de Compra | Hace clic en el CTA "Inscribirse / Comprar". |
| 7 | Sistema | Redirección | Prepara el payload del carrito y redirige al módulo de pago protegido. |

### 3.3 Proceso 3: Procesamiento de Pago Seguro
* **Relación de Requerimientos:** RF06, RF07, RF08.
* **Actores:** Cliente, Sistema, Pasarela de Pagos Externa (API).
* **Objetivo:** Garantizar la integridad y confidencialidad en las transacciones financieras.

| Paso | Actor | Acción | Descripción Técnica |
| :--- | :--- | :--- | :--- |
| 1 | Cliente | Checkout | Visualiza el resumen de compra e ingresa datos de facturación y tarjeta. |
| 2 | Sistema | Cifrado y Petición | El frontend/backend cifra la data sensible. Laravel envía un POST request a la API de la Pasarela. |
| 3 | Pasarela | Procesamiento | Valida fondos, riesgo de fraude y procesa la transacción bancaria. |
| 4 | Pasarela | Respuesta (Fallo) | Si es denegada, retorna código de error. El Sistema mapea el error y muestra la alerta al cliente. |
| 5 | Pasarela | Respuesta (Éxito) | Si es aprobada, retorna un Webhook/Token de confirmación. |
| 6 | Sistema | Registro Financiero | Laravel recibe el Webhook, verifica la firma, y realiza un `INSERT` en la tabla `transactions` de MySQL. |

### 3.4 Proceso 4: Habilitación y Consumo de Contenido
* **Relación de Requerimientos:** RF09, RF10.
* **Actores:** Sistema, Cliente (Estudiante).
* **Objetivo:** Automatizar la entrega del servicio post-compra sin intervención humana.

| Paso | Actor | Acción | Descripción Técnica |
| :--- | :--- | :--- | :--- |
| 1 | Sistema | Detección | El Listener de Laravel detecta el evento de pago validado del Proceso 3. |
| 2 | Sistema | Inscripción | Ejecuta la lógica para insertar un registro en la tabla `enrollments` vinculando `user_id` y `course_id`. |
| 3 | Sistema | Habilitación | Actualiza las políticas de acceso (Gates/Policies) otorgando permisos de visualización al usuario. |
| 4 | Cliente | Acceso a Panel | El usuario navega a la ruta protegida `/mis-cursos`. |
| 5 | Sistema | Carga de Material | El servidor despacha el reproductor de video (streaming seguro) y enlaces firmados para documentos PDF. |
| 6 | Cliente | Consumo | Inicia la reproducción asíncrona o descarga de material de estudio. |

### 3.5 Proceso 5: Gestión Administrativa de Cursos (CRUD)
* **Relación de Requerimientos:** RF12, RF13.
* **Actores:** Administrador, Sistema.
* **Objetivo:** Mantener actualizada la oferta comercial mediante un panel de control restringido.

| Paso | Actor | Acción | Descripción Técnica |
| :--- | :--- | :--- | :--- |
| 1 | Admin | Autenticación Admin | Ingresa al Dashboard administrativo (Rutas protegidas por middleware de roles). |
| 2 | Admin | Selección de Acción | Decide la operación a realizar sobre la entidad "Curso". |
| 3A | Admin | Create (Crear) | Llena formulario multipart. Sube media (imágenes/videos) al Storage. |
| 3B | Admin | Update (Editar) | Modifica atributos existentes (precio, temario, estado). |
| 3C | Admin | Delete (Soft Delete) | Deshabilita el curso (cambio de estado o soft delete en MySQL, no destrucción física para mantener integridad histórica). |
| 4 | Sistema | Persistencia | Ejecuta la transacción SQL correspondiente en la base de datos (MySQL). |
| 5 | Sistema | Sincronización | Invalida caché si aplica y refleja los cambios en el catálogo público. |
| 6 | Sistema | Feedback | Retorna un Toast/Alerta de éxito en la interfaz del Administrador. |

### 3.6 Proceso 6: Soporte Inteligente y Escalamiento
* **Relación de Requerimientos:** RF11.
* **Actores:** Cliente, Chatbot IA, Asesor (Humano).
* **Objetivo:** Optimizar los tiempos de respuesta mediante IA, reservando el soporte humano para casos complejos.

| Paso | Actor | Acción | Descripción Técnica |
| :--- | :--- | :--- | :--- |
| 1 | Cliente | Solicitud | Abre el widget de chat flotante en la plataforma web. |
| 2 | Chatbot | Recepción | El script inicializa y muestra botones de respuestas rápidas (FAQs). |
| 3 | Cliente | Interacción | Selecciona una opción o tipea una consulta en texto libre (NLP). |
| 4 | Chatbot | Respuesta Automatizada| Consulta la base de conocimiento y retorna la solución. |
| 5 | Cliente | Evaluación | Si la duda es resuelta, abandona el flujo. Si no, solicita escalamiento. |
| 6 | Chatbot | Handoff (Escalamiento)| Genera una URL dinámica de API WhatsApp (`wa.me/numero?text=contexto_previo`) y redirige al usuario. |
| 7 | Asesor | Recepción en WA | El asesor humano recibe el mensaje con el contexto de la duda no resuelta. |
| 8 | Asesor | Resolución | Atención directa mediante la plataforma de mensajería (WhatsApp Business). |

---
## 4. Consideraciones de Seguridad y Rendimiento
* **Protección CSRF/XSS:** Todas las peticiones del frontend hacia Laravel deben incluir tokens CSRF y sanear entradas.
* **Transacciones ACID:** Los procesos de registro de pago (Proceso 3) y enrolamiento (Proceso 4) deben ejecutarse dentro de un bloque `DB::transaction()` en Laravel para asegurar la atomicidad (rollbacks automáticos en caso de fallo en MySQL).
* **Optimización de Consultas:** Al centralizar todo en MySQL, se deben implementar índices adecuados (Indexes) en las tablas más consultadas como `courses`, `users` y `enrollments` para asegurar tiempos de respuesta óptimos.

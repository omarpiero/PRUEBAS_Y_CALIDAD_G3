# Documentación General — JM y JS Alimentos
## Plataforma de Capacitación en Línea

---

## 1. Objetivos

### 1.1 Objetivo General

Desarrollar una plataforma web de e-learning para la empresa **JM y JS Alimentos**, con sede en Huancayo, Junín, que permita a profesionales de la industria alimentaria peruana acceder a cursos de certificación en línea, gestionar sus inscripciones y pagos, y recibir asistencia mediante inteligencia artificial; centralizando además la administración de usuarios y comunicaciones en un panel de control.

---

### 1.2 Objetivos Específicos

| # | Objetivo | Indicador de cumplimiento |
|---|---|---|
| OE-01 | Publicar un catálogo de 9 cursos organizados por nivel (Básico, Intermedio, Avanzado) con información de duración, precio y certificación | El usuario puede ver, filtrar y seleccionar cursos desde `/cursos` |
| OE-02 | Implementar un sistema de registro e inicio de sesión seguro para estudiantes | Los usuarios pueden crear una cuenta, autenticarse y acceder a su panel personal |
| OE-03 | Habilitar un carrito de compras y flujo de pago que genere inscripciones automáticamente | El pago exitoso crea registros en la tabla `enrollments` con estado `pagado` |
| OE-04 | Proveer un panel personal al estudiante donde visualice sus cursos, perfil y logros | La página `/mi-cuenta` muestra inscripciones, progreso y estadísticas en tiempo real |
| OE-05 | Implementar un sistema de contacto asíncrono para consultas de los visitantes | Los mensajes se guardan en la BD y el administrador los gestiona desde `/admin/contacts` |
| OE-06 | Crear un panel de administración con control de usuarios, mensajes e inscripciones | El administrador accede a estadísticas y puede gestionar usuarios y contactos desde `/admin` |
| OE-07 | Integrar un asistente virtual con IA para orientación en tiempo real | El chatbot responde consultas sobre cursos y servicios usando Google Gemini |
| OE-08 | Garantizar que la plataforma sea responsive y funcional en dispositivos móviles | El diseño se adapta a pantallas desde 320px sin pérdida de funcionalidad |

---

## 2. Requerimientos

### 2.1 Requerimientos Funcionales

Los requerimientos funcionales describen **qué debe hacer** el sistema.

#### RF-01 — Gestión de cuentas de usuario

| ID | Descripción |
|---|---|
| RF-01.1 | El sistema debe permitir el registro de nuevos usuarios con nombre, correo, contraseña y datos opcionales (DNI, teléfono) |
| RF-01.2 | El sistema debe validar que el correo electrónico sea único en el momento del registro |
| RF-01.3 | El sistema debe requerir contraseñas de mínimo 8 caracteres con confirmación |
| RF-01.4 | El sistema debe permitir el inicio de sesión con correo y contraseña |
| RF-01.5 | El sistema debe soportar la opción "Recuérdame" para sesiones persistentes |
| RF-01.6 | El sistema debe invalidar completamente la sesión al cerrar sesión |
| RF-01.7 | El sistema debe redirigir al administrador al panel admin y al estudiante a su cuenta tras el login |

#### RF-02 — Catálogo de cursos

| ID | Descripción |
|---|---|
| RF-02.1 | El sistema debe mostrar los 9 cursos disponibles con nombre, nivel, duración, precio y si incluye certificación |
| RF-02.2 | El sistema debe permitir filtrar los cursos por nivel (Básico, Intermedio, Avanzado) |
| RF-02.3 | El sistema debe mostrar un curso destacado en la sección hero de la página de cursos |

#### RF-03 — Carrito de compras

| ID | Descripción |
|---|---|
| RF-03.1 | El sistema debe permitir agregar cursos al carrito sin requerir autenticación |
| RF-03.2 | El sistema debe impedir agregar el mismo curso dos veces al carrito |
| RF-03.3 | El sistema debe permitir eliminar cursos del carrito individualmente |
| RF-03.4 | El sistema debe mostrar el conteo de cursos en el carrito de forma persistente en la barra de navegación |
| RF-03.5 | El sistema debe requerir autenticación para acceder al checkout |
| RF-03.6 | El sistema debe calcular y mostrar el subtotal, el IGV (18%) y el total en el checkout |

#### RF-04 — Proceso de pago e inscripción

| ID | Descripción |
|---|---|
| RF-04.1 | El sistema debe validar los datos de la tarjeta (nombre, número mínimo de 16 dígitos, expiración, CVC mínimo de 3 dígitos) |
| RF-04.2 | El sistema debe crear una inscripción con estado `pagado` por cada curso en el carrito al confirmar el pago |
| RF-04.3 | El sistema debe impedir la inscripción duplicada si el usuario ya está inscrito en un curso del carrito |
| RF-04.4 | El sistema debe vaciar el carrito inmediatamente después de procesar el pago |
| RF-04.5 | El sistema debe redirigir al usuario a una página de confirmación tras el pago exitoso |
| RF-04.6 | El sistema debe redirigir al catálogo de cursos si se intenta pagar con el carrito vacío |

#### RF-05 — Panel del estudiante

| ID | Descripción |
|---|---|
| RF-05.1 | El sistema debe mostrar todos los cursos inscritos del usuario con nombre, nivel, precio, estado y fecha |
| RF-05.2 | El sistema debe calcular y mostrar estadísticas del estudiante: cursos inscritos, pagados, completados e inversión total |
| RF-05.3 | El sistema debe mostrar el perfil del usuario con todos sus datos registrados |
| RF-05.4 | El sistema debe desbloquear logros automáticamente según condiciones (primera inscripción, primer pago, 3+ cursos, primer curso completado) |

#### RF-06 — Formulario de contacto

| ID | Descripción |
|---|---|
| RF-06.1 | El sistema debe permitir a cualquier visitante enviar un mensaje de consulta |
| RF-06.2 | El sistema debe mostrar el campo de selección de curso solo cuando el tema seleccionado sea "Cursos" |
| RF-06.3 | El sistema debe guardar el mensaje en la base de datos con estado `no leído` |
| RF-06.4 | El sistema debe confirmar el envío al usuario mediante una notificación sin recargar la página |

#### RF-07 — Panel de administración

| ID | Descripción |
|---|---|
| RF-07.1 | El sistema debe restringir el acceso al panel admin exclusivamente a usuarios con rol `is_admin = true` |
| RF-07.2 | El sistema debe mostrar estadísticas generales: total de usuarios, nuevos registros, mensajes y inscripciones |
| RF-07.3 | El sistema debe permitir al administrador alternar el rol de administrador de cualquier usuario |
| RF-07.4 | El sistema debe mostrar los mensajes de contacto con distinción visual entre leídos y no leídos |
| RF-07.5 | El sistema debe permitir al administrador marcar mensajes como leídos y eliminarlos |

#### RF-08 — Asistente virtual con IA

| ID | Descripción |
|---|---|
| RF-08.1 | El sistema debe integrar un chatbot accesible desde cualquier página de la plataforma |
| RF-08.2 | El chatbot debe responder preguntas sobre los cursos, servicios y empresa usando Google Gemini |
| RF-08.3 | El sistema debe manejar errores de la API de IA con mensajes descriptivos al usuario |

---

### 2.2 Requerimientos No Funcionales

Los requerimientos no funcionales describen **cómo debe comportarse** el sistema.

#### Seguridad

| ID | Descripción |
|---|---|
| RNF-01 | Las contraseñas deben almacenarse como hash bcrypt con mínimo 12 rondas |
| RNF-02 | El sistema debe regenerar el ID de sesión tras cada inicio de sesión para prevenir session fixation |
| RNF-03 | Todas las rutas POST deben estar protegidas por tokens CSRF |
| RNF-04 | Las rutas del panel admin deben ser inaccesibles para usuarios sin rol de administrador (HTTP 403) |
| RNF-05 | La clave de API de Gemini debe almacenarse en variables de entorno, nunca en el código fuente |

#### Rendimiento

| ID | Descripción |
|---|---|
| RNF-06 | Las operaciones del carrito (agregar/eliminar) deben responder en menos de 500ms usando AJAX |
| RNF-07 | Las páginas públicas deben cargar en menos de 3 segundos en conexiones de 10 Mbps |
| RNF-08 | El chatbot debe tener un timeout máximo de 30 segundos para las respuestas de Gemini |

#### Usabilidad

| ID | Descripción |
|---|---|
| RNF-09 | La interfaz debe ser completamente funcional en pantallas desde 320px de ancho (móvil) |
| RNF-10 | El sistema debe mostrar mensajes de error claros y en español para todas las validaciones |
| RNF-11 | Las operaciones de éxito y error deben comunicarse mediante notificaciones toast no intrusivas |
| RNF-12 | La barra de navegación debe permanecer visible al hacer scroll en todas las páginas |

#### Mantenibilidad

| ID | Descripción |
|---|---|
| RNF-13 | El código debe seguir el estándar PSR-12 de PHP, verificable con Laravel Pint |
| RNF-14 | Los cambios en la estructura de la base de datos deben gestionarse exclusivamente mediante migraciones de Laravel |
| RNF-15 | Las variables de configuración sensibles deben definirse en `.env` y nunca ser subidas al repositorio |

#### Disponibilidad

| ID | Descripción |
|---|---|
| RNF-16 | El sistema debe funcionar en un entorno local con XAMPP sin requerir conexión a Internet para las funcionalidades principales (excepto el chatbot) |
| RNF-17 | El sistema debe manejar la ausencia de la clave de API de Gemini sin detener el funcionamiento del resto de la plataforma |

---

## 3. Actores Involucrados

El sistema define tres actores con distintos niveles de acceso y responsabilidades.

---

### Actor 1 — Visitante (no autenticado)

```
┌────────────────────────────────────────────────────────┐
│  VISITANTE                                             │
│  Perfil: Cualquier persona que accede al sitio sin     │
│          haber iniciado sesión                         │
│                                                        │
│  Puede hacer:                                          │
│  ✓ Ver la página de inicio                             │
│  ✓ Ver la página "Nosotros"                            │
│  ✓ Explorar el catálogo de cursos y filtrarlos         │
│  ✓ Agregar cursos al carrito (sesión temporal)         │
│  ✓ Enviar mensajes de contacto                         │
│  ✓ Usar el chatbot de IA                               │
│  ✓ Registrarse como nuevo usuario                      │
│  ✓ Iniciar sesión si ya tiene cuenta                   │
│                                                        │
│  No puede hacer:                                       │
│  ✗ Ver el checkout (redirige a login)                  │
│  ✗ Acceder a "Mi cuenta"                               │
│  ✗ Realizar pagos                                      │
│  ✗ Ver el panel de administración                      │
└────────────────────────────────────────────────────────┘
```

**Ejemplo de usuario real:** Un técnico en producción alimentaria de Huancayo que llega al sitio buscando capacitación en HACCP, navega los cursos, los agrega al carrito y luego se registra para completar la compra.

---

### Actor 2 — Estudiante (usuario autenticado)

```
┌────────────────────────────────────────────────────────┐
│  ESTUDIANTE                                            │
│  Perfil: Usuario registrado que ha iniciado sesión.    │
│          Puede ser profesional del sector alimentario, │
│          técnico, ingeniero o emprendedor.             │
│                                                        │
│  Puede hacer (todo lo del Visitante, más):             │
│  ✓ Acceder al checkout y completar el pago             │
│  ✓ Ver su historial de inscripciones                   │
│  ✓ Ver su progreso en cada curso                       │
│  ✓ Ver y actualizar su perfil personal                 │
│  ✓ Desbloquear logros según su actividad               │
│  ✓ Ver su inversión total acumulada                    │
│  ✓ Cerrar sesión de forma segura                       │
│                                                        │
│  No puede hacer:                                       │
│  ✗ Acceder al panel de administración                  │
│  ✗ Ver datos de otros usuarios                         │
│  ✗ Modificar el estado de sus inscripciones            │
│  ✗ Alterar roles de usuario                            │
└────────────────────────────────────────────────────────┘
```

**Ejemplo de usuario real:** Una supervisora de calidad de una planta de alimentos que ya pagó el curso de BPM y quiere revisar cuántos cursos lleva y cuánto ha invertido en su formación.

---

### Actor 3 — Administrador

```
┌────────────────────────────────────────────────────────┐
│  ADMINISTRADOR                                         │
│  Perfil: Usuario con is_admin = true. Generalmente     │
│          el equipo interno de JM y JS Alimentos        │
│          encargado de la operación del sitio.            │
│                                                        │
│  Puede hacer (todo lo del Estudiante, más):            │
│  ✓ Acceder al panel de administración (/admin)         │
│  ✓ Ver estadísticas globales de la plataforma          │
│  ✓ Listar todos los usuarios registrados               │
│  ✓ Elevar o remover el rol de administrador a usuarios │
│  ✓ Ver todos los mensajes de contacto recibidos        │
│  ✓ Marcar mensajes como leídos                         │
│  ✓ Eliminar mensajes de contacto                       │
│  ✓ Ver las inscripciones más recientes                 │
│                                                        │
│  No puede hacer (restricciones de diseño):             │
│  ✗ Remocionarse a sí mismo el rol de administrador     │
│    (protección contra quedarse sin acceso)             │
└────────────────────────────────────────────────────────┘
```

**Ejemplo de usuario real:** El coordinador académico de JM y JS que revisa cada mañana los mensajes nuevos de contacto, responde consultas sobre cursos y monitorea cuántos estudiantes se inscribieron en la semana.

---

### Resumen de permisos por actor

| Funcionalidad | Visitante | Estudiante | Administrador |
|---|:---:|:---:|:---:|
| Ver páginas públicas (inicio, nosotros, cursos, contacto) | ✓ | ✓ | ✓ |
| Usar el chatbot de IA | ✓ | ✓ | ✓ |
| Agregar cursos al carrito | ✓ | ✓ | ✓ |
| Enviar formulario de contacto | ✓ | ✓ | ✓ |
| Registrarse / Iniciar sesión | ✓ | — | — |
| Acceder al checkout | — | ✓ | ✓ |
| Realizar pagos | — | ✓ | ✓ |
| Ver "Mi cuenta" (cursos, perfil, logros) | — | ✓ | ✓ |
| Panel de administración | — | — | ✓ |
| Gestionar usuarios | — | — | ✓ |
| Gestionar mensajes de contacto | — | — | ✓ |
| Ver estadísticas globales | — | — | ✓ |

---

## 4. Alcance del Proyecto

### 4.1 Lo que incluye el sistema (dentro del alcance)

| Área | Funcionalidades incluidas |
|---|---|
| **Sitio público** | Páginas de inicio, nosotros, cursos y contacto con diseño responsivo completo |
| **Autenticación** | Registro, login, logout y sesiones seguras con manejo de roles |
| **Catálogo** | 9 cursos con filtros por nivel, precios en soles peruanos e información de certificación |
| **Comercio** | Carrito de compras en sesión, checkout con validación de tarjeta e inscripciones automáticas |
| **Panel de estudiante** | Historial de inscripciones, estadísticas personales, perfil y sistema de logros |
| **Contacto** | Formulario asíncrono con campos dinámicos y almacenamiento en base de datos |
| **Administración** | Dashboard con estadísticas, gestión de usuarios y gestión de mensajes |
| **IA** | Chatbot integrado con Google Gemini con system prompt especializado en la empresa |
| **Base de datos** | SQLite local con migraciones versionadas y relaciones entre modelos |
| **Frontend** | CSS personalizado, React para el chatbot, Tailwind CSS disponible, diseño mobile-first |
| **Pruebas** | Suite de pruebas con PHPUnit (Feature + Unit), BD en memoria para tests |

---

### 4.2 Lo que NO incluye el sistema (fuera del alcance)

| Área | Descripción |
|---|---|
| **Procesamiento local de tarjeta** | Los datos de tarjeta no se reciben ni almacenan en el servidor; el pago se deriva a Stripe Checkout |
| **Streaming de video** | Los cursos no incluyen reproducción de contenido multimedia; la plataforma gestiona inscripciones, no la entrega del contenido |
| **Sistema de correo electrónico** | No se envían correos de confirmación, recuperación de contraseña ni notificaciones por email (el mailer está en modo `log`) |
| **Recuperación de contraseña** | No existe el flujo de "olvidé mi contraseña" con envío de enlace al email |
| **Pagos recurrentes o suscripciones** | El modelo de negocio es pago único por curso, sin planes de membresía |
| **App móvil nativa** | La plataforma es web responsiva; no hay apps para iOS ni Android |
| **Integración con LMS externo** | No se conecta con Moodle, Canvas ni otras plataformas de gestión de aprendizaje |
| **Certificados digitales** | El sistema registra que un curso está "completado" pero no genera ni emite certificados en PDF |
| **Múltiples idiomas** | La plataforma está diseñada exclusivamente en español |
| **Roles intermedios** | Solo existen dos roles: usuario regular y administrador. No hay roles de instructor, moderador, etc. |
| **Analítica avanzada** | No se integra Google Analytics, Hotjar ni herramientas de métricas externas |
| **Despliegue en nube** | El sistema está diseñado para entorno local (XAMPP). No incluye configuración para AWS, DigitalOcean, Heroku, etc. |

---

### 4.3 Restricciones del proyecto

| Restricción | Detalle |
|---|---|
| **Tecnológica** | El sistema debe construirse con Laravel (PHP) como framework principal |
| **De datos** | La base de datos debe ser SQLite en el entorno de desarrollo local |
| **De entorno** | El sistema debe funcionar sobre XAMPP sin requerir Docker ni servicios adicionales |
| **De idioma** | Toda la interfaz, mensajes de error y documentación deben estar en español |
| **De pago** | Los pagos se procesan en Stripe Checkout; el servidor solo registra ventas, cupones, webhooks y matriculas |
| **De IA** | La inteligencia artificial depende de un servicio externo (Google Gemini); su disponibilidad está sujeta a la cuota gratuita de la API |

---

### 4.4 Supuestos del proyecto

- Los usuarios tienen acceso a un navegador web moderno (Chrome 90+, Firefox 90+, Edge 90+).
- La empresa JM y JS Alimentos proporcionará su propia clave de API de Google AI Studio para el chatbot.
- El contenido de los cursos (videos, materiales) se entregará por canales externos; la plataforma solo gestiona las inscripciones.
- Un único administrador técnico será responsable de mantener la aplicación en el servidor XAMPP local.

---

*Documentación general — JM y JS Alimentos — Mayo 2026*

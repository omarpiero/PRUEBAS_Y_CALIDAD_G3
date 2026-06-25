# Metodología de Desarrollo — JM y JS Alimentos
## Plataforma de Capacitación en Línea

---

## 1. Metodología Seleccionada: Desarrollo Iterativo e Incremental

### 1.1 Descripción

El proyecto adoptó la **metodología de Desarrollo Iterativo e Incremental**, con prácticas ágiles inspiradas en **Scrum**. Este enfoque divide el desarrollo en ciclos cortos (iteraciones), donde cada ciclo produce una versión funcional del sistema que se enriquece progresivamente hasta alcanzar el producto final.

A diferencia del modelo en cascada (Waterfall), donde todas las fases se completan de forma estrictamente secuencial, el modelo iterativo permite:

- Detectar y corregir errores en etapas tempranas.
- Priorizar las funcionalidades de mayor valor para el usuario.
- Adaptar el alcance según los avances reales del equipo.
- Entregar versiones funcionales en cada iteración.

### 1.2 Justificación de la elección

| Criterio | Razón |
|---|---|
| **Tamaño del equipo** | Equipo pequeño; la coordinación ágil es más eficiente que procesos formales pesados |
| **Requisitos cambiantes** | Durante el desarrollo surgieron nuevas funcionalidades (chatbot IA, sistema de logros) que el modelo iterativo absorbió sin necesidad de replantear todo |
| **Entrega temprana de valor** | Desde la primera versión el sistema era navegable y funcional |
| **Control de calidad continuo** | Cada iteración incluyó pruebas antes de pasar a la siguiente |
| **Contexto académico** | El modelo iterativo facilita la trazabilidad del avance para presentaciones y revisiones |

---

## 2. Fases de la Metodología

El ciclo de vida del proyecto se organizó en **4 iteraciones principales** (versiones), precedidas por una fase de planificación y cerradas con una fase de documentación.

```
┌─────────────────────────────────────────────────────────────┐
│  FASE 0 — Planificación y diseño inicial                    │
│  Definición de requisitos, arquitectura y diseño visual     │
└──────────────────────────┬──────────────────────────────────┘
                           │
┌──────────────────────────▼──────────────────────────────────┐
│  ITERACIÓN 1 — Versión base (feat: index)                   │
│  Estructura del proyecto, páginas públicas, navegación      │
└──────────────────────────┬──────────────────────────────────┘
                           │
┌──────────────────────────▼──────────────────────────────────┐
│  ITERACIÓN 2 — Última versión                               │
│  Autenticación, carrito, checkout, panel del estudiante     │
└──────────────────────────┬──────────────────────────────────┘
                           │
┌──────────────────────────▼──────────────────────────────────┐
│  ITERACIÓN 3 — 3era versión                                 │
│  Panel admin, contactos, inscripciones, chatbot IA          │
└──────────────────────────┬──────────────────────────────────┘
                           │
┌──────────────────────────▼──────────────────────────────────┐
│  ITERACIÓN 4 — 4ta versión                                  │
│  Refinamiento de UI, correcciones, ajustes de experiencia   │
└──────────────────────────┬──────────────────────────────────┘
                           │
┌──────────────────────────▼──────────────────────────────────┐
│  FASE FINAL — Documentación y entrega                       │
│  Generación de los 7 documentos técnicos del proyecto       │
└─────────────────────────────────────────────────────────────┘
```

---

## 3. Detalle de Cada Iteración

### Fase 0 — Planificación y Diseño Inicial

**Objetivo:** Definir qué se va a construir y cómo.

| Actividad | Resultado |
|---|---|
| Levantamiento de requisitos | Lista de funcionalidades priorizadas |
| Definición de actores | Visitante, Estudiante, Administrador |
| Diseño de la base de datos | Schema de `users`, `enrollments`, `contacts` |
| Definición del stack tecnológico | Laravel 12, React 19, Vite 7, SQLite |
| Diseño visual (wireframes) | Paleta de colores, tipografía Poppins, estructura de páginas |
| Configuración del repositorio | Repositorio Git en GitHub (`pruebas-calidad-grupo-03`) |

---

### Iteración 1 — Versión Base (`feat: index`)

**Objetivo:** Tener el proyecto corriendo con las páginas públicas funcionales.

**Funcionalidades desarrolladas:**

- Estructura del proyecto Laravel (rutas, layouts, vistas)
- Layout principal (`layouts/app.blade.php`) con navbar y footer
- Página de **Inicio** con hero, sección de cursos destacados y testimonios
- Página **Nosotros** con historia, metodología y valores (bento grid)
- Página **Cursos** con catálogo y filtros por nivel
- Página **Contacto** con información y formulario básico
- Hoja de estilos principal (`public/css/site.css`) — diseño base
- Modelo de color y tipografía definidos

**Criterios de aceptación verificados:**
- ✓ Las 4 páginas públicas cargan sin errores
- ✓ La navegación entre páginas funciona correctamente
- ✓ El diseño es consistente en desktop y móvil
- ✓ El catálogo muestra los 9 cursos con sus datos correctos

---

### Iteración 2 — Última Versión

**Objetivo:** Agregar el sistema de usuarios y el flujo de compra completo.

**Funcionalidades desarrolladas:**

- **Autenticación:** Registro, login, logout con validaciones y mensajes de error
- **Migraciones:** Tablas `users` (con `is_admin`, `dni`, `phone`), `enrollments`, `contacts`
- **Modelos Eloquent:** `User`, `Enrollment`, `Contact` con relaciones
- **Carrito de compras:** Agregar/eliminar cursos via AJAX, badge en navbar
- **Checkout:** Resumen del carrito, selección de método de pago, validación de tarjeta
- **Pago:** Proceso de pago con creación automática de inscripciones, vaciado del carrito
- **Página de éxito:** Confirmación post-pago
- **Mi Cuenta:** Panel del estudiante con tabs (Mis Cursos, Mi Perfil, Logros)
- **Formulario de contacto:** Envío AJAX, campos condicionales, guardado en BD
- **Middleware:** `AdminMiddleware` registrado como alias `admin`

**Criterios de aceptación verificados:**
- ✓ Un usuario puede registrarse e iniciar sesión
- ✓ El carrito acepta múltiples cursos y previene duplicados
- ✓ El pago crea inscripciones con estado `pagado`
- ✓ Mi Cuenta muestra correctamente los cursos inscritos
- ✓ Los logros se desbloquean automáticamente según condiciones

---

### Iteración 3 — 3era Versión

**Objetivo:** Completar el sistema con el panel de administración y el chatbot de IA.

**Funcionalidades desarrolladas:**

- **Panel Admin — Dashboard:** Estadísticas de usuarios, contactos e inscripciones
- **Panel Admin — Usuarios:** Lista paginada, toggle de rol `is_admin`
- **Panel Admin — Contactos:** Lista de mensajes, marcar como leído, eliminar
- **Layout admin:** Sidebar de navegación exclusivo para administradores
- **Redirección por rol:** Login redirige a `/admin` para admins y a `/mi-cuenta` para estudiantes
- **Chatbot IA:** Integración con Google Gemini API, system prompt de la empresa, manejo de errores
- **API route:** `POST /api/chat` en `routes/api.php`
- **Componente React:** Chatbot montado en el layout principal
- **Config Gemini:** `config/services.php` con key y modelo configurables via `.env`

**Criterios de aceptación verificados:**
- ✓ El admin puede acceder a todas las secciones del panel
- ✓ Usuarios sin rol admin reciben error 403
- ✓ El chatbot responde preguntas sobre la empresa y cursos
- ✓ Los errores de la API de Gemini no detienen la plataforma

---

### Iteración 4 — 4ta Versión

**Objetivo:** Pulir la experiencia de usuario y corregir inconsistencias visuales.

**Archivos modificados:**

| Archivo | Cambios realizados |
|---|---|
| `public/css/site.css` | Refinamiento de estilos: animaciones, responsive, cards de cursos |
| `resources/views/cursos.blade.php` | Mejoras en la presentación del catálogo y filtros |
| `resources/views/layouts/app.blade.php` | Ajustes en la navbar, badge del carrito, integración del chatbot |
| `resources/views/mi-cuenta.blade.php` | Mejoras en las tabs, barras de progreso y sección de logros |

**Criterios de aceptación verificados:**
- ✓ La interfaz es visualmente consistente en todas las páginas
- ✓ El diseño responsive funciona en pantallas desde 320px
- ✓ Los elementos interactivos (filtros, tabs, carrito) responden correctamente
- ✓ No se introdujeron regresiones en funcionalidades anteriores

---

### Fase Final — Documentación y Entrega

**Objetivo:** Generar toda la documentación técnica y académica del proyecto.

**Documentos generados en la carpeta `documentacion/`:**

| Documento | Contenido |
|---|---|
| `DOCUMENTACION_GENERAL.md` | Objetivos, requerimientos, actores y alcance |
| `DOCUMENTACION_FUNCIONAL.md` | Flujo de pantallas, diseño de páginas y procesos automatizados |
| `ARQUITECTURA.md` | Estructura de carpetas, paradigmas MVC, CDD y SDD |
| `PRUEBAS_CALIDAD.md` | 59 casos de prueba de caja negra y blanca |
| `IMPLEMENTACION.md` | Especificaciones técnicas, requisitos y plan de implementación |
| `ESTADO_DEL_ARTE.md` | Análisis del contexto tecnológico y educativo del proyecto |
| `METODOLOGIA.md` | Este documento |

---

## 4. Prácticas Ágiles Aplicadas

### 4.1 Control de versiones con Git

Cada iteración quedó registrada como un **commit en el repositorio Git**, lo que permite:
- Trazabilidad completa del desarrollo
- Capacidad de revertir cambios si una iteración introduce errores
- Historial claro del progreso del proyecto

```
Historial de commits:
03c753a  feat: index          ← Iteración 1
df56fe4  Ultima version       ← Iteración 2
3bca91f  3era version         ← Iteración 3
accff0b  docs: documentacion  ← Fase documentación (parcial)
c7946e6  4ta version          ← Iteración 4
918c095  docs: estado del arte ← Fase documentación
```

### 4.2 Pruebas por iteración

Cada iteración incluyó verificación manual de los criterios de aceptación antes de ser registrada como commit. La suite de pruebas automatizadas (`phpunit`) valida el comportamiento de las rutas públicas y la integridad del sistema.

### 4.3 Separación de entornos

Se mantuvo una separación clara entre:
- **Entorno de desarrollo:** Variables en `.env`, base de datos local SQLite
- **Entorno de pruebas:** Variables en `phpunit.xml`, SQLite en memoria (`:memory:`)

### 4.4 Integración continua de funcionalidades

Cada funcionalidad nueva se integró de forma que no rompiera las anteriores. El enfoque de **no regresiones** fue prioritario: antes de agregar el chatbot (iteración 3), se verificó que el carrito y el pago (iteración 2) seguían funcionando correctamente.

---

## 5. Roles del Equipo

| Rol | Responsabilidades en el proyecto |
|---|---|
| **Desarrollador Full Stack** | Implementación de backend (Laravel) y frontend (Blade, CSS, React) |
| **Diseñador UX/UI** | Definición de la paleta, tipografía, layouts y experiencia de usuario |
| **Arquitecto de software** | Decisiones de stack, estructura de carpetas y paradigmas (MVC, CDD, SDD) |
| **Tester** | Diseño y ejecución de pruebas de caja negra y caja blanca |
| **Documentador técnico** | Redacción de los 7 documentos técnicos del proyecto |

> En un equipo pequeño, un mismo integrante puede asumir múltiples roles según la iteración en curso.

---

## 6. Herramientas de Soporte al Desarrollo

| Herramienta | Uso en el proyecto |
|---|---|
| **Git + GitHub** | Control de versiones y repositorio remoto |
| **XAMPP** | Servidor local Apache + PHP para desarrollo |
| **Visual Studio Code** | Editor de código principal |
| **php artisan** | CLI de Laravel: migraciones, tinker, rutas, servidor |
| **Composer** | Gestión de dependencias PHP |
| **npm + Vite** | Gestión de dependencias JS y bundling del frontend |
| **Google AI Studio** | Obtención de la API key para Google Gemini |
| **PHPUnit** | Ejecución de pruebas automatizadas |
| **Laravel Pint** | Formateo de código PHP según PSR-12 |

---

## 7. Comparación con Otras Metodologías

| Aspecto | Cascada (Waterfall) | Scrum puro | Iterativo (elegido) |
|---|---|---|---|
| **Flexibilidad** | Baja | Alta | Alta |
| **Documentación** | Extensa al inicio | Mínima | Moderada, al final |
| **Entrega** | Al final | Cada sprint (1-4 semanas) | Por versión funcional |
| **Adaptación a cambios** | Costosa | Natural | Natural |
| **Adecuado para equipo pequeño** | No | Sí | Sí |
| **Trazabilidad** | Alta | Media (depende del tablero) | Alta (commits Git) |
| **Aplicado en este proyecto** | No | Parcialmente | Sí |

---

## 8. Resumen del Ciclo de Vida

```
Mayo 2026
│
├── Semana 1 — Planificación
│   └── Requisitos, arquitectura, diseño visual, setup del proyecto
│
├── Semana 2 — Iteración 1
│   └── Páginas públicas: inicio, nosotros, cursos, contacto
│
├── Semana 3 — Iteración 2
│   └── Autenticación, carrito, checkout, pago, mi cuenta
│
├── Semana 4 — Iteración 3
│   └── Panel admin, chatbot IA, contactos, inscripciones
│
├── Semana 4 — Iteración 4
│   └── Refinamiento visual, correcciones, ajustes UX
│
└── Semana 4 — Documentación
    └── 7 documentos técnicos generados y subidos al repositorio
```

---

*Metodología de Desarrollo — JM y JS Alimentos — Mayo 2026*

# Capturas de Pantalla — LMS JM y JS Alimentos

Capturas reales tomadas el **24 de junio de 2026** sobre el servidor local `http://127.0.0.1:8000`.  
Resolución: **1440 × 900 px** (full-page, scroll completo). Generadas con Puppeteer + Chrome headless.

---

## 01_PUBLICAS — Páginas de acceso libre

| Archivo | Ruta | Descripción |
|---------|------|-------------|
| `01_inicio.png` | `/` | Hero principal, estadísticas, CTA |
| `02_nosotros.png` | `/nosotros` | Presentación de la empresa |
| `03_cursos.png` | `/cursos` | Catálogo público con filtros por categoría |
| `04_contacto.png` | `/contacto` | Formulario de contacto |

---

## 02_AUTH — Autenticación

| Archivo | Ruta | Descripción |
|---------|------|-------------|
| `01_login.png` | `/login` | Formulario de inicio de sesión |
| `02_registro.png` | `/register` | Formulario de registro de nuevo usuario |

---

## 03_ADMIN — Panel de Administración

> Sesión: `Administrador LMS` (rol admin)

| Archivo | Ruta | Descripción |
|---------|------|-------------|
| `01_dashboard.png` | `/admin` | Dashboard: ventas, inscripciones, actividad reciente |
| `02_cursos_lista.png` | `/admin/courses` | Lista de cursos con acciones CRUD |
| `02b_curso_detalle.png` | `/admin/courses/1/edit` | Editor de curso: módulos y materiales |
| `03_usuarios.png` | `/admin/users` | Gestión de usuarios y roles |
| `04_estudiantes.png` | `/admin/students` | Vista de estudiantes inscritos |
| `05_ventas.png` | `/admin/sales` | Historial de ventas / órdenes |
| `06_cupones.png` | `/admin/coupons` | Gestión de cupones de descuento |
| `07_contactos.png` | `/admin/contacts` | Mensajes de contacto recibidos |
| `08_auditoria.png` | `/admin/audit` | Log de auditoría de acciones |
| `09_roles.png` | `/admin/roles` | Roles y permisos del sistema |
| `10_configuracion.png` | `/admin/settings` | Configuración general de la plataforma |

---

## 04_ESTUDIANTE — Área del Estudiante

> Sesión: `Estudiante de Prueba` (test@example.com)

| Archivo | Ruta | Descripción |
|---------|------|-------------|
| `01_mi_cuenta.png` | `/mi-cuenta` | Dashboard del estudiante y cursos inscritos |
| `02_catalogo_cursos.png` | `/cursos` | Catálogo visto como estudiante autenticado |
| `03_carrito.png` | `/checkout` | Carrito de compras / checkout |
| `04_aula_virtual.png` | `/mi-cuenta/cursos/bpm-en-industria-alimentaria` | Aula virtual: módulos, progreso y materiales |

---

## Uso en Figma

Estas capturas están organizadas para importarse como marcos de referencia en Figma y generar:
- Wireframes de alta fidelidad
- Mockups por flujo de usuario (visitante → registro → compra → aprendizaje)
- Mockups del flujo administrador (gestión de cursos, usuarios, ventas)

# Diagrama de arquitectura - JM y JS Alimentos LMS

Fecha: 2026-06-10

```mermaid
flowchart TD
    U["Usuario publico / alumno"] --> WEB["Laravel Web UI"]
    A["Administrador"] --> ADMIN["Panel administrativo"]
    WEB --> AUTH["Autenticacion y sesiones"]
    ADMIN --> AUTH
    WEB --> CATALOG["Catalogo de cursos"]
    WEB --> CART["Carrito y Stripe Checkout"]
    WEB --> CLASSROOM["Aula virtual"]
    WEB --> CHAT["Asistente IA"]
    ADMIN --> LMS["Gestion LMS: cursos, modulos, materiales"]
    ADMIN --> SALES["Ventas, cupones y estudiantes"]
    ADMIN --> AUDIT["Auditoria y configuracion"]
    CATALOG --> DB["Base de datos MySQL/SQLite"]
    CART --> DB
    CLASSROOM --> DB
    LMS --> DB
    SALES --> DB
    AUDIT --> DB
    CLASSROOM --> STORAGE["Storage privado de materiales"]
    LMS --> STORAGE
    CHAT --> GEMINI["Google Gemini API"]
    CART --> STRIPE["Stripe Checkout / Webhooks"]
    WEB --> ASSETS["Vite / React / CSS / assets publicos"]
```

## Componentes principales

- Laravel 12 como backend MVC y capa de rutas web/API.
- MySQL en despliegue local XAMPP; SQLite para pruebas automatizadas.
- React/Vite para el widget de asistente IA.
- Storage privado para materiales descargables del aula.
- Google Gemini consumido solo desde backend para no exponer la clave al navegador.
- Stripe Checkout procesa los pagos fuera de la plataforma y confirma ventas mediante retorno seguro y webhooks firmados.

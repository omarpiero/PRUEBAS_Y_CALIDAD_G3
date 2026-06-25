# Control de Versiones y Gestión del Repositorio (Capítulo 9)

> **Referencia al Informe Final:** Cubre el Capítulo 9 (Control de Versiones y Gestión del Repositorio). Muestra la evidencia directa del trabajo colaborativo.

## 9.1. Repositorio del Proyecto
El código fuente íntegro del sistema LMS está alojado en GitHub. La plataforma funge como el Single Source of Truth (SSOT).
- **Enlace:** `https://github.com/ROGERCanchumanyaUC/pruebas-calidad-grupo-03.git`

## 9.2. Estrategia de Control de Versiones
El proyecto adopta un modelo ágil basado en flujos simplificados (Trunk-Based Development adaptado) soportado por Git.
- **Micro-Commits:** Las integraciones son granulares (ej. un commit para aislar la actualización del componente `AdminCourseCrudTest.php` o para inyectar `AuditService`).
- **Idempotencia Local:** Antes de cada `git push`, el equipo ejecuta la suite local (`php artisan test`) garantizando que ninguna confirmación rompa la rama principal.

## 9.3. Gestión de Ramas del Proyecto
- **`main`:** Es la rama principal y refleja la versión en producción o el "release candidate" más estable. Contiene las configuraciones validadas (como la integración definitiva con `StripeService`).
- **`feat_LMS_v2.0`:** Es la rama activa del contexto de desarrollo que contiene los avances masivos (integración de Gemini, mejoras arquitectónicas, nueva documentación). Funciona como la rama de consolidación (Integration Branch) antes del pull request final a producción.

## 9.4. Registro de Entregables Relevantes (Commits)
Los artefactos críticos se han versionado siguiendo convenciones de nombrado semánticas para facilitar la auditoría (trazabilidad):
- `feat: integrate Stripe API and webhook idempotency`
- `test: expand PHPUnit suite to 407 assertions for ISO-29119 compliance`
- `docs: implement spec-driven documentation mapping directly to architecture`
- `refactor: extract AuditService for immutable admin logs`

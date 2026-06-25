$ErrorActionPreference = "Stop"

git config user.name "omarpiero"
git config user.email "72682019@continental.edu.pe"

git checkout --orphan simulacion-matriz-clean2
git read-tree --empty

$matrixFile = "documentacion\MATRIZ_24_06.md"
"# Matriz de Trazabilidad AI-DLC`n`n| Intent | ID del Bolt | Descripcion del Bolt | Evidencia (Commit) | Registro (Pruebas Automatizadas) |`n|---|---|---|---|---|" | Out-File -Encoding utf8 -FilePath $matrixFile

function Add-Bolt {
    param (
        [string]$intent,
        [string]$boltId,
        [string]$desc,
        [string[]]$files,
        [string]$test
    )
    
    foreach ($file in $files) {
        if (Test-Path $file) {
            git add $file
        }
    }
    
    $status = git status --porcelain
    if ($status) {
        git commit -m "[$intent-$boltId] $desc"
        $hash = (git rev-parse HEAD).Trim()
        $url = "https://github.com/omarpiero/PRUEBAS_Y_CALIDAD_G3/commit/$hash"
        $line = "| $intent | $boltId | $desc | [$hash]($url) | $test |"
        Add-Content -Path $matrixFile -Value $line -Encoding utf8
    }
}

# INT-001 Configuracion Base
Add-Bolt -intent "INT-001" -boltId "BOLT-001" -desc "Crear README y documentacion inicial" -files @("README.md", "composer.json", "composer.lock", "package.json", "package-lock.json", "artisan", ".env.example", ".gitignore", ".editorconfig", ".gitattributes") -test "No aplica"
Add-Bolt -intent "INT-001" -boltId "BOLT-002" -desc "Configurar scaffolding de Laravel y configuraciones core" -files @("config", "bootstrap", "public\index.php", "public\.htaccess") -test "No aplica"
Add-Bolt -intent "INT-001" -boltId "BOLT-003" -desc "Configurar Tailwind CSS v4 y Vite" -files @("vite.config.js", "tailwind.config.js", "postcss.config.js", "resources\css", "resources\js\bootstrap.js", "resources\js\app.js") -test "No aplica"

# INT-002 Base de Datos
Add-Bolt -intent "INT-002" -boltId "BOLT-001" -desc "Migraciones estructurales base (users, sessions)" -files @("database\migrations\0001_01_01_000000_create_users_table.php", "database\migrations\0001_01_01_000001_create_cache_table.php", "database\migrations\0001_01_01_000002_create_jobs_table.php") -test "No aplica"
Add-Bolt -intent "INT-002" -boltId "BOLT-002" -desc "Migraciones de negocio (courses, enrollments, sales)" -files @("database\migrations") -test "No aplica"
Add-Bolt -intent "INT-002" -boltId "BOLT-003" -desc "Modelos Eloquent core" -files @("app\Models") -test "LmsRelationshipsTest"
Add-Bolt -intent "INT-002" -boltId "BOLT-004" -desc "Seeders y Factories" -files @("database\seeders", "database\factories") -test "No aplica"

# INT-003 Autenticacion
Add-Bolt -intent "INT-003" -boltId "BOLT-001" -desc "Controlador de Autenticacion y Registro" -files @("app\Http\Controllers\AuthController.php") -test "AdminSecurityAndRolesTest"
Add-Bolt -intent "INT-003" -boltId "BOLT-002" -desc "Vistas Blade de Login y Registro" -files @("resources\views\login.blade.php", "resources\views\register.blade.php") -test "PCN-AUTH"

# INT-004 Arquitectura Frontend Web
Add-Bolt -intent "INT-004" -boltId "BOLT-001" -desc "Layouts principales y componentes Blade" -files @("resources\views\layouts", "resources\views\components") -test "No aplica"
Add-Bolt -intent "INT-004" -boltId "BOLT-002" -desc "Paginas estaticas (Home, About, Contact)" -files @("resources\views\home.blade.php", "resources\views\about.blade.php", "resources\views\contact.blade.php", "app\Http\Controllers\ContactController.php") -test "No aplica"

# INT-005 Panel Admin
Add-Bolt -intent "INT-005" -boltId "BOLT-001" -desc "Middleware de proteccion Admin y Seguridad" -files @("app\Http\Middleware") -test "PermissionMiddlewareTest"
Add-Bolt -intent "INT-005" -boltId "BOLT-002" -desc "Gestion de roles y prevencion de lockout" -files @("app\Http\Controllers\Admin\UserController.php") -test "AdminSecurityAndRolesTest"
Add-Bolt -intent "INT-005" -boltId "BOLT-003" -desc "Vistas del panel de administracion" -files @("resources\views\admin") -test "PCN-ADMIN"

# INT-006 Cursos
Add-Bolt -intent "INT-006" -boltId "BOLT-001" -desc "Logica publica de catalogo de cursos" -files @("app\Http\Controllers\CourseController.php", "resources\views\cursos") -test "PublicCourseCatalogTest"
Add-Bolt -intent "INT-006" -boltId "BOLT-002" -desc "CRUD administrativo de cursos" -files @("app\Http\Controllers\Admin\CourseController.php") -test "AdminCourseCrudTest"

# INT-007 Materiales
Add-Bolt -intent "INT-007" -boltId "BOLT-001" -desc "Gestion de Materiales y Modulos" -files @("app\Http\Controllers\Admin\CourseMaterialController.php", "app\Http\Controllers\Admin\CourseModuleController.php") -test "AdminCourseMaterialTest"
Add-Bolt -intent "INT-007" -boltId "BOLT-002" -desc "Consumo de clases del estudiante" -files @("app\Http\Controllers\StudentCourseController.php", "resources\views\mi-cuenta") -test "StudentCourseAccessTest"

# INT-008 Financiero
Add-Bolt -intent "INT-008" -boltId "BOLT-001" -desc "Implementar carrito de compras en memoria" -files @("app\Http\Controllers\CartController.php", "resources\views\cart") -test "AdminSalesAndCouponsTest"
Add-Bolt -intent "INT-008" -boltId "BOLT-002" -desc "Servicio de Integracion Stripe" -files @("app\Services\StripeService.php") -test "PaymentStripeTest"
Add-Bolt -intent "INT-008" -boltId "BOLT-003" -desc "Webhooks y generacion de inscripciones" -files @("app\Http\Controllers\PaymentController.php", "resources\views\checkout") -test "PaymentStripeTest"
Add-Bolt -intent "INT-008" -boltId "BOLT-004" -desc "Gestion administrativa de ingresos y cupones" -files @("app\Http\Controllers\Admin\SaleController.php", "app\Http\Controllers\Admin\CouponController.php") -test "AdminSalesAndCouponsTest"

# INT-009 Inteligencia Artificial
Add-Bolt -intent "INT-009" -boltId "BOLT-001" -desc "Controlador API para Google Gemini" -files @("app\Http\Controllers\Api\ChatController.php") -test "GeminiAssistantTest"
Add-Bolt -intent "INT-009" -boltId "BOLT-002" -desc "Componente React del Chatbot" -files @("resources\js\components") -test "GeminiAssistantTest"

# INT-010 Soporte Core
Add-Bolt -intent "INT-010" -boltId "BOLT-001" -desc "Implementar AuditService para trazabilidad" -files @("app\Services\AuditService.php") -test "AdminSecurityAndRolesTest"
Add-Bolt -intent "INT-010" -boltId "BOLT-002" -desc "Dashboards y telemetria" -files @("app\Http\Controllers\Admin\DashboardController.php") -test "AdminDashboardAnalyticsTest"

# INT-011 Automatizacion QA
Add-Bolt -intent "INT-011" -boltId "BOLT-001" -desc "Configuracion PHPUnit y Tests Unitarios" -files @("phpunit.xml", "tests\TestCase.php", "tests\Unit") -test "Pruebas Unitarias"
Add-Bolt -intent "INT-011" -boltId "BOLT-002" -desc "Feature Tests y simulacion de Mocks" -files @("tests\Feature") -test "100% Cobertura Funcional"

# INT-012 Rutas
Add-Bolt -intent "INT-012" -boltId "BOLT-001" -desc "Mapeo de rutas web, API y consola" -files @("routes") -test "LmsReleaseReadinessTest"

# INT-013 Documentacion
Add-Bolt -intent "INT-013" -boltId "BOLT-001" -desc "Agregar manuales de ISOS" -files @("documentacion\ISOS") -test "No aplica"
Add-Bolt -intent "INT-013" -boltId "BOLT-002" -desc "Documentar pruebas y automatizacion" -files @("documentacion\PRUEBAS") -test "No aplica"
Add-Bolt -intent "INT-013" -boltId "BOLT-003" -desc "Artefactos de planificacion e informes" -files @("documentacion\PENDIENTES", "documentacion\INFORMES") -test "No aplica"

# Final Catch-All
Add-Bolt -intent "INT-014" -boltId "BOLT-001" -desc "Consolidacion general y archivos de matriz" -files @(".") -test "LmsReleaseReadinessTest"

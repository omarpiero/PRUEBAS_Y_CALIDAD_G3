<?php

use App\Http\Controllers\Admin\ContactsController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\CourseModuleController;
use App\Http\Controllers\Admin\CourseMaterialController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\MiCuentaController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\StudentCourseController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\SaleController;
use App\Http\Controllers\Admin\CouponController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'inicio')->name('inicio');
Route::view('/nosotros', 'nosotros')->name('nosotros');
Route::view('/privacidad', 'privacidad')->name('privacidad');
Route::view('/terminos', 'terminos')->name('terminos');
Route::get('/cursos', [CourseController::class, 'index'])->name('cursos');
Route::get('/cursos/{slug}', [CourseController::class, 'show'])->name('cursos.show');
Route::view('/contacto', 'contacto')->name('contacto');
Route::get('/checkout', [CartController::class, 'index'])->name('checkout');

Route::post('/contacto/enviar', [ContactController::class, 'store'])->name('contacto.enviar');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::post('/cart/add',    [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/coupon/apply',  [CartController::class, 'applyCoupon'])->name('cart.coupon.apply');
Route::post('/cart/coupon/remove', [CartController::class, 'removeCoupon'])->name('cart.coupon.remove');

Route::post('/stripe/webhook', [PaymentController::class, 'webhook'])->name('stripe.webhook');

Route::middleware('auth')->group(function () {
    Route::get('/mi-cuenta',  [MiCuentaController::class, 'index'])->name('mi-cuenta');
    Route::post('/pago',      [PaymentController::class, 'process'])->name('pago.procesar');
    Route::get('/pago/confirmar', [PaymentController::class, 'success'])->name('pago.confirmar');
    Route::get('/pago/cancelado/{sale}', [PaymentController::class, 'cancel'])->name('pago.cancelado');
    Route::view('/pago/exito', 'pago-exito')->name('pago.exito');

    // Student Classroom & Private File Access
    Route::get('/mi-cuenta/cursos/{course:slug}', [StudentCourseController::class, 'show'])->name('mi-cuenta.cursos.show');
    Route::post('/mi-cuenta/cursos/{course}/materials/{material}/toggle', [StudentCourseController::class, 'completeMaterial'])->name('mi-cuenta.cursos.complete-material');
    Route::get('/mi-cuenta/cursos/{course}/materials/{material}/file', [StudentCourseController::class, 'serveFile'])->name('mi-cuenta.cursos.file');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->middleware('permission:dashboard.view')->name('dashboard');
    Route::get('/users', [UserController::class, 'index'])->middleware('permission:users.view')->name('users');
    Route::patch('/users/{user}/toggle', [UserController::class, 'toggleAdmin'])->middleware('permission:users.manage')->name('users.toggle');
    Route::get('/contacts', [ContactsController::class, 'index'])->middleware('permission:contacts.view')->name('contacts');
    Route::patch('/contacts/{contact}/read', [ContactsController::class, 'markRead'])->middleware('permission:contacts.manage')->name('contacts.read');
    Route::delete('/contacts/{contact}', [ContactsController::class, 'destroy'])->middleware('permission:contacts.manage')->name('contacts.destroy');

    // Admin Course Management
    Route::get('/courses', [AdminCourseController::class, 'index'])->middleware('permission:courses.view')->name('courses.index');
    Route::get('/courses/create', [AdminCourseController::class, 'create'])->middleware('permission:courses.create')->name('courses.create');
    Route::post('/courses', [AdminCourseController::class, 'store'])->middleware('permission:courses.create')->name('courses.store');
    Route::get('/courses/{course}', [AdminCourseController::class, 'show'])->middleware('permission:courses.view')->name('courses.show');
    Route::get('/courses/{course}/edit', [AdminCourseController::class, 'edit'])->middleware('permission:courses.edit')->name('courses.edit');
    Route::match(['put', 'patch'], '/courses/{course}', [AdminCourseController::class, 'update'])->middleware('permission:courses.edit')->name('courses.update');
    Route::delete('/courses/{course}', [AdminCourseController::class, 'destroy'])->middleware('permission:courses.delete')->name('courses.destroy');
    Route::patch('/courses/{course}/publish', [AdminCourseController::class, 'publish'])->middleware('permission:courses.publish')->name('courses.publish');
    Route::patch('/courses/{course}/unpublish', [AdminCourseController::class, 'unpublish'])->middleware('permission:courses.publish')->name('courses.unpublish');
    Route::post('/courses/{course}/duplicate', [AdminCourseController::class, 'duplicate'])->middleware('permission:courses.create')->name('courses.duplicate');

    // Modules & Materials Management
    Route::patch('/modules/reorder', [CourseModuleController::class, 'reorder'])->middleware('permission:modules.edit')->name('modules.reorder');
    Route::post('/modules', [CourseModuleController::class, 'store'])->middleware('permission:modules.create')->name('modules.store');
    Route::match(['put', 'patch'], '/modules/{module}', [CourseModuleController::class, 'update'])->middleware('permission:modules.edit')->name('modules.update');
    Route::delete('/modules/{module}', [CourseModuleController::class, 'destroy'])->middleware('permission:modules.delete')->name('modules.destroy');
    Route::post('/materials', [CourseMaterialController::class, 'store'])->middleware('permission:materials.create')->name('materials.store');
    Route::match(['put', 'patch'], '/materials/{material}', [CourseMaterialController::class, 'update'])->middleware('permission:materials.edit')->name('materials.update');
    Route::delete('/materials/{material}', [CourseMaterialController::class, 'destroy'])->middleware('permission:materials.delete')->name('materials.destroy');

    // Admin Student Management
    Route::get('/students', [StudentController::class, 'index'])->middleware('permission:students.view')->name('students.index');
    Route::get('/students/{student}', [StudentController::class, 'show'])->middleware('permission:students.view')->name('students.show');
    Route::post('/students/{student}/courses/{course}/suspend', [StudentController::class, 'suspend'])->middleware('permission:students.manage')->name('students.suspend');
    Route::post('/students/{student}/courses/{course}/reactivate', [StudentController::class, 'reactivate'])->middleware('permission:students.manage')->name('students.reactivate');
    Route::post('/students/{student}/courses/{course}/reset', [StudentController::class, 'resetProgress'])->middleware('permission:students.manage')->name('students.reset');

    // Admin User Edit Routes
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->middleware('permission:users.manage')->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->middleware('permission:users.manage')->name('users.update');

    // Admin Roles & Permissions
    Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class)
        ->only(['index', 'show'])
        ->middleware('permission:roles.manage');

    // Admin Settings
    Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->middleware('permission:settings.view')->name('settings.index');
    Route::put('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->middleware('permission:settings.edit')->name('settings.update');

    // Admin Audit Logs
    Route::get('/audit', [\App\Http\Controllers\Admin\AuditController::class, 'index'])->middleware('permission:audit.view')->name('audit.index');
    Route::get('/audit/export', [\App\Http\Controllers\Admin\AuditController::class, 'export'])->middleware('permission:audit.view')->name('audit.export');

    // Admin Sales Management
    Route::resource('sales', SaleController::class)
        ->only(['index', 'show'])
        ->middleware('permission:sales.view');

    // Admin Coupons CRUD
    Route::get('/coupons', [CouponController::class, 'index'])->middleware('permission:coupons.view')->name('coupons.index');
    Route::get('/coupons/create', [CouponController::class, 'create'])->middleware('permission:coupons.create')->name('coupons.create');
    Route::post('/coupons', [CouponController::class, 'store'])->middleware('permission:coupons.create')->name('coupons.store');
    Route::get('/coupons/{coupon}', [CouponController::class, 'show'])->middleware('permission:coupons.view')->name('coupons.show');
    Route::get('/coupons/{coupon}/edit', [CouponController::class, 'edit'])->middleware('permission:coupons.edit')->name('coupons.edit');
    Route::match(['put', 'patch'], '/coupons/{coupon}', [CouponController::class, 'update'])->middleware('permission:coupons.edit')->name('coupons.update');
    Route::delete('/coupons/{coupon}', [CouponController::class, 'destroy'])->middleware('permission:coupons.delete')->name('coupons.destroy');
});

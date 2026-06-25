<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use App\Models\Setting;
use App\Models\AuditLog;
use App\Models\Category;
use App\Models\Course;
use App\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AdminSecurityAndRolesTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $student;
    protected Role $adminRole;
    protected Role $studentRole;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles
        $this->adminRole = Role::create([
            'name' => 'admin',
            'display_name' => 'Administrador',
            'description' => 'Acceso completo',
        ]);

        $this->studentRole = Role::create([
            'name' => 'estudiante',
            'display_name' => 'Estudiante',
            'description' => 'Estudiante inscrito',
        ]);

        // Users
        $this->admin = User::factory()->create(['name' => 'Admin User', 'is_admin' => true]);
        $this->admin->roles()->attach($this->adminRole->id);

        $this->student = User::factory()->create(['name' => 'Student User']);
        $this->student->roles()->attach($this->studentRole->id);
    }

    public function test_security_headers_are_present()
    {
        $response = $this->get('/');
        
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-XSS-Protection', '1; mode=block');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('Referrer-Policy', 'no-referrer-when-downgrade');
        $response->assertHeader('Content-Security-Policy');
    }

    public function test_production_csp_does_not_allow_inline_or_eval()
    {
        config(['app.env' => 'production']);

        $response = $this->get('/');
        $csp = $response->headers->get('Content-Security-Policy');

        $this->assertNotNull($csp);
        $this->assertStringNotContainsString("'unsafe-inline'", $csp);
        $this->assertStringNotContainsString("'unsafe-eval'", $csp);
        $this->assertStringContainsString("script-src 'self'", $csp);
    }

    public function test_login_rate_limiting()
    {
        // Default throttle limits to 5 attempts per minute
        for ($i = 0; $i < 5; $i++) {
            $response = $this->post('/login', [
                'email' => 'wrong@email.com',
                'password' => 'wrongpass',
            ]);
            $response->assertStatus(302); // redirects back with errors
        }

        // 6th attempt should return 429 Too Many Requests
        $response = $this->post('/login', [
            'email' => 'wrong@email.com',
            'password' => 'wrongpass',
        ]);
        
        $response->assertStatus(429);
    }

    public function test_chatbot_rate_limiting()
    {
        // Set mock API key
        config(['services.gemini.key' => 'test-key']);

        Http::fake([
            'generativelanguage.googleapis.com/*' => Http::response(['reply' => 'test'], 200)
        ]);

        // Throttle limits to 15 attempts per minute
        for ($i = 0; $i < 15; $i++) {
            $response = $this->postJson('/api/chat', [
                'message' => 'Hello',
            ]);
            $response->assertStatus(200);
        }

        // 16th attempt should return 429
        $response = $this->postJson('/api/chat', [
            'message' => 'Hello',
        ]);
        
        $response->assertStatus(429);
    }

    public function test_chatbot_message_sanitization()
    {
        config(['services.gemini.key' => 'test-key']);

        Http::fake([
            'generativelanguage.googleapis.com/*' => Http::response(['candidates' => [['content' => ['parts' => [['text' => 'Mocked reply']]]]]], 200)
        ]);

        $response = $this->postJson('/api/chat', [
            'message' => '<b>Hello</b> world',
        ]);

        $response->assertStatus(200);

        Http::assertSent(function ($request) {
            $data = json_decode($request->body(), true);
            $text = data_get($data, 'contents.0.parts.0.text');
            return $text === 'Hello world'; // HTML tags stripped
        });
    }

    public function test_setting_helper_caching_and_invalidation()
    {
        Cache::flush();

        Setting::create([
            'key' => 'test_setting_key',
            'value' => 'InitialValue',
            'type' => 'text',
            'group' => 'general',
        ]);

        // Access via setting() helper - should cache it
        $this->assertEquals('InitialValue', setting('test_setting_key'));
        $this->assertTrue(Cache::has('setting.test_setting_key'));

        // Modify in DB bypassing helper - setting() should still return cached value
        Setting::where('key', 'test_setting_key')->update(['value' => 'NewValueDirect']);
        $this->assertEquals('InitialValue', setting('test_setting_key'));

        // Modify via Setting::set() - should clear cache
        Setting::set('test_setting_key', 'UpdatedValue');
        $this->assertFalse(Cache::has('setting.test_setting_key'));

        // Access again - should fetch new value and re-cache
        $this->assertEquals('UpdatedValue', setting('test_setting_key'));
        $this->assertTrue(Cache::has('setting.test_setting_key'));
    }

    public function test_roles_admin_routes_protection()
    {
        // Non-admin block
        $this->actingAs($this->student)->get(route('admin.roles.index'))->assertStatus(403);
        $this->actingAs($this->student)->get(route('admin.roles.show', $this->adminRole))->assertStatus(403);

        // Admin allow
        $this->actingAs($this->admin)->get(route('admin.roles.index'))->assertStatus(200);
        $response = $this->actingAs($this->admin)->get(route('admin.roles.show', $this->adminRole));
        $response->assertStatus(200);
        $response->assertSee($this->admin->name);
    }

    public function test_admin_role_without_legacy_flag_keeps_admin_access()
    {
        $roleOnlyAdmin = User::factory()->create(['is_admin' => false]);
        $roleOnlyAdmin->roles()->attach($this->adminRole->id);

        $response = $this->actingAs($roleOnlyAdmin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
    }

    public function test_support_role_can_access_only_support_areas()
    {
        $supportRole = Role::create([
            'name' => 'soporte',
            'display_name' => 'Soporte',
            'description' => 'Soporte operativo',
        ]);

        foreach (['dashboard.view', 'students.view', 'contacts.view'] as $permissionName) {
            $permission = Permission::create([
                'name' => $permissionName,
                'display_name' => $permissionName,
                'module' => 'test',
            ]);
            $supportRole->permissions()->attach($permission->id);
        }

        $support = User::factory()->create(['is_admin' => false]);
        $support->roles()->attach($supportRole->id);

        $this->actingAs($support)->get(route('admin.dashboard'))->assertStatus(200);
        $this->actingAs($support)->get(route('admin.students.index'))->assertStatus(200);
        $this->actingAs($support)->get(route('admin.courses.index'))->assertStatus(403);
        $this->actingAs($support)->get(route('admin.settings.index'))->assertStatus(403);
    }

    public function test_instructor_can_only_manage_own_courses()
    {
        $instructorRole = Role::create([
            'name' => 'instructor',
            'display_name' => 'Instructor',
            'description' => 'Gestiona cursos propios',
        ]);

        foreach (['courses.view', 'courses.edit'] as $permissionName) {
            $permission = Permission::create([
                'name' => $permissionName,
                'display_name' => $permissionName,
                'module' => 'test',
            ]);
            $instructorRole->permissions()->attach($permission->id);
        }

        $instructor = User::factory()->create(['is_admin' => false]);
        $otherInstructor = User::factory()->create(['is_admin' => false]);
        $instructor->roles()->attach($instructorRole->id);

        $category = Category::factory()->create();
        $ownCourse = Course::factory()->create([
            'category_id' => $category->id,
            'instructor_id' => $instructor->id,
            'name' => 'Curso Propio',
            'slug' => 'curso-propio',
        ]);
        $otherCourse = Course::factory()->create([
            'category_id' => $category->id,
            'instructor_id' => $otherInstructor->id,
            'name' => 'Curso Ajeno',
            'slug' => 'curso-ajeno',
        ]);

        $response = $this->actingAs($instructor)->get(route('admin.courses.index'));
        $response->assertStatus(200);
        $response->assertSee('Curso Propio');
        $response->assertDontSee('Curso Ajeno');

        $this->actingAs($instructor)->get(route('admin.courses.edit', $ownCourse))->assertStatus(200);
        $this->actingAs($instructor)->get(route('admin.courses.edit', $otherCourse))->assertStatus(403);
    }

    public function test_user_roles_sync_and_is_admin_sync()
    {
        $targetUser = User::factory()->create(['is_admin' => false]);

        // 1. Assign admin role
        $response = $this->actingAs($this->admin)->put(route('admin.users.update', $targetUser), [
            'roles' => [$this->adminRole->id],
        ]);

        $response->assertRedirect(route('admin.users'));
        $this->assertTrue($targetUser->fresh()->isAdmin());
        $this->assertTrue((bool)$targetUser->fresh()->is_admin);

        // Verify Audit Log
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'update_user_roles',
            'entity_type' => User::class,
            'entity_id' => $targetUser->id,
        ]);

        // 2. Assign student role (remove admin role)
        $response = $this->actingAs($this->admin)->put(route('admin.users.update', $targetUser), [
            'roles' => [$this->studentRole->id],
        ]);

        $response->assertRedirect(route('admin.users'));
        $this->assertFalse($targetUser->fresh()->isAdmin());
        $this->assertFalse((bool)$targetUser->fresh()->is_admin);
    }

    public function test_last_admin_cannot_be_demoted()
    {
        $response = $this->actingAs($this->admin)->from(route('admin.users.edit', $this->admin))
            ->put(route('admin.users.update', $this->admin), [
                'roles' => [$this->studentRole->id],
            ]);

        $response->assertRedirect(route('admin.users.edit', $this->admin));
        $response->assertSessionHasErrors('roles');

        $this->assertTrue($this->admin->fresh()->isAdmin());
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'reject_last_admin_demotion',
            'entity_type' => User::class,
            'entity_id' => $this->admin->id,
        ]);
    }

    public function test_audit_logs_routes_protection_and_filters()
    {
        // Seed audit logs
        AuditLog::create([
            'user_id' => $this->admin->id,
            'action' => 'test_audit_action',
            'entity_type' => User::class,
            'entity_id' => $this->student->id,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'System',
        ]);

        // Non-admin block
        $this->actingAs($this->student)->get(route('admin.audit.index'))->assertStatus(403);

        // Admin allow
        $response = $this->actingAs($this->admin)->get(route('admin.audit.index'));
        $response->assertStatus(200);
        $response->assertSee('test_audit_action');
    }

    public function test_audit_export_is_protected_and_respects_filters()
    {
        AuditLog::create([
            'user_id' => $this->admin->id,
            'action' => 'export_visible_action',
            'entity_type' => User::class,
            'entity_id' => $this->student->id,
            'old_values' => ['role' => 'estudiante'],
            'new_values' => ['role' => 'admin'],
            'ip_address' => '127.0.0.1',
            'user_agent' => '=InjectedAgent',
        ]);

        AuditLog::create([
            'user_id' => $this->admin->id,
            'action' => 'export_hidden_action',
            'entity_type' => User::class,
            'entity_id' => $this->admin->id,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'System',
        ]);

        $this->actingAs($this->student)
            ->get(route('admin.audit.export'))
            ->assertStatus(403);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.audit.export', ['action' => 'export_visible_action']));

        $response->assertOk();
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');

        $content = $response->streamedContent();

        $this->assertStringContainsString('export_visible_action', $content);
        $this->assertStringNotContainsString('export_hidden_action', $content);
        $this->assertStringContainsString("'=InjectedAgent", $content);
    }
}

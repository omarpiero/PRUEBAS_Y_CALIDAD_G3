<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class PermissionMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Register a temporary test route for testing the middleware
        Route::get('/_test/permission-protected', function () {
            return 'success';
        })->middleware(['web', 'permission:test.permission']);
    }

    public function test_guest_is_unauthorized()
    {
        $response = $this->get('/_test/permission-protected');
        $response->assertStatus(401);
    }

    public function test_admin_user_can_bypass_permission_check()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->get('/_test/permission-protected');

        $response->assertStatus(200);
        $response->assertSee('success');
    }

    public function test_user_without_permission_is_forbidden()
    {
        $user = User::factory()->create(['is_admin' => false]);

        $response = $this->actingAs($user)->get('/_test/permission-protected');

        $response->assertStatus(403);
    }

    public function test_user_with_permission_can_access()
    {
        $user = User::factory()->create(['is_admin' => false]);
        $role = Role::create([
            'name' => 'test-role',
            'display_name' => 'Test Role',
            'description' => 'Test Description'
        ]);
        $permission = Permission::create([
            'name' => 'test.permission',
            'display_name' => 'Test Permission',
            'module' => 'test'
        ]);

        $role->permissions()->attach($permission->id);
        $user->roles()->attach($role->id);

        $response = $this->actingAs($user)->get('/_test/permission-protected');

        $response->assertStatus(200);
        $response->assertSee('success');
    }
}

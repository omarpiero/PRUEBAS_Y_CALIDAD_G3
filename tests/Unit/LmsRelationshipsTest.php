<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Course;
use App\Models\CourseMaterial;
use App\Models\CourseModule;
use App\Models\Enrollment;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LmsRelationshipsTest extends TestCase
{
    use RefreshDatabase;

    public function test_course_relationships()
    {
        $category = Category::create([
            'name' => 'Test Cat',
            'slug' => 'test-cat',
            'description' => 'Test Desc'
        ]);

        $course = Course::create([
            'category_id' => $category->id,
            'name' => 'Test Course',
            'slug' => 'test-course',
            'price' => 10.00
        ]);

        $module = CourseModule::create([
            'course_id' => $course->id,
            'name' => 'Test Module'
        ]);

        $material = CourseMaterial::create([
            'module_id' => $module->id,
            'type' => 'texto',
            'title' => 'Test Material'
        ]);

        $this->assertInstanceOf(Category::class, $course->category);
        $this->assertEquals($category->id, $course->category->id);

        $this->assertCount(1, $course->modules);
        $this->assertEquals($module->id, $course->modules->first()->id);

        $this->assertInstanceOf(Course::class, $module->course);
        $this->assertEquals($course->id, $module->course->id);

        $this->assertCount(1, $module->materials);
        $this->assertEquals($material->id, $module->materials->first()->id);

        $this->assertInstanceOf(CourseModule::class, $material->module);
        $this->assertEquals($module->id, $material->module->id);
    }

    public function test_enrollment_relationships()
    {
        $user = User::factory()->create();
        
        $category = Category::create([
            'name' => 'Test Cat',
            'slug' => 'test-cat',
            'description' => 'Test Desc'
        ]);

        $course = Course::create([
            'category_id' => $category->id,
            'name' => 'Test Course',
            'slug' => 'test-course',
            'price' => 10.00
        ]);

        $enrollment = Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'status' => 'activo',
            'progress' => 0
        ]);

        $this->assertInstanceOf(User::class, $enrollment->user);
        $this->assertEquals($user->id, $enrollment->user->id);

        $this->assertInstanceOf(Course::class, $enrollment->course);
        $this->assertEquals($course->id, $enrollment->course->id);
        
        $this->assertCount(1, $user->enrollments);
        $this->assertEquals($enrollment->id, $user->enrollments->first()->id);
    }

    public function test_roles_and_permissions_relationships()
    {
        $user = User::factory()->create();
        $role = Role::create([
            'name' => 'test-role',
            'display_name' => 'Test Role',
            'description' => 'Test Description'
        ]);
        $permission = Permission::create([
            'name' => 'test-perm',
            'display_name' => 'Test Perm',
            'module' => 'test'
        ]);

        $role->permissions()->attach($permission->id);
        $user->roles()->attach($role->id);

        $this->assertCount(1, $user->roles);
        $this->assertEquals($role->id, $user->roles->first()->id);

        $this->assertCount(1, $role->permissions);
        $this->assertEquals($permission->id, $role->permissions->first()->id);
    }
}

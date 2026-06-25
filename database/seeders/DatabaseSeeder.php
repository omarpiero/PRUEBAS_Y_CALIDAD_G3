<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Roles and Permissions (must run first)
        $this->call(RoleAndPermissionSeeder::class);

        // 2. Create admin users
        $admin1 = User::updateOrCreate(
            ['email' => '72682019@continental.edu.pe'],
            [
                'name' => 'Administrador LMS',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_admin' => true,
            ],
        );
        $admin1->assignRole('admin');

        $admin2 = User::updateOrCreate(
            ['email' => '71993692@continental.edu.pe'],
            [
                'name' => 'Giancarlo Guerreros Cordova',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_admin' => true,
            ],
        );
        $admin2->assignRole('admin');

        // 3. Create test student user
        $student = User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Estudiante de Prueba',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
        );
        $student->assignRole('estudiante');

        // 4. Seed categories and courses
        $this->call(CourseSeeder::class);

        // 5. Seed settings
        $this->call(SettingSeeder::class);

        // 6. Seed LMS demo data for QA and smoke testing
        $this->call(DemoLmsSeeder::class);
    }
}

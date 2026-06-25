<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Role::updateOrCreate(['name' => 'admin'], [
            'display_name' => 'Administrador',
            'description' => 'Acceso total al sistema. Gestiona cursos, usuarios, ventas, configuracion y auditoria.',
        ]);

        $instructor = Role::updateOrCreate(['name' => 'instructor'], [
            'display_name' => 'Instructor',
            'description' => 'Gestiona sus cursos, modulos y materiales. No puede acceder a configuracion global.',
        ]);

        $soporte = Role::updateOrCreate(['name' => 'soporte'], [
            'display_name' => 'Soporte',
            'description' => 'Ve estudiantes y gestiona incidencias. No puede modificar cursos.',
        ]);

        Role::updateOrCreate(['name' => 'estudiante'], [
            'display_name' => 'Estudiante',
            'description' => 'Accede a los cursos en los que esta inscrito.',
        ]);

        $permissions = [
            ['name' => 'dashboard.view', 'display_name' => 'Ver Dashboard', 'module' => 'dashboard'],

            ['name' => 'courses.view', 'display_name' => 'Ver Cursos', 'module' => 'cursos'],
            ['name' => 'courses.create', 'display_name' => 'Crear Cursos', 'module' => 'cursos'],
            ['name' => 'courses.edit', 'display_name' => 'Editar Cursos', 'module' => 'cursos'],
            ['name' => 'courses.delete', 'display_name' => 'Eliminar Cursos', 'module' => 'cursos'],
            ['name' => 'courses.publish', 'display_name' => 'Publicar Cursos', 'module' => 'cursos'],

            ['name' => 'modules.view', 'display_name' => 'Ver Modulos', 'module' => 'modulos'],
            ['name' => 'modules.create', 'display_name' => 'Crear Modulos', 'module' => 'modulos'],
            ['name' => 'modules.edit', 'display_name' => 'Editar Modulos', 'module' => 'modulos'],
            ['name' => 'modules.delete', 'display_name' => 'Eliminar Modulos', 'module' => 'modulos'],

            ['name' => 'materials.view', 'display_name' => 'Ver Materiales', 'module' => 'materiales'],
            ['name' => 'materials.create', 'display_name' => 'Crear Materiales', 'module' => 'materiales'],
            ['name' => 'materials.edit', 'display_name' => 'Editar Materiales', 'module' => 'materiales'],
            ['name' => 'materials.delete', 'display_name' => 'Eliminar Materiales', 'module' => 'materiales'],

            ['name' => 'students.view', 'display_name' => 'Ver Estudiantes', 'module' => 'estudiantes'],
            ['name' => 'students.manage', 'display_name' => 'Gestionar Estudiantes', 'module' => 'estudiantes'],

            ['name' => 'sales.view', 'display_name' => 'Ver Ventas', 'module' => 'ventas'],
            ['name' => 'sales.manage', 'display_name' => 'Gestionar Ventas', 'module' => 'ventas'],

            ['name' => 'coupons.view', 'display_name' => 'Ver Cupones', 'module' => 'cupones'],
            ['name' => 'coupons.create', 'display_name' => 'Crear Cupones', 'module' => 'cupones'],
            ['name' => 'coupons.edit', 'display_name' => 'Editar Cupones', 'module' => 'cupones'],
            ['name' => 'coupons.delete', 'display_name' => 'Eliminar Cupones', 'module' => 'cupones'],

            ['name' => 'users.view', 'display_name' => 'Ver Usuarios', 'module' => 'usuarios'],
            ['name' => 'users.manage', 'display_name' => 'Gestionar Usuarios', 'module' => 'usuarios'],
            ['name' => 'roles.manage', 'display_name' => 'Gestionar Roles', 'module' => 'usuarios'],

            ['name' => 'settings.view', 'display_name' => 'Ver Configuracion', 'module' => 'configuracion'],
            ['name' => 'settings.edit', 'display_name' => 'Editar Configuracion', 'module' => 'configuracion'],

            ['name' => 'audit.view', 'display_name' => 'Ver Auditoria', 'module' => 'auditoria'],

            ['name' => 'contacts.view', 'display_name' => 'Ver Contactos', 'module' => 'contactos'],
            ['name' => 'contacts.manage', 'display_name' => 'Gestionar Contactos', 'module' => 'contactos'],
        ];

        $permissionModels = [];

        foreach ($permissions as $permission) {
            $permissionModels[$permission['name']] = Permission::updateOrCreate(
                ['name' => $permission['name']],
                [
                    'display_name' => $permission['display_name'],
                    'module' => $permission['module'],
                ]
            );
        }

        $admin->permissions()->sync(collect($permissionModels)->pluck('id')->all());

        $instructor->permissions()->sync($this->permissionIds($permissionModels, [
            'dashboard.view',
            'courses.view',
            'courses.create',
            'courses.edit',
            'courses.publish',
            'modules.view',
            'modules.create',
            'modules.edit',
            'modules.delete',
            'materials.view',
            'materials.create',
            'materials.edit',
            'materials.delete',
            'students.view',
        ]));

        $soporte->permissions()->sync($this->permissionIds($permissionModels, [
            'dashboard.view',
            'students.view',
            'students.manage',
            'contacts.view',
            'contacts.manage',
        ]));
    }

    /**
     * @param array<string, Permission> $permissions
     * @param list<string> $names
     * @return list<int>
     */
    private function permissionIds(array $permissions, array $names): array
    {
        return collect($names)->map(fn (string $name) => $permissions[$name]->id)->all();
    }
}

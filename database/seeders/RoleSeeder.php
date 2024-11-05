<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role1 = Role::create(['name' => 'Super-admin']);
        $role2 = Role::create(['name' => 'Presidente']);
        $role3 = Role::create(['name' => 'Secretario']);
        $role4 = Role::create(['name' => 'Miembro']);

        Permission::create(['name' => 'admin.home', 'section' => 'Estadística', 'description' => 'Ver dashboard'])->syncRoles([$role1, $role2, $role3, $role4]);

        Permission::create(['name' => 'admin.manage.profile', 'section' => 'Configuración', 'description' => 'Administrar perfil personal'])->syncRoles([$role1, $role2, $role3, $role4]);
        Permission::create(['name' => 'admin.manage.yape', 'section' => 'Configuración', 'description' => 'Administrar cuenta Yape'])->syncRoles([$role1]);

        Permission::create(['name' => 'admin.roles', 'section' => 'Roles', 'description' => 'Ver listado de roles'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.roles.create', 'section' => 'Roles', 'description' => 'Crear roles'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.roles.edit', 'section' => 'Roles', 'description' => 'Editar roles'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.roles.assign-permission', 'section' => 'Roles', 'description' => 'Asignar permisos al rol'])->syncRoles([$role1]);

        Permission::create(['name' => 'admin.users', 'section' => 'Usuarios', 'description' => 'Ver listado de usuarios'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.users.create', 'section' => 'Usuarios', 'description' => 'Crear usuarios'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.users.edit', 'section' => 'Usuarios', 'description' => 'Editar usuarios'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.users.delete', 'section' => 'Usuarios', 'description' => 'Eliminar usuarios'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.users.assign-role', 'section' => 'Usuarios', 'description' => 'Asignar roles al usuario'])->syncRoles([$role1]);

        Permission::create(['name' => 'admin.groups', 'section' => 'Grupos', 'description' => 'Ver listado de grupos'])->syncRoles([$role1, $role2, $role3]);
        Permission::create(['name' => 'admin.groups.create', 'section' => 'Grupos', 'description' => 'Crear grupos'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'admin.groups.edit', 'section' => 'Grupos', 'description' => 'Editar grupos'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'admin.groups.delete', 'section' => 'Grupos', 'description' => 'Eliminar grupos'])->syncRoles([$role1]);

        Permission::create(['name' => 'admin.attendance.create', 'section' => 'Asistencia', 'description' => 'Crear asistencia'])->syncRoles([$role1, $role2]);
    }
}

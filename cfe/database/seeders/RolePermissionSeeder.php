<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ==================== PERMISOS ====================

        // 1. Permisos para préstamos
        $this->createPermissions([
            'ver todos los prestamos',
            'ver mis prestamos',
            'crear prestamos',
            'editar prestamos',
            'eliminar prestamos',
            'aprobar prestamos'
        ], 'prestamos');

        // 2. Permisos para devoluciones
        $this->createPermissions([
            'ver todas las devoluciones',
            'ver mis devoluciones',
            'crear devoluciones',
            'editar devoluciones',
            'eliminar devoluciones'
        ], 'devoluciones');

        // 3. Permisos para resguardos
        $this->createPermissions([
            'ver todos los resguardos',
            'ver mis resguardos',
            'crear resguardos',
            'editar resguardos',
            'eliminar resguardos'
        ], 'resguardos');

        // 4. Permisos administrativos (nuevos permisos mejorados)
        $permisosAdmin = [
            'gestionar usuarios' => ['crear usuarios', 'editar usuarios', 'eliminar usuarios', 'asignar roles'],
            'gestionar roles' => ['crear roles', 'editar roles', 'eliminar roles', 'asignar permisos'],
            'gestionar configuracion' => ['modificar configuracion sistema', 'backup base datos']
        ];

        foreach ($permisosAdmin as $categoria => $permisos) {
            foreach ($permisos as $permiso) {
                Permission::firstOrCreate(['name' => $permiso, 'group' => $categoria]);
            }
        }

        // ==================== ROLES ====================

        // 1. Rol Empleado
        $empleado = Role::firstOrCreate(['name' => 'empleado']);
        $empleado->syncPermissions([
            'ver mis prestamos',
            'ver mis devoluciones',
            'ver mis resguardos'
        ]);

        // 2. Rol Encargado
        $encargado = Role::firstOrCreate(['name' => 'encargado']);
        $encargado->syncPermissions([
            'ver todos los prestamos',
            'crear prestamos',
            'editar prestamos',
            'aprobar prestamos',
            'ver todas las devoluciones',
            'crear devoluciones',
            'editar devoluciones',
            'ver todos los resguardos',
            'crear resguardos',
            'editar resguardos',
            'gestionar usuarios' // Nuevo permiso agregado
        ]);

        // 3. Rol Admin
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions(Permission::all());

        // 4. (Opcional) Super Admin - Para desarrollo
        // $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        // $superAdmin->syncPermissions(Permission::all());
    }

    /**
     * Crea permisos y los agrupa por categoría
     */
    protected function createPermissions(array $permisos, string $categoria)
    {
        foreach ($permisos as $permiso) {
            Permission::firstOrCreate([
                'name' => $permiso,
                'group' => $categoria
            ]);
        }
    }
}

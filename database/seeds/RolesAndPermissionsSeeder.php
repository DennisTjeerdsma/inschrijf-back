<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions(); //
        
        //Create Permissions
        Permission::create(['name' => 'create events']);
        Permission::create(['name' => 'edit events']);
        Permission::create(['name' => 'delete events']);

        Permission::create(['name' => 'create users']);
        Permission::create(['name' => 'edit users']);
        Permission::create(['name' => 'delete users']);

        Permission::create(['name' => 'edit permissions']);

        // Create Roles and assign permissions
        Role::create(['name' => 'Super Admin'])->givePermissionTo(Permission::all());
        Role::create(['name' => 'Bestuur'])->givePermissionTo(['create events', 'edit events', 'delete events', 'create users', 'edit users', 'delete users']);
        Role::create(['name' => 'Gebruiker'])->givePermissionTo(['create events', 'edit events', 'delete events']);

        

    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class BasicAdminPermissionSeeder extends Seeder
{

    /**
     * Create the initial roles and permissions.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permssions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'permission list',
            'permission create',
            'permission edit',
            'permission delete',
            'role list',
            'role create',
            'role edit',
            'role delete',
            'user list',
            'user create',
            'user edit',
            'user delete',
            'post list',
            'post create',
            'post edit',
            'post delete',
        ];


        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        /* 
            Create roles and assign exisiting permissions
        */

        // Writer
        $role1 = Role::create(['name' => 'writer']);
        $role1->givePermissionTo('permission list');
        $role1->givePermissionTo('role list');
        $role1->givePermissionTo('user list');
        $role1->givePermissionTo('post list');

        $role1->givePermissionTo('post create');
        $role1->givePermissionTo('post edit');
        $role1->givePermissionTo('post delete');


        // Admin
        $role2 = Role::create(['name' => 'admin']);
        // .. all
        foreach ($permissions as $permission) {
            $role2->givePermissionTo($permission);
        }

        // Gets all permissions via Gate::before rule; see AuthServiceProvider
        $role3 = Role::create(['name' => 'super-admin']);


        /* 
            Create demo users
        */
        $user1 = \App\Models\User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@laraveltuts.com'
        ]);
        $user1->assignRole($role3);

        $user2 = \App\Models\User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@laraveltuts.com'
        ]);
        $user2->assignRole($role2);

        $user = \App\Models\User::factory()->create([
            'name' => 'Example User',
            'email' => 'test@laraveltuts.com',
        ]);
        $user->assignRole($role1);

    }
}
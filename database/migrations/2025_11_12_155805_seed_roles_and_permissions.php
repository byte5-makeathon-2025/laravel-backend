<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    public function up(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $resources = ['user', 'role', 'permission', 'wish'];
        $actions = ['view', 'create', 'update', 'delete'];

        foreach ($resources as $resource) {
            foreach ($actions as $action) {
                Permission::create(['name' => "{$action}_{$resource}"]);
            }
        }

        Permission::create(['name' => 'view_all_wishes']);

        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        $userRole = Role::create(['name' => 'user']);
        $userRole->givePermissionTo([
            'view_user',
            'view_wish',
            'create_wish',
            'update_wish',
            'delete_wish',
        ]);

        $moderatorRole = Role::create(['name' => 'moderator']);
        $moderatorRole->givePermissionTo([
            'view_user',
            'create_user',
            'update_user',
            'view_role',
            'view_permission',
        ]);

        $santaRole = Role::create(['name' => 'santa_claus']);
        $santaRole->givePermissionTo([
            'view_all_wishes',
            'update_wish',
        ]);
    }

    public function down(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Role::whereIn('name', ['admin', 'user', 'moderator', 'santa_claus'])->delete();

        Permission::whereIn('name', [
            'view_user', 'create_user', 'update_user', 'delete_user',
            'view_role', 'create_role', 'update_role', 'delete_role',
            'view_permission', 'create_permission', 'update_permission', 'delete_permission',
            'view_wish', 'create_wish', 'update_wish', 'delete_wish',
            'view_all_wishes',
        ])->delete();
    }
};

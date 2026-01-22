<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view posts', 'create posts', 'edit posts', 'delete posts', 'publish posts',
            'view pages', 'create pages', 'edit pages', 'delete pages', 'publish pages',
            'view categories', 'create categories', 'edit categories', 'delete categories',
            'view tags', 'create tags', 'edit tags', 'delete tags',
            'view media', 'upload media', 'edit media', 'delete media',
            'view templates', 'create templates', 'edit templates', 'delete templates', 'activate templates',
            'view plugins', 'create plugins', 'edit plugins', 'delete plugins', 'activate plugins',
            'use ai generation', 'manage ai settings',
            'view users', 'create users', 'edit users', 'delete users',
            'view roles', 'create roles', 'edit roles', 'delete roles',
            'assign roles', 'view permissions',
            'view settings', 'edit settings',
            'view products', 'create products', 'edit products', 'delete products',
            'view orders', 'manage orders',
            'view menus', 'create menus', 'edit menus', 'delete menus',
            'view comments', 'moderate comments', 'delete comments',
            'view reviews', 'moderate reviews', 'delete reviews',
            'view analytics', 'export data',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $superAdmin = Role::create(['name' => 'Super Admin']);
        $superAdmin->givePermissionTo(Permission::all());

        $admin = Role::create(['name' => 'Admin']);
        $admin->givePermissionTo(['view posts', 'create posts', 'edit posts', 'delete posts', 'publish posts',
            'view pages', 'create pages', 'edit pages', 'delete pages', 'publish pages',
            'view categories', 'create categories', 'edit categories', 'delete categories',
            'view tags', 'create tags', 'edit tags', 'delete tags',
            'view media', 'upload media', 'edit media', 'delete media',
            'view templates', 'edit templates', 'activate templates',
            'view plugins', 'edit plugins', 'activate plugins',
            'use ai generation', 'view products', 'create products', 'edit products', 'delete products',
            'view orders', 'manage orders', 'view menus', 'create menus', 'edit menus', 'delete menus',
            'view comments', 'moderate comments', 'delete comments',
            'view reviews', 'moderate reviews', 'delete reviews', 'view analytics']);

        $editor = Role::create(['name' => 'Editor']);
        $editor->givePermissionTo(['view posts', 'create posts', 'edit posts', 'publish posts',
            'view pages', 'create pages', 'edit pages', 'publish pages',
            'view categories', 'create categories', 'edit categories',
            'view tags', 'create tags', 'edit tags',
            'view media', 'upload media', 'edit media',
            'use ai generation', 'view menus', 'edit menus',
            'view comments', 'moderate comments']);

        $author = Role::create(['name' => 'Author']);
        $author->givePermissionTo(['view posts', 'create posts', 'edit posts',
            'view pages', 'create pages', 'edit pages',
            'view categories', 'view tags', 'view media', 'upload media', 'use ai generation']);

        $contributor = Role::create(['name' => 'Contributor']);
        $contributor->givePermissionTo(['view posts', 'create posts', 'edit posts',
            'view categories', 'view tags', 'view media', 'upload media']);

        Role::create(['name' => 'Customer']);
        Role::create(['name' => 'Freelancer']);
        Role::create(['name' => 'Subscriber']);

        // Assign Super Admin role to the first admin user
        $adminUser = User::where('is_admin', true)->first();
        if ($adminUser) {
            $adminUser->assignRole('Super Admin');
        }
    }
}

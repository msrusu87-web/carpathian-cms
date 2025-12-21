<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Shop Management
            'view shop',
            'manage products',
            'manage orders',
            'view orders',
            
            // Customer Permissions
            'place orders',
            'view own orders',
            'track orders',
            
            // Staff Permissions
            'process orders',
            'update order status',
            'contact customers',
            
            // Admin Permissions
            'manage users',
            'manage roles',
            'manage settings',
            'view analytics',
            'manage payment gateways',
            
            // CMS Permissions
            'manage pages',
            'manage posts',
            'manage media',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create or update roles with permissions
        
        // 1. Super Admin (all permissions)
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $superAdmin->syncPermissions(Permission::all());

        // 2. Admin (most permissions except user management)
        $admin = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $admin->syncPermissions([
            'view shop',
            'manage products',
            'manage orders',
            'view orders',
            'process orders',
            'update order status',
            'contact customers',
            'view analytics',
            'manage payment gateways',
            'manage pages',
            'manage posts',
            'manage media',
        ]);

        // 3. Editor (CMS content only)
        $editor = Role::firstOrCreate(['name' => 'Editor', 'guard_name' => 'web']);
        $editor->syncPermissions([
            'manage pages',
            'manage posts',
            'manage media',
        ]);

        // 4. Staff Operators (NEW - Order processing)
        $staff = Role::firstOrCreate(['name' => 'Staff Operator', 'guard_name' => 'web']);
        $staff->syncPermissions([
            'view shop',
            'view orders',
            'process orders',
            'update order status',
            'contact customers',
        ]);

        // 5. Customers (NEW - Shopping only)
        $customer = Role::firstOrCreate(['name' => 'Customer', 'guard_name' => 'web']);
        $customer->syncPermissions([
            'view shop',
            'place orders',
            'view own orders',
            'track orders',
        ]);

        $this->command->info('✅ Roles and permissions created successfully!');
        $this->command->info('');
        $this->command->info('Created/Updated Roles:');
        $this->command->info('- Super Admin (all permissions)');
        $this->command->info('- Admin (management permissions)');
        $this->command->info('- Editor (content permissions)');
        $this->command->info('- Staff Operator (order processing)');
        $this->command->info('- Customer (shopping permissions)');
    }
}

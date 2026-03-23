<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define all permissions
        $permissions = [
            // Dashboard
            'view-dashboard',
            
            // Products
            'view-products',
            'create-products',
            'edit-products',
            'delete-products',
            
            // Categories & Brands
            'manage-categories',
            'manage-brands',
            
            // Orders
            'view-orders',
            'edit-orders',
            'delete-orders',
            
            // Customers
            'view-customers',
            'edit-customers',
            'delete-customers',
            
            // Inventory/Warehouse
            'view-inventory',
            'manage-purchase-orders',
            'manage-suppliers',
            'adjust-stock',
            
            // Coupons
            'view-coupons',
            'manage-coupons',
            
            // Reviews
            'view-reviews',
            'manage-reviews',
            
            // Reports
            'view-reports',
            
            // Settings
            'manage-settings',
            
            // Users (Admin Management)
            'view-users',
            'create-users',
            'edit-users',
            'delete-users',
            
            // Blog
            'manage-blog',
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create roles and assign permissions
        
        // 1. ADMIN - Full access
        $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->givePermissionTo(Permission::all());

        // 2. SALE - Sales, customers, orders, products, coupons, reviews, blog
        $saleRole = Role::create(['name' => 'sale', 'guard_name' => 'web']);
        $saleRole->givePermissionTo([
            'view-dashboard',
            'view-products',
            'create-products',
            'edit-products',
            'manage-categories',
            'manage-brands',
            'view-orders',
            'edit-orders',
            'view-customers',
            'edit-customers',
            'view-coupons',
            'manage-coupons',
            'view-reviews',
            'manage-reviews',
            'view-reports',
            'manage-blog',
        ]);

        // 3. WAREHOUSE - Inventory, purchase orders, suppliers, stock
        $warehouseRole = Role::create(['name' => 'warehouse', 'guard_name' => 'web']);
        $warehouseRole->givePermissionTo([
            'view-dashboard',
            'view-products',
            'view-inventory',
            'manage-purchase-orders',
            'manage-suppliers',
            'adjust-stock',
            'view-orders', // To see what needs to be shipped
        ]);

        $this->command->info('Roles and permissions created successfully!');
        $this->command->info('Roles: admin, sale, warehouse');
    }
}


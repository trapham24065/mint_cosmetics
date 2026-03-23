<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default admin user
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@mintcosmetics.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        
        $admin->assignRole('admin');
        
        $this->command->info('Admin user created successfully!');
        $this->command->info('Email: admin@mintcosmetics.com');
        $this->command->info('Password: password');
        
        // Create sample sale user
        $sale = User::create([
            'name' => 'Sale Manager',
            'email' => 'sale@mintcosmetics.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        
        $sale->assignRole('sale');
        
        $this->command->info('Sale user created successfully!');
        $this->command->info('Email: sale@mintcosmetics.com');
        $this->command->info('Password: password');
        
        // Create sample warehouse user
        $warehouse = User::create([
            'name' => 'Warehouse Manager',
            'email' => 'warehouse@mintcosmetics.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        
        $warehouse->assignRole('warehouse');
        
        $this->command->info('Warehouse user created successfully!');
        $this->command->info('Email: warehouse@mintcosmetics.com');
        $this->command->info('Password: password');
    }
}


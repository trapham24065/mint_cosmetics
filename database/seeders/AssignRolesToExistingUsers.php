<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AssignRolesToExistingUsers extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find existing admin user and assign admin role
        $adminUser = User::where('email', 'admin@mintcosmetics.com')->first();

        if ($adminUser) {
            $adminUser->update(['role' => 'admin']);
            $this->command->info('Admin role assigned to: ' . $adminUser->email);
        } else {
            $this->command->warn('Admin user not found!');
        }

        // You can add more users here if needed
        $this->command->info('Role assignment completed!');
    }
}

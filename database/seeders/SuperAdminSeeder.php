<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create superadmin role if it doesn't exist
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin']);

        // Get all permissions
        $permissions = Permission::all();

        // Assign all permissions to superadmin role
        $superAdminRole->syncPermissions($permissions);

        // Create superadmin user if it doesn't exist
        $user = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('password'),
            ]
        );

        // Assign superadmin role to user
        $user->assignRole($superAdminRole);
    }
}

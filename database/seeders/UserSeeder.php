<?php

namespace Database\Seeders;

use App\Models\Kelurahan;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all kelurahan
        $kelurahans = Kelurahan::all();

        // Create admin kelurahan role if it doesn't exist
        $adminKelurahanRole = Role::firstOrCreate(['name' => 'Admin Kelurahan']);

        foreach ($kelurahans as $kelurahan) {
            // Create user for each kelurahan
            $user = User::firstOrCreate(
                ['email' => 'admin.' . strtolower(str_replace(' ', '', $kelurahan->nama)) . '@example.com'],
                [
                    'name' => 'Admin ' . $kelurahan->nama,
                    'password' => bcrypt('password'),
                    'kelurahan_id' => $kelurahan->id,
                ]
            );

            // Assign role
            $user->assignRole($adminKelurahanRole);
        }
    }
}

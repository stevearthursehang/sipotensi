<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin role with full permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions(Permission::all());

        // Create Operator role for village officers with limited permissions
        $operatorRole = Role::firstOrCreate(['name' => 'operator']);

        // Give operator view permissions for most resources
        $operatorPermissions = [
            'ViewAny:PotensiInvestasi',
            'View:PotensiInvestasi',
            'ViewAny:Kelurahan',
            'View:Kelurahan',
            'ViewAny:MinatInvestor',
            'View:MinatInvestor',
            'ViewAny:Infrastruktur',
            'View:Infrastruktur',
            'ViewAny:UploadGeoJson',
            'View:UploadGeoJson',
        ];

        // Add edit permissions for potensi investasi (for village officers to update their area)
        $operatorPermissions = array_merge($operatorPermissions, [
            'Update:PotensiInvestasi',
        ]);

        $operatorRole->syncPermissions(Permission::whereIn('name', $operatorPermissions)->get());

        // Create Viewer role with only view permissions
        $viewerRole = Role::firstOrCreate(['name' => 'viewer']);
        $viewerPermissions = [
            'ViewAny:PotensiInvestasi',
            'View:PotensiInvestasi',
            'ViewAny:Kelurahan',
            'View:Kelurahan',
            'ViewAny:MinatInvestor',
            'View:MinatInvestor',
            'ViewAny:Infrastruktur',
            'View:Infrastruktur',
        ];
        $viewerRole->syncPermissions(Permission::whereIn('name', $viewerPermissions)->get());

        // Create Admin Kelurahan role with full permissions (for kelurahan admins)
        $adminKelurahanRole = Role::firstOrCreate(['name' => 'Admin Kelurahan']);
        $adminKelurahanRole->syncPermissions(Permission::all());
    }
}

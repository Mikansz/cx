<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class HRDRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create HRD role if not exists
        $hrdRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'hrd']);

        // Create permissions for HRD
        $permissions = [
            'view_leave',
            'create_leave',
            'update_leave',
            'approve_leave',
            'reject_leave',
            'view_karyawan',
            'create_karyawan',
            'update_karyawan',
            'view_attendance',
            'view_penggajian',
            'create_penggajian',
            'update_penggajian',
            'view_jabatan',
        ];

        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign permissions to HRD role
        $hrdRole->syncPermissions($permissions);

        $this->command->info('HRD role and permissions created successfully!');
    }
}

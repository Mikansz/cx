<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class KaryawanRoleSeeder extends Seeder
{
    public function run(): void
    {
        // Create karyawan role if it doesn't exist
        $karyawanRole = Role::firstOrCreate(['name' => 'karyawan']);

        // Create permissions for karyawan if they don't exist
        $permissions = [
            'view_attendance',
            'create_attendance',
            'view_leave',
            'create_leave',
            'view_own_payroll',
            'download_salary_slip',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign permissions to karyawan role
        $karyawanRole->syncPermissions($permissions);

        $this->command->info('Karyawan role and permissions created successfully!');
    }
}

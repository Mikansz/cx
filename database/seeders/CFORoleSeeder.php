<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CFORoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create CFO role if not exists
        $cfoRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'cfo']);

        // Create permissions for CFO
        $permissions = [
            'view_penggajian',
            'create_penggajian',
            'update_penggajian',
            'approve_penggajian',
            'reject_penggajian',
            'view_karyawan',
            'view_jabatan',
            'view_reports',
            'manage_finance',
        ];

        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign permissions to CFO role
        $cfoRole->syncPermissions($permissions);

        $this->command->info('CFO role and permissions created successfully!');
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Leave;

class TestPermissionPage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:permission-page';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test permission page functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing permission page functionality...');
        
        // Check if there are permission records
        $permissionRecords = Leave::where('leave_type', Leave::IZIN)->get();
        
        $this->line("Total permission records: {$permissionRecords->count()}");
        
        if ($permissionRecords->count() > 0) {
            foreach ($permissionRecords as $permission) {
                $this->line("Permission ID: {$permission->id}");
                $this->line("User: {$permission->user->name}");
                $this->line("Type: {$permission->permission_type}");
                $this->line("Status: {$permission->status}");
                $this->line("Start Date: {$permission->start_date}");
                $this->line("End Date: {$permission->end_date}");
                $this->line('');
            }
        } else {
            $this->info('No permission records found. Creating a test record...');
            
            // Create a test permission record
            $testUser = \App\Models\User::first();
            if ($testUser) {
                Leave::create([
                    'user_id' => $testUser->id,
                    'leave_type' => Leave::IZIN,
                    'permission_type' => Leave::IZIN_KELUAR_KANTOR,
                    'start_date' => now(),
                    'end_date' => now(),
                    'permission_start_time' => '09:00',
                    'permission_end_time' => '11:00',
                    'reason' => 'Test permission request',
                    'status' => 'pending',
                ]);
                
                $this->info('✅ Test permission record created!');
            } else {
                $this->error('No users found to create test record.');
            }
        }
        
        $this->info('✅ Permission page test completed!');
    }
}

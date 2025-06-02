<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class FixMultipleRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:fix-multiple-roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix users with multiple roles by keeping only their primary role';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fixing users with multiple roles...');
        
        $users = User::with('roles')->get();
        $fixedUsers = [];
        
        foreach ($users as $user) {
            if ($user->hasMultipleRoles()) {
                $currentRoles = $user->roles->pluck('name')->toArray();
                
                // Define role priority (higher priority roles take precedence)
                $rolePriority = [
                    'super_admin' => 100,
                    'cfo' => 80,
                    'hrd' => 60,
                    'Hrd' => 60, // Handle case variation
                    'karyawan' => 20,
                ];
                
                // Find the highest priority role
                $highestPriorityRole = null;
                $highestPriority = 0;
                
                foreach ($currentRoles as $role) {
                    $priority = $rolePriority[$role] ?? 10;
                    if ($priority > $highestPriority) {
                        $highestPriority = $priority;
                        $highestPriorityRole = $role;
                    }
                }
                
                // Special handling: Super admin should never have CFO role
                if (in_array('super_admin', $currentRoles)) {
                    $highestPriorityRole = 'super_admin';
                }
                
                $this->warn("User: {$user->name} - Current roles: " . implode(', ', $currentRoles));
                $this->info("Keeping role: {$highestPriorityRole}");
                
                // Assign single role
                $user->assignSingleRole($highestPriorityRole);
                $fixedUsers[] = $user;
                
                $this->line('');
            }
        }
        
        if (count($fixedUsers) > 0) {
            $this->info("✅ Fixed " . count($fixedUsers) . " users with multiple roles.");
            
            // Run check again to verify
            $this->info("\nVerifying fix...");
            $this->call('user:check-roles');
        } else {
            $this->info("✅ No users with multiple roles found!");
        }
        
        return 0;
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class PreventRoleConflicts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:prevent-role-conflicts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ensure no user has conflicting roles like super_admin and cfo simultaneously';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for role conflicts...');
        
        $conflictingUsers = User::with('roles')
            ->get()
            ->filter(function ($user) {
                $roles = $user->roles->pluck('name')->toArray();
                
                // Check if user has both super_admin and cfo roles
                return in_array('super_admin', $roles) && in_array('cfo', $roles);
            });
            
        if ($conflictingUsers->count() > 0) {
            $this->warn("Found users with conflicting roles:");
            
            foreach ($conflictingUsers as $user) {
                $this->error("- {$user->name}: " . $user->roles->pluck('name')->join(', '));
                
                // Remove CFO role, keep super_admin
                $user->assignSingleRole('super_admin');
                $this->info("  → Fixed: Kept super_admin role");
            }
            
            $this->info("\n✅ Fixed all role conflicts!");
        } else {
            $this->info("✅ No role conflicts found!");
        }
        
        return 0;
    }
}

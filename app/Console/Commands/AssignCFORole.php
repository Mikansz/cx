<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AssignCFORole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assign:cfo-role {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign CFO role to a user by email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        // If email is 'list', show all users
        if ($email === 'list') {
            $this->info("Available users:");
            $users = \App\Models\User::all(['id', 'name', 'email']);
            foreach ($users as $user) {
                $roles = $user->getRoleNames()->implode(', ') ?: 'No roles';
                $this->line("ID: {$user->id} - {$user->name} ({$user->email}) - Roles: {$roles}");
            }
            return;
        }
        
        $user = \App\Models\User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("User with email {$email} not found!");
            $this->info("Run 'php artisan assign:cfo-role list' to see available users");
            return;
        }
        
        // Check if user already has super_admin role
        if ($user->hasRole('super_admin')) {
            $this->error("Cannot assign CFO role to super admin!");
            $this->info("User {$user->name} is already a super admin. Super admins cannot have CFO role.");
            return;
        }
        
        // Use single role assignment to ensure no conflicts
        $user->assignSingleRole('cfo');
        
        $this->info("CFO role assigned to user: {$user->name} ({$user->email})");
        $this->info("User roles: " . $user->getRoleNames()->implode(', '));
    }
}

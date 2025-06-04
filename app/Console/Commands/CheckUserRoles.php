<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CheckUserRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:check-roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check user roles and identify users with multiple roles';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking user roles...');

        $users = User::with('roles')->get();
        $multiRoleUsers = [];

        foreach ($users as $user) {
            $roleCount = $user->roles->count();
            $roleNames = $user->roles->pluck('name')->join(', ');

            $this->line("User: {$user->name} ({$user->email}) - Roles: {$roleNames} ({$roleCount} roles)");

            if ($roleCount > 1) {
                $multiRoleUsers[] = $user;
            }
        }

        if (count($multiRoleUsers) > 0) {
            $this->warn("\nUsers with multiple roles found:");
            foreach ($multiRoleUsers as $user) {
                $this->error("- {$user->name}: ".$user->roles->pluck('name')->join(', '));
            }
        } else {
            $this->info("\n✅ All users have single roles!");
        }

        return 0;
    }
}

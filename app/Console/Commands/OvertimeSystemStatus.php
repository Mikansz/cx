<?php

namespace App\Console\Commands;

use App\Models\Overtime;
use App\Models\User;
use Illuminate\Console\Command;

class OvertimeSystemStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'overtime:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check overtime system status and functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('📊 Overtime System Status Report');
        $this->line('');

        // Check system components
        $this->checkSystemComponents();

        // Check data integrity
        $this->checkDataIntegrity();

        // Check user relationships
        $this->checkUserRelationships();

        // Show system statistics
        $this->showStatistics();

        $this->line('');
        $this->info('✅ Overtime system status check completed!');
    }

    private function checkSystemComponents()
    {
        $this->info('1. System Components Check:');

        // Check database table
        try {
            Overtime::count();
            $this->line('   ✅ Database table: OK');
        } catch (\Exception $e) {
            $this->error('   ❌ Database table: ERROR - '.$e->getMessage());
        }

        // Check model relationships
        try {
            $user = User::first();
            if ($user) {
                $user->overtimes()->count();
                $this->line('   ✅ User-Overtime relationship: OK');
            } else {
                $this->warn('   ⚠️  No users found to test relationship');
            }
        } catch (\Exception $e) {
            $this->error('   ❌ User-Overtime relationship: ERROR - '.$e->getMessage());
        }

        // Check Filament routes
        $routes = ['overtimes.index', 'overtimes.create', 'overtimes.view', 'overtimes.edit'];
        $routeExists = collect($routes)->every(function ($route) {
            return \Route::has("filament.backoffice.resources.{$route}");
        });

        if ($routeExists) {
            $this->line('   ✅ Filament routes: OK');
        } else {
            $this->error('   ❌ Filament routes: Some routes missing');
        }

        $this->line('');
    }

    private function checkDataIntegrity()
    {
        $this->info('2. Data Integrity Check:');

        $overtimes = Overtime::all();

        foreach ($overtimes as $overtime) {
            $issues = [];

            // Check if hours calculation is correct
            $calculatedHours = $overtime->calculateHours();
            if (abs($calculatedHours - $overtime->hours) > 0.1) {
                $issues[] = 'Hours mismatch';
            }

            // Check if amount calculation is correct
            $calculatedAmount = $overtime->calculateAmount();
            if (abs($calculatedAmount - $overtime->total_amount) > 0.1) {
                $issues[] = 'Amount mismatch';
            }

            // Check if user exists
            if (! $overtime->user) {
                $issues[] = 'User not found';
            }

            if (empty($issues)) {
                $this->line("   ✅ Overtime #{$overtime->id}: OK");
            } else {
                $this->error("   ❌ Overtime #{$overtime->id}: ".implode(', ', $issues));
            }
        }

        if ($overtimes->count() === 0) {
            $this->warn('   ⚠️  No overtime records to check');
        }

        $this->line('');
    }

    private function checkUserRelationships()
    {
        $this->info('3. User Relationships Check:');

        $users = User::with('overtimes')->get();

        foreach ($users as $user) {
            $overtimeCount = $user->overtimes->count();
            $this->line("   👤 {$user->name}: {$overtimeCount} overtime records");
        }

        $this->line('');
    }

    private function showStatistics()
    {
        $this->info('4. System Statistics:');

        $totalOvertimes = Overtime::count();
        $totalHours = Overtime::sum('hours');
        $totalAmount = Overtime::sum('total_amount');

        $statusCounts = [
            'pending' => Overtime::where('status', 'pending')->count(),
            'approved' => Overtime::where('status', 'approved')->count(),
            'rejected' => Overtime::where('status', 'rejected')->count(),
        ];

        $typeCounts = [
            'weekday' => Overtime::where('type', 'weekday')->count(),
            'weekend' => Overtime::where('type', 'weekend')->count(),
            'holiday' => Overtime::where('type', 'holiday')->count(),
        ];

        $this->line("   📋 Total Overtime Records: {$totalOvertimes}");
        $this->line("   ⏱️  Total Hours: {$totalHours} jam");
        $this->line('   💰 Total Amount: Rp '.number_format($totalAmount));
        $this->line('');

        $this->line('   📊 By Status:');
        foreach ($statusCounts as $status => $count) {
            $this->line("      {$status}: {$count}");
        }
        $this->line('');

        $this->line('   📅 By Type:');
        foreach ($typeCounts as $type => $count) {
            $this->line("      {$type}: {$count}");
        }
    }
}

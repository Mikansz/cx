<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Overtime;
use App\Models\User;
use Carbon\Carbon;

class TestOvertimeSystem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:overtime-system';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test overtime system functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Testing Overtime System...');
        $this->line('');
        
        // Test 1: Check overtime model and database
        $this->testOvertimeModel();
        
        // Test 2: Create sample overtime records
        $this->createSampleOvertimes();
        
        // Test 3: Test calculations
        $this->testCalculations();
        
        // Test 4: Test overtime types and rates
        $this->testOvertimeTypes();
        
        // Test 5: Display current overtime data
        $this->displayOvertimeData();
        
        $this->info('');
        $this->info('✅ Overtime system test completed!');
    }
    
    private function testOvertimeModel()
    {
        $this->info('1. Testing Overtime Model...');
        
        // Check if table exists and model is working
        try {
            $count = Overtime::count();
            $this->line("   📊 Current overtime records: {$count}");
            
            // Test overtime types
            $types = Overtime::getOvertimeTypes();
            $this->line("   📝 Available overtime types: " . implode(', ', array_keys($types)));
            
            // Test status options
            $statuses = Overtime::getStatusOptions();
            $this->line("   🎯 Available statuses: " . implode(', ', array_keys($statuses)));
            
            $this->info('   ✅ Model test passed');
        } catch (\Exception $e) {
            $this->error("   ❌ Model test failed: " . $e->getMessage());
        }
        
        $this->line('');
    }
    
    private function createSampleOvertimes()
    {
        $this->info('2. Creating Sample Overtime Records...');
        
        $users = User::limit(3)->get();
        
        if ($users->count() === 0) {
            $this->warn('   ⚠️  No users found to create sample overtimes');
            return;
        }
        
        $samples = [
            [
                'date' => now()->subDays(2),
                'start_time' => '18:00',
                'end_time' => '20:00',
                'reason' => 'Menyelesaikan laporan bulanan',
                'type' => Overtime::TYPE_WEEKDAY,
            ],
            [
                'date' => now()->subDays(1),
                'start_time' => '08:00',
                'end_time' => '12:00',
                'reason' => 'Maintenance sistem weekend',
                'type' => Overtime::TYPE_WEEKEND,
            ],
            [
                'date' => now(),
                'start_time' => '19:00',
                'end_time' => '22:00',
                'reason' => 'Meeting dengan client internasional',
                'type' => Overtime::TYPE_WEEKDAY,
            ],
        ];
        
        foreach ($samples as $index => $sample) {
            $user = $users[$index % $users->count()];
            
            try {
                // Calculate hours and amount
                $start = Carbon::parse($sample['start_time']);
                $end = Carbon::parse($sample['end_time']);
                $hours = $start->diffInMinutes($end) / 60;
                $rate = Overtime::getDefaultRate($sample['type']);
                $amount = $hours * $rate;
                
                $overtime = Overtime::create([
                    'user_id' => $user->id,
                    'date' => $sample['date'],
                    'start_time' => $sample['start_time'],
                    'end_time' => $sample['end_time'],
                    'hours' => $hours,
                    'reason' => $sample['reason'],
                    'type' => $sample['type'],
                    'rate_per_hour' => $rate,
                    'total_amount' => $amount,
                    'status' => Overtime::STATUS_PENDING,
                ]);
                
                $this->line("   ✅ Created overtime for {$user->name}: {$hours} hours, Rp " . number_format($amount));
                
            } catch (\Exception $e) {
                $this->error("   ❌ Failed to create overtime for {$user->name}: " . $e->getMessage());
            }
        }
        
        $this->line('');
    }
    
    private function testCalculations()
    {
        $this->info('3. Testing Calculations...');
        
        // Test hour calculation
        $start = Carbon::parse('18:00');
        $end = Carbon::parse('21:30');
        $expectedHours = 3.5;
        $calculatedHours = $start->diffInMinutes($end) / 60;
        
        $this->line("   🕐 Time calculation test:");
        $this->line("      Start: 18:00, End: 21:30");
        $this->line("      Expected: {$expectedHours} hours");
        $this->line("      Calculated: {$calculatedHours} hours");
        
        if ($calculatedHours === $expectedHours) {
            $this->info("   ✅ Time calculation correct");
        } else {
            $this->error("   ❌ Time calculation incorrect");
        }
        
        // Test amount calculation
        $hours = 3.5;
        $rate = 25000;
        $expectedAmount = 87500;
        $calculatedAmount = $hours * $rate;
        
        $this->line("   💰 Amount calculation test:");
        $this->line("      Hours: {$hours}, Rate: Rp " . number_format($rate));
        $this->line("      Expected: Rp " . number_format($expectedAmount));
        $this->line("      Calculated: Rp " . number_format($calculatedAmount));
        
        if (abs($calculatedAmount - $expectedAmount) < 0.01) {
            $this->info("   ✅ Amount calculation correct");
        } else {
            $this->error("   ❌ Amount calculation incorrect");
        }
        
        $this->line('');
    }
    
    private function testOvertimeTypes()
    {
        $this->info('4. Testing Overtime Types and Rates...');
        
        $types = [
            Overtime::TYPE_WEEKDAY => 'Weekday',
            Overtime::TYPE_WEEKEND => 'Weekend', 
            Overtime::TYPE_HOLIDAY => 'Holiday',
        ];
        
        foreach ($types as $type => $name) {
            $rate = Overtime::getDefaultRate($type);
            $this->line("   📅 {$name} ({$type}): Rp " . number_format($rate) . "/hour");
        }
        
        // Test overtime type detection
        $weekday = Carbon::parse('2025-06-02'); // Monday
        $weekend = Carbon::parse('2025-06-01'); // Sunday
        
        $weekdayType = Overtime::getOvertimeType($weekday);
        $weekendType = Overtime::getOvertimeType($weekend);
        
        $this->line("   🗓️  Type detection:");
        $this->line("      Monday -> {$weekdayType} " . ($weekdayType === Overtime::TYPE_WEEKDAY ? '✅' : '❌'));
        $this->line("      Sunday -> {$weekendType} " . ($weekendType === Overtime::TYPE_WEEKEND ? '✅' : '❌'));
        
        $this->line('');
    }
    
    private function displayOvertimeData()
    {
        $this->info('5. Current Overtime Data...');
        
        $overtimes = Overtime::with('user')->orderBy('date', 'desc')->get();
        
        if ($overtimes->count() === 0) {
            $this->warn('   ⚠️  No overtime records found');
            return;
        }
        
        $this->table(
            ['ID', 'Employee', 'Date', 'Type', 'Hours', 'Rate/Hour', 'Total', 'Status'],
            $overtimes->map(function ($overtime) {
                return [
                    $overtime->id,
                    $overtime->user->name,
                    $overtime->date->format('Y-m-d'),
                    $overtime->type,
                    $overtime->hours . ' jam',
                    'Rp ' . number_format($overtime->rate_per_hour),
                    'Rp ' . number_format($overtime->total_amount),
                    $overtime->status,
                ];
            })
        );
        
        // Summary
        $totalHours = $overtimes->sum('hours');
        $totalAmount = $overtimes->sum('total_amount');
        $pendingCount = $overtimes->where('status', Overtime::STATUS_PENDING)->count();
        $approvedCount = $overtimes->where('status', Overtime::STATUS_APPROVED)->count();
        
        $this->line('');
        $this->info('📊 Summary:');
        $this->line("   Total Records: {$overtimes->count()}");
        $this->line("   Total Hours: {$totalHours} jam");
        $this->line("   Total Amount: Rp " . number_format($totalAmount));
        $this->line("   Pending: {$pendingCount}, Approved: {$approvedCount}");
    }
}

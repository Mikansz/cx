<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Leave;
use Illuminate\Support\Facades\Storage;

class TestSickCertificate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:sick-certificate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test sick certificate download functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing sick certificate functionality...');
        
        $leavesWithCertificates = Leave::whereNotNull('sick_certificate')->get();
        
        if ($leavesWithCertificates->count() === 0) {
            $this->warn('No leaves with sick certificates found.');
            return;
        }
        
        foreach ($leavesWithCertificates as $leave) {
            $this->line("Leave ID: {$leave->id}");
            $this->line("User: {$leave->user->name}");
            $this->line("Certificate: {$leave->sick_certificate}");
            
            // Check if file exists
            if (Storage::disk('public')->exists($leave->sick_certificate)) {
                $this->info("✅ File exists in public storage");
                
                // Generate download URL
                $downloadUrl = route('sick-certificate.download', $leave);
                $this->line("Download URL: {$downloadUrl}");
            } else {
                $this->error("❌ File NOT found in public storage");
            }
            
            $this->line('');
        }
    }
}

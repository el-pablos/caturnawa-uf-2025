<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Registration;
use App\Models\Payment;

class ResetPaymentData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dev:reset-payments 
                            {--user= : Reset payments for specific user ID}
                            {--registration= : Reset payments for specific registration ID}
                            {--all : Reset all payments (DANGEROUS)}
                            {--confirm : Skip confirmation prompt}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset payment data for debugging purposes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!app()->environment(['local', 'development'])) {
            $this->error('This command can only be run in local/development environment!');
            return 1;
        }

        $userId = $this->option('user');
        $registrationId = $this->option('registration');
        $resetAll = $this->option('all');
        $skipConfirm = $this->option('confirm');

        if (!$userId && !$registrationId && !$resetAll) {
            $this->error('Please specify --user, --registration, or --all option');
            return 1;
        }

        // Build query
        $query = Registration::query();
        
        if ($userId) {
            $query->where('user_id', $userId);
            $scope = "user ID {$userId}";
        } elseif ($registrationId) {
            $query->where('id', $registrationId);
            $scope = "registration ID {$registrationId}";
        } else {
            $scope = "ALL registrations";
        }

        $registrations = $query->with(['payments', 'user'])->get();

        if ($registrations->isEmpty()) {
            $this->info('No registrations found to reset.');
            return 0;
        }

        // Show what will be reset
        $this->info("Found {$registrations->count()} registration(s) for {$scope}:");
        
        foreach ($registrations as $registration) {
            $this->line("- Registration #{$registration->registration_number} ({$registration->user->name})");
            $this->line("  Status: {$registration->status}");
            $this->line("  Payments: {$registration->payments->count()}");
        }

        // Confirmation
        if (!$skipConfirm) {
            if (!$this->confirm('Are you sure you want to reset these payments?')) {
                $this->info('Operation cancelled.');
                return 0;
            }
        }

        // Reset data
        $totalPayments = 0;
        $totalRegistrations = 0;

        foreach ($registrations as $registration) {
            $paymentCount = $registration->payments->count();
            $totalPayments += $paymentCount;

            // Delete payments
            $registration->payments()->delete();

            // Reset registration status
            $registration->update([
                'status' => 'pending',
                'qr_code' => null,
                'confirmed_at' => null,
            ]);

            $totalRegistrations++;
            
            $this->line("✓ Reset registration #{$registration->registration_number} ({$paymentCount} payments)");
        }

        $this->info("Successfully reset {$totalRegistrations} registrations and deleted {$totalPayments} payments.");
        
        return 0;
    }
}

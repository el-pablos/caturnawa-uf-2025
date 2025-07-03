<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Registration;
use App\Models\Payment;
use App\Models\User;

class DevHelpers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dev:helpers 
                            {action : Action to perform: status, payments, users, regenerate-qr}
                            {--user= : Filter by user ID}
                            {--limit=10 : Limit results}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Development helper commands for debugging';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!app()->environment(['local', 'development'])) {
            $this->error('This command can only be run in local/development environment!');
            return 1;
        }

        $action = $this->argument('action');
        $userId = $this->option('user');
        $limit = $this->option('limit');

        switch ($action) {
            case 'status':
                $this->showStatus($userId, $limit);
                break;
            case 'payments':
                $this->showPayments($userId, $limit);
                break;
            case 'users':
                $this->showUsers($limit);
                break;
            case 'regenerate-qr':
                $this->regenerateQRCodes($userId);
                break;
            default:
                $this->error('Invalid action. Available: status, payments, users, regenerate-qr');
                return 1;
        }

        return 0;
    }

    private function showStatus($userId = null, $limit = 10)
    {
        $this->info('=== Registration Status Overview ===');
        
        $query = Registration::with(['user', 'competition']);
        
        if ($userId) {
            $query->where('user_id', $userId);
        }
        
        $registrations = $query->latest()->limit($limit)->get();
        
        $this->table(
            ['ID', 'Registration #', 'User', 'Competition', 'Status', 'Amount', 'Created'],
            $registrations->map(function ($reg) {
                return [
                    $reg->id,
                    $reg->registration_number,
                    $reg->user->name,
                    $reg->competition->name,
                    $reg->status,
                    'Rp ' . number_format($reg->amount),
                    $reg->created_at->format('Y-m-d H:i')
                ];
            })
        );
    }

    private function showPayments($userId = null, $limit = 10)
    {
        $this->info('=== Payment Status Overview ===');
        
        $query = Payment::with(['registration.user', 'registration.competition']);
        
        if ($userId) {
            $query->whereHas('registration', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            });
        }
        
        $payments = $query->latest()->limit($limit)->get();
        
        $this->table(
            ['ID', 'Order ID', 'User', 'Status', 'Amount', 'Method', 'Created'],
            $payments->map(function ($payment) {
                return [
                    $payment->id,
                    $payment->order_id,
                    $payment->registration->user->name,
                    $payment->transaction_status,
                    'Rp ' . number_format($payment->gross_amount),
                    $payment->payment_method ?? 'N/A',
                    $payment->created_at->format('Y-m-d H:i')
                ];
            })
        );
    }

    private function showUsers($limit = 10)
    {
        $this->info('=== Users Overview ===');
        
        $users = User::withCount('registrations')->latest()->limit($limit)->get();
        
        $this->table(
            ['ID', 'Name', 'Email', 'Registrations', 'Created'],
            $users->map(function ($user) {
                return [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->registrations_count,
                    $user->created_at->format('Y-m-d H:i')
                ];
            })
        );
    }

    private function regenerateQRCodes($userId = null)
    {
        $this->info('=== Regenerating QR Codes ===');
        
        $query = Registration::where('status', 'confirmed');
        
        if ($userId) {
            $query->where('user_id', $userId);
        }
        
        $registrations = $query->get();
        
        if ($registrations->isEmpty()) {
            $this->info('No confirmed registrations found.');
            return;
        }
        
        $this->info("Found {$registrations->count()} confirmed registrations.");
        
        $bar = $this->output->createProgressBar($registrations->count());
        $bar->start();
        
        foreach ($registrations as $registration) {
            try {
                $registration->generateQRCode();
                $bar->advance();
            } catch (\Exception $e) {
                $this->newLine();
                $this->error("Failed to generate QR for registration #{$registration->registration_number}: " . $e->getMessage());
            }
        }
        
        $bar->finish();
        $this->newLine();
        $this->info('QR code regeneration completed!');
    }
}

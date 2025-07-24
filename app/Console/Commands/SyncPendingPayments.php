<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Payment;
use App\Services\MidtransService;

class SyncPendingPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:sync-pending {--dry-run : Show what would be updated without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync pending payments with Midtrans to update their actual status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        $midtransService = app(MidtransService::class);

        $this->info('🔄 Syncing pending payments with Midtrans...');

        // Get all pending payments with order_id
        $pendingPayments = Payment::where('transaction_status', 'pending')
            ->whereNotNull('order_id')
            ->get();

        if ($pendingPayments->isEmpty()) {
            $this->info('✅ No pending payments found to sync.');
            return;
        }

        $this->info("📋 Found {$pendingPayments->count()} pending payments to check.");

        $updated = 0;
        $errors = 0;

        foreach ($pendingPayments as $payment) {
            try {
                $this->line("🔍 Checking payment {$payment->id} (Order: {$payment->order_id})...");

                $result = $midtransService->checkTransactionStatus($payment->order_id);

                if ($result['success']) {
                    $data = $result['data'];
                    if (is_object($data)) {
                        $data = json_decode(json_encode($data), true);
                    }

                    $midtransStatus = $data['transaction_status'] ?? 'unknown';

                    if ($midtransStatus !== 'pending') {
                        if ($dryRun) {
                            $this->warn("   [DRY RUN] Would update payment {$payment->id}: pending → {$midtransStatus}");
                        } else {
                            $payment->updateFromMidtrans($data);
                            $this->info("   ✅ Updated payment {$payment->id}: pending → {$midtransStatus}");
                        }
                        $updated++;
                    } else {
                        $this->line("   ⏳ Payment {$payment->id} is still pending in Midtrans");
                    }
                } else {
                    $this->error("   ❌ Failed to check payment {$payment->id}: {$result['message']}");
                    $errors++;
                }

            } catch (\Exception $e) {
                $this->error("   ❌ Error checking payment {$payment->id}: {$e->getMessage()}");
                $errors++;
            }
        }

        $this->newLine();
        if ($dryRun) {
            $this->info("🔍 DRY RUN COMPLETE:");
            $this->info("   - Would update: {$updated} payments");
            $this->info("   - Errors: {$errors}");
            $this->info("   - Run without --dry-run to apply changes");
        } else {
            $this->info("✅ SYNC COMPLETE:");
            $this->info("   - Updated: {$updated} payments");
            $this->info("   - Errors: {$errors}");
        }
    }
}

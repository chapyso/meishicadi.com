<?php

namespace App\Console\Commands;

use App\Models\BulkTransfer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CleanupExpiredTransfers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transfers:cleanup {--days=7 : Number of days to keep expired transfers}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up expired bulk transfers and their associated files';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');
        $cutoffDate = now()->subDays($days);

        $this->info("Cleaning up transfers expired before {$cutoffDate->format('Y-m-d H:i:s')}...");

        // Find expired transfers
        $expiredTransfers = BulkTransfer::where('expires_at', '<', $cutoffDate)
            ->with('files')
            ->get();

        if ($expiredTransfers->isEmpty()) {
            $this->info('No expired transfers found.');
            return 0;
        }

        $this->info("Found {$expiredTransfers->count()} expired transfers to clean up.");

        $deletedCount = 0;
        $errorCount = 0;

        foreach ($expiredTransfers as $transfer) {
            try {
                DB::beginTransaction();

                // Delete associated files from storage
                foreach ($transfer->files as $file) {
                    $this->deleteFileFromStorage($file);
                }

                // Delete the transfer (this will cascade delete files and logs)
                $transfer->delete();

                DB::commit();
                $deletedCount++;

                $this->line("✓ Deleted transfer: {$transfer->transfer_id}");

            } catch (\Exception $e) {
                DB::rollBack();
                $errorCount++;

                $this->error("✗ Failed to delete transfer {$transfer->transfer_id}: {$e->getMessage()}");
            }
        }

        $this->info("\nCleanup completed:");
        $this->info("- Successfully deleted: {$deletedCount} transfers");
        $this->info("- Errors: {$errorCount} transfers");

        return 0;
    }

    /**
     * Delete file from storage
     */
    private function deleteFileFromStorage($file): void
    {
        try {
            $filePath = $file->getFullPath();
            
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            // Also clean up any chunk files that might still exist
            $chunkPattern = $filePath . '.chunk.*';
            $chunkFiles = glob($chunkPattern);
            
            foreach ($chunkFiles as $chunkFile) {
                if (file_exists($chunkFile)) {
                    unlink($chunkFile);
                }
            }

        } catch (\Exception $e) {
            $this->warn("Warning: Could not delete file {$file->original_name}: {$e->getMessage()}");
        }
    }
} 
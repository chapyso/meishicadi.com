<?php

namespace App\Http\Controllers;

use App\Models\BulkTransfer;
use App\Models\BulkTransferFile;
use App\Models\BulkTransferLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use ZipArchive;

class BulkTransferController extends Controller
{
    /**
     * Show the bulk transfer interface
     */
    public function index()
    {
        if (request()->ajax()) {
            $transfers = BulkTransfer::where('user_id', auth()->id())
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'transfers' => $transfers
            ]);
        }

        $transfers = BulkTransfer::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('bulk-transfer.index', compact('transfers'));
    }

    /**
     * Create a new transfer session
     */
    public function create(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'expires_in_days' => 'nullable|integer|min:1|max:30',
            'settings' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $transfer = BulkTransfer::create([
                'user_id' => auth()->id(),
                'title' => $request->title,
                'description' => $request->description,
                'expires_at' => $request->expires_in_days 
                    ? Carbon::now()->addDays($request->expires_in_days)
                    : Carbon::now()->addDays(7),
                'settings' => $request->settings ?? [
                    'chunk_size' => 1024 * 1024, // 1MB chunks
                    'max_concurrent_chunks' => 3,
                    'encryption_enabled' => false,
                ],
            ]);

            BulkTransferLog::createLog(
                $transfer->id,
                'transfer_created',
                'success',
                'Transfer session created',
                ['title' => $transfer->title]
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transfer session created successfully',
                'data' => [
                    'transfer_id' => $transfer->transfer_id,
                    'access_token' => $transfer->access_token,
                    'share_url' => $transfer->getShareUrl(),
                    'expires_at' => $transfer->expires_at,
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            BulkTransferLog::createLog(
                $transfer->id ?? null,
                'transfer_created',
                'failed',
                'Failed to create transfer: ' . $e->getMessage()
            );

            return response()->json([
                'success' => false,
                'message' => 'Failed to create transfer session',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Initialize file upload
     */
    public function initializeUpload(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'transfer_id' => 'required|string',
            'file_name' => 'required|string|max:255',
            'file_size' => 'required|integer|min:1',
            'mime_type' => 'required|string|max:100',
            'checksum' => 'nullable|string|max:64',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $transfer = BulkTransfer::where('transfer_id', $request->transfer_id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$transfer) {
            return response()->json([
                'success' => false,
                'message' => 'Transfer not found'
            ], 404);
        }

        if ($transfer->is_expired) {
            return response()->json([
                'success' => false,
                'message' => 'Transfer has expired'
            ], 400);
        }

        try {
            DB::beginTransaction();

            // Calculate chunks
            $chunkSize = $transfer->settings['chunk_size'] ?? 1024 * 1024;
            $chunksTotal = ceil($request->file_size / $chunkSize);

            $file = BulkTransferFile::create([
                'bulk_transfer_id' => $transfer->id,
                'original_name' => $request->file_name,
                'stored_name' => Str::random(32) . '_' . $request->file_name,
                'mime_type' => $request->mime_type,
                'size' => $request->file_size,
                'path' => 'bulk-transfers/' . $transfer->transfer_id . '/' . Str::random(32),
                'chunks_total' => $chunksTotal,
                'checksum' => $request->checksum,
            ]);

            // Update transfer totals
            $transfer->total_files++;
            $transfer->total_size += $request->file_size;
            $transfer->save();

            BulkTransferLog::createLog(
                $transfer->id,
                'file_upload_initiated',
                'success',
                "File upload initiated: {$request->file_name}",
                [
                    'file_id' => $file->file_id,
                    'file_size' => $request->file_size,
                    'chunks_total' => $chunksTotal
                ],
                $file->file_id
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'File upload initialized',
                'data' => [
                    'file_id' => $file->file_id,
                    'chunks_total' => $chunksTotal,
                    'chunk_size' => $chunkSize,
                    'upload_url' => route('bulk-transfer.upload-chunk'),
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to initialize upload',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload file chunk
     */
    public function uploadChunk(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'transfer_id' => 'required|string',
            'file_id' => 'required|string',
            'chunk_index' => 'required|integer|min:0',
            'chunk_data' => 'required|string', // Base64 encoded chunk
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $transfer = BulkTransfer::where('transfer_id', $request->transfer_id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$transfer) {
            return response()->json([
                'success' => false,
                'message' => 'Transfer not found'
            ], 404);
        }

        $file = BulkTransferFile::where('file_id', $request->file_id)
            ->where('bulk_transfer_id', $transfer->id)
            ->first();

        if (!$file) {
            return response()->json([
                'success' => false,
                'message' => 'File not found'
            ], 404);
        }

        try {
            // Decode chunk data
            $chunkData = base64_decode($request->chunk_data);
            if ($chunkData === false) {
                throw new \Exception('Invalid chunk data');
            }

            // Create directory if it doesn't exist
            $directory = dirname($file->getFullPath());
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            // Write chunk to temporary file
            $chunkPath = $file->getFullPath() . '.chunk.' . $request->chunk_index;
            file_put_contents($chunkPath, $chunkData);

            // Update chunk status
            $file->updateChunkStatus($request->chunk_index, true);

            // Check if all chunks are uploaded
            if ($file->chunks_uploaded >= $file->chunks_total) {
                $this->assembleFile($file);
            }

            BulkTransferLog::createLog(
                $transfer->id,
                'chunk_uploaded',
                'success',
                "Chunk {$request->chunk_index} uploaded for {$file->original_name}",
                ['chunk_index' => $request->chunk_index],
                $file->file_id
            );

            return response()->json([
                'success' => true,
                'message' => 'Chunk uploaded successfully',
                'data' => [
                    'chunks_uploaded' => $file->chunks_uploaded,
                    'chunks_total' => $file->chunks_total,
                    'is_complete' => $file->isComplete(),
                ]
            ]);

        } catch (\Exception $e) {
            BulkTransferLog::createLog(
                $transfer->id,
                'chunk_upload_failed',
                'failed',
                "Chunk upload failed: {$e->getMessage()}",
                ['chunk_index' => $request->chunk_index],
                $file->file_id ?? null
            );

            return response()->json([
                'success' => false,
                'message' => 'Failed to upload chunk',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Assemble file from chunks
     */
    private function assembleFile(BulkTransferFile $file): void
    {
        try {
            $outputPath = $file->getFullPath();
            $outputHandle = fopen($outputPath, 'wb');

            if (!$outputHandle) {
                throw new \Exception('Cannot create output file');
            }

            // Combine all chunks
            for ($i = 0; $i < $file->chunks_total; $i++) {
                $chunkPath = $outputPath . '.chunk.' . $i;
                
                if (!file_exists($chunkPath)) {
                    throw new \Exception("Missing chunk {$i}");
                }

                $chunkData = file_get_contents($chunkPath);
                fwrite($outputHandle, $chunkData);
                
                // Delete chunk file
                unlink($chunkPath);
            }

            fclose($outputHandle);

            // Verify checksum if provided
            if ($file->checksum) {
                $calculatedChecksum = hash_file('sha256', $outputPath);
                if ($calculatedChecksum !== $file->checksum) {
                    throw new \Exception('Checksum verification failed');
                }
            }

            // Mark file as complete
            $file->markAsComplete();
            
            // Update transfer progress
            $file->transfer->updateProgress();

            // Check if transfer is complete
            if ($file->transfer->isComplete()) {
                $file->transfer->markAsComplete();
            }

            BulkTransferLog::createLog(
                $file->bulk_transfer_id,
                'file_assembled',
                'success',
                "File assembled successfully: {$file->original_name}",
                ['file_size' => $file->size],
                $file->file_id
            );

        } catch (\Exception $e) {
            BulkTransferLog::createLog(
                $file->bulk_transfer_id,
                'file_assembly_failed',
                'failed',
                "File assembly failed: {$e->getMessage()}",
                ['file_id' => $file->file_id]
            );
            throw $e;
        }
    }

    /**
     * Get transfer status
     */
    public function getStatus(Request $request): JsonResponse
    {
        $transfer = BulkTransfer::where('transfer_id', $request->transfer_id)
            ->where('user_id', auth()->id())
            ->with(['files' => function ($query) {
                $query->orderBy('created_at', 'asc');
            }])
            ->first();

        if (!$transfer) {
            return response()->json([
                'success' => false,
                'message' => 'Transfer not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'transfer' => $transfer,
                'files' => $transfer->files->map(function ($file) {
                    return [
                        'file_id' => $file->file_id,
                        'original_name' => $file->original_name,
                        'size' => $file->size,
                        'formatted_size' => $file->formatted_size,
                        'status' => $file->status,
                        'progress_percentage' => $file->progress_percentage,
                        'chunks_uploaded' => $file->chunks_uploaded,
                        'chunks_total' => $file->chunks_total,
                    ];
                }),
            ]
        ]);
    }

    /**
     * Download transfer as ZIP
     */
    public function download(Request $request)
    {
        $transfer = BulkTransfer::where('download_token', $request->token)
            ->with('files')
            ->first();

        if (!$transfer) {
            abort(404, 'Transfer not found');
        }

        if ($transfer->is_expired) {
            abort(410, 'Transfer has expired');
        }

        if (!$transfer->isComplete()) {
            abort(400, 'Transfer is not complete');
        }

        try {
            $zipPath = storage_path('app/temp/' . $transfer->transfer_id . '.zip');
            $zipDir = dirname($zipPath);
            
            if (!is_dir($zipDir)) {
                mkdir($zipDir, 0755, true);
            }

            $zip = new ZipArchive();
            if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
                throw new \Exception('Cannot create ZIP file');
            }

            foreach ($transfer->files as $file) {
                if ($file->isComplete() && file_exists($file->getFullPath())) {
                    $zip->addFile($file->getFullPath(), $file->original_name);
                }
            }

            $zip->close();

            BulkTransferLog::createLog(
                $transfer->id,
                'transfer_downloaded',
                'success',
                'Transfer downloaded as ZIP',
                ['files_count' => $transfer->files->count()]
            );

            return response()->download($zipPath, $transfer->title ?: 'transfer.zip')
                ->deleteFileAfterSend();

        } catch (\Exception $e) {
            BulkTransferLog::createLog(
                $transfer->id,
                'transfer_download_failed',
                'failed',
                "Download failed: {$e->getMessage()}"
            );

            abort(500, 'Failed to create download');
        }
    }

    /**
     * Share transfer page
     */
    public function share(Request $request)
    {
        $transfer = BulkTransfer::where('access_token', $request->token)
            ->with(['files', 'user'])
            ->first();

        if (!$transfer) {
            abort(404, 'Transfer not found');
        }

        if ($transfer->is_expired) {
            return view('bulk-transfer.expired', compact('transfer'));
        }

        return view('bulk-transfer.share', compact('transfer'));
    }

    /**
     * Delete transfer
     */
    public function destroy(Request $request): JsonResponse
    {
        $transfer = BulkTransfer::where('transfer_id', $request->transfer_id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$transfer) {
            return response()->json([
                'success' => false,
                'message' => 'Transfer not found'
            ], 404);
        }

        try {
            DB::beginTransaction();

            // Delete files from storage
            foreach ($transfer->files as $file) {
                if (file_exists($file->getFullPath())) {
                    unlink($file->getFullPath());
                }
            }

            // Delete transfer and related records
            $transfer->delete();

            BulkTransferLog::createLog(
                $transfer->id,
                'transfer_deleted',
                'success',
                'Transfer deleted',
                ['transfer_id' => $transfer->transfer_id]
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transfer deleted successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete transfer',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 
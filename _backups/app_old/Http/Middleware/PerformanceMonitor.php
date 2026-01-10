<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class PerformanceMonitor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $startTime = microtime(true);
        $startMemory = memory_get_usage();

        $response = $next($request);

        $endTime = microtime(true);
        $endMemory = memory_get_usage();

        $executionTime = ($endTime - $startTime) * 1000; // Convert to milliseconds
        $memoryUsage = $endMemory - $startMemory;

        // Log performance metrics for business edit pages
        if ($request->is('business/*/edit')) {
            $this->logPerformanceMetrics($request, $executionTime, $memoryUsage);
        }

        // Add performance headers for monitoring
        $response->headers->set('X-Execution-Time', round($executionTime, 2) . 'ms');
        $response->headers->set('X-Memory-Usage', $this->formatBytes($memoryUsage));

        return $response;
    }

    /**
     * Log performance metrics
     */
    private function logPerformanceMetrics(Request $request, float $executionTime, int $memoryUsage)
    {
        $metrics = [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'execution_time_ms' => round($executionTime, 2),
            'memory_usage_bytes' => $memoryUsage,
            'memory_usage_formatted' => $this->formatBytes($memoryUsage),
            'user_id' => auth()->id(),
            'timestamp' => now()->toISOString(),
            'user_agent' => $request->userAgent(),
            'ip' => $request->ip(),
        ];

        // Log to file
        Log::channel('performance')->info('Business Edit Performance', $metrics);

        // Store in cache for analytics
        $this->storePerformanceData($metrics);

        // Alert if performance is poor
        if ($executionTime > 2000) { // More than 2 seconds
            Log::channel('performance')->warning('Slow Business Edit Page', $metrics);
        }
    }

    /**
     * Store performance data for analytics
     */
    private function storePerformanceData(array $metrics)
    {
        $key = 'performance_metrics_' . date('Y-m-d');
        
        $existingData = Cache::get($key, []);
        $existingData[] = $metrics;
        
        // Keep only last 1000 entries
        if (count($existingData) > 1000) {
            $existingData = array_slice($existingData, -1000);
        }
        
        Cache::put($key, $existingData, 86400); // Store for 24 hours
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
} 
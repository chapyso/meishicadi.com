<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Services\AssetOptimizer;

class PerformanceController extends Controller
{
    /**
     * Display performance dashboard
     */
    public function dashboard()
    {
        $metrics = $this->getPerformanceMetrics();
        $optimizationStats = $this->getOptimizationStats();
        
        return view('performance.dashboard', compact('metrics', 'optimizationStats'));
    }

    /**
     * Get performance metrics
     */
    private function getPerformanceMetrics()
    {
        $metrics = [];
        
        // Get cached performance metrics
        $cachedMetrics = Cache::get('performance_metrics_*');
        if ($cachedMetrics) {
            $metrics['average_response_time'] = collect($cachedMetrics)->avg('response_time');
            $metrics['average_memory_usage'] = collect($cachedMetrics)->avg('memory_usage');
            $metrics['total_requests'] = count($cachedMetrics);
        }

        // Database performance
        $metrics['database_connections'] = DB::connection()->getPdo()->getAttribute(\PDO::ATTR_CONNECTION_STATUS);
        
        // Cache performance
        $metrics['cache_hit_rate'] = $this->calculateCacheHitRate();
        
        return $metrics;
    }

    /**
     * Get optimization statistics
     */
    private function getOptimizationStats()
    {
        $stats = [];
        
        // CSS optimization stats
        $originalCSSSize = $this->getDirectorySize('public/css');
        $optimizedCSSSize = file_exists('public/css/optimized.min.css') ? filesize('public/css/optimized.min.css') : 0;
        $stats['css_savings'] = $originalCSSSize > 0 ? (($originalCSSSize - $optimizedCSSSize) / $originalCSSSize) * 100 : 0;
        
        // JS optimization stats
        $originalJSSize = $this->getDirectorySize('public/js');
        $optimizedJSSize = file_exists('public/js/optimized.min.js') ? filesize('public/js/optimized.min.js') : 0;
        $stats['js_savings'] = $originalJSSize > 0 ? (($originalJSSize - $optimizedJSSize) / $originalJSSize) * 100 : 0;
        
        return $stats;
    }

    /**
     * Calculate cache hit rate
     */
    private function calculateCacheHitRate()
    {
        // This is a simplified calculation - in production you'd use Redis or Memcached stats
        return 85; // Placeholder value
    }

    /**
     * Get directory size
     */
    private function getDirectorySize($path)
    {
        $size = 0;
        if (is_dir($path)) {
            $files = scandir($path);
            foreach ($files as $file) {
                if ($file != '.' && $file != '..') {
                    $filePath = $path . '/' . $file;
                    if (is_file($filePath)) {
                        $size += filesize($filePath);
                    }
                }
            }
        }
        return $size;
    }

    /**
     * Run performance test
     */
    public function runTest()
    {
        $startTime = microtime(true);
        
        // Simulate some operations
        $this->simulateDatabaseQueries();
        $this->simulateCacheOperations();
        
        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime) * 1000; // Convert to milliseconds
        
        return response()->json([
            'execution_time' => round($executionTime, 2),
            'memory_usage' => memory_get_peak_usage(true),
            'status' => 'success'
        ]);
    }

    /**
     * Simulate database queries
     */
    private function simulateDatabaseQueries()
    {
        // Simulate some database operations
        DB::table('users')->count();
        DB::table('business')->count();
    }

    /**
     * Simulate cache operations
     */
    private function simulateCacheOperations()
    {
        Cache::put('test_key', 'test_value', 60);
        Cache::get('test_key');
        Cache::forget('test_key');
    }

    /**
     * Optimize assets via API
     */
    public function optimizeAssets()
    {
        try {
            $optimizer = new AssetOptimizer();
            $cssPath = $optimizer->optimizeCSS();
            $jsPath = $optimizer->optimizeJS();
            $manifest = $optimizer->generateManifest();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Assets optimized successfully',
                'files' => [
                    'css' => $cssPath,
                    'js' => $jsPath
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
} 
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PerformanceOptimizer
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
        $response = $next($request);

        // Add performance headers
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Enable compression for text-based responses
        if ($this->shouldCompress($response)) {
            $response->headers->set('Content-Encoding', 'gzip');
        }

        // Add cache headers for static assets
        if ($this->isStaticAsset($request)) {
            $response->headers->set('Cache-Control', 'public, max-age=31536000');
            $response->headers->set('Expires', gmdate('D, d M Y H:i:s \G\M\T', time() + 31536000));
        }

        // Log performance metrics
        $this->logPerformanceMetrics($request, $response);

        return $response;
    }

    /**
     * Check if response should be compressed
     */
    private function shouldCompress($response)
    {
        $contentType = $response->headers->get('Content-Type');
        $compressibleTypes = [
            'text/html',
            'text/css',
            'application/javascript',
            'application/json',
            'text/plain',
            'text/xml'
        ];

        foreach ($compressibleTypes as $type) {
            if (strpos($contentType, $type) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if request is for static asset
     */
    private function isStaticAsset(Request $request)
    {
        $path = $request->path();
        $staticExtensions = ['css', 'js', 'png', 'jpg', 'jpeg', 'gif', 'webp', 'svg', 'woff', 'woff2', 'ttf', 'eot', 'ico'];

        foreach ($staticExtensions as $ext) {
            if (str_ends_with($path, '.' . $ext)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Log performance metrics
     */
    private function logPerformanceMetrics(Request $request, $response)
    {
        $startTime = microtime(true);
        $memoryUsage = memory_get_peak_usage(true);
        
        $metrics = [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'response_time' => microtime(true) - $startTime,
            'memory_usage' => $memoryUsage,
            'response_size' => strlen($response->getContent()),
            'timestamp' => now()
        ];

        // Cache metrics for analysis
        Cache::put('performance_metrics_' . uniqid(), $metrics, 3600);
    }
} 
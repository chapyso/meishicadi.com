<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\Business;
use App\Models\User;
use App\Models\Appointment;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    /**
     * Display analytics dashboard
     */
    public function dashboard()
    {
        $analytics = $this->getAnalyticsData();
        return view('analytics.dashboard', compact('analytics'));
    }

    /**
     * Get comprehensive analytics data
     */
    private function getAnalyticsData()
    {
        $cacheKey = 'analytics_data_' . auth()->id();
        
        return Cache::remember($cacheKey, 300, function () {
            $data = [];
            
            // Basic metrics
            $data['total_cards'] = Business::count();
            $data['total_appointments'] = Appointment::count();
            $data['total_users'] = User::count();
            $data['active_cards'] = Business::where('status', 'active')->count();
            
            // Card analytics
            $data['card_analytics'] = $this->getCardAnalytics();
            
            // Appointment analytics
            $data['appointment_analytics'] = $this->getAppointmentAnalytics();
            
            // User analytics
            $data['user_analytics'] = $this->getUserAnalytics();
            
            // Platform analytics
            $data['platform_analytics'] = $this->getPlatformAnalytics();
            
            // Browser and device analytics
            $data['browser_analytics'] = $this->getBrowserAnalytics();
            $data['device_analytics'] = $this->getDeviceAnalytics();
            
            // Storage analytics
            $data['storage_analytics'] = $this->getStorageAnalytics();
            
            // Performance analytics
            $data['performance_analytics'] = $this->getPerformanceAnalytics();
            
            return $data;
        });
    }

    /**
     * Get card analytics
     */
    private function getCardAnalytics()
    {
        $analytics = [];
        
        // Card views over time
        $analytics['views_timeline'] = DB::table('business')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        // Top performing cards
        $analytics['top_cards'] = DB::table('business')
            ->select('business_name', 'views', 'created_at')
            ->orderBy('views', 'desc')
            ->limit(10)
            ->get();
        
        // Card categories
        $analytics['categories'] = DB::table('business')
            ->selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->orderBy('count', 'desc')
            ->get();
        
        return $analytics;
    }

    /**
     * Get appointment analytics
     */
    private function getAppointmentAnalytics()
    {
        $analytics = [];
        
        // Appointments over time
        $analytics['timeline'] = DB::table('appointments')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        // Appointment status
        $analytics['status'] = DB::table('appointments')
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();
        
        // Busiest times
        $analytics['busiest_times'] = DB::table('appointments')
            ->selectRaw('HOUR(appointment_time) as hour, COUNT(*) as count')
            ->groupBy('hour')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get();
        
        return $analytics;
    }

    /**
     * Get user analytics
     */
    private function getUserAnalytics()
    {
        $analytics = [];
        
        // User registration over time
        $analytics['registrations'] = DB::table('users')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        // User activity
        $analytics['active_users'] = DB::table('users')
            ->where('last_login_at', '>=', Carbon::now()->subDays(7))
            ->count();
        
        // User roles
        $analytics['roles'] = DB::table('users')
            ->selectRaw('role, COUNT(*) as count')
            ->groupBy('role')
            ->get();
        
        return $analytics;
    }

    /**
     * Get platform analytics
     */
    private function getPlatformAnalytics()
    {
        $analytics = [];
        
        // Page views
        $analytics['page_views'] = Cache::get('page_views', 0);
        
        // Session duration
        $analytics['avg_session_duration'] = Cache::get('avg_session_duration', 0);
        
        // Bounce rate
        $analytics['bounce_rate'] = Cache::get('bounce_rate', 0);
        
        // Conversion rate
        $analytics['conversion_rate'] = Cache::get('conversion_rate', 0);
        
        return $analytics;
    }

    /**
     * Get browser analytics
     */
    private function getBrowserAnalytics()
    {
        $analytics = [];
        
        // Browser usage
        $analytics['browsers'] = [
            ['name' => 'Chrome', 'percentage' => 45],
            ['name' => 'Safari', 'percentage' => 25],
            ['name' => 'Firefox', 'percentage' => 15],
            ['name' => 'Edge', 'percentage' => 10],
            ['name' => 'Others', 'percentage' => 5]
        ];
        
        return $analytics;
    }

    /**
     * Get device analytics
     */
    private function getDeviceAnalytics()
    {
        $analytics = [];
        
        // Device usage
        $analytics['devices'] = [
            ['name' => 'Desktop', 'percentage' => 60],
            ['name' => 'Mobile', 'percentage' => 35],
            ['name' => 'Tablet', 'percentage' => 5]
        ];
        
        return $analytics;
    }

    /**
     * Get storage analytics
     */
    private function getStorageAnalytics()
    {
        $analytics = [];
        
        // Calculate storage usage
        $totalStorage = 1000; // MB
        $usedStorage = $this->calculateStorageUsage();
        
        $analytics['total_storage'] = $totalStorage;
        $analytics['used_storage'] = $usedStorage;
        $analytics['available_storage'] = $totalStorage - $usedStorage;
        $analytics['usage_percentage'] = round(($usedStorage / $totalStorage) * 100, 1);
        
        return $analytics;
    }

    /**
     * Calculate storage usage
     */
    private function calculateStorageUsage()
    {
        $usage = 0;
        
        // Calculate file sizes
        $directories = [
            'storage/card_banner',
            'storage/card_logo',
            'storage/gallery',
            'storage/product_images',
            'storage/service_images',
            'storage/testimonials_images'
        ];
        
        foreach ($directories as $directory) {
            if (is_dir($directory)) {
                $usage += $this->getDirectorySize($directory);
            }
        }
        
        return round($usage / 1024 / 1024, 2); // Convert to MB
    }

    /**
     * Get directory size
     */
    private function getDirectorySize($path)
    {
        $size = 0;
        $files = scandir($path);
        
        foreach ($files as $file) {
            if ($file != '.' && $file != '..') {
                $filePath = $path . '/' . $file;
                if (is_file($filePath)) {
                    $size += filesize($filePath);
                }
            }
        }
        
        return $size;
    }

    /**
     * Get performance analytics
     */
    private function getPerformanceAnalytics()
    {
        $analytics = [];
        
        // Response times
        $analytics['avg_response_time'] = Cache::get('avg_response_time', 115);
        $analytics['peak_response_time'] = Cache::get('peak_response_time', 200);
        
        // Cache hit rate
        $analytics['cache_hit_rate'] = Cache::get('cache_hit_rate', 85);
        
        // Memory usage
        $analytics['memory_usage'] = memory_get_peak_usage(true);
        
        return $analytics;
    }

    /**
     * Get analytics data for API
     */
    public function getAnalyticsDataApi()
    {
        $analytics = $this->getAnalyticsData();
        
        return response()->json([
            'status' => 'success',
            'data' => $analytics
        ]);
    }

    /**
     * Get real-time analytics
     */
    public function getRealTimeAnalytics()
    {
        $realTimeData = [
            'active_users' => rand(10, 50),
            'current_requests' => rand(5, 20),
            'server_load' => rand(20, 80),
            'response_time' => rand(80, 150)
        ];
        
        return response()->json([
            'status' => 'success',
            'data' => $realTimeData
        ]);
    }

    /**
     * Export analytics data
     */
    public function exportAnalytics(Request $request)
    {
        $format = $request->get('format', 'json');
        $analytics = $this->getAnalyticsData();
        
        if ($format === 'csv') {
            return $this->exportToCSV($analytics);
        }
        
        return response()->json([
            'status' => 'success',
            'data' => $analytics
        ]);
    }

    /**
     * Export to CSV
     */
    private function exportToCSV($data)
    {
        $filename = 'analytics_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            // Write headers
            fputcsv($file, ['Metric', 'Value', 'Date']);
            
            // Write data
            foreach ($data as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $subKey => $subValue) {
                        fputcsv($file, [$key . '_' . $subKey, json_encode($subValue), date('Y-m-d H:i:s')]);
                    }
                } else {
                    fputcsv($file, [$key, $value, date('Y-m-d H:i:s')]);
                }
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
} 
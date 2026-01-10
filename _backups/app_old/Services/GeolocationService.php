<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GeolocationService
{
    protected $apiKey;
    protected $cacheDuration = 86400; // 24 hours

    public function __construct()
    {
        $this->apiKey = config('services.ipapi.key', env('IPAPI_KEY'));
    }

    /**
     * Get location data from IP address
     */
    public function getLocationFromIp($ipAddress)
    {
        // Skip local IPs
        if ($this->isLocalIp($ipAddress)) {
            return $this->getDefaultLocation();
        }

        // Check cache first
        $cacheKey = 'geolocation_' . md5($ipAddress);
        $cachedData = Cache::get($cacheKey);
        
        if ($cachedData) {
            return $cachedData;
        }

        try {
            $locationData = $this->fetchLocationFromApi($ipAddress);
            
            if ($locationData) {
                // Cache the result
                Cache::put($cacheKey, $locationData, $this->cacheDuration);
                return $locationData;
            }
        } catch (\Exception $e) {
            Log::error('Geolocation API error', [
                'ip' => $ipAddress,
                'error' => $e->getMessage()
            ]);
        }

        return $this->getDefaultLocation();
    }

    /**
     * Fetch location data from IP-API
     */
    protected function fetchLocationFromApi($ipAddress)
    {
        $url = "http://ip-api.com/json/{$ipAddress}";
        
        if ($this->apiKey) {
            $url .= "?key={$this->apiKey}";
        }

        $response = Http::timeout(5)->get($url);
        
        if ($response->successful()) {
            $data = $response->json();
            
            if ($data['status'] === 'success') {
                return [
                    'country' => $data['country'] ?? null,
                    'city' => $data['city'] ?? null,
                    'region' => $data['regionName'] ?? null,
                    'latitude' => $data['lat'] ?? null,
                    'longitude' => $data['lon'] ?? null,
                    'timezone' => $data['timezone'] ?? null,
                ];
            }
        }

        return null;
    }

    /**
     * Check if IP is local/private
     */
    protected function isLocalIp($ipAddress)
    {
        return in_array($ipAddress, [
            '127.0.0.1',
            '::1',
            'localhost'
        ]) || filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false;
    }

    /**
     * Get default location for local IPs
     */
    protected function getDefaultLocation()
    {
        return [
            'country' => 'Unknown',
            'city' => 'Unknown',
            'region' => 'Unknown',
            'latitude' => null,
            'longitude' => null,
            'timezone' => 'UTC',
        ];
    }

    /**
     * Get device type from user agent
     */
    public function getDeviceType($userAgent)
    {
        if (empty($userAgent)) {
            return 'unknown';
        }

        $userAgent = strtolower($userAgent);

        if (preg_match('/(android|webos|iphone|ipad|ipod|blackberry|windows phone)/', $userAgent)) {
            if (preg_match('/(ipad|tablet)/', $userAgent)) {
                return 'tablet';
            }
            return 'mobile';
        }

        return 'desktop';
    }

    /**
     * Get browser info from user agent
     */
    public function getBrowserInfo($userAgent)
    {
        if (empty($userAgent)) {
            return ['browser' => 'Unknown', 'platform' => 'Unknown'];
        }

        $browser = 'Unknown';
        $platform = 'Unknown';

        // Detect browser
        if (preg_match('/chrome/i', $userAgent)) {
            $browser = 'Chrome';
        } elseif (preg_match('/firefox/i', $userAgent)) {
            $browser = 'Firefox';
        } elseif (preg_match('/safari/i', $userAgent)) {
            $browser = 'Safari';
        } elseif (preg_match('/edge/i', $userAgent)) {
            $browser = 'Edge';
        } elseif (preg_match('/opera/i', $userAgent)) {
            $browser = 'Opera';
        }

        // Detect platform
        if (preg_match('/windows/i', $userAgent)) {
            $platform = 'Windows';
        } elseif (preg_match('/macintosh|mac os x/i', $userAgent)) {
            $platform = 'macOS';
        } elseif (preg_match('/linux/i', $userAgent)) {
            $platform = 'Linux';
        } elseif (preg_match('/android/i', $userAgent)) {
            $platform = 'Android';
        } elseif (preg_match('/iphone|ipad|ipod/i', $userAgent)) {
            $platform = 'iOS';
        }

        return [
            'browser' => $browser,
            'platform' => $platform
        ];
    }

    /**
     * Extract UTM parameters from referrer
     */
    public function extractUtmParams($referrer)
    {
        if (empty($referrer)) {
            return [];
        }

        $url = parse_url($referrer);
        $params = [];

        if (isset($url['query'])) {
            parse_str($url['query'], $queryParams);
            
            $utmFields = ['utm_source', 'utm_medium', 'utm_campaign'];
            foreach ($utmFields as $field) {
                if (isset($queryParams[$field])) {
                    $params[$field] = $queryParams[$field];
                }
            }
        }

        return $params;
    }
} 
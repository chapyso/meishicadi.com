<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LogoSliderController extends Controller
{
    /**
     * Get business logos for the logo slider
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBusinessLogos(Request $request)
    {
        try {
            // Get active businesses with logos
            $businesses = Business::whereNotNull('logo')
                ->where('logo', '!=', '')
                ->whereHas('creator', function($query) {
                    $query->where('delete_status', '!=', 1);
                })
                ->with(['creator' => function($query) {
                    $query->select('id', 'name', 'type');
                }])
                ->select('id', 'title', 'logo', 'created_by', 'created_at')
                ->orderBy('created_at', 'desc')
                ->limit(20) // Limit to 20 businesses for performance
                ->get();

            $logoData = [];
            $logoPath = \App\Models\Utility::get_file('card_logo/');

            foreach ($businesses as $business) {
                // Check if logo file exists
                if (Storage::disk('local')->exists('card_logo/' . $business->logo)) {
                    $logoData[] = [
                        'id' => $business->id,
                        'title' => $business->title,
                        'logo_url' => $logoPath . '/' . $business->logo,
                        'business_name' => $business->title,
                        'created_at' => $business->created_at->format('M Y'),
                        'creator_name' => $business->creator->name ?? 'Unknown',
                        'fallback_text' => $this->generateFallbackText($business->title)
                    ];
                }
            }

            // If we don't have enough logos, add some default ones
            if (count($logoData) < 8) {
                $defaultLogos = $this->getDefaultLogos();
                $logoData = array_merge($logoData, $defaultLogos);
            }

            // Shuffle the logos for variety
            shuffle($logoData);

            return response()->json([
                'success' => true,
                'data' => $logoData,
                'total' => count($logoData)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching business logos: ' . $e->getMessage(),
                'data' => $this->getDefaultLogos()
            ]);
        }
    }

    /**
     * Get logos for public display (landing page, etc.)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPublicLogos(Request $request)
    {
        try {
            // Get featured businesses (you can add a 'featured' column to businesses table)
            $businesses = Business::whereNotNull('logo')
                ->where('logo', '!=', '')
                ->whereHas('creator', function($query) {
                    $query->where('delete_status', '!=', 1);
                })
                ->with(['creator' => function($query) {
                    $query->select('id', 'name', 'type');
                }])
                ->select('id', 'title', 'logo', 'created_by', 'created_at')
                ->orderBy('created_at', 'desc')
                ->limit(12)
                ->get();

            $logoData = [];
            $logoPath = \App\Models\Utility::get_file('card_logo/');

            foreach ($businesses as $business) {
                if (Storage::disk('local')->exists('card_logo/' . $business->logo)) {
                    $logoData[] = [
                        'id' => $business->id,
                        'title' => $business->title,
                        'logo_url' => $logoPath . '/' . $business->logo,
                        'business_name' => $business->title,
                        'fallback_text' => $this->generateFallbackText($business->title)
                    ];
                }
            }

            // Add default logos if needed
            if (count($logoData) < 6) {
                $defaultLogos = $this->getDefaultLogos();
                $logoData = array_merge($logoData, $defaultLogos);
            }

            return response()->json([
                'success' => true,
                'data' => $logoData,
                'total' => count($logoData)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching public logos: ' . $e->getMessage(),
                'data' => $this->getDefaultLogos()
            ]);
        }
    }

    /**
     * Get logos for admin dashboard
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAdminLogos(Request $request)
    {
        try {
            // Get recent businesses created by the current user or their companies
            $user = auth()->user();
            
            $businesses = Business::whereNotNull('logo')
                ->where('logo', '!=', '')
                ->where(function($query) use ($user) {
                    if ($user->type === 'super admin') {
                        // Super admin sees all businesses
                        $query->whereHas('creator', function($q) {
                            $q->where('delete_status', '!=', 1);
                        });
                    } else {
                        // Company admin sees their own businesses
                        $query->where('created_by', $user->id);
                    }
                })
                ->with(['creator' => function($query) {
                    $query->select('id', 'name', 'type');
                }])
                ->select('id', 'title', 'logo', 'created_by', 'created_at')
                ->orderBy('created_at', 'desc')
                ->limit(15)
                ->get();

            $logoData = [];
            $logoPath = \App\Models\Utility::get_file('card_logo/');

            foreach ($businesses as $business) {
                if (Storage::disk('local')->exists('card_logo/' . $business->logo)) {
                    $logoData[] = [
                        'id' => $business->id,
                        'title' => $business->title,
                        'logo_url' => $logoPath . '/' . $business->logo,
                        'business_name' => $business->title,
                        'created_at' => $business->created_at->format('M Y'),
                        'creator_name' => $business->creator->name ?? 'Unknown',
                        'fallback_text' => $this->generateFallbackText($business->title)
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'data' => $logoData,
                'total' => count($logoData)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching admin logos: ' . $e->getMessage(),
                'data' => []
            ]);
        }
    }

    /**
     * Generate fallback text for businesses without logos
     * 
     * @param string $businessName
     * @return string
     */
    private function generateFallbackText($businessName)
    {
        // Extract initials from business name
        $words = explode(' ', $businessName);
        $initials = '';
        
        foreach ($words as $word) {
            if (!empty($word)) {
                $initials .= strtoupper(substr($word, 0, 1));
            }
        }
        
        // Limit to 3 characters
        return substr($initials, 0, 3);
    }

    /**
     * Get default logos when no business logos are available
     * 
     * @return array
     */
    private function getDefaultLogos()
    {
        return [
            [
                'id' => 'default-1',
                'title' => 'TechCorp',
                'logo_url' => asset('landing/assets/img/default-logo-1.png'),
                'business_name' => 'TechCorp',
                'fallback_text' => 'TC',
                'is_default' => true
            ],
            [
                'id' => 'default-2',
                'title' => 'InnovateLab',
                'logo_url' => asset('landing/assets/img/default-logo-2.png'),
                'business_name' => 'InnovateLab',
                'fallback_text' => 'IL',
                'is_default' => true
            ],
            [
                'id' => 'default-3',
                'title' => 'GlobalSoft',
                'logo_url' => asset('landing/assets/img/default-logo-3.png'),
                'business_name' => 'GlobalSoft',
                'fallback_text' => 'GS',
                'is_default' => true
            ],
            [
                'id' => 'default-4',
                'title' => 'DataFlow',
                'logo_url' => asset('landing/assets/img/default-logo-4.png'),
                'business_name' => 'DataFlow',
                'fallback_text' => 'DF',
                'is_default' => true
            ],
            [
                'id' => 'default-5',
                'title' => 'CloudTech',
                'logo_url' => asset('landing/assets/img/default-logo-5.png'),
                'business_name' => 'CloudTech',
                'fallback_text' => 'CT',
                'is_default' => true
            ],
            [
                'id' => 'default-6',
                'title' => 'SmartBiz',
                'logo_url' => asset('landing/assets/img/default-logo-6.png'),
                'business_name' => 'SmartBiz',
                'fallback_text' => 'SB',
                'is_default' => true
            ]
        ];
    }

    /**
     * Update logo slider cache
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateCache()
    {
        try {
            // Clear any existing cache
            \Cache::forget('business_logos_public');
            \Cache::forget('business_logos_admin');
            
            // Regenerate cache
            $publicLogos = $this->getPublicLogos(request());
            $adminLogos = $this->getAdminLogos(request());
            
            \Cache::put('business_logos_public', $publicLogos->getData(), now()->addHours(6));
            \Cache::put('business_logos_admin', $adminLogos->getData(), now()->addHours(6));
            
            return response()->json([
                'success' => true,
                'message' => 'Logo slider cache updated successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating cache: ' . $e->getMessage()
            ]);
        }
    }
} 
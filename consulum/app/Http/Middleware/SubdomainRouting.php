<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Business;

class SubdomainRouting
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
        $host = $request->getHost();
        
        // Extract subdomain from host
        // Example: subdomain.example.com -> subdomain
        $hostParts = explode('.', $host);
        
        // Skip if this is localhost or IP address
        if (count($hostParts) <= 2 || in_array($host, ['localhost', '127.0.0.1'])) {
            return $next($request);
        }
        
        // Get subdomain (first part before the main domain)
        $subdomain = $hostParts[0];
        
        // Skip 'www' subdomain
        if ($subdomain === 'www') {
            return $next($request);
        }
        
        // Check if a business exists with this subdomain enabled
        $business = Business::where('subdomain', $host)
            ->where('enable_subdomain', 'on')
            ->first();
        
        // If not found, try matching just the subdomain part
        if (!$business) {
            $business = Business::where('subdomain', 'like', $subdomain . '.%')
                ->where('enable_subdomain', 'on')
                ->first();
        }
        
        // Store business in request for later use
        if ($business) {
            $request->attributes->set('business_by_subdomain', $business);
            $request->attributes->set('subdomain', $subdomain);
        }
        
        return $next($request);
    }
}




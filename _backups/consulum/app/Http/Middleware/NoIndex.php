<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class NoIndex
{
    /**
     * Force no-index headers on every response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Apply X-Robots-Tag to all responses (web + API).
        $response->headers->set('X-Robots-Tag', 'noindex, nofollow, noarchive, nosnippet');

        // Ensure robots.txt is always served as plain text and not cached.
        if ($request->is('robots.txt')) {
            $response->headers->set('Content-Type', 'text/plain');
            $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
            $response->headers->set('Pragma', 'no-cache');
        }

        return $response;
    }
}

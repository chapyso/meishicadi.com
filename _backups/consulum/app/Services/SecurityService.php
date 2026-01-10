<?php

namespace App\Services;

/**
 * Security Service
 * Provides methods to sanitize user input to prevent XSS and phishing attacks
 */
class SecurityService
{
    /**
     * Sanitize JavaScript code
     * Removes dangerous patterns that could be used for XSS or phishing
     * 
     * @param string|null $jsCode
     * @return string
     */
    public static function sanitizeJavaScript(?string $jsCode): string
    {
        if (empty($jsCode)) {
            return '';
        }

        $sanitized = $jsCode;

        // Remove dangerous JavaScript patterns
        $dangerousPatterns = [
            // Event handlers
            '/on\w+\s*=/i',
            // Dangerous functions
            '/eval\s*\(/i',
            '/Function\s*\(/i',
            '/setTimeout\s*\(\s*["\']?[^"\']*["\']?\s*,\s*\d+\)/i',
            '/setInterval\s*\(\s*["\']?[^"\']*["\']?\s*,\s*\d+\)/i',
            // External script loading
            '/\.src\s*=\s*["\']?https?:\/\/(?!cdnjs\.cloudflare\.com|maps\.googleapis\.com|www\.googleapis\.com|connect\.facebook\.net|www\.googletagmanager\.com)[^"\']+/i',
            // Dangerous DOM manipulation
            '/document\.(write|writeln|cookie|domain)/i',
            '/window\.(location|open|eval|Function)/i',
            // Base64 encoded code
            '/atob\s*\(/i',
            '/btoa\s*\(/i',
            // Obfuscation attempts
            '/fromCharCode\s*\(/i',
            '/unescape\s*\(/i',
            '/decodeURIComponent\s*\(\s*["\'][^"\']*["\']\s*\)\s*\.\s*eval/i',
            // Iframe injection
            '/<iframe[^>]*>/i',
            // Form submission hijacking
            '/\.submit\s*\(\s*\)/i',
            '/addEventListener\s*\(\s*["\']submit["\']/i',
            // Data exfiltration patterns
            '/XMLHttpRequest\s*\(\s*\)/i',
            '/fetch\s*\(\s*["\']?https?:\/\/[^"\']+["\']?/i',
            '/\.send\s*\(\s*["\']?[^"\']*["\']?\s*\)/i',
        ];

        foreach ($dangerousPatterns as $pattern) {
            $sanitized = preg_replace($pattern, '', $sanitized);
        }

        // Remove script tags entirely
        $sanitized = preg_replace('/<script[^>]*>.*?<\/script>/is', '', $sanitized);
        $sanitized = preg_replace('/<\/?script[^>]*>/i', '', $sanitized);

        // Remove comments that might hide malicious code
        $sanitized = preg_replace('/<!--.*?-->/s', '', $sanitized);
        $sanitized = preg_replace('/\/\*.*?\*\//s', '', $sanitized);

        return trim($sanitized);
    }

    /**
     * Sanitize HTML content
     * Allows safe HTML tags but removes dangerous attributes and scripts
     * 
     * @param string|null $html
     * @return string
     */
    public static function sanitizeHtml(?string $html): string
    {
        if (empty($html)) {
            return '';
        }

        $sanitized = $html;

        // Remove script tags and content
        $sanitized = preg_replace('/<script[^>]*>.*?<\/script>/is', '', $sanitized);
        $sanitized = preg_replace('/<\/?script[^>]*>/i', '', $sanitized);

        // Remove event handlers from all tags
        $sanitized = preg_replace('/\s*on\w+\s*=\s*["\'][^"\']*["\']/i', '', $sanitized);
        $sanitized = preg_replace('/\s*on\w+\s*=\s*[^\s>]+/i', '', $sanitized);

        // Remove javascript: protocol
        $sanitized = preg_replace('/javascript\s*:/i', '', $sanitized);
        $sanitized = preg_replace('/href\s*=\s*["\']?\s*javascript\s*:/i', 'href="#', $sanitized);

        // Remove data URIs with javascript
        $sanitized = preg_replace('/data\s*:\s*text\/html[^,]*,\s*.*?(;|")/i', '', $sanitized);

        // Remove dangerous iframe sources
        $sanitized = preg_replace('/<iframe[^>]*src\s*=\s*["\']?javascript:/i', '<iframe src="about:blank"', $sanitized);

        // Remove style tags that could contain malicious CSS
        $sanitized = preg_replace('/<style[^>]*>.*?<\/style>/is', '', $sanitized);

        // Remove object and embed tags (often used for XSS)
        $sanitized = preg_replace('/<object[^>]*>.*?<\/object>/is', '', $sanitized);
        $sanitized = preg_replace('/<embed[^>]*>/i', '', $sanitized);

        // Remove form tags to prevent form hijacking
        $sanitized = preg_replace('/<form[^>]*>.*?<\/form>/is', '', $sanitized);

        // Remove meta refresh redirects
        $sanitized = preg_replace('/<meta[^>]*http-equiv\s*=\s*["\']?refresh["\']?[^>]*>/i', '', $sanitized);

        // Escape remaining content
        $allowedTags = '<p><br><strong><em><u><a><img><div><span><h1><h2><h3><h4><h5><h6><ul><ol><li><table><tr><td><th><thead><tbody><tfoot>';
        $sanitized = strip_tags($sanitized, $allowedTags);

        return trim($sanitized);
    }

    /**
     * Validate and sanitize custom JavaScript for safe output
     * This is a stricter version that completely blocks dangerous code
     * 
     * @param string|null $jsCode
     * @return string
     */
    public static function validateJavaScript(?string $jsCode): string
    {
        if (empty($jsCode)) {
            return '';
        }

        // First sanitize
        $sanitized = self::sanitizeJavaScript($jsCode);

        // Additional validation: Check for suspicious patterns
        $suspiciousPatterns = [
            '/document\.(cookie|domain|location)/i',
            '/window\.(location|open)/i',
            '/XMLHttpRequest/i',
            '/fetch\s*\(/i',
            '/\.innerHTML\s*=/i',
            '/\.outerHTML\s*=/i',
            '/document\.write/i',
        ];

        foreach ($suspiciousPatterns as $pattern) {
            if (preg_match($pattern, $sanitized)) {
                // Log suspicious activity (optional)
                \Log::warning('Suspicious JavaScript pattern detected and removed', [
                    'pattern' => $pattern,
                    'user_id' => auth()->id() ?? null
                ]);
                $sanitized = preg_replace($pattern, '', $sanitized);
            }
        }

        return trim($sanitized);
    }
}




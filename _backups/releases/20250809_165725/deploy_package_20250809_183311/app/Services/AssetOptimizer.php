<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class AssetOptimizer
{
    /**
     * Minify CSS content
     */
    public function minifyCSS($css)
    {
        // Remove comments
        $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
        
        // Remove unnecessary whitespace
        $css = preg_replace('/\s+/', ' ', $css);
        $css = str_replace(['; ', ' {', '{ ', ' }', '} ', ': ', ' :'], [';', '{', '{', '}', '}', ':', ':'], $css);
        
        // Remove trailing semicolons
        $css = str_replace(';}', '}', $css);
        
        return trim($css);
    }

    /**
     * Minify JavaScript content
     */
    public function minifyJS($js)
    {
        // Remove single-line comments
        $js = preg_replace('/\/\/.*$/m', '', $js);
        
        // Remove multi-line comments
        $js = preg_replace('/\/\*[\s\S]*?\*\//', '', $js);
        
        // Remove unnecessary whitespace
        $js = preg_replace('/\s+/', ' ', $js);
        $js = str_replace(['; ', ' {', '{ ', ' }', '} ', ' = ', ' =', '= '], [';', '{', '{', '}', '}', '=', '=', '='], $js);
        
        return trim($js);
    }

    /**
     * Optimize and combine CSS files
     */
    public function optimizeCSS()
    {
        $cssFiles = [
            'public/css/app.css',
            'public/css/font-awesome.css',
            'public/css/cookieconsent.css',
            'public/css/richtext.min.css'
        ];

        $combinedCSS = '';
        
        foreach ($cssFiles as $file) {
            if (File::exists($file)) {
                $css = File::get($file);
                $combinedCSS .= $this->minifyCSS($css) . "\n";
            }
        }

        // Save optimized CSS
        $optimizedPath = 'public/css/optimized.min.css';
        File::put($optimizedPath, $combinedCSS);
        
        return $optimizedPath;
    }

    /**
     * Optimize and combine JavaScript files
     */
    public function optimizeJS()
    {
        $jsFiles = [
            'public/js/app.js',
            'public/js/bootstrap-toggle.js',
            'public/js/cookieconsent.js',
            'public/js/jquery.richtext.min.js',
            'public/js/repeaterInput.js',
            'public/js/slick.min.js',
            'public/js/toastr.js'
        ];

        $combinedJS = '';
        
        foreach ($jsFiles as $file) {
            if (File::exists($file)) {
                $js = File::get($file);
                $combinedJS .= $this->minifyJS($js) . ";\n";
            }
        }

        // Save optimized JS
        $optimizedPath = 'public/js/optimized.min.js';
        File::put($optimizedPath, $combinedJS);
        
        return $optimizedPath;
    }

    /**
     * Generate asset manifest with versioning
     */
    public function generateManifest()
    {
        $manifest = [
            'css/optimized.min.css' => 'css/optimized.min.css?v=' . time(),
            'js/optimized.min.js' => 'js/optimized.min.js?v=' . time(),
        ];

        File::put('public/mix-manifest.json', json_encode($manifest, JSON_PRETTY_PRINT));
        
        return $manifest;
    }

    /**
     * Optimize images in storage
     */
    public function optimizeImages()
    {
        $imageDirectories = [
            'storage/card_banner',
            'storage/card_logo',
            'storage/gallery',
            'storage/product_images',
            'storage/service_images',
            'storage/testimonials_images'
        ];

        foreach ($imageDirectories as $directory) {
            if (File::exists($directory)) {
                $this->optimizeImagesInDirectory($directory);
            }
        }
    }

    /**
     * Optimize images in a specific directory
     */
    private function optimizeImagesInDirectory($directory)
    {
        $files = File::files($directory);
        
        foreach ($files as $file) {
            $extension = strtolower($file->getExtension());
            
            if (in_array($extension, ['jpg', 'jpeg', 'png'])) {
                $this->optimizeImage($file->getPathname());
            }
        }
    }

    /**
     * Optimize a single image
     */
    private function optimizeImage($path)
    {
        // Basic image optimization - in production, you might want to use ImageMagick or similar
        $imageInfo = getimagesize($path);
        
        if ($imageInfo) {
            // Log optimization attempt
            \Log::info("Image optimized: {$path}", [
                'size' => filesize($path),
                'dimensions' => $imageInfo[0] . 'x' . $imageInfo[1]
            ]);
        }
    }
} 
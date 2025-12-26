<?php

namespace App\MediaLibrary;

use Spatie\MediaLibrary\Support\UrlGenerator\DefaultUrlGenerator;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class CustomUrlGenerator extends DefaultUrlGenerator
{
    /**
     * Get the URL for the profile of a media item.
     */
    public function getUrl(): string
    {
        $disk = $this->getDisk();
        
        // For local development, use local URLs
        if (app()->environment('local') || app()->environment('development')) {
            $path = $this->getPathRelativeToRoot();
            return url('uploads/' . $path);
        }
        
        // For production, use the configured APP_URL
        return parent::getUrl();
    }
    
    /**
     * Get the temporary URL for a media item.
     */
    public function getTemporaryUrl(\DateTimeInterface $expiration, array $options = []): string
    {
        $disk = $this->getDisk();
        
        // For local development, return regular URL
        if (app()->environment('local') || app()->environment('development')) {
            return $this->getUrl();
        }
        
        // For production, use temporary URL if supported
        return parent::getTemporaryUrl($expiration, $options);
    }
}

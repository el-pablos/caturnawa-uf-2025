<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

/**
 * SEO Service for managing meta tags and structured data
 */
class SEOService
{
    protected $config;
    protected $currentPage;
    protected $customData = [];

    public function __construct()
    {
        $this->config = Config::get('seo');
    }

    /**
     * Set current page for SEO
     */
    public function setPage(string $page): self
    {
        $this->currentPage = $page;
        return $this;
    }

    /**
     * Set custom SEO data
     */
    public function setCustomData(array $data): self
    {
        $this->customData = array_merge($this->customData, $data);
        return $this;
    }

    /**
     * Get page title
     */
    public function getTitle(): string
    {
        if (isset($this->customData['title'])) {
            return $this->customData['title'];
        }

        if ($this->currentPage && isset($this->config['pages'][$this->currentPage]['title'])) {
            return $this->config['pages'][$this->currentPage]['title'];
        }

        return $this->config['default']['title'];
    }

    /**
     * Get page description
     */
    public function getDescription(): string
    {
        if (isset($this->customData['description'])) {
            return $this->customData['description'];
        }

        if ($this->currentPage && isset($this->config['pages'][$this->currentPage]['description'])) {
            return $this->config['pages'][$this->currentPage]['description'];
        }

        return $this->config['default']['description'];
    }

    /**
     * Get page keywords
     */
    public function getKeywords(): string
    {
        if (isset($this->customData['keywords'])) {
            return $this->customData['keywords'];
        }

        if ($this->currentPage && isset($this->config['pages'][$this->currentPage]['keywords'])) {
            return $this->config['pages'][$this->currentPage]['keywords'];
        }

        return $this->config['default']['keywords'];
    }

    /**
     * Get canonical URL
     */
    public function getCanonical(): string
    {
        if (isset($this->customData['canonical'])) {
            return $this->customData['canonical'];
        }

        return URL::current();
    }

    /**
     * Get Open Graph data
     */
    public function getOpenGraph(): array
    {
        return [
            'og:title' => $this->getTitle(),
            'og:description' => $this->getDescription(),
            'og:url' => $this->getCanonical(),
            'og:type' => $this->customData['og_type'] ?? $this->config['default']['og_type'],
            'og:image' => $this->customData['og_image'] ?? asset($this->config['assets']['logo']['main']),
            'og:site_name' => 'UNAS Fest 2025',
            'og:locale' => 'id_ID',
        ];
    }

    /**
     * Get Twitter Card data
     */
    public function getTwitterCard(): array
    {
        return [
            'twitter:card' => $this->config['default']['twitter_card'],
            'twitter:title' => $this->getTitle(),
            'twitter:description' => $this->getDescription(),
            'twitter:image' => $this->customData['twitter_image'] ?? asset($this->config['assets']['logo']['main']),
            'twitter:site' => '@unasfest',
        ];
    }

    /**
     * Get structured data for organization
     */
    public function getOrganizationStructuredData(): array
    {
        $data = $this->config['structured_data']['organization'];
        $data['url'] = config('app.url');
        $data['logo'] = asset($this->config['assets']['logo']['main']);
        
        return $data;
    }

    /**
     * Get structured data for event
     */
    public function getEventStructuredData(): array
    {
        $data = $this->config['structured_data']['event'];
        $data['organizer']['url'] = config('app.url');
        
        return $data;
    }

    /**
     * Generate meta tags HTML
     */
    public function generateMetaTags(): string
    {
        $html = '';
        
        // Basic meta tags
        $html .= '<title>' . $this->getTitle() . '</title>' . "\n";
        $html .= '<meta name="description" content="' . $this->getDescription() . '">' . "\n";
        $html .= '<meta name="keywords" content="' . $this->getKeywords() . '">' . "\n";
        $html .= '<meta name="author" content="' . $this->config['default']['author'] . '">' . "\n";
        $html .= '<meta name="robots" content="' . $this->config['default']['robots'] . '">' . "\n";
        $html .= '<link rel="canonical" href="' . $this->getCanonical() . '">' . "\n";
        
        // Open Graph tags
        foreach ($this->getOpenGraph() as $property => $content) {
            $html .= '<meta property="' . $property . '" content="' . $content . '">' . "\n";
        }
        
        // Twitter Card tags
        foreach ($this->getTwitterCard() as $name => $content) {
            $html .= '<meta name="' . $name . '" content="' . $content . '">' . "\n";
        }
        
        // Favicon and icons
        $html .= '<link rel="icon" type="image/x-icon" href="' . asset($this->config['assets']['logo']['favicon']) . '">' . "\n";
        $html .= '<link rel="apple-touch-icon" href="' . asset($this->config['assets']['logo']['icon']) . '">' . "\n";
        
        return $html;
    }

    /**
     * Generate structured data JSON-LD
     */
    public function generateStructuredData(): string
    {
        $structuredData = [
            $this->getOrganizationStructuredData(),
        ];

        if ($this->currentPage === 'home') {
            $structuredData[] = $this->getEventStructuredData();
        }

        $json = '';
        foreach ($structuredData as $data) {
            $json .= '<script type="application/ld+json">' . "\n";
            $json .= json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n";
            $json .= '</script>' . "\n";
        }

        return $json;
    }

    /**
     * Get asset URL
     */
    public function getAsset(string $key): string
    {
        $keys = explode('.', $key);
        $asset = $this->config['assets'];
        
        foreach ($keys as $k) {
            if (isset($asset[$k])) {
                $asset = $asset[$k];
            } else {
                return '';
            }
        }
        
        return asset($asset);
    }

    /**
     * Get social media links
     */
    public function getSocialLinks(): array
    {
        return $this->config['social'];
    }

    /**
     * Get contact information
     */
    public function getContactInfo(): array
    {
        return $this->config['contact'];
    }
}

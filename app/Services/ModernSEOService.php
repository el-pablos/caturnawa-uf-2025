<?php

namespace App\Services;

use Artesaos\SEOTools\Facades\SEOTools;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\TwitterCard;
use Artesaos\SEOTools\Facades\JsonLd;

class ModernSEOService
{
    protected $defaultTitle = 'UNAS Fest 2025 - Festival Kompetisi Nasional Terbesar Indonesia';
    protected $defaultDescription = 'Bergabunglah dengan UNAS Fest 2025, festival kompetisi nasional terbesar di Indonesia. Kompetisi Teknologi, Kesehatan, dan Biodiversitas dengan total hadiah 500 Juta Rupiah.';
    protected $defaultKeywords = 'unas fest, kompetisi nasional, teknologi, kesehatan, biodiversitas, mahasiswa, indonesia, hadiah, festival';
    protected $siteName = 'UNAS Fest 2025';
    protected $siteUrl;
    protected $logoUrl;

    public function __construct()
    {
        $this->siteUrl = config('app.url');
        $this->logoUrl = $this->siteUrl . '/assets/images/logo/unas-fest-logo.png';
    }

    /**
     * Set SEO for homepage
     */
    public function setHomePage()
    {
        SEOMeta::setTitle($this->defaultTitle);
        SEOMeta::setDescription($this->defaultDescription);
        SEOMeta::setKeywords($this->defaultKeywords);
        SEOMeta::setCanonical($this->siteUrl);

        OpenGraph::setTitle($this->defaultTitle);
        OpenGraph::setDescription($this->defaultDescription);
        OpenGraph::setUrl($this->siteUrl);
        OpenGraph::setType('website');
        OpenGraph::setSiteName($this->siteName);
        OpenGraph::addImage($this->logoUrl, ['height' => 630, 'width' => 1200]);

        TwitterCard::setTitle($this->defaultTitle);
        TwitterCard::setDescription($this->defaultDescription);
        TwitterCard::setType('summary_large_image');
        TwitterCard::setImage($this->logoUrl);
        TwitterCard::setSite('@unasfest');

        JsonLd::setTitle($this->defaultTitle);
        JsonLd::setDescription($this->defaultDescription);
        JsonLd::setType('Organization');
        JsonLd::addValue('url', $this->siteUrl);
        JsonLd::addValue('logo', $this->logoUrl);
        JsonLd::addValue('sameAs', [
            'https://facebook.com/unasfest',
            'https://instagram.com/unasfest',
            'https://twitter.com/unasfest',
            'https://youtube.com/unasfest'
        ]);
    }

    /**
     * Set SEO for competitions page
     */
    public function setCompetitionsPage()
    {
        $title = 'Kompetisi UNAS Fest 2025 - Teknologi, Kesehatan, Biodiversitas';
        $description = 'Ikuti kompetisi nasional UNAS Fest 2025 dalam bidang Teknologi, Kesehatan, dan Biodiversitas. Total hadiah 500 Juta Rupiah. Daftar sekarang!';
        $keywords = 'kompetisi teknologi, kompetisi kesehatan, kompetisi biodiversitas, lomba mahasiswa, hadiah kompetisi, unas fest 2025';

        SEOMeta::setTitle($title);
        SEOMeta::setDescription($description);
        SEOMeta::setKeywords($keywords);
        SEOMeta::setCanonical($this->siteUrl . '/competitions');

        OpenGraph::setTitle($title);
        OpenGraph::setDescription($description);
        OpenGraph::setUrl($this->siteUrl . '/competitions');
        OpenGraph::setType('website');

        TwitterCard::setTitle($title);
        TwitterCard::setDescription($description);

        JsonLd::setTitle($title);
        JsonLd::setDescription($description);
        JsonLd::setType('Event');
        JsonLd::addValue('startDate', '2025-01-01');
        JsonLd::addValue('endDate', '2025-03-30');
        JsonLd::addValue('location', [
            '@type' => 'Place',
            'name' => 'Universitas Nasional',
            'address' => 'Jakarta Selatan, Indonesia'
        ]);
    }

    /**
     * Set SEO for about page
     */
    public function setAboutPage()
    {
        $title = 'Tentang UNAS Fest 2025 - Visi, Misi, dan Tim Profesional';
        $description = 'Pelajari lebih lanjut tentang UNAS Fest 2025, visi misi kami, dan tim profesional yang menyelenggarakan festival kompetisi nasional terbesar di Indonesia.';
        $keywords = 'tentang unas fest, visi misi, tim panitia, festival kompetisi, universitas nasional';

        SEOMeta::setTitle($title);
        SEOMeta::setDescription($description);
        SEOMeta::setKeywords($keywords);
        SEOMeta::setCanonical($this->siteUrl . '/about');

        OpenGraph::setTitle($title);
        OpenGraph::setDescription($description);
        OpenGraph::setUrl($this->siteUrl . '/about');

        TwitterCard::setTitle($title);
        TwitterCard::setDescription($description);

        JsonLd::setTitle($title);
        JsonLd::setDescription($description);
        JsonLd::setType('AboutPage');
    }

    /**
     * Set SEO for contact page
     */
    public function setContactPage()
    {
        $title = 'Kontak UNAS Fest 2025 - Hubungi Tim Kami';
        $description = 'Hubungi tim UNAS Fest 2025 untuk informasi lebih lanjut tentang kompetisi, pendaftaran, dan kerjasama. Customer support 24/7 siap membantu Anda.';
        $keywords = 'kontak unas fest, hubungi panitia, customer support, informasi kompetisi, bantuan pendaftaran';

        SEOMeta::setTitle($title);
        SEOMeta::setDescription($description);
        SEOMeta::setKeywords($keywords);
        SEOMeta::setCanonical($this->siteUrl . '/contact');

        OpenGraph::setTitle($title);
        OpenGraph::setDescription($description);
        OpenGraph::setUrl($this->siteUrl . '/contact');

        TwitterCard::setTitle($title);
        TwitterCard::setDescription($description);

        JsonLd::setTitle($title);
        JsonLd::setDescription($description);
        JsonLd::setType('ContactPage');
    }

    /**
     * Set SEO for testimonials page
     */
    public function setTestimonialsPage()
    {
        $title = 'Testimoni Peserta UNAS Fest - Pengalaman dan Review';
        $description = 'Baca testimoni dan pengalaman peserta UNAS Fest dari berbagai kompetisi. Dapatkan insight tentang manfaat mengikuti festival kompetisi nasional terbesar ini.';
        $keywords = 'testimoni unas fest, review peserta, pengalaman kompetisi, feedback mahasiswa, cerita sukses';

        SEOMeta::setTitle($title);
        SEOMeta::setDescription($description);
        SEOMeta::setKeywords($keywords);
        SEOMeta::setCanonical($this->siteUrl . '/testimonials');

        OpenGraph::setTitle($title);
        OpenGraph::setDescription($description);
        OpenGraph::setUrl($this->siteUrl . '/testimonials');

        TwitterCard::setTitle($title);
        TwitterCard::setDescription($description);

        JsonLd::setTitle($title);
        JsonLd::setDescription($description);
        JsonLd::setType('ReviewPage');
    }

    /**
     * Set SEO for specific competition
     */
    public function setCompetitionPage($competition)
    {
        $title = "Kompetisi {$competition->name} - UNAS Fest 2025";
        $description = "Ikuti kompetisi {$competition->name} di UNAS Fest 2025. {$competition->description} Hadiah menarik menanti!";
        $keywords = "kompetisi {$competition->name}, {$competition->category}, lomba mahasiswa, hadiah kompetisi";

        SEOMeta::setTitle($title);
        SEOMeta::setDescription($description);
        SEOMeta::setKeywords($keywords);
        SEOMeta::setCanonical($this->siteUrl . "/competitions/{$competition->slug}");

        OpenGraph::setTitle($title);
        OpenGraph::setDescription($description);
        OpenGraph::setUrl($this->siteUrl . "/competitions/{$competition->slug}");
        OpenGraph::setType('article');
        
        if ($competition->image) {
            OpenGraph::addImage($this->siteUrl . '/storage/' . $competition->image);
        }

        TwitterCard::setTitle($title);
        TwitterCard::setDescription($description);
        
        if ($competition->image) {
            TwitterCard::setImage($this->siteUrl . '/storage/' . $competition->image);
        }

        JsonLd::setTitle($title);
        JsonLd::setDescription($description);
        JsonLd::setType('Event');
        JsonLd::addValue('name', $competition->name);
        JsonLd::addValue('startDate', $competition->registration_start);
        JsonLd::addValue('endDate', $competition->registration_end);
        JsonLd::addValue('offers', [
            '@type' => 'Offer',
            'price' => $competition->registration_fee ?? 0,
            'priceCurrency' => 'IDR'
        ]);
    }

    /**
     * Set SEO for blog post
     */
    public function setBlogPost($post)
    {
        $title = $post->title . ' - UNAS Fest 2025 Blog';
        $description = $post->excerpt ?? substr(strip_tags($post->content), 0, 160);
        $keywords = $post->tags ?? 'unas fest, blog, artikel, informasi';

        SEOMeta::setTitle($title);
        SEOMeta::setDescription($description);
        SEOMeta::setKeywords($keywords);
        SEOMeta::setCanonical($this->siteUrl . "/blog/{$post->slug}");

        OpenGraph::setTitle($title);
        OpenGraph::setDescription($description);
        OpenGraph::setUrl($this->siteUrl . "/blog/{$post->slug}");
        OpenGraph::setType('article');
        OpenGraph::addProperty('article:published_time', $post->created_at->toISOString());
        OpenGraph::addProperty('article:modified_time', $post->updated_at->toISOString());
        
        if ($post->featured_image) {
            OpenGraph::addImage($this->siteUrl . '/storage/' . $post->featured_image);
        }

        TwitterCard::setTitle($title);
        TwitterCard::setDescription($description);
        TwitterCard::setType('summary_large_image');
        
        if ($post->featured_image) {
            TwitterCard::setImage($this->siteUrl . '/storage/' . $post->featured_image);
        }

        JsonLd::setTitle($title);
        JsonLd::setDescription($description);
        JsonLd::setType('BlogPosting');
        JsonLd::addValue('headline', $post->title);
        JsonLd::addValue('datePublished', $post->created_at->toISOString());
        JsonLd::addValue('dateModified', $post->updated_at->toISOString());
        JsonLd::addValue('author', [
            '@type' => 'Person',
            'name' => $post->author->name ?? 'UNAS Fest Team'
        ]);
    }

    /**
     * Add structured data for organization
     */
    public function addOrganizationSchema()
    {
        JsonLd::addValue('@context', 'https://schema.org');
        JsonLd::addValue('@type', 'Organization');
        JsonLd::addValue('name', $this->siteName);
        JsonLd::addValue('url', $this->siteUrl);
        JsonLd::addValue('logo', $this->logoUrl);
        JsonLd::addValue('description', $this->defaultDescription);
        JsonLd::addValue('address', [
            '@type' => 'PostalAddress',
            'streetAddress' => 'Jl. Sawo Manila No.61, Pejaten',
            'addressLocality' => 'Jakarta Selatan',
            'postalCode' => '12560',
            'addressCountry' => 'ID'
        ]);
        JsonLd::addValue('contactPoint', [
            '@type' => 'ContactPoint',
            'telephone' => '+62-21-7806700',
            'contactType' => 'customer service',
            'email' => 'info@unasfest.com'
        ]);
    }

    /**
     * Generate breadcrumb schema
     */
    public function addBreadcrumbSchema($breadcrumbs)
    {
        $itemListElement = [];
        
        foreach ($breadcrumbs as $index => $breadcrumb) {
            $itemListElement[] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $breadcrumb['name'],
                'item' => $breadcrumb['url']
            ];
        }

        JsonLd::addValue('@context', 'https://schema.org');
        JsonLd::addValue('@type', 'BreadcrumbList');
        JsonLd::addValue('itemListElement', $itemListElement);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

/**
 * Sitemap Controller for SEO optimization
 */
class SitemapController extends Controller
{
    /**
     * Generate XML sitemap
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $urls = [];

        // Static pages with priority and change frequency
        $staticPages = [
            [
                'url' => route('public.home'),
                'lastmod' => now()->toAtomString(),
                'changefreq' => 'daily',
                'priority' => '1.0'
            ],
            [
                'url' => route('public.competitions'),
                'lastmod' => now()->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.9'
            ],
            [
                'url' => route('public.about'),
                'lastmod' => now()->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.8'
            ],
            [
                'url' => route('public.contact'),
                'lastmod' => now()->toAtomString(),
                'changefreq' => 'yearly',
                'priority' => '0.7'
            ]
        ];

        // Add static pages
        $urls = array_merge($urls, $staticPages);

        // Dynamic competition pages
        $competitions = Competition::where('is_active', true)
            ->orderBy('updated_at', 'desc')
            ->get();

        foreach ($competitions as $competition) {
            $urls[] = [
                'url' => route('public.competition.detail', $competition->slug),
                'lastmod' => $competition->updated_at->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.8'
            ];
        }

        // Generate XML
        $xml = $this->generateSitemapXML($urls);

        return response($xml, 200)
            ->header('Content-Type', 'application/xml');
    }

    /**
     * Generate sitemap XML
     * 
     * @param array $urls
     * @return string
     */
    private function generateSitemapXML(array $urls): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($urls as $url) {
            $xml .= '  <url>' . "\n";
            $xml .= '    <loc>' . htmlspecialchars($url['url']) . '</loc>' . "\n";
            $xml .= '    <lastmod>' . $url['lastmod'] . '</lastmod>' . "\n";
            $xml .= '    <changefreq>' . $url['changefreq'] . '</changefreq>' . "\n";
            $xml .= '    <priority>' . $url['priority'] . '</priority>' . "\n";
            $xml .= '  </url>' . "\n";
        }

        $xml .= '</urlset>';

        return $xml;
    }

    /**
     * Generate robots.txt
     * 
     * @return \Illuminate\Http\Response
     */
    public function robots()
    {
        $content = "User-agent: *\n";
        $content .= "Allow: /\n";
        $content .= "Disallow: /admin/\n";
        $content .= "Disallow: /dashboard/\n";
        $content .= "Disallow: /login\n";
        $content .= "Disallow: /register\n";
        $content .= "Disallow: /password/\n";
        $content .= "Disallow: /api/\n";
        $content .= "Disallow: /*.json\n";
        $content .= "Disallow: /build/\n";
        $content .= "\n";
        $content .= "Sitemap: " . route('sitemap') . "\n";
        $content .= "\n";
        $content .= "# Crawl-delay for better server performance\n";
        $content .= "Crawl-delay: 1\n";

        return response($content, 200)
            ->header('Content-Type', 'text/plain');
    }
}
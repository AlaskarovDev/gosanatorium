<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Modules\Blog\App\Models\Blog;
use Modules\Team\App\Models\Team;
use Modules\Page\App\Models\CustomPage;
use Modules\Ecommerce\Entities\Product;
use Modules\TourBooking\App\Models\Service;
use Modules\TourBooking\App\Models\Destination;

class SitemapController extends Controller
{
    /**
     * All sitemap URLs are emitted under the /ru/ prefix to match the canonical
     * strategy. EN/AZ alternates are exposed via hreflang tags on each page.
     */
    public function index()
    {
        $urls = [];

        // Home
        $urls[] = $this->makeEntry('/', '1.0', 'daily');

        // Static frontend pages
        $staticPaths = [
            ['path' => 'about-us',         'priority' => '0.8', 'changefreq' => 'monthly'],
            ['path' => 'blogs',            'priority' => '0.8', 'changefreq' => 'weekly'],
            ['path' => 'faq',              'priority' => '0.6', 'changefreq' => 'monthly'],
            ['path' => 'pricing',          'priority' => '0.7', 'changefreq' => 'monthly'],
            ['path' => 'teams',            'priority' => '0.6', 'changefreq' => 'monthly'],
            ['path' => 'contact-us',       'priority' => '0.6', 'changefreq' => 'yearly'],
            ['path' => 'privacy-policy',   'priority' => '0.3', 'changefreq' => 'yearly'],
            ['path' => 'terms-conditions', 'priority' => '0.3', 'changefreq' => 'yearly'],
        ];

        foreach ($staticPaths as $item) {
            $urls[] = $this->makeEntry($item['path'], $item['priority'], $item['changefreq']);
        }

        // TourBooking — static landing pages
        $tourbookingStatic = [
            ['path' => 'tour-booking',              'priority' => '0.9', 'changefreq' => 'daily'],
            ['path' => 'tour-booking/services',     'priority' => '0.9', 'changefreq' => 'daily'],
        ];

        foreach ($tourbookingStatic as $item) {
            $urls[] = $this->makeEntry($item['path'], $item['priority'], $item['changefreq']);
        }

        // Destination landing pages — təmiz URL (/hotels/{slug})
        // Yalnız ana səhifədə görünən (featured) və slug-u olan istiqamətlər.
        if (class_exists(Destination::class)) {
            Destination::where('status', true)
                ->where('is_featured', true)
                ->whereNotNull('slug')
                ->where('slug', '!=', '')
                ->select('slug', 'updated_at')
                ->get()
                ->each(function ($destination) use (&$urls) {
                    $urls[] = $this->makeEntry('hotels/' . $destination->slug, '0.8', 'weekly', $destination->updated_at);
                });
        }

        // Blog posts
        if (class_exists(Blog::class)) {
            Blog::where('status', 1)
                ->select('slug', 'updated_at')
                ->get()
                ->each(function ($blog) use (&$urls) {
                    $urls[] = $this->makeEntry('blog/' . $blog->slug, '0.7', 'weekly', $blog->updated_at);
                });
        }

        // Teams
        if (class_exists(Team::class)) {
            Team::select('slug', 'updated_at')
                ->get()
                ->each(function ($team) use (&$urls) {
                    $urls[] = $this->makeEntry('team/' . $team->slug, '0.5', 'monthly', $team->updated_at);
                });
        }

        // Custom pages
        if (class_exists(CustomPage::class)) {
            CustomPage::select('slug', 'updated_at')
                ->get()
                ->each(function ($page) use (&$urls) {
                    $urls[] = $this->makeEntry('custom-page/' . $page->slug, '0.5', 'monthly', $page->updated_at);
                });
        }

        // E-commerce products (kept for compatibility)
        if (class_exists(Product::class) && \Route::has('product.view')) {
            Product::where('status', 1)
                ->select('slug', 'updated_at')
                ->get()
                ->each(function ($product) use (&$urls) {
                    $urls[] = $this->makeEntry('product/' . $product->slug, '0.7', 'weekly', $product->updated_at);
                });
        }

        // TourBooking — services (sanatoriums, hotels, tours, etc.)
        if (class_exists(Service::class)) {
            Service::where('status', true)
                ->select('slug', 'updated_at')
                ->get()
                ->each(function ($service) use (&$urls) {
                    $urls[] = $this->makeEntry($service->slug, '0.9', 'weekly', $service->updated_at);
                });
        }

        return Response::make($this->buildXml($urls), 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
        ]);
    }

    private function makeEntry(string $path, string $priority, string $changefreq, $updatedAt = null): array
    {
        $path = trim($path, '/');
        // Canonical URL-lər hər zaman sonda "/" ilə bitir, ona görə sitemap-dakı
        // URL-ləri də eyniləşdiririk (əks halda "Non-canonical page in sitemap" xəbərdarlığı çıxır).
        $loc  = rtrim(url('/ru' . ($path !== '' ? '/' . $path : '')), '/') . '/';

        return [
            'loc'        => $loc,
            'lastmod'    => $updatedAt ? $updatedAt->toAtomString() : now()->toAtomString(),
            'changefreq' => $changefreq,
            'priority'   => $priority,
        ];
    }

    private function buildXml(array $urls): string
    {
        $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($urls as $u) {
            $xml .= "  <url>\n";
            $xml .= '    <loc>' . htmlspecialchars($u['loc'], ENT_XML1) . "</loc>\n";
            $xml .= '    <lastmod>' . $u['lastmod'] . "</lastmod>\n";
            $xml .= '    <changefreq>' . $u['changefreq'] . "</changefreq>\n";
            $xml .= '    <priority>' . $u['priority'] . "</priority>\n";
            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>' . "\n";
        return $xml;
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Modules\Blog\App\Models\Blog;
use Modules\Page\App\Models\CustomPage;
use App\Models\Akcii;
use Modules\TourBooking\App\Models\Disease;
use Modules\TourBooking\App\Models\Service;
use Modules\TourBooking\App\Models\Destination;

class SitemapController extends Controller
{
    /**
     * Task 16: every indexable RU URL, in the canonical format (App\Support\CanonicalUrl).
     * Built on request from the database, so new/updated sanatoriums, posts and promotions
     * appear immediately (no cron needed). lastmod = real updated_at; static pages have no lastmod.
     * Not included: booking steps, search/sort/comparison URLs, admin/user pages, template demo pages
     * (team, products, pricing) and URLs that redirect.
     */
    public function index()
    {
        $urls = [];
        $latest = fn ($items) => collect($items)->pluck('updated_at')->filter()->max();

        $services = class_exists(Service::class)
            ? Service::where('status', true)->whereNotNull('slug')->where('slug', '!=', '')->select('slug', 'updated_at')->get()
            : collect();
        $blogs = class_exists(Blog::class)
            ? Blog::where('status', 1)->select('slug', 'updated_at')->get()
            : collect();

        // Home + catalog
        $urls[] = $this->makeEntry('/', '1.0', 'daily', $latest($services->concat($blogs)));
        $urls[] = $this->makeEntry('sanatoriyi', '0.9', 'daily', $latest($services));

        // Static pages
        foreach ([
            ['about-us', '0.6', 'monthly'],
            ['contact-us', '0.6', 'yearly'],
            ['faq', '0.6', 'monthly'],
            ['bez-predoplaty', '0.7', 'monthly'],
            ['naftalan-protivopokazaniya', '0.6', 'monthly'],
            ['blogs', '0.7', 'weekly'],
            ['privacy-policy', '0.2', 'yearly'],
            ['terms-conditions', '0.2', 'yearly'],
        ] as [$path, $priority, $freq]) {
            $urls[] = $this->makeEntry($path, $priority, $freq);
        }

        // Destinations (/hotels/{slug}) — the ones shown on the home page
        if (class_exists(Destination::class)) {
            Destination::where('status', true)->where('is_featured', true)
                ->whereNotNull('slug')->where('slug', '!=', '')
                ->select('slug', 'updated_at')->get()
                ->each(function ($d) use (&$urls) {
                    $urls[] = $this->makeEntry('hotels/' . $d->slug, '0.8', 'weekly', $d->updated_at);
                });
        }

        // Sanatoriums
        foreach ($services as $service) {
            $urls[] = $this->makeEntry($service->slug, '0.9', 'weekly', $service->updated_at);
        }

        // Treatment directions + disease pages + catalog filtered by disease (own canonical, Task 7/15)
        if (class_exists(Disease::class)) {
            $diseases = Disease::visible()->whereNotNull('slug')->where('slug', '!=', '')->get();
            $urls[] = $this->makeEntry('napravleniya-lecheniya', '0.7', 'monthly', $latest($diseases));
            $urls[] = $this->makeEntry('bolezni', '0.7', 'monthly', $latest($diseases));
            foreach ($diseases as $disease) {
                $urls[] = $this->makeEntry('bolezni/' . $disease->slug, '0.7', 'weekly', $disease->updated_at);
                $urls[] = $this->makeEntry('sanatoriyi', '0.7', 'weekly', $disease->updated_at, ['disease' => $disease->slug]);
            }
        }

        // Promotions
        if (class_exists(Akcii::class)) {
            $akcii = Akcii::visible()->whereNotNull('slug')->where('slug', '!=', '')->get();
            $urls[] = $this->makeEntry('akcii', '0.7', 'weekly', $latest($akcii));
            foreach ($akcii as $akcia) {
                $urls[] = $this->makeEntry('akcii/' . $akcia->slug, '0.6', 'weekly', $akcia->updated_at);
            }
        }

        // Blog posts
        foreach ($blogs as $blog) {
            $urls[] = $this->makeEntry('blog/' . $blog->slug, '0.6', 'monthly', $blog->updated_at);
        }

        // Custom pages (consultation, transfers, video) — published only
        if (class_exists(CustomPage::class)) {
            CustomPage::where('status', 1)->select('slug', 'updated_at')->get()
                ->each(function ($page) use (&$urls) {
                    $urls[] = $this->makeEntry(\App\Http\Controllers\HomeController::CLEAN_PAGE_URLS[$page->slug] ?? 'custom-page/' . $page->slug, '0.5', 'monthly', $page->updated_at);
                });
        }

        // One entry per URL
        $urls = collect($urls)->unique('loc')->values()->all();

        return Response::make($this->buildXml($urls), 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
        ]);
    }

    private function makeEntry(string $path, string $priority, string $changefreq, $updatedAt = null, array $query = []): array
    {
        // Task 15: same format as the canonical tag (https, non-www, /ru, no trailing slash).
        $loc  = \App\Support\CanonicalUrl::to($path, 'ru') . ($query ? '?' . http_build_query($query) : '');

        return [
            'loc'        => $loc,
            'lastmod'    => $updatedAt ? \Illuminate\Support\Carbon::parse($updatedAt)->toAtomString() : null,
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
            if ($u['lastmod']) {
                $xml .= '    <lastmod>' . $u['lastmod'] . "</lastmod>\n";
            }
            $xml .= '    <changefreq>' . $u['changefreq'] . "</changefreq>\n";
            $xml .= '    <priority>' . $u['priority'] . "</priority>\n";
            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>' . "\n";
        return $xml;
    }
}

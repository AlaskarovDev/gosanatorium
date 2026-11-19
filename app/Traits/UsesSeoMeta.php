<?php

namespace App\Traits;

use App\Models\SeoMeta;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use SEOMeta as SEOMetaFacade;
use OpenGraph;
use Twitter;

trait UsesSeoMeta
{
    /**
     * Fill SEO / Open Graph / Twitter tags for a page. Values saved in the admin panel
     * (seo_meta table) win over the defaults passed by the controller.
     */
    protected function applySeo(string $pageKey, array $defaults = [])
    {
        $seo = null;
        try {
            $seo = SeoMeta::where('page_key', $pageKey)->first();
        } catch (\Throwable $e) {
            // table not migrated yet - fall back to defaults
        }

        $filled = fn ($value) => is_string($value) && trim($value) !== '';

        $title       = $filled($seo?->seo_title) ? $seo->seo_title : ($defaults['title'] ?? config('app.name'));
        $description = $filled($seo?->seo_description) ? $seo->seo_description : ($defaults['description'] ?? '');
        $keywords    = $filled($seo?->seo_keywords) ? $seo->seo_keywords : ($defaults['keywords'] ?? '');
        // Task 11: plain text only (no tags / HTML entities), description max 160 chars at a word boundary
        $title       = \Modules\TourBooking\App\Services\SeoText::title($title);
        $description = \Modules\TourBooking\App\Services\SeoText::description($description);
        $ogTitle     = $filled($seo?->og_title) ? $seo->og_title : $title;
        $ogDesc      = $filled($seo?->og_description) ? \Modules\TourBooking\App\Services\SeoText::description($seo->og_description, 200) : $description;
        $ogImage     = $filled($seo?->og_image) ? $seo->og_image : (!empty($defaults['image']) ? $defaults['image'] : asset('images/og-default.jpg'));
        if (!preg_match('#^https?://#i', $ogImage)) {
            $ogImage = asset($ogImage);
        }
        // Task 15: one format (https, non-www, /ru, no trailing slash, ?page=N kept, sort/filters dropped)
        $canonical   = $filled($seo?->canonical_url) ? $seo->canonical_url : \App\Support\CanonicalUrl::current();
        $robots      = $seo?->robots ?? 'index,follow';

        SEOMetaFacade::setTitle($title, false);
        SEOMetaFacade::setDescription($description);
        if ($filled($keywords)) {
            // no empty <meta name="keywords">
            SEOMetaFacade::setKeywords($keywords);
        }
        SEOMetaFacade::setRobots($robots);
        SEOMetaFacade::setCanonical($canonical);

        OpenGraph::setTitle($ogTitle);
        OpenGraph::setDescription($ogDesc);
        OpenGraph::setUrl($canonical);
        OpenGraph::setSiteName('GoSanatorium');
        OpenGraph::addImage($ogImage);

        Twitter::setTitle($ogTitle);
        Twitter::setDescription($ogDesc);
        Twitter::setImage($ogImage);

        // Tells the layouts to print these tags instead of the page's hard-coded title/meta
        View::share('seoApplied', true);
    }
}

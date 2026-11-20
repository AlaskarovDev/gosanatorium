<?php

namespace App\Support;

use Illuminate\Support\Str;
use Modules\TourBooking\App\Services\FromPrice;

/**
 * Task 14: JSON-LD built from the database. Every method returns a schema array (or null when there is no data).
 */
class StructuredData
{
    public const ORG_ID = 'https://gosanatorium.com/#organization';
    public const SITE_ID = 'https://gosanatorium.com/#website';

    public static function script(?array $schema): string
    {
        if (! $schema) {
            return '';
        }

        return '<script type="application/ld+json">'
            . json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG)
            . '</script>';
    }

    /** Absolute URL for a site path that may already carry the locale prefix (url() would add it again). */
    public static function abs(?string $path): string
    {
        $path = (string) $path;
        if ($path === '' ) {
            return url()->current();
        }
        if (preg_match('~^https?://~i', $path)) {
            return $path;
        }

        return request()->getSchemeAndHttpHost() . '/' . ltrim($path, '/');
    }

    private static function countryCode(string $country): string
    {
        $map = ['азербайджан' => 'AZ', 'azerbaijan' => 'AZ', 'azərbaycan' => 'AZ', 'грузия' => 'GE', 'georgia' => 'GE', 'gürcüstan' => 'GE', 'россия' => 'RU', 'турция' => 'TR'];

        return $map[mb_strtolower(trim($country))] ?? $country;
    }

    public static function plain(?string $html, int $max = 0): string
    {
        $text = html_entity_decode(strip_tags((string) $html), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = trim(preg_replace('/\s+/u', ' ', $text));

        return $max > 0 ? Str::limit($text, $max) : $text;
    }

    /** Organization + WebSite for every page. */
    public static function site(?string $logoPath = null): array
    {
        $facts = company_facts();
        $logo = $logoPath ? asset($logoPath) : null;

        $phones = array_values(array_filter([$facts->get('phone_main'), $facts->get('phone_second')]));
        $sameAs = [
            'https://instagram.com/gosanatorium',
            'https://facebook.com/gosanatorium',
            'https://vk.com/gosanatorium',
        ];
        if ($tg = trim((string) $facts->get('telegram'), " @")) {
            $sameAs[] = 'https://t.me/' . $tg;
        }
        foreach (['google_url', 'yandex_url'] as $k) { // Task 26
            $u = trim((string) $facts->get($k));
            if (preg_match('#^https?://#i', $u)) {
                $sameAs[] = $u;
            }
        }

        $org = array_filter([
            '@type' => 'Organization',
            '@id' => self::ORG_ID,
            'name' => 'GoSanatorium',
            'legalName' => $facts->get('legal_name') ?: null,
            'url' => self::abs(localize_front_url('/')),
            'logo' => $logo,
            'telephone' => $phones[0] ?? null,
            'email' => $facts->get('email') ?: null,
            'foundingDate' => (string) $facts->foundedYear(),
            'address' => array_filter([
                '@type' => 'PostalAddress',
                'streetAddress' => self::street($facts->get('office_address')),
                'addressLocality' => 'Баку',
                'addressCountry' => 'AZ',
            ]),
            'contactPoint' => array_map(fn ($p) => [
                '@type' => 'ContactPoint',
                'telephone' => $p,
                'contactType' => 'customer service',
                'availableLanguage' => ['Russian', 'Azerbaijani', 'English'],
            ], $phones),
            'sameAs' => $sameAs,
        ]);

        $site = [
            '@type' => 'WebSite',
            '@id' => self::SITE_ID,
            'name' => 'GoSanatorium',
            'url' => self::abs(localize_front_url('/')),
            'inLanguage' => 'ru',
            'publisher' => ['@id' => self::ORG_ID],
        ];

        return ['@context' => 'https://schema.org', '@graph' => [$org, $site]];
    }

    /** «Баку, Азербайджан» is only a city; a real street address is kept. */
    private static function street(?string $address): ?string
    {
        $address = trim((string) $address);
        if ($address === '' || preg_match('/^(Баку|Baku|Bakı)\s*(,\s*(Азербайджан|Azerbaijan|Azərbaycan))?$/iu', $address)) {
            return null;
        }

        return $address;
    }

    /** Hotel schema for a sanatorium page. Ratings/reviews only from published reviews and only with ≥ 3 of them. */
    public static function hotel($service, $reviews = null, ?float $avgRating = null, $amenities = [], ?int $reviewTotal = null): array
    {
        $tr = $service->translation ?? null;
        $name = (string) ($tr?->title ?? $service->title ?? '');
        $media = $service->media ?? collect();
        $images = $media->where('file_type', '!=', 'video')
            ->sortByDesc('is_thumbnail')->sortBy('display_order')
            ->take(10)->map(fn ($m) => asset($m->file_path))->values()->all();

        $destination = $service->destination ?? null;
        $city = '';
        if ($destination) {
            $dname = (string) ($destination->name ?: $destination->city);
            $parts = preg_split('/\s*[-|–—,]\s*/u', trim($dname));
            $city = trim((string) ($parts[0] ?? $dname));
        }
        $country = trim((string) ($destination->country ?? ''));

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Hotel',
            '@id' => url()->current() . '#hotel',
            'name' => $name,
            'url' => url()->current(),
            'description' => self::plain(FromPrice::fill((string) ($tr?->short_description ?: $tr?->description), $service), 300) ?: null,
            'image' => $images ?: null,
            'address' => array_filter([
                '@type' => 'PostalAddress',
                'streetAddress' => trim((string) ($service->address ?? '')) ?: null,
                'addressLocality' => $city ?: (trim((string) ($service->location ?? '')) ?: null),
                'addressCountry' => $country !== '' ? self::countryCode($country) : null,
            ]),
        ];

        if (is_numeric($service->latitude ?? null) && is_numeric($service->longitude ?? null)
            && (float) $service->latitude != 0.0 && (float) $service->longitude != 0.0) {
            $schema['geo'] = ['@type' => 'GeoCoordinates', 'latitude' => (float) $service->latitude, 'longitude' => (float) $service->longitude];
        }

        $from = FromPrice::for($service);
        if ($from) {
            $schema['priceRange'] = 'от $' . rtrim(rtrim(number_format($from, 2, '.', ''), '0'), '.');
        }

        $stars = self::stars($service);
        if ($stars) {
            $schema['starRating'] = ['@type' => 'Rating', 'ratingValue' => (string) $stars];
        }

        $amenityNames = collect($amenities)->map(fn ($a) => trim((string) ($a->name ?? '')))->filter()->unique()->values();
        if ($amenityNames->isNotEmpty()) {
            $schema['amenityFeature'] = $amenityNames->map(fn ($n) => [
                '@type' => 'LocationFeatureSpecification', 'name' => $n, 'value' => true,
            ])->all();
        }

        $published = collect($reviews ?? [])->filter(fn ($r) => (float) ($r->rating ?? 0) > 0);
        $total = max($published->count(), (int) $reviewTotal);
        if ($total >= 3 && $published->isNotEmpty()) {
            $avg = $avgRating ?: (float) $published->avg('rating');
            $schema['aggregateRating'] = [
                '@type' => 'AggregateRating',
                'ratingValue' => (string) round($avg, 1),
                'reviewCount' => $total,
                'bestRating' => '5',
                'worstRating' => '1',
            ];
            $schema['review'] = $published->sortByDesc('created_at')->take(5)->map(function ($r) {
                $body = self::plain(($r->review_ru ?? null) ?: ($r->review ?? ''), 500);

                return array_filter([
                    '@type' => 'Review',
                    'author' => ['@type' => 'Person', 'name' => self::reviewer($r)],
                    'datePublished' => $r->created_at ? $r->created_at->toDateString() : null,
                    'reviewBody' => $body ?: null,
                    'reviewRating' => ['@type' => 'Rating', 'ratingValue' => (string) round((float) $r->rating, 1), 'bestRating' => '5', 'worstRating' => '1'],
                ]);
            })->values()->all();
        }

        return array_filter($schema, fn ($v) => $v !== null && $v !== [] && $v !== '');
    }

    private static function reviewer($review): string
    {
        try {
            $name = trim((string) $review->displayName());
        } catch (\Throwable $e) {
            $name = trim((string) ($review->name ?? ''));
        }

        return $name !== '' ? $name : 'Гость';
    }

    private static function stars($service): ?int
    {
        $tr = $service->translation ?? null;
        foreach ([$tr?->title, $service->title ?? null, $tr?->short_description] as $src) {
            $plain = self::plain((string) $src);
            if (preg_match('/(?<!\d)([1-5])\s*(?:★|\*|звезд)/u', $plain, $m)) {
                return (int) $m[1];
            }
        }

        return null;
    }

    /** FAQPage from [['q' => ..., 'a' => ...]]; null when empty. */
    public static function faq(array $items): ?array
    {
        $items = array_values(array_filter(array_map(fn ($i) => [
            'q' => self::plain($i['q'] ?? ''),
            'a' => self::plain($i['a'] ?? ''),
        ], $items), fn ($i) => $i['q'] !== '' && $i['a'] !== ''));

        if (! $items) {
            return null;
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => array_map(fn ($i) => [
                '@type' => 'Question',
                'name' => $i['q'],
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $i['a']],
            ], $items),
        ];
    }

    /** FAQ items from rendered accordion HTML (question / answer CSS classes). */
    public static function faqFromHtml(string $html, string $qClass, string $aClass): array
    {
        if (trim($html) === '') {
            return [];
        }
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="UTF-8"><div>' . $html . '</div>');
        libxml_clear_errors();
        $xp = new \DOMXPath($dom);
        $qs = $xp->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' {$qClass} ')]");
        $as = $xp->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' {$aClass} ')]");
        $items = [];
        foreach ($qs as $i => $q) {
            $a = $as->item($i);
            if ($a) {
                $items[] = ['q' => $q->textContent, 'a' => $a->textContent];
            }
        }

        return $items;
    }

    /** ItemList of sanatorium URLs. */
    public static function itemList(string $name, $services, int $offset = 1): ?array
    {
        $list = collect($services)->values();
        if ($list->isEmpty()) {
            return null;
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'ItemList',
            'name' => $name,
            'itemListElement' => $list->map(fn ($s, $i) => [
                '@type' => 'ListItem',
                'position' => $offset + $i,
                'url' => route('front.tourbooking.services.show', ['slug' => $s->slug]),
                'name' => (string) ($s->translation?->title ?? $s->title ?? ''),
            ])->all(),
        ];
    }

    /** BlogPosting for a blog post. */
    public static function blogPosting($blog, ?string $logo = null): array
    {
        $authorName = trim((string) ($blog->author?->name ?? ''));
        $author = $authorName !== ''
            ? ['@type' => 'Person', 'name' => $authorName]
            : ['@type' => 'Organization', 'name' => 'GoSanatorium', '@id' => self::ORG_ID];

        return array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            'headline' => Str::limit(self::plain($blog->title ?? ''), 110, ''),
            'description' => self::plain($blog->seo_description ?: ($blog->short_description ?? $blog->description ?? ''), 160) ?: null,
            'image' => ! empty($blog->image) ? [asset($blog->image)] : null,
            'datePublished' => $blog->created_at?->toIso8601String(),
            'dateModified' => ($blog->updated_at ?? $blog->created_at)?->toIso8601String(),
            'author' => $author,
            'publisher' => array_filter([
                '@type' => 'Organization',
                '@id' => self::ORG_ID,
                'name' => 'GoSanatorium',
                'logo' => $logo ? ['@type' => 'ImageObject', 'url' => $logo] : null,
            ]),
            'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => url()->current()],
            'inLanguage' => 'ru',
        ]);
    }
}

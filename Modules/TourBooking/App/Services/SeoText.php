<?php

namespace Modules\TourBooking\App\Services;

/**
 * Task 11: title / meta description templates used when the admin SEO fields are empty.
 */
class SeoText
{
    public const SUFFIX = ' | GoSanatorium';

    /** Plain text for meta tags: no tags, no HTML entities, single spaces, cut at a word boundary. */
    public static function description(?string $html, int $max = 160): string
    {
        $text = (string) $html;
        // decode twice: some admin texts are stored already escaped (&amp;mdash;)
        for ($i = 0; $i < 2; $i++) {
            $text = html_entity_decode(strip_tags($text), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }
        $text = trim(preg_replace('/[\s\x{00A0}]+/u', ' ', $text));

        if (mb_strlen($text) <= $max) {
            return $text;
        }
        $cut = mb_substr($text, 0, $max - 1);
        $space = mb_strrpos($cut, ' ');
        if ($space !== false && $space > $max * 0.6) {
            $cut = mb_substr($cut, 0, $space);
        }

        return rtrim($cut, " ,;:—–-.(") . '…';
    }

    /** Plain one-line title. */
    public static function title(?string $text): string
    {
        return self::description($text, 1000);
    }

    /** Adds « | GoSanatorium» only when the whole title stays within 65 characters. */
    public static function withSuffix(string $title, int $max = 65): string
    {
        return mb_strlen($title . self::SUFFIX) <= $max ? $title . self::SUFFIX : $title;
    }

    /** «{Name} {stars}★, {City}: цены {year}, лечение, отзывы | GoSanatorium» */
    public static function serviceTitle($service): string
    {
        $tr = $service?->translation;
        $seo = self::title($tr?->seo_title ?? '');
        if ($seo !== '') {
            return $seo;
        }

        $name = self::title($tr?->title ?? $service?->title ?? 'Санаторий');
        $stars = self::stars($service);
        $city = self::title($service?->destination?->name ?? '');
        if ($city === '') {
            $city = self::title(preg_split('/[,(]/u', (string) ($service?->location ?? ''))[0] ?? '');
        }

        $title = $name;
        if ($stars && !preg_match('/\d\s*★/u', $name)) {
            $title .= ' ' . $stars . '★';
        }
        if ($city !== '' && mb_stripos($name, $city) === false) {
            $title .= ', ' . $city;
        }
        $title .= ': цены ' . now('Asia/Baku')->year . ', лечение, отзывы';

        return self::withSuffix($title);
    }

    public static function serviceDescription($service): string
    {
        $tr = $service?->translation;
        foreach ([$tr?->seo_description, $tr?->short_description, $tr?->description] as $src) {
            $text = self::description(FromPrice::fill((string) $src, $service));
            if ($text !== '') {
                return $text;
            }
        }

        return self::description(self::title($tr?->title ?? '') . ' — бронирование без предоплаты на GoSanatorium.com');
    }

    public static function catalogTitle(): string
    {
        return 'Санатории Азербайджана и Грузии с лечением — цены ' . now('Asia/Baku')->year . self::SUFFIX;
    }

    /** Star class: not a separate field, so it is read from «5★» / «5*» in the name or short text. */
    protected static function stars($service): ?int
    {
        if ((int) ($service?->stars ?? 0) > 0) {
            return (int) $service->stars; // Task 28: admin field
        }
        $tr = $service?->translation;
        foreach ([$tr?->title, $service?->title, $tr?->short_description] as $src) {
            $plain = self::title((string) $src);
            if (preg_match('/(?<!\d)([1-5])\s*(?:★|\*|звезд)/u', $plain, $m)) {
                return (int) $m[1];
            }
        }

        return null;
    }
}

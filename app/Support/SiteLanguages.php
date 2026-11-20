<?php

namespace App\Support;

/**
 * Task 17: which language versions really exist.
 *
 * config('site_languages') lists every known URL prefix; only entries with
 * 'native' => true are real (translated) versions. The others are retired:
 * their URLs 301 to the /ru page, and they are hidden from the picker,
 * hreflang and sitemap.
 */
class SiteLanguages
{
    /** Every known prefix code (active + retired). */
    public static function allCodes(): array
    {
        $codes = array_keys(config('site_languages', []));

        return $codes ?: ['ru'];
    }

    /** Active (translated) languages: code => config. RU always first. */
    public static function active(): array
    {
        $active = array_filter(config('site_languages', []), fn ($cfg) => ! empty($cfg['native']));
        if (empty($active)) {
            $active = ['ru' => ['name' => 'Русский', 'flag' => 'ru', 'native' => true]];
        }

        return $active;
    }

    public static function activeCodes(): array
    {
        return array_keys(self::active());
    }

    public static function isActive(string $code): bool
    {
        return in_array($code, self::activeCodes(), true);
    }

    public static function default(): string
    {
        return self::isActive('ru') ? 'ru' : self::activeCodes()[0];
    }
}

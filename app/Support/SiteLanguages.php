<?php

namespace App\Support;

/**
 * Site languages (config/site_languages.php).
 *
 * active  = 'enabled' (or native) entries: shown in the picker; non-native ones are
 *           Google auto-translated from RU.
 * native  = real content (RU): the only ones in hreflang/canonical.
 * Other known prefixes are retired: their URLs 301 to the same /ru page.
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
        $active = array_filter(config('site_languages', []), fn ($cfg) => ! empty($cfg['enabled']) || ! empty($cfg['native']));
        if (empty($active)) {
            $active = ['ru' => ['name' => 'Русский', 'flag' => 'ru', 'native' => true]];
        }

        return $active;
    }

    public static function activeCodes(): array
    {
        return array_keys(self::active());
    }

    /** Languages with real (non machine-translated) content — used for hreflang. */
    public static function nativeCodes(): array
    {
        $codes = array_keys(array_filter(self::active(), fn ($cfg) => ! empty($cfg['native'])));

        return $codes ?: [self::default()];
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

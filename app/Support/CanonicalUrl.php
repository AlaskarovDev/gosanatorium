<?php

namespace App\Support;

use Illuminate\Http\Request;

/**
 * Task 15: one canonical URL format for the whole site.
 *
 *  - https, non-www host
 *  - always the /ru prefix (az/en are machine-translated copies of the RU content)
 *  - no trailing slash (home = /ru)
 *  - query: only ?page=N (N > 1) and the catalog disease filter (?disease=slug) are kept;
 *    sort/budget/nights/utm … point to the clean URL
 *  - alias routes point to their main URL (/diseases, /napravleniya → /bolezni, /sanatorii → /sanatoriyi)
 *
 * canonical, og:url, hreflang and sitemap.xml all use this.
 */
class CanonicalUrl
{
    /** Alias path => main path (without locale prefix). */
    private const ALIASES = [
        '#^/diseases(?=/|$)#'   => '/bolezni',
        '#^/napravleniya$#'     => '/bolezni',
        '#^/sanatorii$#'        => '/sanatoriyi',
        '#^/home$#'             => '',
    ];

    public static function host(): string
    {
        $host = request()->getHost() ?: parse_url((string) config('app.url'), PHP_URL_HOST);

        return 'https://' . preg_replace('#^www\.#i', '', $host);
    }

    /** Path without the locale prefix, no trailing slash ('' for home). */
    public static function basePath(?Request $request = null): string
    {
        $request = $request ?: request();
        $path = '/' . trim($request->getPathInfo(), '/');
        $path = preg_replace('#^/(ru|az|en)(?=/|$)#', '', $path) ?? $path;
        $path = rtrim($path, '/');

        foreach (self::ALIASES as $pattern => $main) {
            if (preg_match($pattern, $path)) {
                $path = preg_replace($pattern, $main, $path, 1) ?? $path;
                break;
            }
        }

        return $path;
    }

    /** Absolute URL for a base path (e.g. '/sanatoriyi') under the given locale. */
    public static function to(string $basePath, string $locale = 'ru'): string
    {
        $basePath = rtrim('/' . ltrim($basePath, '/'), '/');

        return self::host() . '/' . $locale . $basePath;
    }

    public static function current(?Request $request = null): string
    {
        $request = $request ?: request();
        $base = self::basePath($request);

        $query = [];
        $disease = trim((string) $request->query('disease', ''));
        if ($base === '/sanatoriyi' && $disease !== '' && preg_match('#^[a-z0-9-]+$#', $disease)) {
            $query['disease'] = $disease;
        }
        $page = (int) $request->query('page', 1);
        if ($page > 1) {
            $query['page'] = $page;
        }

        return self::to($base) . ($query ? '?' . http_build_query($query) : '');
    }
}

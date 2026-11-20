<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

/**
 * Responsive images for public pages (Task 19).
 *
 * Variants are WebP copies of an uploaded image at fixed widths, stored as
 *   public/uploads/_r/{width}/{original path under uploads/}.webp
 * They are generated on the first request (route "uploads/_r/...") and afterwards
 * served directly by the web server as static files. The original file is never changed
 * and stays the <img src> fallback.
 */
class ResponsiveImage
{
    public const WIDTHS = [400, 800, 1200];

    public const MAX_UPLOAD_WIDTH = 1600;

    private const SOURCE_EXT = ['jpg', 'jpeg', 'png', 'webp'];

    /** Normalise a stored path or full URL to "uploads/..." relative to public/, or null. */
    public static function localPath(?string $path): ?string
    {
        if (!$path) {
            return null;
        }
        $path = trim($path);
        if (preg_match('#^https?://#i', $path)) {
            $host = parse_url($path, PHP_URL_HOST);
            $appHost = parse_url(config('app.url'), PHP_URL_HOST);
            $reqHost = request()?->getHost();
            if ($host !== $appHost && $host !== $reqHost) {
                return null;
            }
            $path = (string) parse_url($path, PHP_URL_PATH);
        }
        $path = ltrim(rawurldecode($path), '/');
        if (!str_starts_with($path, 'uploads/') || str_starts_with($path, 'uploads/_r/') || str_contains($path, '..')) {
            return null;
        }
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        if (!in_array($ext, self::SOURCE_EXT, true)) {
            return null;
        }

        return is_file(public_path($path)) ? $path : null;
    }

    /** [width, height] of a local image (cached by path + mtime), or null. */
    public static function size(?string $path): ?array
    {
        $local = self::localPath($path);
        if (!$local) {
            return null;
        }
        $full = public_path($local);
        $key = 'img-size:' . md5($local . '|' . @filemtime($full));

        $size = Cache::rememberForever($key, function () use ($full) {
            $info = @getimagesize($full);

            return $info ? [(int) $info[0], (int) $info[1]] : false;
        });

        return $size ?: null;
    }

    public static function variantUrl(string $localPath, int $width): string
    {
        $rel = substr($localPath, strlen('uploads/'));
        $segments = array_map('rawurlencode', explode('/', $rel . '.webp'));

        return asset('uploads/_r/' . $width . '/' . implode('/', $segments));
    }

    /** "url 400w, url 800w, ..." — only widths smaller than the original, plus the original itself. */
    public static function srcset(?string $path): ?string
    {
        $local = self::localPath($path);
        $size = $local ? self::size($local) : null;
        if (!$local || !$size) {
            return null;
        }
        [$w] = $size;
        $parts = [];
        foreach (self::WIDTHS as $width) {
            if ($width < $w) {
                $parts[] = self::variantUrl($local, $width) . ' ' . $width . 'w';
            }
        }
        if (!$parts) {
            return null;
        }
        $parts[] = asset($local) . ' ' . $w . 'w';

        return implode(', ', $parts);
    }

    /**
     * HTML attributes for an <img>: src, srcset/sizes, width/height, alt, loading, decoding.
     * Options: sizes (string), lazy (bool, default true), priority (bool: eager + fetchpriority=high),
     *          class, extra (raw string appended).
     */
    public static function attrs(?string $path, ?string $alt = '', array $opt = []): string
    {
        $src = $path ? (preg_match('#^https?://#i', $path) ? $path : asset($path)) : asset('frontend/assets/img/shape/placeholder.png');
        $html = 'src="' . e($src) . '"';

        if ($srcset = self::srcset($path)) {
            $html .= ' srcset="' . e($srcset) . '" sizes="' . e($opt['sizes'] ?? '100vw') . '"';
        }
        if ($size = self::size($path)) {
            $html .= ' width="' . $size[0] . '" height="' . $size[1] . '"';
        }
        $html .= ' alt="' . e(trim((string) $alt)) . '"';

        if (!empty($opt['priority'])) {
            $html .= ' fetchpriority="high" decoding="async"';
        } elseif ($opt['lazy'] ?? true) {
            $html .= ' loading="lazy" decoding="async"';
        }
        if (!empty($opt['class'])) {
            $html .= ' class="' . e($opt['class']) . '"';
        }

        return $html;
    }

    /**
     * Alt text for a gallery photo: «{caption} — {sanatorium}, {city}»; without caption «{sanatorium}, {city}».
     */
    public static function galleryAlt(?string $caption, ?string $name, ?string $city = null): string
    {
        $place = trim((string) $name);
        $city = trim((string) $city);
        if ($city !== '' && mb_stripos($place, $city) === false) {
            $place = $place !== '' ? $place . ', ' . $city : $city;
        }
        $caption = trim((string) $caption);

        if ($caption === '' || $caption === trim((string) $name)) {
            return $place;
        }

        return $place !== '' ? $caption . ' — ' . $place : $caption;
    }

    /** City of a sanatorium from its destination name («Нафталан - Азербайджан» → «Нафталан»). */
    public static function serviceCity($service): ?string
    {
        $name = $service?->location ?: $service?->destination?->name;
        if (!$name) {
            return null;
        }
        $city = trim(preg_split('/\s+[-–—|]\s+|\s*\|\s*/u', strip_tags($name))[0] ?? '');

        return $city !== '' ? $city : null;
    }

    /** Alt for a sanatorium photo: «{caption} — {Sanatorium}, {City}». */
    public static function serviceAlt($service, ?string $caption = null): string
    {
        $title = $service?->translation?->title ?? $service?->title ?? '';

        return self::galleryAlt($caption, $title, self::serviceCity($service));
    }

    /** Create (or return) the WebP variant file. Returns absolute path or null on failure. */
    public static function makeVariant(int $width, string $relWebp): ?string
    {
        if (!in_array($width, self::WIDTHS, true) || !str_ends_with($relWebp, '.webp')) {
            return null;
        }
        $source = self::localPath('uploads/' . substr($relWebp, 0, -5));
        if (!$source) {
            return null;
        }
        $size = self::size($source);
        if (!$size || $size[0] <= $width) {
            return null; // no upscaling: the original is used instead
        }
        $target = public_path('uploads/_r/' . $width . '/' . $relWebp);
        if (is_file($target) && filemtime($target) >= filemtime(public_path($source))) {
            return $target;
        }

        try {
            if (!is_dir(dirname($target))) {
                mkdir(dirname($target), 0755, true);
            }
            $img = Image::make(public_path($source));
            if ($img->width() > $width) {
                $img->resize($width, null, function ($c) {
                    $c->aspectRatio();
                    $c->upsize();
                });
            }
            $img->encode('webp', 78)->save($target);

            return $target;
        } catch (\Throwable $e) {
            Log::warning('Responsive variant failed: ' . $source . ' @' . $width . ' — ' . $e->getMessage());

            return null;
        }
    }

    /** Downscale an uploaded original wider than MAX_UPLOAD_WIDTH (in place). */
    public static function limitUploadWidth(string $fullPath, int $max = self::MAX_UPLOAD_WIDTH): void
    {
        try {
            $ext = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
            if (!in_array($ext, self::SOURCE_EXT, true) || !is_file($fullPath)) {
                return;
            }
            $info = @getimagesize($fullPath);
            if (!$info || $info[0] <= $max) {
                return;
            }
            $img = Image::make($fullPath);
            $img->resize($max, null, function ($c) {
                $c->aspectRatio();
                $c->upsize();
            });
            $img->save($fullPath, $ext === 'png' ? null : 85);
        } catch (\Throwable $e) {
            Log::warning('Upload downscale failed: ' . $fullPath . ' — ' . $e->getMessage());
        }
    }
}

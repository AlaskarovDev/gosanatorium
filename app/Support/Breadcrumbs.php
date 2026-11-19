<?php

namespace App\Support;

/**
 * Task 13: breadcrumb trails. Each crumb is ['name' => string, 'url' => string|null]; the last one has no URL.
 * «Главная» always links to the locale home (/ru), never to /ru/home.
 */
class Breadcrumbs
{
    public static function home(): array
    {
        return ['name' => 'Главная', 'url' => localize_front_url('/')];
    }

    /** Главная > Санатории > {Destination} > {Sanatorium} */
    public static function forService($service, bool $linkLast = false): array
    {
        $crumbs = [
            self::home(),
            ['name' => 'Санатории', 'url' => localize_front_url('/sanatoriyi')],
        ];

        $destination = $service->destination ?? null;
        if ($destination && $destination->status && filled($destination->slug)) {
            $name = (string) ($destination->name ?: $destination->city);
            $parts = preg_split('/\s*[-|–—,]\s*/u', trim($name));
            $city = trim((string) ($parts[0] ?? $name));
            if ($city !== '') {
                $crumbs[] = [
                    'name' => $city,
                    'url' => route('front.tourbooking.hotels.destination', ['slug' => $destination->slug]),
                ];
            }
        }

        $crumbs[] = [
            'name' => (string) ($service->translation?->title ?? $service->title ?? ''),
            'url' => $linkLast ? route('front.tourbooking.services.show', ['slug' => $service->slug]) : null,
        ];

        return $crumbs;
    }

    /** … > {Sanatorium} > Выбор номера */
    public static function forRooms($service): array
    {
        $crumbs = self::forService($service, true);
        $crumbs[] = ['name' => 'Выбор номера', 'url' => null];

        return $crumbs;
    }

    /** Главная > Блог > {Category} > {Post title} */
    public static function forBlog($blog): array
    {
        $crumbs = [
            self::home(),
            ['name' => 'Блог', 'url' => route('blogs')],
        ];

        $category = $blog->category ?? null;
        if ($category && filled($category->name)) {
            $crumbs[] = ['name' => (string) $category->name, 'url' => route('blogs', ['category' => $category->id])];
        }

        $crumbs[] = ['name' => (string) $blog->title, 'url' => null];

        return $crumbs;
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Modules\Blog\App\Models\Blog;

/**
 * Bloq şəkillərinin 404 problemini həll edir. İki yeri yoxlayır:
 *   1) Featured şəkil   → blogs.image
 *   2) Məqalə mətnindəki embedded şəkillər → blog_translations.description / short_description
 *      (redaktorun "uploads/custom-images/..." faylları — çoxu silinib və 404 verir)
 *
 * Faylı public/ altında mövcud olmayan şəkilləri tapır və --fix ilə placeholder
 * (general_setting->not_found) ilə əvəz edir. Mətndəki əvəz tam asset() URL-i ilə
 * edilir ki, /blog/slug kimi alt-səhifələrdə nisbi yol 404-ü yaranmasın.
 *
 * İstifadə:
 *   php artisan blogs:fix-images                 # yalnız hesabat
 *   php artisan blogs:fix-images --fix           # sınıqları placeholder ilə əvəz et
 *   php artisan blogs:fix-images --fix --placeholder=uploads/website-images/x.webp
 */
class FixBlogImages extends Command
{
    protected $signature = 'blogs:fix-images
        {--fix : Sınıq şəkilləri placeholder ilə əvəz et (default: yalnız hesabat)}
        {--placeholder= : İstifadə olunacaq placeholder yolu (məs. uploads/website-images/x.webp)}';

    protected $description = 'Faylı olmayan bloq şəkillərini (featured + mətn içi) tapır və (--fix ilə) placeholder ilə əvəz edir.';

    /** custom-images şəkil URL-lərini tutan regex (tam URL və ya /uploads/... və ya uploads/...) */
    private const IMG_RE = '~(?:https?://[^\s"\'<>()]*?)?/?uploads/custom-images/[^\s"\'<>()]+?\.(?:jpe?g|png|webp|gif|svg)~i';

    public function handle(): int
    {
        $placeholder = $this->resolvePlaceholder($this->option('placeholder'));
        $placeholderUrl = asset($placeholder);
        $fix = (bool) $this->option('fix');

        $this->info("Placeholder: {$placeholder}");
        $this->newLine();

        $this->fixFeaturedImages($fix, $placeholder);
        $this->newLine();
        $this->fixContentImages($fix, $placeholderUrl);

        if (!$fix) {
            $this->newLine();
            $this->comment('Düzəltmək üçün: php artisan blogs:fix-images --fix');
        } else {
            Cache::forget('setting');
        }

        return self::SUCCESS;
    }

    /** 1) blogs.image sütunu */
    private function fixFeaturedImages(bool $fix, string $placeholder): void
    {
        $missing = [];
        Blog::query()->select('id', 'image')->orderBy('id')->chunk(200, function ($blogs) use (&$missing) {
            foreach ($blogs as $blog) {
                if (!$this->fileExists($blog->image)) {
                    $missing[] = $blog->id;
                }
            }
        });

        if (empty($missing)) {
            $this->info('[1/2] Featured şəkil: sınıq tapılmadı.');
            return;
        }

        $this->warn('[1/2] Featured şəkli sınıq olan bloqlar: ' . implode(', ', $missing));

        if ($fix) {
            $n = DB::table('blogs')->whereIn('id', $missing)->update(['image' => $placeholder]);
            $this->info("      → {$n} bloqun featured şəkli əvəz olundu.");
        }
    }

    /** 2) blog_translations.description / short_description içindəki embedded şəkillər */
    private function fixContentImages(bool $fix, string $placeholderUrl): void
    {
        $cols = ['description', 'short_description'];
        $brokenTotal = 0;
        $rowsFixed = 0;
        $samples = [];

        DB::table('blog_translations')
            ->select('id', 'blog_id', 'description', 'short_description')
            ->orderBy('id')
            ->chunk(100, function ($rows) use ($cols, $fix, $placeholderUrl, &$brokenTotal, &$rowsFixed, &$samples) {
                foreach ($rows as $row) {
                    $update = [];
                    foreach ($cols as $col) {
                        $html = (string) ($row->$col ?? '');
                        if ($html === '' || !preg_match_all(self::IMG_RE, $html, $m)) {
                            continue;
                        }
                        $newHtml = $html;
                        foreach (array_unique($m[0]) as $url) {
                            if ($this->fileExists($url)) {
                                continue; // fayl var, toxunma
                            }
                            $brokenTotal++;
                            if (count($samples) < 15) {
                                $samples[$url] = ($samples[$url] ?? 0) + 1;
                            }
                            if ($fix) {
                                $newHtml = str_replace($url, $placeholderUrl, $newHtml);
                            }
                        }
                        if ($fix && $newHtml !== $html) {
                            $update[$col] = $newHtml;
                        }
                    }
                    if ($fix && !empty($update)) {
                        DB::table('blog_translations')->where('id', $row->id)->update($update);
                        $rowsFixed++;
                    }
                }
            });

        if ($brokenTotal === 0) {
            $this->info('[2/2] Mətn içi şəkil: sınıq tapılmadı.');
            return;
        }

        $this->warn("[2/2] Mətn içində {$brokenTotal} sınıq şəkil istinadı tapıldı. Nümunələr:");
        foreach ($samples as $url => $count) {
            $this->line("      {$url}");
        }
        if ($fix) {
            $this->info("      → {$rowsFixed} tərcümə sətrində sınıq şəkillər placeholder ilə əvəz olundu.");
        }
    }

    private function fileExists(?string $path): bool
    {
        $rel = ltrim((string) parse_url((string) $path, PHP_URL_PATH), '/');
        return $rel !== '' && is_file(public_path($rel));
    }

    private function resolvePlaceholder(?string $given): string
    {
        $candidates = array_filter([
            $given,
            optional(Cache::get('setting'))->not_found,
            'frontend/assets/img/shape/blog-shape-bg.jpg',
        ]);

        foreach ($candidates as $candidate) {
            if ($this->fileExists($candidate)) {
                return ltrim((string) parse_url((string) $candidate, PHP_URL_PATH), '/');
            }
        }

        return 'frontend/assets/img/shape/blog-shape-bg.jpg';
    }
}

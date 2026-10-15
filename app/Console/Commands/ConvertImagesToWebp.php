<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Intervention\Image\Facades\Image;
use Symfony\Component\Finder\Finder;

class ConvertImagesToWebp extends Command
{
    protected $signature = 'images:webp
                            {--path=uploads : Folder under public/ to scan}
                            {--quality=80 : WebP quality (1-100)}
                            {--max-width=1920 : Resize images wider than this (pixels)}
                            {--dry-run : Show what would be converted without writing files}
                            {--force : Re-convert even if .webp already exists}';

    protected $description = 'Generate WebP versions of all JPG/PNG images under public/ (originals are kept)';

    public function handle(): int
    {
        $relativePath = trim($this->option('path'), '/');
        $basePath     = public_path($relativePath);

        if (!is_dir($basePath)) {
            $this->error("Folder not found: $basePath");
            return self::FAILURE;
        }

        $quality  = (int) $this->option('quality');
        $maxWidth = (int) $this->option('max-width');
        $dryRun   = (bool) $this->option('dry-run');
        $force    = (bool) $this->option('force');

        $this->info("Scanning: $basePath");

        $finder = (new Finder())->files()->in($basePath)
            ->name(['*.jpg', '*.jpeg', '*.JPG', '*.JPEG', '*.png', '*.PNG']);

        $total = iterator_count($finder);

        if ($total === 0) {
            $this->warn('No JPG/PNG images found.');
            return self::SUCCESS;
        }

        $this->info("Found $total images. Quality: $quality, Max width: {$maxWidth}px" . ($dryRun ? ' (DRY RUN)' : ''));

        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $converted = $skipped = $errors = 0;
        $bytesSaved = 0;
        $errorList = [];

        foreach ($finder as $file) {
            $sourcePath = $file->getRealPath();
            $webpPath   = preg_replace('/\.(jpe?g|png)$/i', '.webp', $sourcePath);

            if (file_exists($webpPath) && !$force) {
                $skipped++;
                $bar->advance();
                continue;
            }

            if ($dryRun) {
                $converted++;
                $bar->advance();
                continue;
            }

            try {
                $img = Image::make($sourcePath);

                if ($img->width() > $maxWidth) {
                    $img->resize($maxWidth, null, function ($c) {
                        $c->aspectRatio();
                        $c->upsize();
                    });
                }

                $img->encode('webp', $quality)->save($webpPath);

                $converted++;
                $bytesSaved += filesize($sourcePath) - filesize($webpPath);
            } catch (\Throwable $e) {
                $errors++;
                $errorList[] = $sourcePath . ' — ' . $e->getMessage();
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->table(['Metric', 'Value'], [
            ['Total images',                 $total],
            ['Converted',                    $converted],
            ['Skipped (.webp already exists)', $skipped],
            ['Errors',                       $errors],
            ['Disk saved',                   $this->formatBytes($bytesSaved)],
        ]);

        if (!empty($errorList)) {
            $this->newLine();
            $this->warn('First few errors:');
            foreach (array_slice($errorList, 0, 5) as $err) {
                $this->line('  • ' . $err);
            }
        }

        if ($dryRun) {
            $this->warn('Dry run — no files written. Remove --dry-run to actually convert.');
        }

        return self::SUCCESS;
    }

    private function formatBytes(int $bytes): string
    {
        if ($bytes < 1024)        return "$bytes B";
        if ($bytes < 1024 ** 2)   return round($bytes / 1024, 2) . ' KB';
        if ($bytes < 1024 ** 3)   return round($bytes / (1024 ** 2), 2) . ' MB';
        return round($bytes / (1024 ** 3), 2) . ' GB';
    }
}

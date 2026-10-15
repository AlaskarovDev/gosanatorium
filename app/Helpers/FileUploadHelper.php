<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

class FileUploadHelper
{
    public static function uploadImage(UploadedFile $file, string $prefix = 'file', string $folder = 'uploads/custom-images'): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $filename  = $prefix . '-' . date('Y-m-d-h-i-s') . '-' . rand(999, 9999) . '.' . $extension;
        $path      = $folder . '/' . $filename;

        $file->move(public_path($folder), $filename);

        if (in_array($extension, ['jpg', 'jpeg', 'png'], true)) {
            self::generateWebp(public_path($path));
        }

        return $path;
    }

    public static function deleteImage(string $path): bool
    {
        $fullPath = public_path($path);
        $deleted  = false;

        if (File::exists($fullPath)) {
            $deleted = File::delete($fullPath);
        }

        $webpPath = preg_replace('/\.(jpe?g|png)$/i', '.webp', $fullPath);
        if ($webpPath !== $fullPath && File::exists($webpPath)) {
            File::delete($webpPath);
        }

        return $deleted;
    }

    public static function generateWebp(string $sourcePath, int $maxWidth = 1920, int $quality = 80): bool
    {
        try {
            $webpPath = preg_replace('/\.(jpe?g|png)$/i', '.webp', $sourcePath);

            if ($webpPath === $sourcePath) {
                return false;
            }

            $img = Image::make($sourcePath);

            if ($img->width() > $maxWidth) {
                $img->resize($maxWidth, null, function ($c) {
                    $c->aspectRatio();
                    $c->upsize();
                });
            }

            $img->encode('webp', $quality)->save($webpPath);

            return true;
        } catch (\Throwable $e) {
            Log::warning('WebP conversion failed: ' . $sourcePath . ' — ' . $e->getMessage());
            return false;
        }
    }
}

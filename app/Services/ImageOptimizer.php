<?php

namespace App\Services;

class ImageOptimizer
{
    public const MAX_DIMENSION = 1920;

    public const JPEG_QUALITY = 85;

    public const WEBP_QUALITY = 82;

    /**
     * Resize & compress an image file in place (or leave it untouched
     * when GD is unavailable / format is unsupported).
     */
    public function optimize(string $filePath): void
    {
        if (! extension_loaded('gd') || ! function_exists('imagecreatefromstring')) {
            return;
        }

        $mime = @mime_content_type($filePath) ?: 'application/octet-stream';
        $contents = @file_get_contents($filePath);

        if ($contents === false || $contents === '') {
            return;
        }

        // Keep GIF untouched (avoid breaking animation) and unknown formats.
        if ($mime === 'image/gif') {
            return;
        }

        $source = @imagecreatefromstring($contents);

        if ($source === false) {
            return;
        }

        $width = imagesx($source);
        $height = imagesy($source);

        if ($width <= 0 || $height <= 0) {
            imagedestroy($source);

            return;
        }

        // Respect EXIF orientation for JPEG.
        if (function_exists('exif_read_data') && $mime === 'image/jpeg') {
            $orientation = @exif_read_data($filePath, 'ORIENTATION', true)['IFD0']['Orientation'] ?? 1;

            if ($orientation > 1) {
                $source = $this->fixOrientation($source, (int) $orientation);
                $width = imagesx($source);
                $height = imagesy($source);
            }
        }

        // Resize only when larger than the cap, preserving aspect ratio.
        $maxW = self::MAX_DIMENSION;
        $maxH = self::MAX_DIMENSION;

        if ($width > $maxW || $height > $maxH) {
            $ratio = min($maxW / $width, $maxH / $height);
            $newW = (int) round($width * $ratio);
            $newH = (int) round($height * $ratio);

            $canvas = imagecreatetruecolor($newW, $newH);

            if (in_array($mime, ['image/png', 'image/webp'], true)) {
                imagealphablending($canvas, false);
                imagesavealpha($canvas, true);
                $transparent = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
                imagefill($canvas, 0, 0, $transparent);
            }

            imagecopyresampled($canvas, $source, 0, 0, 0, 0, $newW, $newH, $width, $height);
            imagedestroy($source);
            $source = $canvas;
        }

        $this->encode($source, $filePath, $mime);

        imagedestroy($source);
    }

    protected function encode($image, string $filePath, string $mime): void
    {
        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        if ($mime === 'image/png' || $ext === 'png') {
            imagepng($image, $filePath, 6);
        } elseif ($mime === 'image/webp' || $ext === 'webp') {
            @imagewebp($image, $filePath, self::WEBP_QUALITY);
        } else {
            imagejpeg($image, $filePath, self::JPEG_QUALITY);
        }
    }

    protected function fixOrientation($image, int $orientation)
    {
        $rotated = $image;

        switch ($orientation) {
            case 3:
                $rotated = imagerotate($image, 180, 0);
                break;
            case 6:
                $rotated = imagerotate($image, -90, 0);
                break;
            case 8:
                $rotated = imagerotate($image, 90, 0);
                break;
        }

        if ($rotated !== $image) {
            imagedestroy($image);
        }

        return $rotated;
    }
}